<?php

namespace frontend\tests\functional;

use \frontend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use frontend\tests\Page\Resend as ResendPage;

class ResendCest
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
        $I->wantTo('ensure that resending of confirmation tokens works');
        $page = new ResendPage($I);

        $I->amGoingTo('try to resend token to non-existent user');
        $page->resend('foo@example.com');
        $I->see('A message has been sent to your email address. It contains a confirmation link that you must click to complete registration.');

        $I->amGoingTo('try to resend token to already confirmed user');
        $user = $I->grabFixture('user', 'user');
        $page->resend($user->email);
        $I->see('A message has been sent to your email address. It contains a confirmation link that you must click to complete registration.');

        $I->amGoingTo('try to resend token to unconfirmed user');
        $user = $I->grabFixture('user', 'unconfirmed');
        $page->resend($user->email);
        $I->see('A message has been sent to your email address. It contains a confirmation link that you must click to complete registration.');
    }
}