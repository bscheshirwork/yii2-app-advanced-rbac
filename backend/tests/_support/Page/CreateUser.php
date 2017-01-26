<?php
namespace backend\tests\Page;

use yii\helpers\Url;

class CreateUser
{
    public static $URL = '/user/admin/create';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $usernameField = '#user-username';
    public static $emailField = '#user-email';
    public static $passwordField = '#user-password';
    public static $saveButton = 'form[action*="create"] button[type=submit]';

    /**
     * @var \Codeception\Actor
     */
    protected $tester;

    public function __construct(\Codeception\Actor $I)
    {
        $this->tester = $I;
    }

    /**
     * Create user action
     * @param $username
     * @param $email
     * @param $password
     * @return $this
     */
    public function create($username, $email, $password)
    {
        $I = $this->tester;

        $I->amOnPage(Url::toRoute(self::$URL));

        $I->fillField(self::$usernameField, $username);
        $I->fillField(self::$emailField, $email);
        $I->fillField(self::$passwordField, $password);
        $I->click(self::$saveButton);

        return $this;
    }


}
