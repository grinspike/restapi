<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\controllers\behaviors\AccessBehavior;
use app\models\User;

class UserController extends Controller
{

    public function behaviors()
    {
        return [
            AccessBehavior::className(),
        ];
    }

    public function beforeAction($action)
    {
        if ($action->actionMethod !== 'actionLogin' && !$this->authorizedUser()) {
            $this->sendUnathorizedMessage();
            return;
        }
        return parent::beforeAction($action);
    }

    /**
     * displaying username if he logged in. Or error if he not
     * 
     */
    public function actionMe()
    {
        echo json_encode(['message' => "Current user is " . $this->isAuthorizedGetName()]);
        return;
    }

    /**
     * Login user. Data comes from post
     * 
     */
    public function actionLogin()
    {
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
        } else {
            echo json_encode(['message' => "Error. Login or password are incorrect"]);
        }
        return;
    }

    /**
     * Logout user
     * 
     */
    public function actionLogout()
    {
        setcookie('token', '', time() - 3600, '/');
        echo json_encode(['message' => "User was logged out"]);
        return;
    }

}
