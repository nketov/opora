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

        if ($this->category && $this->type_id) {

            $products = array_unique(ArrayHelper::getColumn(Product::find()->active()->all(), 'article'));

            foreach (TecDoc::getCategory($this->category, $this->type_id) as $article) {
                if ($article && in_array($article, $products))
                    foreach (Product::find()->where(['like', 'article', $article])->active()->all() as $product)
                        $allModels[] = $product;
            }

        }


        $dataProvider = new ArrayDataProvider([
            'allModels' => $allModels,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);



        return $dataProvider;
    }
}
