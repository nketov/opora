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


    public function rules()
    {
        return [
            [['year', 'mfa_id', 'mod_id', 'type_id'], 'integer'],
            [['category', 'car_name'], 'safe'],
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
        ];
    }


    /**
     * @return ArrayDataProvider
     */
    public function search()
    {

        $allModels = [];

//        if ($this->category && $this->type_id) {
//            \Yii::$app->db->createCommand('SET SESSION wait_timeout = 300;')->execute();
//            $products = array_unique(ArrayHelper::getColumn(Product::find()->active()->all(), 'article'));
//
//            foreach (TecDoc::getCategory($this->category, $this->type_id) as $article) {
//                if ($article && in_array($article, $products))
//                    foreach (Product::find()->where(['like', 'article', $article])->active()->all() as $product)
//                        $allModels[] = $product;
//            }
//
//        }

        $category = $this->category;
        $type =  $this->type_id;

        if ($this->category && $this->type_id) {
            $allModels = \Yii::$app->cache->getOrSet('td_provider_models_' . $category . '_' . $type,
                function () use ($category, $type) {
                    \Yii::$app->db->createCommand('SET SESSION wait_timeout = 300;')->execute();
                    $products = array_unique(ArrayHelper::getColumn(Product::find()->active()->all(), 'article'));
                    $am = [];
                    foreach (TecDoc::getCategory($category, $type) as $article) {
                        if ($article && in_array($article, $products))
                            foreach (Product::find()->where(['like', 'article', $article])->active()->all() as $product)
                                $am[] = $product;
                    }
                    return $am;
                }, 300);
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $allModels,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }
}
