<?php

namespace common\models;

use common\components\TecDoc;
use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use common\models\Product;
use yii\helpers\ArrayHelper;


class TecdocSearch extends Product
{

    public $year;
    public $mfa_id;
    public $mod_id;
    public $type_id;
    public $car_name;
    public $category;
    public $vin;


    public function rules()
    {
        return [
            [['year', 'mfa_id', 'mod_id', 'type_id'], 'integer'],
            [['vin'], 'string','length'=>17],
            [['category', 'car_name', 'brands'], 'safe'],
        ];
    }


    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function attributeLabels()
    {
        return [
            'year' => 'Год выпуска',
            'mfa_id' => 'Марка',
            'mod_id' => 'Модель',
            'type_id' => 'Тип модели',
            'category' => 'Категория',
            'vin' => 'VIN-код',
        ];
    }


    /**
     * @return ArrayDataProvider
     */
    public function search()
    {
        $allModels = [];

        $category = $this->category;
        $type = $this->type_id;

        if ($this->category && $this->type_id) {
            $allModels = \Yii::$app->cache->getOrSet('td_provider_models_' . $category . '_' . $type,
                function () use ($category, $type) {
                    \Yii::$app->db->createCommand('SET SESSION wait_timeout = 300;')->execute();
                    $products = array_unique(ArrayHelper::getColumn(Product::find()->active()->all(), 'article'));
                    $am = [];
                    foreach (TecDoc::getCategory($category, $type) as $article) {
                        if ($article && in_array($article, $products))
                            foreach (Product::find()->where(['like', 'article', $article])->active()->all() as $product)
                                $am[$product->id] = $product;
                    }
                    return $am;
                }, 300);
        }


        $array = ArrayHelper::map($allModels, 'brand', 'brand');
        asort($array);
        unset($array['']);


        $this->brandsList = $array;

        if (!empty($this->brands)) {
            foreach ($allModels as $key => $model) {
                if (!in_array($model->brand, $this->brands)) unset($allModels[$key]);
            }
        } else{
            $this->brands = self::allBrandsList();
        }


        $dataProvider = new ArrayDataProvider([
            'allModels' => $allModels,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => [
                    'price' => ['label' => 'По цене'],
                    'remains' => ['label' => 'По количеству'],
                ],
                'defaultOrder' => [
                    'price' => SORT_DESC
                ]
            ]
        ]);

        return $dataProvider;
    }
}
