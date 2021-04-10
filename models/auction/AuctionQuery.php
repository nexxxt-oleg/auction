<?php
namespace app\models\auction;

use yii\db\ActiveQuery;

/**
 * Class AuctionQuery
 * @package app\models\auction
 */
class AuctionQuery extends ActiveQuery
{
    /** @return $this */
    public function test()
    {
        $this->andWhere(['is_test' => true]);
        return $this;
    }

    /** @return $this */
    public function notTest()
    {
        $this->andWhere(['is_test' => false]);
        return $this;
    }
}