<?php

namespace common\models;

use frontend\components\Tree_1C;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;
use common\models\Category;

/**
 * ProductSearch represents the model behind the search form of `common\models\Product`.
 */
class ProductSearch extends Product
{


    public $category;

    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [['active', 'remains', 'currency'], 'integer'],
            [['code', 'name', 'category', 'image_1', 'article', 'unit', 'brands'], 'safe'],
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


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        if (!empty($params['category'])) {
            $this->category = Category::find()->where(['like', 'code', $params['category']])->one();
            $tree = new Tree_1C();
            $cats = $tree->getChildesIds($this->category->code);
            $this->category = $this->category->id;
            $query->andFilterWhere([
                'in', 'category', $cats
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active
        ]);


        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'article', $this->article]);

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
