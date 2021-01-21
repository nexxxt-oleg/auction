<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 19.07.2016
 * Time: 14:21
 */

namespace app\components\widgets;

use app\components\shop\MyShoppingCart;
use yii\base\Widget;

class FavoriteGoodsWidget extends Widget{
    public $type;
    /** @var  MyShoppingCart */
    protected $cart;

    public function init(){
        parent::init();
        $this->cart = new MyShoppingCart();
    }

    public function run(){
        return $this->render('_favorite_goods', [
            'type' => $this->type,
            'cart' => $this->cart,
        ]);
    }
}
?>
