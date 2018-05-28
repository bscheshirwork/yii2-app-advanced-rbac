<?php

namespace backend\views;

use Yii;
use yii\helpers\Html;

/**
 * Class LayoutHelper
 * @package frontend\views
 */
class LayoutHelper
{
    /**
     * return frontend menu items
     * @return array
     */
    public static function menuItems(): array
    {
        $menuItems = [
            ['label' => Yii::t('main', 'Home'), 'url' => ['/site/index']],
        ];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => Yii::t('main', 'Login'), 'url' => ['/user/security/login']];
        } else {
            if (Yii::$app->user->can('administrateRbac')) {
                $menuItems[] = [
                    'label' => 'RBAC',
                    'options' => ['class' => 'header'],
                    'url' => '#',
                    'items' => [
                        [
                            'label' => Yii::t('main', 'Graphical representation'),
                            'url' => ['/rbac'],
                            'linkOptions' => ['target' => '_blank'],
                        ],
                        ['label' => Yii::t('main', 'Administrate'), 'url' => ['/admin']],
                        ['label' => Yii::t('main', 'Routes'), 'url' => ['/admin/route']],
                        ['label' => Yii::t('main', 'Permissions'), 'url' => ['/admin/permission']],
                        ['label' => Yii::t('main', 'Menu'), 'url' => ['/admin/menu']],
                        ['label' => Yii::t('main', 'Roles'), 'url' => ['/admin/role']],
                    ],
                ];
            }

            if (Yii::$app->user->can('administrateUser')) {
                $menuItems[] = ['label' => 'Users', 'url' => ['/user/admin/index']];
            }
        }

        return $menuItems;
    }

    public static function extendMenuItems()
    {
        $menuItems = self::menuItems() ?? [];

        if (Yii::$app->user->isGuest) {
            return $menuItems;
        }

        $menuItems[] = [
            'label' => Yii::$app->user->identity->username ?? '',
            'options' => ['class' => 'header'],
            'url' => '#',
            'items' => [
                ['label' => Yii::$app->user->identity->username ?? '', 'options' => ['class' => 'dropdown-header']],
                ['label' => Yii::t('main', 'Profile'), 'url' => ['/user/settings/profile']],
                ['label' => Yii::t('main', 'Account'), 'url' => ['/user/settings/account']],
                '<li class="divider"></li>',
                '<li>'
                . Html::beginForm(['/user/security/logout'], 'post', ['class' => 'navbar-form'])
                . Html::submitButton(
                    Yii::t('main', 'Logout ({username})', ['username' => Yii::$app->user->identity->username ?? '']),
                    ['class' => 'btn btn-link btn-secondary logout']
                )
                . Html::endForm()
                . '</li>',
            ],
        ];

        if (Yii::$app->session->has(\dektrium\user\controllers\AdminController::ORIGINAL_USER_SESSION_KEY)) {
            $menuItems[] = '<li>' . Html::beginForm(['/user/admin/switch'], 'post', ['class' => 'navbar-form'])
                . Html::submitButton('<span class="glyphicon glyphicon-user"></span> ' . Yii::t('main',
                        'Back to original user'),
                    ['class' => 'btn btn-link']
                ) . Html::endForm() . '</li>';

        }

        return $menuItems;
    }
}