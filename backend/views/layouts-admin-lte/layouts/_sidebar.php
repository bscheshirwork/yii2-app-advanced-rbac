<?php

/**
 * @see https://github.com/dmstr/yii2-adminlte-asset
 * @see https://github.com/cebe/yii2-gravatar
 */

?>

<?php if( !Yii::$app->user->isGuest ): ?>
<!-- Sidebar user panel -->
<div class="user-panel">
    <div class="pull-left image">
        <?php echo \cebe\gravatar\Gravatar::widget(
            [
                'email' => Yii::$app->user->identity->profile->gravatar_email ?? Yii::$app->user->identity->email ?? '',
                'options' => [
                    'alt' => Yii::$app->user->identity->username ?? '',
                ],
                'size' => 64,
            ]
        ); ?>
    </div>
    <div class="pull-left info">
        <p><?= Yii::$app->user->identity->profile->name ?? Yii::$app->user->identity->username ?? '' ?></p>

        <a href="#"><i class="fa fa-circle text-success"></i> <?=Yii::t('main', 'Online')?></a>
    </div>
</div>
<?php endif; ?>

<?php

// prepare menu items, get all modules
$menuItems = [];

$favouriteMenuItems[] = ['label' => '', 'options' => ['class' => 'header']];

echo dmstr\widgets\Menu::widget([
        'defaultIconHtml' => '',
        'activateParents' => false,
        'items' => \yii\helpers\ArrayHelper::merge($favouriteMenuItems, backend\views\LayoutHelper::menuItems()),
]);
?>
