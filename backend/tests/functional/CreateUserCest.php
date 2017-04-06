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
    public function createUser(FunctionalTester $I)
    {
        $model = new User;

        $I->wantTo('ensure that user creation works');

        $loginPage = new LoginPage($I);
        $page = new CreatePage($I);

        $user = $I->grabFixture('user', 'user');
        $I->amLoggedInAs($user);

        $I->amGoingTo('try to create user with empty fields');
        $page->create('', '', '');
        $I->expectTo('see validations errors');
        $I->see(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('username')]));
        $I->see(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('email')]));

        $I->amGoingTo('try to create user');
        $page->create('foobar', 'foobar@example.com', 'foobar');
        $I->see(Yii::t('user', 'User has been created'));
        $I->seeRecord(User::className(), ['username' => 'foobar']);

        Yii::$app->user->logout();

        $I->amGoingTo('login with new credentials');
        $loginPage->login('foobar@example.com', 'foobar');
        $I->see('foobar');
    }
}