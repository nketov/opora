<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Post;
use common\models\Synonym;

/**
 * PostSearch represents the model behind the search form of `common\models\Post`.
 */
class PostSearch extends Post
{

    public $text;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'type', 'new', 'status'], 'integer'],
            [['title', 'text', 'time', 'image_name', 'article'], 'safe'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Post::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
            'sort'=> ['defaultOrder' => ['status'=>SORT_ASC,'time'=>SORT_DESC]]

        ]);

        $this->load($params,'ProductTextSearch');

        $query_array = ['or',
            ['like', 'name', trim($this->text)],
            ['like', 'article', trim($this->text)],
            ['like', 'text', trim($this->text)],
        ];

        $synonyms = Synonym::getSynonyms($this->text);
        foreach ($synonyms as $syn) {
            $query_array[] = ['like', 'name', $syn];
            $query_array[] = ['like', 'text', $syn];
        }

        $query->andFilterWhere($query_array);


        return $dataProvider;
    }
}
