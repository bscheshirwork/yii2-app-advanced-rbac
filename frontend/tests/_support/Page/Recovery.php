<?php
namespace frontend\tests\Page;

use yii\helpers\Url;

class Recovery
{
    public static $URL = '/user/recovery/request';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $emailField = '#recovery-form-email';
    public static $submitButton = 'form[action*="recovery"] button[type=submit]';

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
    public function recover($email)
    {
        $I = $this->tester;

        $I->amOnPage(Url::toRoute(self::$URL));

        $I->fillField(self::$emailField, $email);
        $I->click(self::$submitButton);

        return $this;
    }


}
