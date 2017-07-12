<?php

namespace frontend\tests\functional;

use dektrium\user\models\SettingsForm;
use frontend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use frontend\fixtures\ProfileFixture;
use dektrium\user\models\User;
use dektrium\user\models\Token;
use common\tests\Page\Login as LoginPage;
use frontend\tests\Page\UpdateSelfAccount as UpdatePage;
use Yii;
use yii\helpers\Html;

class UpdateSelfAccountCest
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
            ],
            'profile' => [
                'class' => ProfileFixture::className(),
                'dataFile' => codecept_data_dir() . 'profile.php'
            ],
        ];
    }

    /**
     * @param FunctionalTester $I
     */
    public function updateSelfAccount(FunctionalTester $I)
    {
        $I->wantTo('ensure that self account update works');

        $loginPage = new LoginPage($I);
        $page = new UpdatePage($I);

        $user = $I->grabFixture('user', 'user');
        $I->amLoggedInAs($user);
        $model = \Yii::createObject(SettingsForm::className()); //expect Yii::$app->user->identity

        $I->amGoingTo('try to update  self account with empty fields');
        $page->update('', '', '', '');
        $I->expectTo('see validations errors');
        $I->see(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('username')]));
        $I->see(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('email')]));
        $I->see(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('current_password')]));

        $I->amGoingTo('check that email is changing properly');
        $page->update($user->username, 'new_user@example.com', 'qwerty');
        $I->seeRecord(User::className(), ['email' => $user->email, 'unconfirmed_email' => 'new_user@example.com']);
        $I->see(Yii::t('user', 'A confirmation message has been sent to your new email address'));
        $user  = $I->grabRecord(User::className(), ['id' => $user->id]);
        $token = $I->grabRecord(Token::className(), ['user_id' => $user->id, 'type' => Token::TYPE_CONFIRM_NEW_EMAIL]);
        /** @var yii\swiftmailer\Message $message */
        $message = $I->grabLastSentEmail();
        $I->assertArrayHasKey($user->unconfirmed_email, $message->getTo());
        $I->assertContains(Html::encode($token->getUrl()), utf8_encode(quoted_printable_decode($message->getSwiftMessage()->toString())));

        Yii::$app->user->logout();

        $I->amGoingTo('log in using new email address before clicking the confirmation link');
        $loginPage->login('new_user@example.com', 'qwerty');
        $I->see(Yii::t('user', 'Invalid login or password'));

        $I->amGoingTo('log in using new email address after clicking the confirmation link');
        $user->attemptEmailChange($token->code);
        $loginPage->login('new_user@example.com', 'qwerty');
        $I->see($user->username);
        $I->seeRecord(User::className(), [
            'id' => 1,
            'email' => 'new_user@example.com',
            'unconfirmed_email' => null,
        ]);

        $I->amGoingTo('reset email changing process');
        $page->update($user->username, 'user@example.com', 'qwerty');
        $I->see(Yii::t('user', 'A confirmation message has been sent to your new email address'));
        $I->seeRecord(User::className(), [
            'id'    => 1,
            'email' => 'new_user@example.com',
            'unconfirmed_email' => 'user@example.com',
        ]);
        $page->update($user->username, 'new_user@example.com', 'qwerty');
        $I->see(Yii::t('user', 'Your account details have been updated'));
        $I->seeRecord(User::className(), [
            'id'    => 1,
            'email' => 'new_user@example.com',
            'unconfirmed_email' => null,
        ]);

        $I->amGoingTo('change username and password');
        $page->update('nickname', 'new_user@example.com', 'qwerty', '123654');
        $I->see(Yii::t('user', 'Your account details have been updated'));
        $I->seeRecord(User::className(), [
            'username' => 'nickname',
            'email'    => 'new_user@example.com',
        ]);

        Yii::$app->user->logout();

        $I->amGoingTo('login with new credentials');
        $loginPage->login('nickname', '123654');
        $I->see('nickname');
    }
}