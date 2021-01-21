<?php

namespace app\models\auction;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%filter}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $name
 * @property string $value
 * @property integer $level
 * @property integer $parent
 * @property string $active
 * @property integer column_view
 *
 * @property Category $category
 */
class Filter extends \yii\db\ActiveRecord
{
    const COLUMN_VIEW_1 = 1;
    const COLUMN_VIEW_2 = 2;

    const ACTIVE_FLAG = 'Y';
    const DISABLE_FLAG = 'N';
    public static function getArActive () {
        return [
            self::ACTIVE_FLAG => 'Активный',
            self::DISABLE_FLAG => 'Отключен',
        ];
    }

    const LEVEL_PARENT = 1;
    const LEVEL_CHILD = 2;
    public static function getArLevel () {
        return [
            self::LEVEL_PARENT => 'Родительский',
            self::LEVEL_CHILD => 'Дочерний',
        ];
    }

    const CACHE_AR_ALL_KEY = "arAllFilter";

    public static function getArParent() {
        $arData = Filter::find()->where(['level' => static::LEVEL_PARENT])
            ->with('category')->asArray()->orderBy('category_id')->all();
        //return $arFilter;
        $arFilter = ArrayHelper::map($arData, 'id', function($data) {
            $name = $data["name"];
            if (isset($data['category']) && isset($data['category']['name'])) {
                $name = "{$data['category']['name']} - $name";
            }
            return $name;
        });
        $arFilter[0] = 'Без родителя(0)';
        return $arFilter;
    }

    public static function getArAll() {
        $cache = Yii::$app->cache;
        $arFilter = $cache->get(self::CACHE_AR_ALL_KEY);
        if ($arFilter === false) {
            $arData = Filter::find()->where(['level' => self::LEVEL_CHILD])
                ->with('category')->asArray()->orderBy('category_id')->all();
            //return $arFilter;
            $arFilter = ArrayHelper::map($arData, 'id', function($data) {
                $name = $data["name"];
                if (isset($data['category']) && isset($data['category']['name'])) {
                    $name .= " ({$data['category']['name']})";
                }
                if ($parent = Filter::findOne($data["parent"])) {
                    $name .= " - $parent->name";
                }
                return $name;
            });
            $cache->set(static::CACHE_AR_ALL_KEY, $arFilter, 7200);
        }

        return $arFilter;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%filter}}';
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'name', 'level', 'parent', 'active'], 'required'],
            [['category_id', 'level', 'parent'], 'integer'],
            [['name', 'value'], 'string', 'max' => 255],
            [['active'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'name' => 'Имя',
            'value' => 'Значение',
            'level' => 'Уровень',
            'parent' => 'Родитель',
            'active' => 'Активность',
        ];
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        Yii::$app->cache->delete(static::CACHE_AR_ALL_KEY);
    }
}
