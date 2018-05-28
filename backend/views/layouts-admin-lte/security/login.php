<?php

/**
 * @see https://github.com/dektrium/yii2-user
 * @see https://github.com/dmstr/yii2-adminlte-asset
 * @see https://github.com/Insolita/yii2-adminlte-widgets
 * @see https://github.com/hiqdev/yii2-asset-icheck
 */

use dektrium\user\widgets\Connect;
use dektrium\user\models\LoginForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module $module
 */

$this->title = Yii::t('user', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;

hiqdev\assets\icheck\iCheckAsset::register($this);
$iCheck = <<< JS
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
JS;
$this->registerJs($iCheck, yii\web\View::POS_READY);
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Yii2</b>APP</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg"><?= Html::encode($this->title) ?></p>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
            'validateOnBlur' => false,
            'validateOnType' => false,
            'validateOnChange' => false,
//            'fieldConfig' => [
//                'template' => "{input}{icon}\n{hint}\n{error}",
//            ],
        ]) ?>

        <div class="form-group has-feedback">
            <?php if ($module->debug): ?>
                <?= $form->field($model, 'login', [
                    'inputOptions' => [
                        'autofocus' => 'autofocus',
                        'class' => 'form-control',
                        'tabindex' => '1',
                        'type' => 'email',
                        'placeholder' => $model->getAttributeLabel('login'),
                    ],
                    'template' => "{input}\n<span class='glyphicon glyphicon-envelope form-control-feedback'></span>\n{hint}\n{error}",
                ])->dropDownList(LoginForm::loginList());
                ?>
            <?php else: ?>
                <?= $form->field($model, 'login', [
                    'inputOptions' => [
                        'autofocus' => 'autofocus',
                        'class' => 'form-control',
                        'tabindex' => '1',
                        'type' => 'email',
                        'placeholder' => $model->getAttributeLabel('login'),
                    ],
                    'template' => "{input}\n<span class='glyphicon glyphicon-envelope form-control-feedback'></span>\n{hint}\n{error}",
                ]);
                ?>
            <?php endif ?>

        </div>

        <div class="form-group has-feedback">
            <?php if ($module->debug): ?>
                <div class="alert alert-warning">
                    <?= Yii::t('user', 'Password is not necessary because the module is in DEBUG mode.'); ?>
                </div>
            <?php else: ?>
                <?= $form->field($model, 'password', [
                    'inputOptions' => [
                        'class' => 'form-control',
                        'tabindex' => '2',
                        'type' => 'password',
                        'placeholder' => $model->getAttributeLabel('password'),
                    ],
                    'template' => "{input}\n<span class='glyphicon glyphicon-lock form-control-feedback'></span>\n{hint}\n{error}",
                ])->passwordInput();
                ?>
            <?php endif ?>
        </div>
        <div class="row">
            <?php $proportions = ['en-Us' => [8, 4], 'ru-Ru' => [6, 6]][Yii::$app->language] ?? [8, 4]; ?>
            <div class="col-xs-<?= $proportions[0] ?>">
                <?= $form->field($model, 'rememberMe', [
                    'inputOptions' => [
                        'tabindex' => '3',
                    ],
                    'options' => ['class' => 'form-group checkbox icheck'],
                ])->checkbox() ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-<?= $proportions[1] ?>">
                <?= Html::submitButton(
                    Yii::t('user', 'Sign in'), [
                    'class' => 'btn btn-primary btn-block btn-flat',
                    'tabindex' => '4',
                    'type' => 'submit',
                ]) ?>
            </div>
            <!-- /.col -->
        </div>
        <?php ActiveForm::end(); ?>

<!--        <div class="social-auth-links text-center">-->
<!--            <p>- OR -</p>-->
<!--            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in-->
<!--                using-->
<!--                Facebook</a>-->
<!--            <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in-->
<!--                using-->
<!--                Google+</a>-->
<!--        </div>-->
        <!-- /.social-auth-links -->
        <?= Connect::widget([
            'baseAuthUrl' => ['/user/security/auth'],
        ]) ?>

        <?php if ($module->enablePasswordRecovery): ?>
            <?= Html::a(Yii::t('user', 'Forgot password?'), ['/user/recovery/request'], ['class' => 'text-center', 'tabindex' => '5']) ?>
            <br>
        <?php endif ?>
        <?php if ($module->enableConfirmation): ?>
            <?= Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'), ['/user/registration/resend'], ['class' => 'text-center', 'tabindex' => '6']) ?>
            <br>
        <?php endif ?>
        <?php if ($module->enableRegistration): ?>
            <?= Html::a(Yii::t('user', 'Don\'t have an account? Sign up!'), ['/user/registration/register'], ['class' => 'text-center', 'tabindex' => '7']) ?>
            <br>
        <?php endif ?>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
