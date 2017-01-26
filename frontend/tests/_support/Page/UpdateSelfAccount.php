<?php
namespace frontend\tests\Page;

use yii\helpers\Url;

class UpdateSelfAccount
{
    public static $URL = '/user/settings/account';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $usernameField = '#settings-form-username';
    public static $emailField = '#settings-form-email';
    public static $newPasswordField = '#settings-form-new_password';
    public static $currentPasswordField = '#settings-form-current_password';
    public static $saveButton = 'form[action*="account"] button[type=submit]';

    /**
     * @var \Codeception\Actor
     */
    protected $tester;

    public function __construct(\Codeception\Actor $I)
    {
        $this->tester = $I;
    }


    /**
     * Update self account action
     * @param $username
     * @param $email
     * @param $currentPassword
     * @param string $password New password or empty string if not change
     * @return $this
     */
    public function update($username, $email, $currentPassword, $password = '')
    {
        $I = $this->tester;

        $I->amOnPage(Url::toRoute(self::$URL));

        $I->fillField(self::$usernameField, $username);
        $I->fillField(self::$emailField, $email);
        $I->fillField(self::$newPasswordField, $password);
        $I->fillField(self::$currentPasswordField, $currentPassword);
        $I->click(self::$saveButton);

        return $this;
    }


}
