<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\controllers\behaviors\AccessBehavior;

class InfoController extends Controller
{

    public function behaviors()
    {
        return [
            AccessBehavior::className(),
        ];
    }

    public function beforeAction($action)
    {
        if (!$this->authorizedUser()) {
            $this->sendUnathorizedMessage();
            return;
        }
        return parent::beforeAction($action);
    }

    public function actionOk()
    {
        return header("HTTP/1.1 200 OK");
    }

}
