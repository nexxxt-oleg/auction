<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 18.07.2016
 * Time: 13:16
 */

namespace app\models\auction;

use app\components\CommonHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class GoodSearch extends Good
{
    public $filter_id;
    public $price;
    public $top_menu;
    public $next_flag;
    protected $minPrice = 0, $maxPrice = 0;

    public $searchString;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auction_id', 'start_price', 'accept_price', 'end_price', 'curr_bid_id', 'win_bid_id', 'status', ], 'integer'],
            [['name', 'description', 'searchString'], 'string', 'max' => 255],
            [['filter_id', 'category_id'], 'each', 'rule' => ['integer']],
            [['price', 'top_menu', 'next_flag'], 'safe'],
        ];
    }

    public  function attributeLabels() {
        return array_merge([
            'filter_id' => 'Идентификатор фильтра',
            'price' => 'Диапазон цен',
            'searchString' => 'Название предмета',
        ], parent::attributeLabels());
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Good::find();
        $auTable = Auction::tableName();
        $goodTable = Good::tableName();
        $query->innerJoin($auTable, "$auTable.id = $goodTable.auction_id");
        $query->orderBy(["$auTable.id" => SORT_DESC, "$goodTable.category_id" => SORT_ASC, "$goodTable.start_price" => SORT_DESC, ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize'     => 25,
                'totalCount' => $query->count(),
                //'route' => ''
            ]
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->top_menu) {
            if ($this->top_menu == 'all') {}
            elseif ($this->top_menu == 'next') {
                /** @var Auction|null $auNearest */
                if($auNearest = Auction::find()->where(['active' => $this->next_flag])->orderBy(['end_date' => SORT_DESC, 'start_date' => SORT_DESC])->one()) {
                    $query->andWhere([
                        "$auTable.id" => $auNearest->id
                    ]);
                }

            }
            elseif (strpos($this->top_menu, 'category') !== false) {
                $catId = substr($this->top_menu, 8);
                // add category from top menu to request params
                $params[CommonHelper::getShortClassName(self::className())]['category_id'] = [$catId=>$catId];
                if (!($this->load($params) && $this->validate())) {
                    return $dataProvider;
                }
            }
        }

        $query->andFilterWhere(['category_id' => $this->category_id]);
        if (!empty($this->filter_id)) {
            $fgTable = FilterGood::tableName();
            $query->innerJoin($fgTable, "$fgTable.good_id = {$this->tableName()}.id");
            $query->andFilterWhere(["$fgTable.filter_id" => $this->filter_id]);
        }


        //$priceSql = "select min(start_price) as min_price, max(start_price) as max_price from au_good";
        //$arPrice = Yii::$app->db->createCommand($priceSql)->queryOne();
        $arPrice = Query::create($query)->select('min(start_price) as min_price, max(start_price) as max_price')->one();
        if (isset($arPrice['min_price']) && isset($arPrice['max_price'])) {
            $this->minPrice = $arPrice['min_price'];
            $this->maxPrice = $arPrice['max_price'];
        }
        if (!$this->price) {
            // need to init price slider
            $this->price = "$this->minPrice;$this->maxPrice";
        }

        if ($this->price) {
            $arPrice = explode(';', $this->price);
            if (isset($arPrice[0]) && isset($arPrice[1])) {
                $query->andFilterWhere(['between', 'start_price', $arPrice[0], $arPrice[1]]);
            }
        }

        return $dataProvider;
    }

    /**
     * @param $form \yii\bootstrap\ActiveForm
     * @return string
     *
     */
    public function renderLeftForm($form) {
        // price slider
        $arPrice = explode(';', $this->price);
        $arSliderParams = ['class' => 'price-slider'];
        if (isset($arPrice[0]) && isset($arPrice[1])) {
            $arSliderParams['data-from'] = $arPrice[0];
            $arSliderParams['data-to'] = $arPrice[1];
            $arSliderParams['data-min'] = $this->minPrice;
            $arSliderParams['data-max'] = $this->maxPrice;

        }
        $html = '<div class="auction-sort__slider">
            <p>цена, руб.:</p>
            '.$form->field($this, 'price')->input('text', $arSliderParams)->label(false).'

            </div>';

        $catTable = Category::tableName();
        $catFinder = Category::find()->where(["$catTable.active" => Category::ACTIVE_ACTIVE])->orderBy(['priority' => SORT_ASC, 'id' => SORT_DESC]);

        $disableInput = '';
        if ($this->top_menu) {
            $auCatTable = AuctionCategories::tableName();
            $auTable = Auction::tableName();
            $catFinder->innerJoin($auCatTable, "$auCatTable.category_id = $catTable.id");
            $catFinder->innerJoin($auTable, "$auTable.id = $auCatTable.auction_id");
            if ($this->top_menu == 'all') {}
            elseif ($this->top_menu == 'next') {
                /** @var Auction|null $auNearest */
                if($auNearest = Auction::find()->where(['active' => $this->next_flag])->orderBy(['end_date' => SORT_DESC, 'start_date' => SORT_DESC])->one()) {
                    $catFinder->andWhere([
                        "$auTable.id" => $auNearest->id
                    ]);
                }
            }
            elseif (strpos($this->top_menu, 'category') !== false) {
                $catId = substr($this->top_menu, 8);
                $catFinder->andFilterWhere(['category_id' => $catId]);
                $disableInput = 'disabled';
            }
        }

        $arCategory = $catFinder->all();
        /** @var $category Category */
        foreach ($arCategory as $category) {
            $html .= $form->field($this, "category_id[$category->id]", [
                'options' => ['class' => 'form__group form__group--title',],
                'template' => "\n{input}\n{beginLabel}\n$category->name\n{endLabel}\n{error}\n{hint}\n",
                'labelOptions' => ['class' => ''],
            ])->checkbox(['id' => "goodsearch-category".$category->id, 'value' => $category->id, 'uncheck' => null, $disableInput=>''], false);

            // category
            $arParentFilter = Filter::find()->where(['category_id' => $category->id, 'level' => 1])->all();
            /** @var $parentFilter Filter */
            foreach ($arParentFilter as $parentFilter) {
                $html .= '<ul class="auction-sort__list">';
                $html .= "<div class='auction-sort__list-title'>
                    $parentFilter->name
                    </div>";

                // filter
                $arFilter = Filter::find()->where(['parent' => $parentFilter->id, 'level' => 2])->all();
                /** @var $filter Filter */
                foreach ($arFilter as $filter) {
                    $goodCntFinder = Good::find()
                        ->innerJoin('{{%good_filters}}', '{{%good_filters}}.good_id = {{%good}}.id')
                        ->where(['{{%good_filters}}.filter_id' => $filter->id]);
                    if (isset($auNearest) && $auNearest) {
                        $goodCntFinder->andWhere([Good::tableName().".auction_id" => $auNearest->id]);
                    }
                    $goodCnt = $goodCntFinder->count();
                    if ($goodCnt > 0) {
                        $html .= $form->field($this, "filter_id[$filter->id]", [
                            'options' => ['class' => ''],
                            'template' => "<li class=\"form__group\">\n{input}\n{beginLabel}\n$filter->name\n{endLabel}\n{error}\n{hint}\n</li>",
                            'labelOptions' => ['class' => ''],
                        ])->checkbox(['id' => "goodsearch-filter".$filter->id, 'value' => $filter->id, 'uncheck' => null], false);
                    }

                }
                $html .= '<div class="clearfix"></div></ul>';
            }
        }
        return $html;
    }
}