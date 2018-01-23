<?php

namespace api\tests\functional;

use api\tests\FunctionalTester;
use Codeception\Example;

class FeedbackFailCest
{
    /**
     * @param FunctionalTester $I
     * @throws \yii\base\InvalidConfigException
     */
    public function _before(FunctionalTester $I)
    {
        \Yii::$app->set('mailer', 'api\tests\stub\MockMailer');
        $I->configureMailer();
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests

    /**
     * Test request for send email with correct data
     * @param FunctionalTester $I
     */
    public function tryToRequestSendFeedback(FunctionalTester $I)
    {
        $data = [
            'name' => 'test message',
            'email' => 'test@gmail.com',
            'subject' => 'test subject',
            'body' => 'test body',
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('feedback/create', $data);
        $I->seeResponseCodeIs(500);
    }

}
