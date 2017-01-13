<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/user/security/login']];
    } else {
        if (Yii::$app->user->can('administrateRbac')) {
            $menuItems[] = [
                'label' => 'RBAC',
                'options' => ['class' => 'header'],
                'url' => '#',
                'items' => [
                    [
                        'label' => 'Графическое представление',
                        'url' => ['/rbac'],
                        'linkOptions' => ['target' => '_blank'],
                    ],
                    ['label' => 'Администрирование', 'url' => ['/admin']],
                    ['label' => 'Пути', 'url' => ['/admin/route']],
                    ['label' => 'Разрешения', 'url' => ['/admin/permission']],
                    ['label' => 'Меню', 'url' => ['/admin/menu']],
                    ['label' => 'Роли', 'url' => ['/admin/role']],
                ],
            ];
        }

        if (Yii::$app->user->can('administrateUser')) {
            $menuItems[] = ['label' => 'Пользователи', 'url' => ['/user/admin/index']];
        }

        $menuItems[] = [
            'label' => Yii::$app->user->identity->username,
            'options' => ['class' => 'header'],
            'url' => '#',
            'items' => [
                ['label' => Yii::$app->user->identity->username, 'options' => ['class' => 'header']],
                ['label' => 'Профиль', 'url' => ['/user/settings/profile']],
                ['label' => 'Аккаунт', 'url' => ['/user/settings/account']],
                [
                    'label' => 'Выход',
                    'icon' => 'fa fa-share',
                    'url' => ['/user/security/logout'],
                    'template' => '<a href="{url}" data-method = "post">{icon} {label}</a>',
                    'linkOptions' => ['data-method' => 'post'],
                ],
                '<li>'
                . Html::beginForm(['/user/security/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>',
            ],
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
