<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 18.07.2016
 * Time: 13:16
 */

namespace app\models\auction;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class GoodStringSearch extends Good
{
    public $searchString;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['searchString'], 'string', 'max' => 255],
        ];
    }

    public  function attributeLabels() {
        return [
            'searchString' => 'Название предмета',
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Good::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->searchString]);

        return $dataProvider;
    }
}