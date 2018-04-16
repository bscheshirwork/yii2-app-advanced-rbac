<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use dektrium\user\models\User;
use common\tests\Page\Login as LoginPage;
use backend\tests\Page\UpdateUserAccount as UpdatePage;
use Yii;

class UpdateUserAccountCest
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
            ]
        ];
    }

    /**
     * @param FunctionalTester $I
     */
    public function updateUser(FunctionalTester $I)
    {
        $I->wantTo('ensure that user update works');

        $model = new User;
        $loginPage = new LoginPage($I);

        $user = $I->grabFixture('user', 'user');
        $I->amLoggedInAs($user);

        $page = new UpdatePage($I, ['id'=>$user->id]);

        $I->amGoingTo('try to update user with empty fields');
        $page->update('', '', '');
        $I->expectTo('see validations errors');
        $I->see(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('username')]));
        $I->see(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('email')]));

        $I->amGoingTo('try to update user');
        $page->update('userfoobar', 'updated_user@example.com', 'new_pass');
        $I->see(Yii::t('user', 'Account details have been updated'));
        $I->seeRecord(User::class, ['username' => 'userfoobar']);

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

        $model = new User;
        $loginPage = new LoginPage($I);

        $user = $I->grabFixture('user', 'user');
        $I->amLoggedInAs($user);

        $anotherUser = $I->grabFixture('user', 'user_with_recovery_token');

        $page = new UpdatePage($I, ['id'=>$anotherUser->id]);

        $I->amGoingTo('try to update user with empty fields');
        $page->update('', '', '');
        $I->expectTo('see validations errors');
        $I->see(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('username')]));
        $I->see(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('email')]));

        $I->amGoingTo('try to update user');
        $page->update('userfoobar', 'updated_user@example.com', 'new_pass');
        $I->see(Yii::t('user', 'Account details have been updated'));
        $I->seeRecord(User::class, ['username' => 'userfoobar']);

        Yii::$app->user->logout();

        $I->amGoingTo('login with new credentials');
        $loginPage->login('updated_user@example.com', 'new_pass');
        $I->see('userfoobar');
    }
}