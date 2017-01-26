<?php
namespace common\tests\Page;

use yii\helpers\Url;

class Login
{
    public static $URL = '/user/security/login';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $usernameField = '#login-form #login-form-login';
    public static $passwordField = '#login-form input[type=password]';
    public static $loginButton = '#login-form button[type=submit]';

    /**
     * @var \Codeception\Actor
     */
    protected $tester;

    public function __construct(\Codeception\Actor $I)
    {
        $this->tester = $I;
    }

    /**
     * Log in action
     * @param $name
     * @param $password
     * @return $this
     */
    public function login($name, $password)
    {
        $I = $this->tester;
        $url = Url::toRoute(self::$URL);
        $I->amOnPage($url);
        $I->fillField(self::$usernameField, $name);
        $I->fillField(self::$passwordField, $password);
        $I->click(self::$loginButton);

        return $this;
    }
}
