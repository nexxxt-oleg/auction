<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 19.07.2016
 * Time: 14:21
 */

namespace app\components\widgets;

use app\models\auction\GoodStringSearch;
use yii\base\Widget;

class SearchFormWidget extends Widget{
    public $type;
    public $model;

    public function init(){
        parent::init();
        if (!$this->model) {
            $this->model = new GoodStringSearch();
        }
    }

    public function run(){
        return $this->render('_search_form', [
            'type' => $this->type,
            'model' => $this->model,
        ]);
    }
}
?>
