<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id

 */
class UnregisteredUser extends Model
{

    public $phone;

    public function rules()
    {
        return [
            ['phone', 'string', 'length' => 9, 'message' => 'Неверный номер'],
            [['phone'], 'required']
        ];
    }


    public function attributeLabels()
    {
        return [
            'phone' => 'Телефон',
        ];
    }

}
