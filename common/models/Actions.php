<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "actions".
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $percent
 */
class Actions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'actions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'percent'], 'required'],
            [['user_id', 'product_id', 'percent'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Клиент',
            'product_id' => 'Товар',
            'percent' => 'Скидка, %',
        ];
    }


    public static function getDiscounts()
    {
        $discounts=[];

        if (Yii::$app->user->isGuest) return $discounts;

        $actions=self::findAll(['user_id'=>Yii::$app->user->identity->getId()]);

        foreach ($actions as $action){
            $discounts[trim($action->product->id)]=$action->percent;
        }

        return $discounts;
    }

    /**
     * @return int
     */


    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }



}
