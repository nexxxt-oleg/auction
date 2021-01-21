<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 19.07.2016
 * Time: 14:21
 */

namespace app\components\widgets;

use app\models\auction\Category;
use app\models\auction\GoodSearch;
use yii\base\Widget;

class FooterCatsWidget extends Widget{
    public $arCategory;
    public $type;
    public $goodReflection;
    /** @var array Шаблон для вывода фильтров. Определяет какую часть должен занимать каждый из столбцов.
     *  Значения поддерживаемые версткой на данный момент: 33, 50, 60. В сумме элементы мыссива должны быть равны 100*/
    public $filterMakeupTemplate = ['66', '33'];
    /** @var array Классы для первой и второй категории в шаблоне main. Если количество категорий для отображения вырастет, необходимо соответствующие классы прописать сюда. */
    public $categoryTemplateClass = ['class="col-xs-12 col-sm-6 col-md-4"', 'class="col-xs-12 col-sm-2 col-md-2"'];

    public function init(){
        parent::init();
        $this->arCategory = Category::find()->where(['active' => 'Y'])->andWhere(['<=', 'priority', '2'])->limit(2)->all();
        $this->goodReflection = new \ReflectionClass(GoodSearch::className());
    }

    public function run(){
        return $this->render('_footer_category', [
            'arCatModel' => $this->arCategory,
            'type' => $this->type,
            'goodReflection' => $this->goodReflection,
            'filterMakeupTemplate' => $this->filterMakeupTemplate,
            'categoryTemplateClass' => $this->categoryTemplateClass,
        ]);
    }
}
?>
