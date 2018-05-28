<?php

/**
 * @see https://github.com/dmstr/yii2-adminlte-asset
 */

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
dmstr\web\AdminLteAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Ionicons -->
    <link href="//code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!-- Theme style -->
    <?php $this->head() ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition <?= \dmstr\helpers\AdminLteHelper::skinClass() ?> login-page">
<?php $this->beginBody() ?>

<!-- Main content -->
<section class="content">
    <?= $this->render('/messages/_alert') ?>
    <?= $content ?>
</section>
<!-- /.content -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
