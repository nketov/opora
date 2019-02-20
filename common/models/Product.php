<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\Color;


/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $code
 * @property int $remains
 * @property int $active
 * @property int $category
 * @property string $name
 * @property string $price
 * @property string $images
 * @property string $description
 * @property string $unit
 * @property string $article
 */
class Product extends \yii\db\ActiveRecord
{


    public $tecdoc_images;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['active', 'remains','currency','category'], 'integer'],
            [['price'], 'number'],
            [['code', 'article'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 200],
            [['images','description'], 'string'],
            [['unit'], 'string', 'max' => 10],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Код товара',
            'article' => 'Артикль',
            'active' => 'Состояние',
            'category' => 'Категория',
            'name' => 'Наименование',
            'price' => 'Цена',
            'currency' => 'Валюта',
            'remains' => 'Остатки',
            'unit' => 'Единица измерения',
            'images' => 'Фотографии',
            'description' => 'Описание',
        ];
    }


    public static function getStatuses()
    {
        return [
            0 => 'Отключён',
            1 => 'Активный',
        ];
    }



    public static function maxPrice($query)
    {
        $model = $query->andFilterWhere(['>','price',0])->orderBy('price DESC')->one();
        if(empty($model)) return 1;

        return ceil($model->price);
    }

    public static function minPrice($query)
    {
        $model = $query->andFilterWhere(['>','price',0])->orderBy('price ASC')->one();
        if(empty($model)) return 1;
        return floor($model->price);
    }


    public static function getActiveCodesArray()
    {
        return ArrayHelper::map(self::find()->active()->all(),'id','code');
    }

    public static function find()
    {
        return new ProductQuery(get_called_class());
    }
}
