<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use frontend\fixtures\TokenFixture;
use dektrium\user\models\User;
use dektrium\user\models\Token;
use frontend\tests\Page\RegistrationConfirm as ConfirmPage;
use Yii;

class ConfirmationCest
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
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
            'token' => [
                'class' => TokenFixture::class,
                'dataFile' => codecept_data_dir() . 'token.php'
            ],
        ];
    }

    /**
     * @param FunctionalTester $I
     */
    public function confirmUser(FunctionalTester $I)
    {
        $I->wantTo('ensure that confirmation works');
        $page = new ConfirmPage($I);

        $I->amGoingTo('check that error is showed when token expired');
        $token = $I->grabFixture('token', 'expired_confirmation');
        $page->check(['id' => $token->user_id, 'code' => $token->code]);
        $I->see(Yii::t('user', 'The confirmation link is invalid or expired. Please try requesting a new one.'));

        $I->amGoingTo('check that user get confirmed');
        $token = $I->grabFixture('token', 'confirmation');
        $page->check(['id' => $token->user_id, 'code' => $token->code]);
        $I->see(Yii::t('user', 'Thank you, registration is now complete.'));
        $I->dontSeeRecord(Token::class, ['user_id' => $token->user_id, 'type' => Token::TYPE_CONFIRMATION]);
        $user = $I->grabRecord(User::class, ['id' => $token->user_id]);
        $I->see($user->username);
    }
}