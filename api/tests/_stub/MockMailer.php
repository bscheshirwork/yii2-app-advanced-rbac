<?php

namespace api\tests\stub;


class MockMailer extends \Codeception\Lib\Connector\Yii2\TestMailer
{
    protected function sendMessage($message)
    {
        return false;
    }
}