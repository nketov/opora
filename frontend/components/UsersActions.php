<?php

namespace frontend\components;


use common\models\Actions;
use yii\base\BaseObject;


class UsersActions extends BaseObject
{

    public function init()
    {
        if (isset(\Yii::$app->user->identity) && $id = \Yii::$app->user->identity->id) {

            $actions=Actions::findAll(['user_id'=>\Yii::$app->user->identity->id]);

            foreach ($actions as $action){
                \Yii::$app->user->identity->actions[trim($action->product_id)]=$action->percent;
            }

        }
    }

}