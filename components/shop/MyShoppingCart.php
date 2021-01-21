<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 25.07.2016
 * Time: 9:12
 */

namespace app\components\shop;

use app\models\auction\Good;
use app\models\auction\GoodCart;
use app\models\auction\GoodFavorite;
use app\models\auction\GoodViewed;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\web\Session;
use yz\shoppingcart\CartActionEvent;
use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CostCalculationEvent;
use yz\shoppingcart\ShoppingCart;

class MyShoppingCart extends ShoppingCart{
    /** @brief Количество лотов, хранящихся в просмотренных  */
    const COUNT_VIEWED = 15;
    /** @brief Количество лотов, хранящихся в избранных  */
    const COUNT_FAVORITE = 15;

    /**
     * @var CartPositionInterface[]
     */
    protected $_positionsViewed = [];
    /**
     * @var CartPositionInterface[]
     */
    protected $_positionsFavorite = [];

    protected $_userId;
    public $userId;


    public function init() {
        $this->_userId = ($this->userId) ? $this->userId : \Yii::$app->user->id;
        parent::init();
        $this->loadFromDb();
    }

    /**
     * Loads cart from database
     */
    public function loadFromDb()
    {
        $this->_positionsFavorite = Good::findAll(
            ArrayHelper::getColumn(GoodFavorite::find()->where(['user_id' => $this->_userId])->all(), 'good_id')
        );
        $this->_positionsViewed = Good::findAll(
            ArrayHelper::getColumn(GoodViewed::find()->where(['user_id' => $this->_userId])->all(), 'good_id')
        );
        $this->_positions = Good::findAll(
            ArrayHelper::getColumn(GoodCart::find()->where(['user_id' => $this->_userId])->all(), 'good_id')
        );
    }

    /**
     * @param string $serialized
     */
    public function setSerialized($serialized)
    {
        /** @var  $positionContainer PositionContainer */
        $positionContainer = unserialize($serialized);
        //$this->_positionsViewed = $positionContainer->viewed;
    }

    /**
     * Returns cart positions as serialized items
     * @return string
     */
    public function getSerialized()
    {
        $cont = $this->getPositions();
        return serialize($cont);
    }

    /**
     * @return PositionContainer
     */
    public function getPositions() {
        $cont = new PositionContainer();
        $cont->positions = $this->_positions;
        $cont->viewed = $this->_positionsViewed;
        $cont->favorite = $this->_positionsFavorite;
        return $cont;
    }


    /**
     * @param array $arPositions
     */
    public function setPositions($arPositions)
    {
        $this->_positions = array_filter($arPositions['positions'], function (CartPositionInterface $position) {
            return $position->quantity > 0;
        });
        $this->_positionsViewed = array_filter($arPositions['viewed'], function (CartPositionInterface $position) {
            return $position->quantity > 0;
        });
        $this->_positionsFavorite = array_filter($arPositions['favorite'], function (CartPositionInterface $position) {
            return $position->quantity > 0;
        });
        $this->trigger(self::EVENT_CART_CHANGE, new CartActionEvent([
            'action' => CartActionEvent::ACTION_SET_POSITIONS,
        ]));
        $this->saveToDb();
    }

    /**
     * Saves cart to the session
     */
    public function saveToDb()
    {
        $this->session = Instance::ensure($this->session, Session::className());
        $cont = $this->getPositions();
        foreach ($cont->favorite as $cartFavorite) {
            if(!$favoriteGoodModel = GoodFavorite::findOne(['good_id' => $cartFavorite->id, 'user_id' => $this->_userId])) {
                $favoriteGoodModel = new GoodFavorite();
                $favoriteGoodModel->user_id = $this->_userId;
                $favoriteGoodModel->good_id = $cartFavorite->id;
                $favoriteGoodModel->save();
            }
        }
        foreach ($cont->viewed as $cartViewed) {
            if(!$viewedGoodModel = GoodViewed::findOne(['good_id' => $cartViewed->id, 'user_id' => $this->_userId])) {
                $viewedGoodModel = new GoodViewed();
                $viewedGoodModel->user_id = $this->_userId;
                $viewedGoodModel->good_id = $cartViewed->id;
                $viewedGoodModel->save();
            }
        }
        foreach ($cont->positions as $cartPosition) {
            if(!$cartGoodModel = GoodCart::findOne(['good_id' => $cartPosition->id, 'user_id' => $this->_userId])) {
                $cartGoodModel = new GoodCart();
                $cartGoodModel->user_id = $this->_userId;
                $cartGoodModel->good_id = $cartPosition->id;
                $cartGoodModel->save();
            }
        }
        $this->session[$this->cartId] = serialize($cont);

    }

    /**
     * @param CartPositionInterface $position
     */
    public function putViewed($position)
    {
        if (!$this->hasPositionViewed($position->getId())) {
            $position->setQuantity(1);
            if (count($this->_positionsViewed) >= self::COUNT_VIEWED) {
                $toDeleteViewed = array_pop($this->_positionsViewed);
                $this->removeViewed($toDeleteViewed);
            }
            array_unshift($this->_positionsViewed, $position);
        }

        $this->saveToDb();
    }

    /**
     * @param CartPositionInterface $position
     */
    public function putFavorite($position)
    {
        if (!$this->hasPositionFavorite($position->getId())) {
            $position->setQuantity(1);
            if (count($this->_positionsFavorite) >= self::COUNT_FAVORITE) {
                $toDeleteFavorite = array_pop($this->_positionsFavorite);
                $this->removeFavorite($toDeleteFavorite);
            }
            array_unshift($this->_positionsFavorite, $position);
        }

        $this->saveToDb();

    }

    /**
     * @param CartPositionInterface $position
     */
    public function put($position, $quantity = 1)
    {
        if (!isset($this->_positions[$position->getId()])) {
            $position->setQuantity(1);
            $this->_positions[$position->getId()] = $position;
        }
        $this->trigger(self::EVENT_POSITION_PUT, new CartActionEvent([
            'action' => CartActionEvent::ACTION_POSITION_PUT,
            'position' => $this->_positions[$position->getId()],
        ]));
        $this->trigger(self::EVENT_CART_CHANGE, new CartActionEvent([
            'action' => CartActionEvent::ACTION_POSITION_PUT,
            'position' => $this->_positions[$position->getId()],
        ]));
        $this->saveToDb();
    }

    /**
     * Removes favorite position from the cart
     * @param CartPositionInterface $position
     */
    public function removeFavorite($position)
    {
        $favoriteGoodId = $position->getId();
        /** @var GoodFavorite $favoriteGood */
        if($favoriteGood = GoodFavorite::findOne(['good_id' => $favoriteGoodId, 'user_id' => $this->_userId])) {
            $favoriteGood->delete();
        }
        unset($this->_positionsFavorite[$favoriteGoodId]);
        $this->saveToSession();
    }

    /**
     * Removes viewed position from the cart
     * @param CartPositionInterface $position
     */
    public function removeViewed($position)
    {
        $viewedGoodId = $position->getId();
        /** @var GoodFavorite $viewedGood */
        if($viewedGood = GoodViewed::findOne(['good_id' => $viewedGoodId, 'user_id' => $this->_userId])) {
            $viewedGood->delete();
        }
        unset($this->_positionsViewed[$viewedGoodId]);
        $this->saveToSession();
    }

    /**
     * Checks whether cart viewed position exists or not
     * @param string $id
     * @return bool
     */
    public function hasPositionViewed($id) {
        foreach ($this->_positionsViewed as $posViewed) {
            if ($posViewed->getId() == $id) {return true;}
        }
        return false;
    }

    /**
     * Checks whether cart favorite position exists or not
     * @param string $id
     * @return bool
     */
    public function hasPositionFavorite($id)
    {
        foreach ($this->_positionsFavorite as $posFavorite) {
            if ($posFavorite->getId() == $id) {return true;}
        }
    }

    /**
     * @param null $auctionId
     * @return int
     */
    public function getCountViewed($auctionId = null) {
        $cnt = 0;
        /** @var Good $posViewed */
        foreach ($this->_positionsViewed as $posViewed) {
            if (!is_null($auctionId)) {
                if ($posViewed->auction_id == $auctionId) {
                    $cnt++;
                }
            } else {$cnt++;}

        }
        return $cnt;
    }

    /**
     * @param null $auctionId
     * @return int
     */
    public function getCountFavorite($auctionId = null) {
        $cnt = 0;
        /** @var Good $posFavorite */
        foreach ($this->_positionsFavorite as $posFavorite) {
            if (!is_null($auctionId)) {
                if ($posFavorite->auction_id == $auctionId) {
                    $cnt++;
                }
            } else {$cnt++;}

        }
        return $cnt;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->_positions);
    }

    /**
     * Return full cart cost as a sum of the individual positions costs
     * @param $withDiscount
     * @return int
     */
    public function getCost($withDiscount = false)
    {
        $cost = 0;
        foreach ($this->_positions as $position) {
            $cost += $position->getCost($withDiscount);
        }
        $costEvent = new CostCalculationEvent([
            'baseCost' => $cost,
        ]);
        $this->trigger(self::EVENT_COST_CALCULATION, $costEvent);
        if ($withDiscount)
            $cost = max(0, $cost - $costEvent->discountValue);
        return \Yii::$app->formatter->asDecimal($cost);
    }

}