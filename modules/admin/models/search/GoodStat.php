<?php

namespace app\modules\admin\models\search;

use app\components\CommonHelper;
use app\models\auction\Filter;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\auction\Good;

/**
 * GoodStat represents the model behind the search form about `app\models\auction\Good`.
 */
class GoodStat extends Good
{
    public $cnt_viewed;
    public $cnt_favorite;
    public $cnt_bid;

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'cnt_viewed' => 'Количество просмотров',
            'cnt_favorite' => 'Количество в избранном',
            'cnt_bid' => 'Количество ставок',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'auction_id', 'category_id', 'start_price', 'accept_price', 'end_price', 'curr_bid_id', 'win_bid_id', 'status', 'type', 'cnt_viewed', 'cnt_favorite', 'cnt_bid'], 'integer'],
            [['name', 'description', ], 'safe'],
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
    public function searchViwed($params)
    {
        $query = static::find()->from(Good::tableName()." g")
            ->joinWith(['good_viewed v'])
            ->select(["g.*","count(v.id) as cnt_viewed"])
            ->groupBy("g.id");

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['cnt_viewed'=>SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['cnt_viewed'] = [
            'asc' => ['cnt_viewed' => SORT_ASC],
            'desc' => ['cnt_viewed' => SORT_DESC],
        ];

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
    public function searchFavorite($params)
    {
        $query = static::find()->from(Good::tableName()." g")
            ->joinWith(['good_favorite f'])
            ->select(["g.*","count(f.id) as cnt_favorite"])
            ->groupBy("g.id");

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['cnt_favorite'=>SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['cnt_favorite'] = [
            'asc' => ['cnt_favorite' => SORT_ASC],
            'desc' => ['cnt_favorite' => SORT_DESC],
        ];

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
        ]);

        $query->andFilterWhere(['like', 'g.name', $this->name])
            ->andFilterWhere(['like', 'g.description', $this->description]);

        return $dataProvider;
    }

}
