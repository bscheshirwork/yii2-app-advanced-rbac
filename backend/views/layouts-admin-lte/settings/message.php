<?php

/**
 * @see https://github.com/dektrium/yii2-user
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var dektrium\user\Module $module
 */

$this->title = $title;
?>

<?= $this->render('/_alert', ['module' => $module]);
