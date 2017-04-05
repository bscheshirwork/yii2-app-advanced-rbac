<?php

namespace backend\tests\acceptance;

use \backend\tests\AcceptanceTester;
use common\fixtures\UserFixture;
use common\tests\Page\Login as LoginPage;
use backend\tests\Page\AdminRoutePage as RoutePage;
use Yii;

class AdminCreateItemCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function routePage(AcceptanceTester $I)
    {
        $user = $I->grabFixture('user', 'user');
        $loginPage = new LoginPage($I);
        $loginPage->login($user->email, 'qwerty');
        $I->wait(2); // wait for page to be opened

        $I->wantTo('ensure that create route work');
        $page = new RoutePage($I);
        $page->amOnPage();
        $I->wait(2); // wait for page to be opened
        Yii::$app->getModule('admin');//i18n t-category 'rbac-admin'
        $I->see(Yii::t('rbac-admin', 'Routes'), 'h1');
        $page->addRoute('testroute');
        $I->wait(2); // wait for page to be opened
        $I->see('testroute');
    }
}