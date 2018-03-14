<?php
namespace frontend\tests\acceptance;

use Yii;
use frontend\tests\AcceptanceTester;
use yii\helpers\Url;

class HomeCest
{
    public function checkHome(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/index'));
        $I->seeInPageSource(Yii::t('main','About'));
    }
}
