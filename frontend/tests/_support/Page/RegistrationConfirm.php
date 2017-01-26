<?php
namespace frontend\tests\Page;

use yii\helpers\Url;

class RegistrationConfirm
{
    public static $URL = '/user/registration/confirm';

    /**
     * @var \Codeception\Actor
     */
    protected $tester;

    /**
     * @var array
     */
    protected $param;

    public function __construct(\Codeception\Actor $I)
    {
        $this->tester = $I;
    }

    /**
     * Update user action
     * @param array $param
     * @return $this
     */
    public function check(array $param = [])
    {
        $I = $this->tester;
        $this->param = $param;
        $route = [self::$URL] + $this->param;
        $I->amOnPage(Url::toRoute($route));
        return $this;
    }

}
