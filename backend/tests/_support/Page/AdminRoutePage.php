<?php
namespace backend\tests\Page;

use yii\helpers\Url;

class AdminRoutePage
{
    public static $URL = '/admin/route/index';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $routeField = '#inp-route';
    public static $addButton = '#btn-new';

    /**
     * @var \Codeception\Actor
     */
    protected $tester;

    public function __construct(\Codeception\Actor $I)
    {
        $this->tester = $I;
    }

    public function amOnPage(){
        $I = $this->tester;
        $I->amOnPage(Url::toRoute(self::$URL));
    }

    /**
     * @param $route
     */
    public function addRoute($route)
    {
        $I = $this->tester;

        $I->amOnPage(Url::toRoute(self::$URL));

        $I->fillField(self::$routeField, $route);
        $I->click(self::$addButton);
    }

}
