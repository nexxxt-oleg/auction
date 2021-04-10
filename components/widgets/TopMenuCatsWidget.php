<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 19.07.2016
 * Time: 14:21
 */

namespace app\components\widgets;

use app\models\auction\Auction;
use app\models\auction\Category;
use yii\base\Widget;

class TopMenuCatsWidget extends Widget{
    public $arCategory;
    public $type;
    public $mobile = false;
    protected $nextAuction;
    protected $auFlag;

    public function init(){
        parent::init();
        $this->arCategory = Category::find()->where(['active' => 'Y'])->andWhere(['<=', 'priority', '2'])->all();
        if(isset($_REQUEST['GoodSearch']['next_flag'])) {$this->auFlag = $_REQUEST['GoodSearch']['next_flag'];}
        else {
            /** @var  $nextAuction Auction */
            $nextAuction = Auction::find()->notTest()->andWhere([
                'active' => Auction::ACTIVE_FLAG,
            ])->one();
            $this->auFlag = Auction::ACTIVE_FLAG;
            if (!$nextAuction) {
                $nextAuction = Auction::find()->notTest()->andWhere([
                    'active' => Auction::NEAREST_FLAG,
                ])->one();
                $this->auFlag = Auction::NEAREST_FLAG;
                if (!$nextAuction) {
                    $nextAuction = Auction::find()->notTest()->andWhere([
                        'active' => Auction::PAST_FLAG,
                    ])->orderBy(['end_date' => SORT_DESC])->one();
                    $this->auFlag = Auction::PAST_FLAG;
                    if (!$nextAuction) {
                        $this->auFlag = Auction::DISABLE_FLAG;
                    }
                }
            }
        }


    }

    public function run(){
        return $this->render($this->mobile ? '_top_menu_category_m' : '_top_menu_category', [
            'arCatModel' => $this->arCategory,
            'type' => $this->type,
            'auFlag' => $this->auFlag,
        ]);
    }
}
?>
