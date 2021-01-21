<?php
/**
 * Created by PhpStorm.
 * User: Shoy
 * Date: 26.10.2016
 * Time: 15:41
 */

namespace app\modules\admin\widgets\my_editable;


use app\modules\admin\widgets\my_editable\MyEditableAsset;
use kartik\editable\Editable;
use kartik\editable\EditablePjaxAsset;
use yii\web\View;

class MyEditable extends Editable {
    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        MyEditableAsset::register($view);
        $this->pluginOptions = [
            'valueIfNull' => $this->valueIfNull,
            'asPopover' => $this->asPopover,
            'placement' => $this->placement,
            'target' => $this->format === self::FORMAT_BUTTON ? '.kv-editable-button' : '.kv-editable-link',
            'displayValueConfig' => $this->displayValueConfig,
            'showAjaxErrors' => $this->showAjaxErrors,
            'ajaxSettings' => $this->ajaxSettings,
            'submitOnEnter' => $this->submitOnEnter,
            'encodeOutput' => $this->encodeOutput,
        ];
        $this->registerPlugin('editable', 'jQuery("#' . $this->containerOptions['id'] . '")');
        if (!empty($this->pjaxContainerId)) {
            EditablePjaxAsset::register($view);
            $toggleButton = $this->_popoverOptions['toggleButton']['id'];
            $initPjaxVar = 'kvEdPjax_' . str_replace('-', '_', $this->_popoverOptions['options']['id']);
            $view->registerJs("var {$initPjaxVar} = false;", View::POS_HEAD);
            if ($this->asPopover) {
                $js = "initEditablePjax('{$this->pjaxContainerId}', '{$toggleButton}', '{$initPjaxVar}');";
                $view->registerJs($js);
            }
        }
    }
}