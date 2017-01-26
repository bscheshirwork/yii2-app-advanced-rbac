<?php
namespace backend\tests\acceptance;

use backend\tests\AcceptanceTester;
use common\fixtures\UserFixture;
use common\tests\Page\Login as LoginPage;
use backend\tests\Page\CreateUser as CreatePage;

class CreateUserCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
        ]);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function createUser(AcceptanceTester $I)
    {
        $I->wantTo('ensure that user creation works');

        $loginPage = new LoginPage($I);
        $page = new CreatePage($I);

        $user = $I->grabFixture('user', 'user');
        $loginPage->login($user->email, 'qwerty');
        $I->wait(2); // wait for page to be opened

        $I->makeScreenshot('createUser_01_login');

        $I->amGoingTo('try to create user with empty fields');
        $page->create('', '', '');
        $I->wait(2); // wait for page to be opened
        $I->expectTo('see validations errors');
        $I->see('Username cannot be blank.');
        $I->see('Email cannot be blank.');

        $I->makeScreenshot('createUser_02_create_validation_error');

        $I->amGoingTo('try to create user');
        $page->create('foobar', 'foobar@example.com', 'foobar');
        $I->wait(2); // wait for page to be opened
        $I->see('User has been created');
        $I->makeScreenshot('createUser_03_create_successful');

        $I->click('user');
        $I->click('Logout (user)');
        $I->wait(2); // wait for page to be opened
        $I->makeScreenshot('createUser_04_logout');

        $I->amGoingTo('login with new credentials');
        $loginPage->login('foobar@example.com', 'foobar');
        $I->wait(2); // wait for page to be opened
        $I->makeScreenshot('createUser_05_new_user_login');
        $I->see('foobar');
    }
}