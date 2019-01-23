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

    public $prices = [];
    public $withoutPricesShow = 1;
    public $withoutImageShow = 0;
    public $maxPrice = 1000000;
    public $minPrice = 1;


    /**
     * {@inheritdoc}
     */


    public function rules()
    {
        return [
            [['id', 'shop', 'active', 'category', 'height', 'diametr', 'width', 'depth', 'images_count'], 'integer'],
            [['code', 'name', 'description', 'image_1', 'image_2', 'image_3', 'image_4', 'image_5', 'color', 'material', 'lamps', 'second_code'], 'safe'],
            [['price'], 'number'],
            [['prices', 'withoutPricesShow', 'withoutImageShow'], 'safe'],
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
            'shop' => 'Магазин',
            'active' => 'Состояние',
            'category' => 'Категория',
            'name' => 'Наименование',
            'price' => 'Цена',
            'description' => 'Описание',
            'images_count' => 'Фотографии',
            'withoutPricesShow' => 'Показывать товары без цены',
            'withoutImageShow' => 'Показывать товары без фото',
            'color' => 'Цвет',
            'material' => 'Материал',
            'height' => 'Высота, мм',
            'diametr' => 'Диаметр, мм',
            'width' => 'Ширина, мм',
            'depth' => 'Глубина, мм',
            'lamps' => 'Лампочки',

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


        if (isset($params['category'])) $this->setAttribute('category', $params['category']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active,
            'category' => $this->category,
        ]);


        if (!$this->withoutImageShow) {
            $query->andFilterWhere(
                ['>', 'images_count', 0]);
        }

        $this->maxPrice = self::maxPrice(clone ($query));
        $this->minPrice = self::minPrice(clone ($query));


        if (isset($params['ProductSearch']['prices'])) {
            $prices = explode(',', $this->prices);
            $this->prices = [];
            $this->prices[0] = $prices[0] > $this->minPrice ? $prices[0] : $this->minPrice;
            $this->prices[1] = $prices[1] < $this->maxPrice ? $prices[1] : $this->maxPrice;
        } else {
            $this->prices[0] = $this->minPrice;
            $this->prices[1] = $this->maxPrice;
        }

        if ($this->prices[1]<$this->prices[0]) $this->prices[1]=$this->prices[0];

        if ($this->withoutPricesShow) {
            $query->andFilterWhere(
                ['or',
                    ['and',
                        ['>=', 'price', $this->prices[0]],
                        ['<=', 'price', $this->prices[1]],],
                    ['price' => 0],
                ]);
        } else {
            $query->andFilterWhere(['and',
                ['>=', 'price', $this->prices[0]],
                ['<=', 'price', $this->prices[1]]]);
        }


        $query->andFilterWhere([
            'shop' => $this->shop,
            'material' => $this->material,
            'color' => $this->color,
            'price' => $this->price,
            'height' => $this->height,
            'diametr' => $this->diametr,
            'width' => $this->width,
            'depth' => $this->depth,
        ]);


        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'lamps', $this->lamps])
            ->andFilterWhere(['like', 'second_code', $this->second_code]);




        return $dataProvider;
    }
}
