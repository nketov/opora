<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $date
 * @property int $user_id
 * @property string $order_content
 * @property string $summ
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date','status'], 'safe'],
            [['user_id', 'order_content', 'summ'], 'required'],
            [['user_id'], 'integer'],
            [['order_content'], 'string'],
            [['summ'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Номер заказа',
            'date' => 'Дата заказа',
            'user_id' => 'Пользователь',
            'order_content' => 'Содержание заказа',
            'summ' => 'Сумма',
            'status' => 'Состояние',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function getStatuses()
    {
        return [
            0 => 'Новый',
            1 => 'В работе',
            2 => 'Закрыт'        
           ];
    }

}
