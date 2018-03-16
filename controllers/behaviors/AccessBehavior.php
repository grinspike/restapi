<?php

namespace app\controllers\behaviors;

use yii\base\Behavior;
use app\models\User;

/**
 * Description of AccessBehavior
 *
 * @author grinspike
 */
class AccessBehavior extends Behavior
{

    public function isAuthorizedGetName()
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
            return $user->username;
        } else {
            return false;
        }
    }

    public function sendUnathorizedMessage()
    {
        return header("HTTP/1.1 401 Unauthorized");
    }

    
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
            return true;
        } else {
            return false;
        }
    }
    
}
