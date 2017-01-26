<?php

namespace frontend\fixtures;

use yii\test\ActiveFixture;

class TokenFixture extends ActiveFixture
{
    public $modelClass = 'dektrium\user\models\Token';

    public $depends = [
        'common\fixtures\UserFixture'
    ];
}
