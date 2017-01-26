<?php
namespace frontend\tests\Page;

use yii\helpers\Url;

class Resend
{
    public static $URL = '/user/registration/resend';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $emailField = '#resend-form-email';
    public static $submitButton = 'form[action*="resend"] button[type=submit]';

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
    public function resend($email)
    {
        $I = $this->tester;

        $I->amOnPage(Url::toRoute(self::$URL));

        $I->fillField(self::$emailField, $email);
        $I->click(self::$submitButton);

        return $this;
    }


}
