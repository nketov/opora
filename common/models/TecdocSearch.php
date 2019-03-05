<?php

namespace common\models;

use common\components\TecDoc;
use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use common\models\Product;

/**
 * ProductSearch represents the model behind the search form of `common\models\Product`.
 */
class TecdocSearch extends Product
{

    public $year;
    public $mfa_id;
    public $mod_id;
    public $type_id;
    public $car_name;
    public $category;

    /**
     * {@inheritdoc}
     */


    public function rules()
    {
        return [
            [['year', 'mfa_id','mod_id','type_id'], 'integer'],
            [['category', 'car_name'], 'safe'],
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
            'year' => 'Год выпуска',
            'mfa_id' => 'Марка',
            'mod_id' => 'Модель',
            'type_id' => 'Тип модели',
        ];
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search()
    {

        $allModels=[];
        if($this->category && $this->type_id) {
            $allModels= TecDoc::getCategory($this->category, $this->type_id);
        }


        $dataProvider = new ArrayDataProvider([
            'allModels' => $allModels,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);



//        $query = Product::find();
//
//        // add conditions that should always apply here
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'pagination' => [
//                'pageSize' => 12,
//            ],
//        ]);
//
//
//        $dataProvider->sort->defaultOrder['price'] = SORT_DESC;
//
//
//        $this->load($params);
//
//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }
//
//        // grid filtering conditions
//        $query->andFilterWhere([
//            'id' => $this->id,
//            'active' => $this->active
//        ]);
//
//
//
//        $query->andFilterWhere(['like', 'code', $this->code])
//            ->andFilterWhere(['like', 'name', $this->name])
//            ->andFilterWhere(['like', 'article', $this->article])
//            ->andFilterWhere(['like', 'category', $this->category]) ;
//
        return $dataProvider;
    }
}
