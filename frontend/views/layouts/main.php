<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
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
        ['label' => Yii::t('main', 'Home'), 'url' => ['/site/index']],
        ['label' => Yii::t('main', 'About'), 'url' => ['/site/about']],
        ['label' => Yii::t('main', 'Contact'), 'url' => ['/site/contact']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => Yii::t('main', 'Signup'), 'url' => ['/user/registration/register']];
        $menuItems[] = ['label' => Yii::t('main', 'Login'), 'url' => ['/user/security/login']];
    } else {
        $menuItems[] = [
            'label' => Yii::$app->user->identity->username,
            'items' => [
                '<li class="dropdown-header">' . Yii::$app->user->identity->username . '</li>',
                ['label' => Yii::t('main', 'Profile'), 'url' => ['/user/settings/profile']],
                ['label' => Yii::t('main', 'Account'), 'url' => ['/user/settings/account']],
                '<li class="divider"></li>',
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
