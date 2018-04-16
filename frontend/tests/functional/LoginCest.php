<?php

namespace frontend\tests\functional;

use dektrium\user\models\LoginForm;
use frontend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use common\tests\Page\Login as LoginPage;
use Yii;

class LoginCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php',
            ],
        ];
    }

    /**
     * @param FunctionalTester $I
     */
    public function loginUser(FunctionalTester $I)
    {
        $model = \Yii::createObject(LoginForm::className());
        $page = new LoginPage($I);

        $I->amGoingTo('try to login with empty credentials');
        $page->login('', '');
        $I->expectTo('see validations errors');
        $I->see(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('login')]));
        $I->see(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('password')]));

        $I->amGoingTo('try to login with unconfirmed account');
        $user = $I->grabFixture('user', 'unconfirmed');
        $page->login($user->email, 'qwerty');
        $I->see(Yii::t('user', 'You need to confirm your email address'));

        $I->amGoingTo('try to login with blocked account');
        $user = $I->grabFixture('user', 'blocked');
        $page->login($user->email, 'qwerty');
        $I->see(Yii::t('user', 'Your account has been blocked'));

        $I->amGoingTo('try to login with wrong credentials');
        $user = $I->grabFixture('user', 'user');
        $page->login($user->email, 'wrong');
        $I->expectTo('see validations errors');
        $I->see(Yii::t('user', 'Invalid login or password'));

        $I->amGoingTo('try to login with correct credentials');
        $page->login($user->email, 'qwerty');
        $I->dontSee(Yii::t('user', 'Login'));
        $I->see($user->username);
    }
}
