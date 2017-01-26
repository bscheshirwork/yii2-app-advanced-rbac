<?php

namespace frontend\tests\functional;

use \frontend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use frontend\fixtures\TokenFixture;
use dektrium\user\models\User;
use dektrium\user\models\Token;
use common\tests\Page\Login as LoginPage;
use frontend\tests\Page\Recovery as RecoveryPage;
use frontend\tests\Page\RecoveryReset as ResetPage;
use yii\helpers\Html;

class RecoveryCest
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
        $page = new RecoveryPage($I);
        $resetPage = new ResetPage($I);
        $loginPage = new LoginPage($I);

        $I->wantTo('ensure that password recovery works');

        $I->amGoingTo('try to request recovery token for unconfirmed account');
        $user = $I->grabFixture('user', 'unconfirmed');
        $page->recover($user->email);
        $I->see('An email has been sent with instructions for resetting your password');

        $I->amGoingTo('try to request recovery token');
        $user = $I->grabFixture('user', 'user');
        $page->recover($user->email);
        $I->see('An email has been sent with instructions for resetting your password');
        $user = $I->grabRecord(User::className(), ['email' => $user->email]);
        $token = $I->grabRecord(Token::className(), ['user_id' => $user->id, 'type' => Token::TYPE_RECOVERY]);
        /** @var yii\swiftmailer\Message $message */
        $message = $I->grabLastSentEmail();
        $I->assertArrayHasKey($user->email, $message->getTo());
        $I->assertContains(Html::encode($token->getUrl()), utf8_encode(quoted_printable_decode($message->getSwiftMessage()->toString())));

        $I->amGoingTo('reset password with invalid token');
        $user = $I->grabFixture('user', 'user_with_expired_recovery_token');
        $token = $I->grabRecord(Token::className(), ['user_id' => $user->id, 'type' => Token::TYPE_RECOVERY]);
        $resetPage->check(['id' => $user->id, 'code' => $token->code]);
        $I->see('Recovery link is invalid or expired. Please try requesting a new one.');

        $I->amGoingTo('reset password');
        $user = $I->grabFixture('user', 'user_with_recovery_token');
        $token = $I->grabRecord(Token::className(), ['user_id' => $user->id, 'type' => Token::TYPE_RECOVERY]);
        $resetPage->reset('newpass', ['id' => $user->id, 'code' => $token->code]);
        $I->see('Your password has been changed successfully.');

        $loginPage->login($user->email, 'qwerty');
        $I->see('Invalid login or password');
        $loginPage->login($user->email, 'newpass');
        $I->dontSee('Invalid login or password');
    }
}