<?php

namespace common\models;

use Yii;

class MainPage extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'main_page';
    }


    public function rules()
    {
        return [
            [['product_id'], 'integer'],
        ];
    }


    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }


}
