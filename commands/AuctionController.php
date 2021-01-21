<?php

namespace app\commands;

use app\components\CommonHelper;
use app\models\auction\Auction;
use app\models\auction\Bid;
use app\models\auction\Good;
use app\models\auction\GoodCart;
use app\models\Mail;
use Yii;
use yii\console\Controller;

class AuctionController extends Controller {
    public function actionProcess() {
        $f = new \yii\i18n\Formatter();
        $f->timeZone = 'GMT+3';
        $arNearestAuction = Auction::find()->where(['active' => Auction::NEAREST_FLAG])->all();
        /** @var Auction $nearestAuction */
        foreach ($arNearestAuction as $nearestAuction) {
            if (CommonHelper::getUnixEpoch() >= CommonHelper::getUnixEpoch($nearestAuction, 'start_date')) {
                echo "activate\n";
                $nearestAuction->active = Auction::ACTIVE_FLAG;
                $nearestAuction->active_date = date('Y-m-d');
                if($nearestAuction->save()) {
                    GoodCart::deleteAll();
                }
                echo "start auction $nearestAuction->name";
            }
        }

        $arActiveAuction = Auction::find()->where(['active' => Auction::ACTIVE_FLAG])->all();
        /** @var Auction $activeAuction */
        foreach ($arActiveAuction as $activeAuction) {
            if (CommonHelper::getUnixEpoch() >= CommonHelper::getUnixEpoch($activeAuction, 'end_date')) {
                echo "time is out\n";
                $activeAuction->active = Auction::PAST_FLAG;
                $activeAuction->save();
                echo "stop auction $activeAuction->name";

                /** @var Good $good */
                foreach ($activeAuction->goods as $good) {
                    $good->status = Good::STATUS_NOT_SOLD;
                    /** @var Bid $maxBid */
                    if($maxBid = $good->getBids()->orderBy(['value' => SORT_DESC])->one()) {
                        $sendEmailFlag = false;
                        if ($maxBid->value >= $good->accept_price) {
                            $good->status = Good::STATUS_SOLD;
                            $adminBid = new Bid();
                            $adminBid->user_id = 1;
                            $adminBid->good_id = $good->id;

                            switch ($good->sell_rule) {
                                case Good::SELL_RULE_ANY:
                                    $good->win_bid_id = $maxBid->id;
                                    $sendEmailFlag = true;
                                    break;
                                case Good::SELL_RULE_MIN:
                                    if ($maxBid->value >= $good->accept_price) {
                                        $good->win_bid_id = $maxBid->id;
                                        $sendEmailFlag = true;
                                    } else {
                                        $adminBid->value = intval($good->accept_price + $good->start_price * 0.05);
                                        $adminBid->save();
                                        $good->win_bid_id = $adminBid->id;
                                        $good->status = Good::STATUS_SOLD_TO_ADMIN;
                                    }
                                    break;
                                case Good::SELL_RULE_NO:
                                default:
                                    $adminBid->value = intval(intval($maxBid->value) + $good->start_price * 0.05);
                                    $adminBid->save();
                                    $good->win_bid_id = $adminBid->id;
                                    $good->status = Good::STATUS_SOLD_TO_ADMIN;
                            }
                        }

                        $good->save();

                        if ($sendEmailFlag) {
                            $subject = "Лот '$good->name' продан";
                            $body = "Лот '$good->name'($good->id) продан в соответствии с правилом: ";
                            $arSellRule = Good::arSellRule();
                            $goodAttr = '';
                            foreach ($good as $name => $value) {
                                $goodAttr .= "$name: $value\n";
                            }
                            $userAttr = '';
                            foreach ($maxBid->user as $name => $value) {
                                $userAttr .= "$name: $value\n";
                            }
                            $body .= $arSellRule[$good->sell_rule]."\n";
                            $body .= "Параметры лота: \n $goodAttr";
                            $body .= "Параметры пользователя: \n $userAttr";

                            Yii::$app->mailer->compose()
                                ->setTo(Yii::$app->params['samonovEmail'])
                                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                                ->setSubject($subject)
                                ->setTextBody($body)
                                ->send();

                            $mailModel = new Mail();
                            $mailModel->user_id = 1;
                            $mailModel->user_name = 'admin';
                            $mailModel->type = Mail::TYPE_GOOD_SOLD;

                            $mailModel->subject = $subject;
                            $mailModel->body = $body;
                            $mailModel->save();
                        }


                    }
                }
            }

        }

    }
}
