<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\Products;
use yii\web\Cookie;

class ApiController extends Controller
{

    /**
     * Checks if user is authorized
     *
     * @return boolean
     */
    public function sendUnathorizedMessage()
    {
        return header("HTTP/1.1 401 Unauthorized");
    }

    /**
     * Checks if user is authorized
     *
     * @return boolean
     */
    public function authorizedUser()
    {

        if (isset($_COOKIE['token'])) {
            $token = $_COOKIE['token'];
        } else {
            return false;
        }

        $conditions = [
            'token' => $token,
        ];
        $user = User::find()->where($conditions)->one();
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }

    public function actionV1()
    {
        // get the HTTP method, path and body of the request
        $method = strtoupper(Yii::$app->request->method);
        $request = explode('/', trim(Yii::$app->request->pathInfo, '/'));

        $api = strtolower(preg_replace('/[^a-z0-9_\*]+/i', '', array_shift($request)));
        $ver = strtolower(preg_replace('/[^a-z0-9_\*]+/i', '', array_shift($request)));
        $table = strtolower(preg_replace('/[^a-z0-9_\*]+/i', '', array_shift($request)));
        $action = strtolower(preg_replace('/[^a-z0-9_\*]+/i', '', array_shift($request)));

        //echo "$api   $ver    $table   $action";
        if ($api === "api" && $ver === "v1") {

            switch ($method) {
                case 'GET':
                    if ($table === "user" && $action === "me") {
                        if ($user = $this->authorizedUser()) {
                            echo json_encode(['message' => "Current user is " . $user->username]);
                            return;
                        }

                        $this->sendUnathorizedMessage();
                        return;
                    }

                    if ($table === "user" && $action === "logout") {
                        if (isset($_COOKIE['token'])) {
                            $token = $_COOKIE['token'];
                            $conditions = [
                                'token' => $token,
                            ];
                            $user = User::find()->where($conditions)->one();
                            if ($user) {
                                setcookie('token', '', time() - 3600, '/');
                                echo json_encode(['message' => "User was logged out"]);
                                return;
                            }
                        };
                        $this->sendUnathorizedMessage();
                        return;
                    }

                    if ($table === "products") {
                        if (!$this->authorizedUser()) {
                            $this->sendUnathorizedMessage();
                            return;
                        }
                        $conditions = [
                            'id' => $action,
                        ];
                        $product = Products::find()->where($conditions)->asArray()->one();
                        echo json_encode($product);
                        return;
                    }

                    break;
                case 'PUT':
                    if ($table === "products") {
                        if (!$this->authorizedUser()) {
                            $this->sendUnathorizedMessage();
                            return;
                        }

                        $name = Yii::$app->request->getBodyParam('name');
                        $price = Yii::$app->request->getBodyParam('price');
                        $amount = Yii::$app->request->getBodyParam('amount');

                        if ($name && $price && amount) {

                            $conditions = [
                                'id' => $action,
                            ];
                            $product = Products::find()->where($conditions)->one();
                            $product->name = $name;
                            $product->price = $price;
                            $product->amount = $amount;
                            $prodcut->save();
                        }
                        return;
                    }

                    break;
                case 'POST':
                    if ($table === "user" && $action === "login") {
                        if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])) {
                            echo json_encode(['message' => "Error. Wrong token"]);
                            return;
                        };

                        $username = Yii::$app->request->post('name');
                        $password = Yii::$app->request->post('password');

                        $conditions = [
                            'username' => $username,
                            'password' => $password,
                        ];
                        $user = User::find()->where($conditions)->one();
                        if ($user) {
                            setcookie('token', $user->token, time() + 86400 * 365, '/');
                            echo json_encode(['message' => "You are logged in"]);
                            return;
                        } else {
                            echo json_encode(['message' => "Error. Login or password are incorrect"]);
                            return;
                        }
                    }

                    if ($table === "products") {

                        if (!$this->authorizedUser()) {
                            $this->sendUnathorizedMessage();
                            return;
                        }

                        if (Yii::$app->request->post('name') !== null && Yii::$app->request->post('name') !== null && Yii::$app->request->post('name') !== null) {
                            $name = Yii::$app->request->post('name');
                            $price = Yii::$app->request->post('price');
                            $amount = Yii::$app->request->post('amount');
                        }

                        $product = new Products();
                        $product->name = $name;
                        $product->price = $price;
                        $product->amount = $amount;
                        $product->save();
                        return;
                    }

                    break;
                case 'OPTIONS':
                    if ($table === "*") {
                        echo "afaa";
                        if ($this->authorizedUser()) {
                            header("HTTP/1.1 200 OK");
                        } else {
                            $this->sendUnathorizedMessage();
                        }
                    }
                    return;
                    break;
            }
        }
        return;
    }

    public function actionUserpost()
    {
        return $this->render('userpost');
    }

    public function actionProductspost()
    {
        return $this->render('productspost');
    }

}
