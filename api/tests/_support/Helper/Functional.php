<?php
namespace api\tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Functional extends \Codeception\Module
{
    /**
     * Set \Codeception\Module\Yii2::$mailer to \Yii::$app->mailer from test
     * For example
     * public function _before(FunctionalTester $I)
     * {
     *     \Yii::$app->set('mailer', 'api\tests\stub\MockMailer');
     *     $I->configureMailer();
     * }
     * @throws \Codeception\Exception\ModuleException
     */
    public function configureMailer()
    {
        /** @var \Codeception\Module\Yii2 $module */
        $module = $this->getModule('Yii2');
        /** @var \yii\base\Application $app */
        $app = $module->app;
        /** @var \Codeception\Lib\Connector\Yii2 $connector */
        $connector = $module->client;
        $connector::$mailer = $app->get('mailer');
    }

}
