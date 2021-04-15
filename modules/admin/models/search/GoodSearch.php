<?php

namespace app\modules\admin\models\search;

use app\components\CommonHelper;
use app\models\auction\Filter;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\auction\Good;

/**
 * GoodSearch represents the model behind the search form about `app\models\auction\Good`.
 */
class GoodSearch extends Good
{
    public $max_bid_date;
    public $bid_count;
    public $is_blitz_reached;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'auction_id', 'category_id', 'start_price', 'accept_price', 'end_price', 'curr_bid_id', 'win_bid_id', 'status', 'type', 'bid_count'], 'integer'],
            [['name', 'description', 'max_bid_date'], 'safe'],
            ['is_blitz_reached', 'boolean'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Good::find()->from(Good::tableName()." g");

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'g.id' => $this->id,
            'g.auction_id' => $this->auction_id,
            'g.category_id' => $this->category_id,
            'g.start_price' => $this->start_price,
            'g.accept_price' => $this->accept_price,
            'g.end_price' => $this->end_price,
            'g.curr_bid_id' => $this->curr_bid_id,
            'g.win_bid_id' => $this->win_bid_id,
            'g.status' => $this->status,
            'g.sell_rule' => $this->sell_rule,
            'g.is_blitz_reached' => $this->is_blitz_reached,
        ]);

        $query->andFilterWhere(['like', 'g.name', $this->name])
            ->andFilterWhere(['like', 'g.description', $this->description]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchBid($params)
    {
        $query = static::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['bid_count'=>SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['bid_count'] = [
            'asc' => ['bid_count' => SORT_ASC],
            'desc' => ['bid_count' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->select(['{{%good}}.*', 'count({{%bid}}.id) as bid_count']);
        $query->innerJoinWith('bids');
        $query->groupBy('{{%good}}.id');
        //$query->having(['>', 'bid_count', 0]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'auction_id' => $this->auction_id,
            'category_id' => $this->category_id,
            'start_price' => $this->start_price,
            'accept_price' => $this->accept_price,
            'end_price' => $this->end_price,
            'curr_bid_id' => $this->curr_bid_id,
            'win_bid_id' => $this->win_bid_id,
            'status' => $this->status,
            'sell_rule' => $this->sell_rule,
            'max_bid_date' => $this->max_bid_date,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);



        return $dataProvider;
    }




}
