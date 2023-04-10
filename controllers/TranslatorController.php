<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use app\forms\TranslatorForm;

class TranslatorController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->layout = "empty";
        
        $model  = new TranslatorForm();
        return $this->render('index', ['model' => $model]);
    }

    public function actionTranslate()
    {
        try {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $form  = new TranslatorForm();
            $form->load(Yii::$app->request->post());
            $title = $form->translate();
    
            return ["success" => true, "title" => $title];
        } catch (\Throwable $th) {
            return ["error" => $th->getMessage()];
        }
    }

    public function actionDownload($title)
    {
        $filepath = Yii::getAlias('@webroot/files/') . "{$title}.json";
		Yii::$app->response->sendFile($filepath, "{$title}.json", [
            'mimeType' => "application/json",
        ]);

        sleep(2);
        unlink($filepath);

        return true;
    }
}