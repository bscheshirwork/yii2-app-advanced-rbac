<?php

namespace frontend\tests\functional;

use \frontend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use common\tests\Page\Login as LoginPage;

class LoginCest
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
    public function loginUser(FunctionalTester $I)
    {
        $page = new LoginPage($I);

        $I->amGoingTo('try to login with empty credentials');
        $page->login('', '');
        $I->expectTo('see validations errors');
        $I->see('Login cannot be blank.');
        $I->see('Password cannot be blank.');

        $I->amGoingTo('try to login with unconfirmed account');
        $user = $I->grabFixture('user', 'unconfirmed');
        $page->login($user->email, 'qwerty');
        $I->see('You need to confirm your email address');

        $I->amGoingTo('try to login with blocked account');
        $user = $I->grabFixture('user', 'blocked');
        $page->login($user->email, 'qwerty');
        $I->see('Your account has been blocked');

        $I->amGoingTo('try to login with wrong credentials');
        $user = $I->grabFixture('user', 'user');
        $page->login($user->email, 'wrong');
        $I->expectTo('see validations errors');
        $I->see('Invalid login or password');

        $I->amGoingTo('try to login with correct credentials');
        $page->login($user->email, 'qwerty');
        $I->dontSee('Login');
        $I->see($user->username);

    }
}