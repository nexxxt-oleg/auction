<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 25.07.2016
 * Time: 10:19
 */

namespace app\components\shop;


use yii\base\BaseObject;
use yz\shoppingcart\CartPositionInterface;

class PositionContainer extends BaseObject
{
    /** @var CartPositionInterface[] */
    public $positions;
    /** @var CartPositionInterface[] */
    public $viewed;
    /** @var CartPositionInterface[] */
    public $favorite;

    /**
     * @param $cart MyShoppingCart
     */
    public function load($cart) {
        $this->positions = $cart->_positions;
        $this->viewed = $cart->_positionsViewed;
        $this->favorite = $cart->_positionsFavorite;
    }
}