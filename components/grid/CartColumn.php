<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 06.08.2016
 * Time: 12:57
 */

namespace app\components\grid;


use yii\grid\DataColumn;
use yii\helpers\Html;

class CartColumn extends DataColumn
{
    /**
     * Renders a data cell.
     * @param mixed $model the data model being rendered
     * @param mixed $key the key associated with the data model
     * @param integer $index the zero-based index of the data item among the item array returned by [[GridView::dataProvider]].
     * @return string the rendering result
     */
    public function renderDataCell($model, $key, $index)
    {
        if ($this->contentOptions instanceof \Closure) {
            $options = call_user_func($this->contentOptions, $model, $key, $index, $this);
        } else {
            $options = $this->contentOptions;
        }
        return Html::tag('th', $this->renderDataCellContent($model, $key, $index), $options);
    }

}