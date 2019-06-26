<?php

namespace common\models;

use common\components\TecDoc;
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
    public $brandsList;
    public $brands = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    const OPORA_SHOP = '0';
    const VLAD_SHOP = '1';

    private static $_shopName = [
        self::OPORA_SHOP => 'Опора',
        self::VLAD_SHOP => 'Владислав',

    ];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['active', 'remains', 'remains_sklad', 'currency', 'category', 'shop'], 'integer'],
            [['price'], 'number'],
            [['code', 'article'], 'string', 'max' => 75],
            [['name'], 'string', 'max' => 200],
            [['brand'], 'string', 'max' => 100],
            [['images', 'description', 'properties'], 'string'],
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
            'article' => 'Артикул',
            'active' => 'Состояние',
            'category' => 'Категория',
            'name' => 'Наименование',
            'price' => 'Цена',
            'currency' => 'Валюта',
            'remains' => 'В наличии',
            'unit' => 'Единица измерения',
            'images' => 'Фотографии',
            'description' => 'Описание',
            'brand' => 'Производитель',
        ];
    }


    public static function getStatuses()
    {
        return [
            0 => 'Отключён',
            1 => 'Активный',
        ];
    }


    public static function shopName($shop)
    {
        return self::$_shopName[$shop];
    }

    public static function shopNamesList()
    {
        return self::$_shopName;
    }


    public static function brandsList($query)
    {
        $array = ArrayHelper::map($query->select(['brand'], 'DISTINCT')->all(), 'brand', 'brand');
        asort($array);
        unset($array['']);
        return $array;
    }

    public static function allBrandsList()
    {
        $array = ArrayHelper::map(self::find()->select(['brand'], 'DISTINCT')->all(), 'brand', 'brand');
        return $array;
    }


    public static function maxPrice($query)
    {
        $model = $query->andFilterWhere(['>', 'price', 0])->orderBy('price DESC')->one();
        if (empty($model)) return 1;

        return ceil($model->price);
    }

    public static function minPrice($query)
    {
        $model = $query->andFilterWhere(['>', 'price', 0])->orderBy('price ASC')->one();
        if (empty($model)) return 1;
        return floor($model->price);
    }

    public function getDiscountPrice()
    {
//        $discounts = \Yii::$app->user->identity->actions ?? [];
//
//        if(array_key_exists($this->id, $discounts)) {
//            return round($this->price *(100-$discounts[$this->id])/100, 2);
//        }
//
//        if(array_key_exists('', $discounts)) {
//            return round($this->price *(100-$discounts[''])/100, 2);
//        }

        return $this->price;
    }

    public function categoryName()
    {
        return Category::findOne($this->category)->name ?? "";
    }

    public function getProperties()
    {
        $props = [];

        $array = explode(';;;;;',$this->properties);
        foreach ($array as $prop) {
            $pair = explode('|||',$prop);
            if($pair[0] && $pair[1])
            $props[$pair[0]] = $pair[1];
        }
        return $props;
    }


    public function getFirstImage()
    {

        return $this->getAllImages()[0] ?? '';
    }

    public function getAllImages()
    {
        $images = [];
        if (!empty($this->images)) {
            foreach (explode(';', $this->images) as $img) {
                $images[] = '/images/1C_images/' . $img;
            }
        } else {
            if ($this->shop == self::VLAD_SHOP && $td_images = TecDoc::getImages($this->article)) {
                foreach ($td_images as $img) {
                    if ($img['PATH'])
                        $images[] = '/images/Foto/' . $img['PATH'];
                }
            }
        }

        return $images;
    }


    public static function getActiveCodesArray()
    {
        return ArrayHelper::map(self::find()->active()->all(), 'id', 'code');
    }

    public static function find()
    {
        return new ProductQuery(get_called_class());
    }


}
