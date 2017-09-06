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
    /**
     * @see \dektrium\user\Module::$urlRules
     */
    public static $submitButton = 'form[action*="request"] button[type=submit]'; // <form id="password-recovery-form" action="/index-test.php?r=user%2Frecovery%2Frequest" method="post">

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
