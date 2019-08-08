<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;
use yii\helpers\ArrayHelper;

/**
 * PS represents the model behind the search form of `common\models\Product`.
 */
class PTS extends Product
{

    public $text;

    public function rules()
    {
        return [
            [['text', 'brands'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    public function search($params)
    {

        $query = Product::find()->active();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
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

        $dataProvider->sort->defaultOrder['price'] = SORT_DESC;

        $this->load($params);

        $synonyms = Synonym::getSynonyms($this->text);

        $query_array = ['or',
            ['like', 'name', trim($this->text)],
            ['like', 'article', trim($this->text)],
        ];


        foreach ($synonyms as $syn) {
            $query_array[] = ['like', 'name', $syn];
        }

        $query->andFilterWhere($query_array);

        $this->brandsList = self::brandsList(clone ($query));

        if (!empty($this->brands)) {

            $brands_query_array = ['or',
                ['like', 'brand', $this->brands[0]],
            ];

            foreach ($this->brands as $br) {
                $brands_query_array[] = ['like', 'brand', $br];
            }

            $query->andFilterWhere($brands_query_array);
        } else {
            $this->brands = self::allBrandsList();
        }

        return $dataProvider;
    }
}
