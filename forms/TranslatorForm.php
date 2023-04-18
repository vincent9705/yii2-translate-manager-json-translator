<?php

namespace app\forms;

use Yii;
use yii\helpers\Url;
use yii\base\Model;
use yii\web\UploadedFile;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use Google\Service\Sheets\Spreadsheet;
use Google\Service\Sheets\BatchUpdateValuesRequest;

class TranslatorForm extends Model
{
    public $file;
	public $result;
	public $language_from = "en-US";
	public $language_to;

	protected $sheetId;

    public function rules()
	{
		return [
			[['result'], 'safe'],
			[['language_from', 'language_to'], 'required'],
			[['file'], 'safe']
		];
	}

    public function translate()
    {
		$client = Yii::$app->Google->client();
        $arr    = self::decodeTranslation();
		if (empty($arr))
			throw new \Exception("Json file is required", 1);

        $language      = $this->language_to;
		$to_language   = (!strpos($this->language_to, "zh")) ? substr($this->language_to, 0 , 2) : $this->language_to;
		$from_language = (!strpos($this->language_from, "zh")) ? substr($this->language_from, 0 , 2) : $this->language_from;
        $source        = $arr['source'];
		$new_translate = [];
		$rows          = [];
		$range         = "";
		$row_count     = 0;
		$title         = date("YmdHis") . "{$this->language_from} {$language}";
		$limit         = 200;
		$this->sheetId = self::createNewSheet($client, $title);


		foreach ($source as $v) {
			$v           = (object) $v;

			$row_count += 1;
			$rows[]    = [$v->id, '=GOOGLETRANSLATE("'.$v->message.'", "'.$from_language.'", "'.$to_language.'")'];
		}

		$pages = ceil($row_count / $limit);

		for ($i = 1; $i <= $pages; $i++) {
			$count = $limit;
			if ($i * $limit > $row_count)
				$count = $limit - (($i * $limit) - $row_count);
			
			$uRows         = array_slice($rows, (($i - 1) * $limit), $limit);
			$range         = "Sheet1!A1:B{$count}";
			self::batchUpdateValues($client, $this->sheetId, $range, $uRows);
			$this->result  = self::batchGetValues($client, $this->sheetId, $range);
			$words         = $this->result->valueRanges[0]['values'];

			foreach ($words as $value) {
				if (strpos(strtolower($value[1]), "#error!") !== false || strpos(strtolower($value[1]), "#value!") !== false)
					continue;
					
				$new_translate[] = [
					'id'          => (int) $value[0],
					'language'    => $language,
					'translation' => $value[1]
				];
			}
		}

		$res = [
			'languages'            => [],
			'languageSources'      => $source,
			'languageTranslations' => $new_translate
		];
		$json_string = json_encode($res, JSON_UNESCAPED_UNICODE);

		$path = Yii::getAlias('@webroot/files/') . "{$title}.json";
		$fp   = fopen($path, 'w');
		fwrite($fp, $json_string);
		fclose($fp);

		return $title;
    }

    protected function decodeTranslation()
    {
        $file = UploadedFile::getInstance($this, 'file');
        $date = date("YmdHis");

        if (!empty($file))
        {
            $directory = Yii::getAlias('@webroot/files/') . $date . '.' . $file->extension;
			if ($file->saveAs($directory))
			{
                $json       = file_get_contents($directory);
                $json_data  = json_decode($json,true);
                $language   = $json_data['languages'];
                $source     = $json_data['languageSources'];
                $tanslation = $json_data['languageTranslations'];

                return ['langauge' => $language, 'source' => $source, 'translation' => $tanslation];
			}
        }

        return [];
    }

    protected function createNewSheet($client, $title)
	{
		$service = new Sheets($client);
        try{
            $spreadsheet = new Spreadsheet([
			'properties' => [
				'title' => $title
				]
			]);
			$spreadsheet = $service->spreadsheets->create($spreadsheet, [
				'fields' => 'spreadsheetId'
			]);

			return $spreadsheet->spreadsheetId;
        }
        catch(\Exception $e) {
			// TODO(developer) - handle error appropriately
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	protected function batchUpdateValues($client, $spreadsheetId, $range, $rows)
    {
        $service = new Sheets($client);

        try{

            $data[] = new ValueRange([
                'range' => $range, //"Sheet1!A3:B3"
				'values' => $rows, //[[1, '=GOOGLETRANSLATE("test", "en", "zh")']]
			]);

			$body = new BatchUpdateValuesRequest([
				'valueInputOption' => "USER_ENTERED",
				'data' => $data
			]);
			$result = $service->spreadsheets_values->batchUpdate($spreadsheetId, $body);
			return $result;
		}
        catch(\Exception $e) {
            // TODO(developer) - handle error appropriately
            Yii::Warning( 'Message: ' .$e->getMessage());
		}
    }

	protected function batchGetValues($client, $spreadsheetId, $ranges)
    {
		$service = new Sheets($client);
		try{
			//$ranges = 'Sheet1!A1:B3';
			$params = array(
				'ranges' => $ranges
			);
			//execute the request
			$result = $service->spreadsheets_values->batchGet($spreadsheetId, $params);
			return $result;
		}
		catch(\Exception $e) {
			// TODO(developer) - handle error appropriately
			echo 'Message: ' .$e->getMessage();
			Yii::Warning($e->getMessage());
		}
	}
}