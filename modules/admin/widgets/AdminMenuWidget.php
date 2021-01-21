<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 19.07.2016
 * Time: 14:21
 */

namespace app\modules\admin\widgets;

use app\components\shop\MyShoppingCart;
use yii\base\Widget;

class AdminMenuWidget extends Widget{
    public function run(){
        return $this->render('_admin_menu', [

        ]);
    }
}
?>
