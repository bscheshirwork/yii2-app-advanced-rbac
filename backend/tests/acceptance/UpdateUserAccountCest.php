<?php
namespace backend\tests\acceptance;

use backend\tests\AcceptanceTester;
use common\fixtures\UserFixture;
use common\tests\Page\Login as LoginPage;
use backend\tests\Page\UpdateUserAccount as UpdatePage;
use dektrium\user\models\User;
use Yii;

class UpdateUserAccountCest
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
    public function updateUser(AcceptanceTester $I)
    {
        $model = new User;
        $I->wantTo('ensure that user update works');

        $loginPage = new LoginPage($I);

        $user = $I->grabFixture('user', 'user');
        $loginPage->login($user->email, 'qwerty');
        $I->wait(2); // wait for page to be opened

        $I->makeScreenshot('updateUser_01_login');

        $page = new UpdatePage($I, ['id' => $user->id]);

        $I->amGoingTo('try to update user with empty fields');
        $page->update('', '', '');
        $I->wait(2); // wait for page to be opened
        $I->expectTo('see validations errors');
        $I->see(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('username')]));
        $I->see(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('email')]));

        $I->makeScreenshot('updateUser_02_update_validation_error');

        $I->amGoingTo('try to update user');
        $page->update('userfoobar', 'updated_user@example.com', 'new_pass');
        $I->wait(2); // wait for page to be opened
        $I->see(Yii::t('user', 'Account details have been updated'));
        $I->makeScreenshot('updateUser_03_update_successful');

        $I->click('userfoobar');
        $I->click(Yii::t('main', 'Logout ({userName})', ['userName' => 'userfoobar']));
        $I->wait(2); // wait for page to be opened
        $I->makeScreenshot('updateUser_04_logout');

        $I->amGoingTo('login with new credentials');
        $loginPage->login('updated_user@example.com', 'new_pass');
        $I->wait(2); // wait for page to be opened
        $I->makeScreenshot('updateUser_05_new_user_login');
        $I->see('userfoobar');
    }
}