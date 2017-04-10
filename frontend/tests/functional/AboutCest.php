<?php
namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;
use Yii;

class AboutCest
{
    public function checkAbout(FunctionalTester $I)
    {
        $I->amOnRoute('site/about');
        $I->see(Yii::t('main', 'About', 'h1'));
    }
}
