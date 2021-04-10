<?php

namespace app\commands;

use app\components\CommonHelper;
use app\modules\admin\models\RobotInterval;
use app\models\auction\Auction;
use app\models\auction\Bid;
use app\models\auction\Good;
use Yii;
use yii\console\Controller;
use app\models\auth\User;
use app\modules\admin\models\GoodRobot;
use yii\helpers\Html;

class BidRobotController extends Controller {
    /** @var bool  */
    public $test = false;

    /** @@var \yii\i18n\Formatter */
    protected $f;

    /** @var User[] */
    protected $arDummyUser;

    /** @var  \app\modules\admin\models\RobotInterval[] */
    protected $arInterval;

    public function actionProcess() {
        $this->f = new \yii\i18n\Formatter();
        $this->f->timeZone = 'Europe/Moscow';

        $this->arDummyUser = GoodRobot::arDummyUser();
        $this->arInterval = RobotInterval::find()->indexBy('value')->all();//GoodRobot::arInterval();

        // проверить наличие пользователей-роботов
        if ($this->initDummyUsers()) {
            // набросать лоты из активного аукциона в таблицу торгов
            if (/*$this->initGoods()*/ true) {
                // обновить значения таблицы, создать ставки при необходимости
                $this->makeBids();
            }

        }

    }

    protected function chooseBid() {}

    /**
     * @param $u User
     * @return bool
     */
    protected function inDummyUserArray($u) {
        if ($u instanceof User) {
            foreach ($this->arDummyUser as $dummy) {
                if ($dummy->id == $u->id) {return true;}
            }
        }
        return false;
    }

    protected function makeBids() {
        /** @var \app\modules\admin\models\GoodRobot $goodRobot */
        foreach (GoodRobot::find()->where(['!=', 'status', GoodRobot::STATUS_PAST])->all() as $goodRobot) {
            /** @var Bid $maxBid */
            $maxBid = $goodRobot->good->getBids()->orderBy(['value' => SORT_DESC])->one();
//            echo "now: ".CommonHelper::getUnixEpoch()."\n";
//            echo "in db: ".(CommonHelper::getUnixEpoch($goodRobot, 'bid_time')+ $goodRobot->bid_interval*60)."\n";
            if (CommonHelper::getUnixEpoch() >= (CommonHelper::getUnixEpoch($goodRobot, 'bid_time') + $goodRobot->bid_interval*60)
                /*&& (!$maxBid || ($maxBid && !$this->inDummyUserArray($maxBid->user)))*/) {
                $userKey = array_rand($this->arDummyUser);
                $dummyUser = $this->arDummyUser[$userKey];
                $dummyBid = new Bid();
                $dummyBid->user_id = $dummyUser->id;
                $dummyBid->good_id = $goodRobot->good_id;
                $needBidFlag = true;

                $goodRobot->status = ($goodRobot->good->curr_price >= $goodRobot->good->accept_price
                    || (CommonHelper::getUnixEpoch() + $goodRobot->bid_interval*60 - 60) >= CommonHelper::getUnixEpoch($goodRobot, ['good', 'auction', 'end_date'])) ? GoodRobot::STATUS_PAST :GoodRobot::STATUS_ACTIVE;

                if ($goodRobot->status == GoodRobot::STATUS_PAST) {
                    if ($goodRobot->good->curr_price < $goodRobot->good->accept_price) {
                        $bidVal = $goodRobot->good->accept_price;
                    } else { $needBidFlag = false;}
                } else {
                    $bidVal = round($goodRobot->good->curr_price + $goodRobot->good->step, -2);
                    if (rand(0,100) < 15) {
                        $bidVal = round(($goodRobot->good->curr_price + ($goodRobot->good->accept_price - $goodRobot->good->curr_price) / 2));
                        $bidVal = ($bidVal < ($goodRobot->good->curr_price + $goodRobot->good->step*3)) ? $bidVal = $goodRobot->good->curr_price + $goodRobot->good->step*3 : $bidVal;
                    }
                    if (($goodRobot->good->accept_price - $goodRobot->good->curr_price) < $goodRobot->good->step*3) {
                        if ($goodRobot->good->curr_price < $goodRobot->good->accept_price) {
                            $bidVal = $goodRobot->good->accept_price;
                        }
                        $goodRobot->status = GoodRobot::STATUS_PAST;
                    }
                    if ($bidVal < 100) {$bidVal = 100;}
                }

                if($needBidFlag) {
                    $dummyBid->value = $bidVal;
                    if (!$dummyBid->save()) {
                        $subject = "Ошибка при попытке создать запись ".$dummyBid->className();
                        $body = "Ошибка при попытке создать запись ".$dummyBid->className().": good_id - $dummyBid->good_id. \n";
                        $body .= Html::errorSummary($dummyBid);
                        CommonHelper::mail_log($subject, $body);
                        return false;
                    }
                    $goodRobot->bid_id = $dummyBid->id;
                }


                $goodRobot->bid_interval = array_rand($this->arInterval);
                if (!$goodRobot->save()) {
                    $subject = "Ошибка при попытке создать запись ".$goodRobot->className();
                    $body = "Ошибка при попытке создать запись ".$goodRobot->className().": good_id - $goodRobot->good_id. \n";
                    $body .= Html::errorSummary($goodRobot);
                    CommonHelper::mail_log($subject, $body);
                    return false;
                }


            }
        }
        return true;
    }

    protected function initGoods() {
        $arActiveAuction = Auction::find()->where(['active' => Auction::ACTIVE_FLAG, 'is_test' => $this->test])->all();
        /** @var Auction $activeAuction */
        foreach ($arActiveAuction as $activeAuction) {
            /** @var Good $good */
            foreach ($activeAuction->getGoods()->where(['sell_rule' => [Good::SELL_RULE_MIN, Good::SELL_RULE_NO]])->all() as $good) {
                if(!$goodRobot = GoodRobot::findOne(['good_id' => $good->id])) {
                    $goodRobot = new GoodRobot();
                    $goodRobot->good_id = $good->id;
                    $goodRobot->status = GoodRobot::STATUS_NEW;
                    $goodRobot->bid_interval = array_rand($this->arInterval);
                    if(!$goodRobot->save()) {
                        $subject = "Ошибка при попытке создать запись ".$goodRobot->className();
                        $body = "Ошибка при попытке создать запись ".$goodRobot->className().": good_id - $goodRobot->good_id. \n";
                        $body .= Html::errorSummary($goodRobot);
                        CommonHelper::mail_log($subject, $body);
                        return false;
                    }
                }
            }
        }
        return true;
    }

    protected function initDummyUsers() {
        /** @var User $user */
        /** @var User|null $dbUser */
        foreach ($this->arDummyUser as $user) {
            if(!$dbUser = User::findOne($user->id)) {
                if(!$user->save()) {
                    $subject = "Ошибка при попытке добавить пользователя $user->name($user->id)";
                    $body = "Ошибка при попытке добавить пользователя $user->name($user->id)\n";
                    $body .= Html::errorSummary($user);
                    CommonHelper::mail_log($subject, $body);
                    return false;
                }
            }
        }
        return true;
    }
}
