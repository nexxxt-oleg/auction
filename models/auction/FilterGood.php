<?php

namespace app\models\auction;

use Yii;

/**
 * This is the model class for table "{{%good_filters}}".
 *
 * @property integer $id
 * @property integer $good_id
 * @property integer $filter_id
 */
class FilterGood extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%good_filters}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['good_id', 'filter_id'], 'required'],
            [['good_id', 'filter_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'good_id' => 'Good ID',
            'filter_id' => 'Filter ID',
        ];
    }
}
