<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 29.10.2016
 * Time: 13:40
 */

namespace app\modules\admin\widgets\my_editable;


use kartik\widgets\AssetBundle;

class MyEditableAsset extends AssetBundle {
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath('@app/modules/admin/widgets/my_editable/assets');
        $this->setupAssets('css', ['css/editable']);
        $this->setupAssets('js', ['js/editable']);
        parent::init();
    }
}