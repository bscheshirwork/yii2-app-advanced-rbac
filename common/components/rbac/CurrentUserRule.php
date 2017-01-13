<?php

namespace components\rbac;

/**
 * CurrentUserRule сравнивает id текущего пользователя с get-параметром id.
 *
 * @author BSCheshir <bscheshir.work@gmail.com>
 */
class CurrentUserRule extends \yii\rbac\Rule
{
    public $name = 'isCurrentUser';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        return \Yii::$app->request->getQueryParam('id', false) == $user;
    }
}
