<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use backend\tests\Page\AdminRoutePage as RoutePage;

class AdminRouteIndexCest
{
    public function _before(FunctionalTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
    }

    /**
     * @param FunctionalTester $I
     */
    public function routePage(FunctionalTester $I)
    {
        $user = $I->grabFixture('user', 'user');
        $I->amLoggedInAs($user);

        $I->wantTo('ensure that create route work');
        $page = new RoutePage($I);
        $page->amOnPage();
        $I->see('Routes', 'h1');
    }
}