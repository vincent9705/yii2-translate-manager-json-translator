<?php

namespace app\components;

use Yii;
use yii\base\Component;
use Google\Client;
use Google\Service\Drive;

class GoogleAPI extends Component
{
    public function client()
	{
		$path = Yii::getAlias("@webroot/") . 'keyfile.json';
		putenv("GOOGLE_APPLICATION_CREDENTIALS={$path}");

		$client = new Client();
		$client->useApplicationDefaultCredentials(); //using service account
        $client->addScope(Drive::DRIVE);

		return $client;
	}
}