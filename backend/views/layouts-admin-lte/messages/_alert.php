<?php

/**
 * @see https://github.com/dektrium/yii2-user
 * @see https://github.com/Insolita/yii2-adminlte-widgets
 */

/**
 * @var dektrium\user\Module|yii\base\Module $module
 */

?>

<?php if ($module->enableFlashMessages ?? true): ?>
    <div class="row">
        <div class="col-xs-12">
            <?= \insolita\wgadminlte\FlashAlerts::widget([
                'errorIcon' => '<i class="fa fa-warning"></i>',
                'successIcon' => '<i class="fa fa-check"></i>',
                'successTitle' => Yii::t('main', 'Done!'), //for non-titled type like 'success-first'
                'closable' => true,
                'encode' => false,
                'bold' => false,
            ]); ?>
        </div>
    </div>
<?php endif ?>
