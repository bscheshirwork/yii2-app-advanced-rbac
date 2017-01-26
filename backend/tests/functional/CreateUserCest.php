<?php

namespace backend\tests\functional;

use \backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use dektrium\user\models\User;
use common\tests\Page\Login as LoginPage;
use backend\tests\Page\CreateUser as CreatePage;
use Yii;

class CreateUserCest
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
    public function createUser(FunctionalTester $I)
    {
        $I->wantTo('ensure that user creation works');

        $loginPage = new LoginPage($I);
        $page = new CreatePage($I);

        $user = $I->grabFixture('user', 'user');
        $I->amLoggedInAs($user);

        $I->amGoingTo('try to create user with empty fields');
        $page->create('', '', '');
        $I->expectTo('see validations errors');
        $I->see('Username cannot be blank.');
        $I->see('Email cannot be blank.');

        $I->amGoingTo('try to create user');
        $page->create('foobar', 'foobar@example.com', 'foobar');
        $I->see('User has been created');
        $I->seeRecord(User::className(), ['username' => 'foobar']);

        Yii::$app->user->logout();

        $I->amGoingTo('login with new credentials');
        $loginPage->login('foobar@example.com', 'foobar');
        $I->see('foobar');
    }
}