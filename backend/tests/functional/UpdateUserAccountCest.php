<?php

namespace backend\tests\functional;

use \backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use dektrium\user\models\User;
use common\tests\Page\Login as LoginPage;
use backend\tests\Page\UpdateUserAccount as UpdatePage;
use Yii;

class UpdateUserAccountCest
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
    public function updateUser(FunctionalTester $I)
    {
        $I->wantTo('ensure that user update works');

        $loginPage = new LoginPage($I);

        $user = $I->grabFixture('user', 'user');
        $I->amLoggedInAs($user);

        $page = new UpdatePage($I, ['id'=>$user->id]);

        $I->amGoingTo('try to update user with empty fields');
        $page->update('', '', '');
        $I->expectTo('see validations errors');
        $I->see('Username cannot be blank.');
        $I->see('Email cannot be blank.');

        $I->amGoingTo('try to update user');
        $page->update('userfoobar', 'updated_user@example.com', 'new_pass');
        $I->see('Account details have been updated');
        $I->seeRecord(User::className(), ['username' => 'userfoobar']);

        Yii::$app->user->logout();

        $I->amGoingTo('login with new credentials');
        $loginPage->login('updated_user@example.com', 'new_pass');
        $I->see('userfoobar');
    }

    /**
     * @param FunctionalTester $I
     */
    public function updateAnotherUser(FunctionalTester $I)
    {
        $I->wantTo('ensure that another user update works');

        $loginPage = new LoginPage($I);

        $user = $I->grabFixture('user', 'user');
        $I->amLoggedInAs($user);

        $anotherUser = $I->grabFixture('user', 'user_with_recovery_token');

        $page = new UpdatePage($I, ['id'=>$anotherUser->id]);

        $I->amGoingTo('try to update user with empty fields');
        $page->update('', '', '');
        $I->expectTo('see validations errors');
        $I->see('Username cannot be blank.');
        $I->see('Email cannot be blank.');

        $I->amGoingTo('try to update user');
        $page->update('userfoobar', 'updated_user@example.com', 'new_pass');
        $I->see('Account details have been updated');
        $I->seeRecord(User::className(), ['username' => 'userfoobar']);

        Yii::$app->user->logout();

        $I->amGoingTo('login with new credentials');
        $loginPage->login('updated_user@example.com', 'new_pass');
        $I->see('userfoobar');
    }
}