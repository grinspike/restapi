<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\Products;
use yii\web\Cookie;



/**
 *  For test 
 */
class ApiController extends Controller
{

    public function actionUserpost()
    {
        return $this->render('userpost');
    }

    public function actionProductspost()
    {
        return $this->render('productspost');
    }

}
