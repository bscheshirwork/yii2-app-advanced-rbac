<?php

namespace backend\tests\functional;

use \backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use backend\tests\Page\AdminCreateItem as CreateItemPage;

class AdminRouteCest
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
    public function routePage(FunctionalTester $I)
    {
        $user = $I->grabFixture('user', 'user');
        $I->amLoggedInAs($user);

        $I->wantTo('ensure that create item work');

        $createItemPage = new CreateItemPage($I);
        $createItemPage->amOnPage();
        $I->see('Create Role', 'h1');

        $I->amGoingTo('submit contact form with no data');
        $createItemPage->submit([]);

        $I->expectTo('see validations errors');
        $I->see('Create Role', 'h1');
        $I->see('Name cannot be blank');

        $I->amGoingTo('submit contact form with correct data');
        $createItemPage->submit([
            'name' => 'roleTester',
            'description' => 'Role created for test',
        ]);
        $I->dontSeeElement('#item-form');
    }
}