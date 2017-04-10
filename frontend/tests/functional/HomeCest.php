<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;
use Yii;

class HomeCest
{
    public function checkOpen(FunctionalTester $I)
    {
        $I->amOnPage(\Yii::$app->homeUrl);
        $I->see('My Company');
        $I->seeLink(Yii::t('main', 'About'));
        $I->click(Yii::t('main', 'About'));
        $I->see('This is the About page.');
    }
}