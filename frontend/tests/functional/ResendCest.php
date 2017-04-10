<?php

namespace frontend\tests\functional;

use \frontend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use frontend\tests\Page\Resend as ResendPage;
use Yii;

class ResendCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures(){
        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ];
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
        $I->see(Yii::t('user', 'A message has been sent to your email address. It contains a confirmation link that you must click to complete registration.'));

        $I->amGoingTo('try to resend token to already confirmed user');
        $user = $I->grabFixture('user', 'user');
        $page->resend($user->email);
        $I->see(Yii::t('user', 'A message has been sent to your email address. It contains a confirmation link that you must click to complete registration.'));

        $I->amGoingTo('try to resend token to unconfirmed user');
        $user = $I->grabFixture('user', 'unconfirmed');
        $page->resend($user->email);
        $I->see(Yii::t('user', 'A message has been sent to your email address. It contains a confirmation link that you must click to complete registration.'));
    }
}