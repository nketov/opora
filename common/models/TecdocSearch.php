<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
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

    /**
     * {@inheritdoc}
     */


    public function rules()
    {
        return [
            [['year', 'mfa_id','mod_id','type_id'], 'integer'],
            [['category'], 'safe'],
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
            'category' => 'Категория',
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
//        return $dataProvider;
    }
}
