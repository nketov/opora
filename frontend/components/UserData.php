<?php

namespace frontend\components;


use common\models\Actions;
use yii\base\BaseObject;


class UserData extends BaseObject
{

    public function init()
    {
        if (isset(\Yii::$app->user->identity) && $user = \Yii::$app->user->identity) {

            if (isset($_COOKIE['car']) && !empty($car = unserialize($_COOKIE['car'], ["allowed_classes" => false]))) {
                $user->car=$car;
            }

//            $actions=Actions::findAll(['user_id'=>\Yii::$app->user->identity->id]);
//
//            foreach ($actions as $action){
//                \Yii::$app->user->identity->actions[trim($action->product_id)]=$action->percent;
//            }

        }
    }

}