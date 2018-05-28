<?php

/**
 * @see https://github.com/dektrium/yii2-user
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var dektrium\user\Module|yii\base\Module $module
 */

$this->title = $title;
echo $this->render('/_alert', ['module' => $module]);
