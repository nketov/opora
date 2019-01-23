<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Actions;

/**
 * ActionsSearch represents the model behind the search form of `common\models\Actions`.
 */
class ActionsSearch extends Actions
{

    public $user;
    public $product;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','percent'], 'integer'],
            [['user_id', 'product_id'], 'safe'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Actions::find()->joinWith(['user','product']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'percent' => $this->percent,
        ]);

        $query->andFilterWhere(['like', 'product.code', $this->product_id])
            ->andFilterWhere(['like', 'user.email', $this->user_id]);


        return $dataProvider;
    }
}
