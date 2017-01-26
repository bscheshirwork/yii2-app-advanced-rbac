<?php

namespace frontend\tests\functional;

use \frontend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use frontend\fixtures\TokenFixture;
use dektrium\user\models\User;
use dektrium\user\models\Token;
use frontend\tests\Page\RegistrationConfirm as ConfirmPage;

class ConfirmationCest
{
    public function _before(FunctionalTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
            'token' => [
                'class' => TokenFixture::className(),
                'dataFile' => codecept_data_dir() . 'token.php'
            ],
        ]);
    }

    /**
     * @param FunctionalTester $I
     */
    public function loginUser(FunctionalTester $I)
    {
        $I->wantTo('ensure that confirmation works');
        $page = new ConfirmPage($I);

        $I->amGoingTo('check that error is showed when token expired');
        $token = $I->grabFixture('token', 'expired_confirmation');
        $page->check(['id' => $token->user_id, 'code' => $token->code]);
        $I->see('The confirmation link is invalid or expired. Please try requesting a new one.');

        $I->amGoingTo('check that user get confirmed');
        $token = $I->grabFixture('token', 'confirmation');
        $page->check(['id' => $token->user_id, 'code' => $token->code]);
        $I->see('Thank you, registration is now complete.');
        $I->dontSeeRecord(Token::className(), ['user_id' => $token->user_id, 'type' => Token::TYPE_CONFIRMATION]);
        $user = $I->grabRecord(User::className(), ['id' => $token->user_id]);
        $I->see($user->username);
    }
}