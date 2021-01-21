<?php

namespace app\models\auction;

use Yii;

/**
 * This is the model class for table "{{%category_goods}}".
 *
 * @property integer $id
 * @property integer $good_id
 * @property integer $category_id
 */
class CategoryGood extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['good_id', 'category_id'], 'required'],
            [['good_id', 'category_id'], 'integer'],
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
            'category_id' => 'Category ID',
        ];
    }
}
