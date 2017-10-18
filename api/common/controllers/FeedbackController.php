<?php

namespace api\common\controllers;


use Yii;
use yii\rest\Controller;
use yii\web\HttpException;

class FeedbackController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'options' => [
                'class' => \yii\rest\OptionsAction::class,
                'collectionOptions' => ['POST', 'OPTIONS'],
                'resourceOptions' => ['OPTIONS'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'create' => ['POST'],
        ];
    }

    /**
     * @param string $modelClassAlias
     * @return Yii\base\Model
     */
    protected static function newModel($modelClassAlias)
    {
        switch ($modelClassAlias) {
            case 'ContactForm':
                return new \api\common\models\ContactForm;
        }

        return null;
    }

    /**
     * Contact form send
     * @return null|mixed
     * @throws HttpException
     */
    public function actionCreate()
    {
        $model = static::newModel('ContactForm');
        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->response->statusCode = 204;

                return null;
            } else {

                throw new HttpException(500, 'There was an error sending your message.');
            }
        }

        if ($model->hasErrors()) {
            return $this->serializeData($model);
        }

        throw new HttpException(400, "Wrong POST data. Required: name, email, subject, body");
    }

}