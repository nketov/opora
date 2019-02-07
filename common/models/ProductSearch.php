<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;

/**
 * ProductSearch represents the model behind the search form of `common\models\Product`.
 */
class ProductSearch extends Product
{



    /**
     * {@inheritdoc}
     */


    public function rules()
    {
        return [
            [['active', 'remains','currency'], 'integer'],
            [['code', 'name', 'category', 'image_1',  'article', 'unit'], 'safe'],
            [['price'], 'number'],
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
            'images_count' => 'Фотографии',
            'image_1' => 'Фото 1',
            'image_2' => 'Фото 2',
            'image_3' => 'Фото 3',
            'image_4' => 'Фото 4',
            'image_5' => 'Фото 5',

        ];
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        $query = Product::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);


        $dataProvider->sort->defaultOrder['price'] = SORT_DESC;


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active
        ]);



        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'article', $this->article])
            ->andFilterWhere(['like', 'category', $this->category]) ;

        return $dataProvider;
    }
}
