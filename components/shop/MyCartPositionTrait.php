<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 06.08.2016
 * Time: 13:12
 */

namespace app\components\shop;


use yii\base\Component;
use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CartPositionTrait;
use yz\shoppingcart\CostCalculationEvent;

trait MyCartPositionTrait
{
    use CartPositionTrait;

    /**
     * Default implementation for getCost function. Cost is calculated as price * quantity
     * @param bool $withDiscount
     * @return int
     */
    public function getCost($withDiscount = true)
    {
        /** @var Component|CartPositionInterface|self $this */
        $cost = $this->getPrice();
        $costEvent = new CostCalculationEvent([
            'baseCost' => $cost,
        ]);
        if ($this instanceof Component)
            $this->trigger(CartPositionInterface::EVENT_COST_CALCULATION, $costEvent);
        if ($withDiscount)
            $cost = max(0, $cost - $costEvent->discountValue);
        return $cost;
    }

}