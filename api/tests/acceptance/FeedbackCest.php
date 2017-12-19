<?php
namespace api\tests\acceptance;
use api\tests\AcceptanceTester;

class FeedbackCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToRequestSendFeedback(AcceptanceTester $I)
    {
        $data = [
            'name' => 'test message',
            'email' => 'test@gmail.com',
            'subject' => 'test subject',
            'body' => 'test body',
        ];

        $I->haveHttpHeader('Content-Type', 'application/json');
        /**
         * use script of refresh `crl` if you can see `<head><title>400 The SSL certificate error</title></head>...`
         * as result of `codecept run acceptance`
         */
        $I->sendPOST('feedback/create', $data);
        $I->seeResponseCodeIs(204);
        $I->seeResponseEquals('');

//        $I->
    }
}
