<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\controllers\behaviors\AccessBehavior;
use app\models\Products;

class ProductController extends Controller
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

    /**
     * Product creation. Data comes from post
     * 
     */
    public function actionCreate()
    {
        $name = Yii::$app->request->post('name');
        $price = Yii::$app->request->post('price');
        $amount = Yii::$app->request->post('amount');

        $product = new Products();
        $product->name = $name;
        $product->price = $price;
        $product->amount = $amount;
        $product->save();
        return;
    }

    /**
     * Product view 
     * @param int $id proudct id
     * 
     */
    public function actionView($id)
    {
        $conditions = [
            'id' => $id,
        ];
        $product = Products::find()->where($conditions)->asArray()->one();
        echo json_encode($product);
        return;
    }

    /**
     * Product update. Data comes from put
     * @param int $id product id
     * 
     */
    public function actionUpdate($id)
    {
        $name = Yii::$app->request->getBodyParam('name');
        $price = Yii::$app->request->getBodyParam('price');
        $amount = Yii::$app->request->getBodyParam('amount');

        $conditions = [
            'id' => $id,
        ];
        $product = Products::find()->where($conditions)->one();
        $product->name = $name;
        $product->price = $price;
        $product->amount = $amount;
        $product->save();

        return;
    }

}
