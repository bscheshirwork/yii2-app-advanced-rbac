<?php
namespace backend\tests\Page;

use backend\tests\AcceptanceTester;
use yii\helpers\Url;

class UpdateUserAccount
{
    public static $URL = '/user/admin/update';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $usernameField = '#user-username';
    public static $emailField = '#user-email';
    public static $passwordField = '#user-password';
    public static $saveButton = 'form[action*="update"] button[type=submit]';

    /**
     * @var \Codeception\Actor
     */
    protected $tester;

    /**
     * @var array
     */
    protected $param;

    public function __construct(\Codeception\Actor $I, array $param = [])
    {
        $this->tester = $I;
        $this->param = $param;
    }

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }


    /**
     * Update user action
     * @param $username
     * @param $email
     * @param $password
     * @return $this
     */
    public function update($username, $email, $password)
    {
        $I = $this->tester;
        $route = [self::$URL] + $this->param;

        $I->amOnPage(Url::toRoute($route));

        $I->fillField(self::$usernameField, $username);
        $I->fillField(self::$emailField, $email);
        $I->fillField(self::$passwordField, $password);
        if ($I instanceof AcceptanceTester){
            $I->wait(2); // wait for page to be render clickable
        }
        $I->click(self::$saveButton);

        return $this;
    }


}
