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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' =>  Yii::t('main', 'Home'), 'url' => ['/site/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' =>  Yii::t('main', 'Login'), 'url' => ['/user/security/login']];
    } else {
        if (Yii::$app->user->can('administrateRbac')) {
            $menuItems[] = [
                'label' => 'RBAC',
                'options' => ['class' => 'header'],
                'url' => '#',
                'items' => [
                    [
                        'label' =>  Yii::t('main', 'Graphical representation'),
                        'url' => ['/rbac'],
                        'linkOptions' => ['target' => '_blank'],
                    ],
                    ['label' =>  Yii::t('main', 'Administrate'), 'url' => ['/admin']],
                    ['label' =>  Yii::t('main', 'Routes'), 'url' => ['/admin/route']],
                    ['label' =>  Yii::t('main', 'Permissions'), 'url' => ['/admin/permission']],
                    ['label' =>  Yii::t('main', 'Menu'), 'url' => ['/admin/menu']],
                    ['label' =>  Yii::t('main', 'Roles'), 'url' => ['/admin/role']],
                ],
            ];
        }

        if (Yii::$app->user->can('administrateUser')) {
            $menuItems[] = ['label' => 'Users', 'url' => ['/user/admin/index']];
        }

        $menuItems[] = [
            'label' => Yii::$app->user->identity->username,
            'options' => ['class' => 'header'],
            'url' => '#',
            'items' => [
                ['label' => Yii::$app->user->identity->username, 'options' => ['class' => 'header']],
                ['label' =>  Yii::t('main', 'Profile'), 'url' => ['/user/settings/profile']],
                ['label' =>  Yii::t('main', 'Account'), 'url' => ['/user/settings/account']],
                '<li>'
                . Html::beginForm(['/user/security/logout'], 'post')
                . Html::submitButton(
                    Yii::t('main', 'Logout ({username})', ['username' => Yii::$app->user->identity->username]),
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>',
            ],
        ];

        if (Yii::$app->session->has(\dektrium\user\controllers\AdminController::ORIGINAL_USER_SESSION_KEY)){
            $menuItems[] = '<li>' . Html::beginForm(['/user/admin/switch'], 'post', ['class' => 'navbar-form'])
                . Html::submitButton('<span class="glyphicon glyphicon-user"></span> ' . Yii::t('main', 'Back to original user'),
                    ['class' => 'btn btn-link']
                ) . Html::endForm() . '</li>';

        }
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
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
