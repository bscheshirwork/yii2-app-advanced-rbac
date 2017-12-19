<?php

namespace api\tests\functional;

use api\tests\FunctionalTester;
use Codeception\Example;

class FeedbackCest
{
    public function _before(FunctionalTester $I)
    {
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
        $I->seeResponseCodeIs(204);
        $I->seeResponseEquals('');

        /** @var \yii\swiftmailer\Message $message */
        $message = $I->grabLastSentEmail();
        $I->assertArrayHasKey($data['email'], $message->getFrom());
        $I->assertArrayHasKey(\Yii::$app->params['adminEmail'], $message->getTo());
        $I->assertArrayHasKey($data['email'], $message->getReplyTo());
        $I->assertEquals($data['subject'], $message->getSubject());
        $I->assertContains($data['body'],
            utf8_encode(quoted_printable_decode($message->getSwiftMessage()->toString())));
    }

    /**
     * Test request send email with no data
     * @param FunctionalTester $I
     */
    public function requestSendFeedbackFailWithEmptyData(FunctionalTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('feedback/create');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['name' => 'Bad Request', 'code' => 0, 'status' => 400,]);

    }

    private function partialDataProvider()
    {
        return [
            [
                [
//                    'name' => 'test message',
                    'email' => 'test@gmail.com',
                    'subject' => 'test subject',
                    'body' => 'test body',
                ],
                422,
                [
                    [
                        'field' => 'name',
                        'message' => \Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => 'Name']),
                    ],
                ],
            ],
            [
                [
                    'name' => 'test message',
//                    'email' => 'test@gmail.com',
//                    'subject' => 'test subject',
//                    'body' => 'test body',
                ],
                422,
                [
                    [
                        'field' => 'email',
                        'message' => \Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => 'Email']),
                    ],
                    [
                        'field' => 'subject',
                        'message' => \Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => 'Subject']),
                    ],
                    [
                        'field' => 'body',
                        'message' => \Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => 'Body']),
                    ],
                ],
            ],
            [
                [
                    'name' => 'test message',
                    'email' => '@gmail.com',
                    'subject' => 'test subject',
                    'body' => 'test body',
                ],
                422,
                [
                    [
                        'field' => 'email',
                        'message' => \Yii::t('yii', '{attribute} is not a valid email address.', ['attribute' => 'Email']),
                    ],
                ],
            ],
        ];
    }

    /**
     * Test request send email with part of data
     * @dataprovider partialDataProvider
     * @see http://codeception.com/docs/07-AdvancedUsage#examples
     * @param FunctionalTester $I
     * @param Example $example
     */
    public function requestSendFeedbackFailWithWrongData(FunctionalTester $I, Example $example)
    {
        list($data, $code, $except) = $example;

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('feedback/create', $data);
        $I->seeResponseCodeIs($code);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($except);

    }

}
