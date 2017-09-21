<?php

namespace api\v1;

/**
 * Class Module
 * API v1
 * @package api\v1
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritDoc
     */
    public $controllerNamespace = 'api\v1\controllers';

    /**
     * @inheritDoc
     */
    public function getVersion()
    {
        return '1.0';
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        // инициализация модуля с помощью конфигурации, загруженной из config.php
        //\Yii::configure($this, require __DIR__ . '/config.php');

        //отключение сессий. REST-архитектура не предполагает сохранения состояний между запросами.
        \Yii::$app->user->enableSession = false;
    }
}
