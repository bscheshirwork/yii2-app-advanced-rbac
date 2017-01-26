<?php
namespace frontend\tests\Page;

use yii\helpers\Url;

class Registration
{
    public static $URL = '/user/registration/register';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $emailField = '#register-form-email';
    public static $usernameField = '#register-form-username';
    public static $passwordField = '#register-form-password';
    public static $submitButton = 'form[action*="register"] button[type=submit]';

    /**
     * @var \Codeception\Actor
     */
    protected $tester;

    public function __construct(\Codeception\Actor $I)
    {
        $this->tester = $I;
    }

    /**
     * Resend email action
     * @param $email
     * @return $this
     */
    public function register($email, $username = '', $password = null)
    {
        $I = $this->tester;

        $I->amOnPage(Url::toRoute(self::$URL));

        $I->fillField(self::$emailField, $email);
        $I->fillField(self::$usernameField, $username);
        if ($password !== null) {
            $I->fillField(self::$passwordField, $password);
        }
        $I->click(self::$submitButton);

        return $this;
    }


}
