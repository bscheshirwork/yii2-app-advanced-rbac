<?php

namespace components\helpers;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * Common decorator functional
 * Class ViewHelper
 * @package components\helpers
 */
class ViewHelper
{
    /**
     * Generate player tags
     * @param $fileName
     * @return string
     */
    public static function CallAudio($fileName)
    {
        return Html::tag('audio',
            Html::tag('source', null, [
                'src' => Yii::getAlias('@storageUrl') . '/' . $fileName . '.mp3',
                'type' => 'audio/mpeg'
            ]) . Yii::t('audio', 'Audio does not support in your browser'), ['controls' => 'controls']);
    }

    /**
     * Create the parameters array with options for use in activeDropDownList
     * Using in XxxDropDownList($activeOnly = false)
     * @param $all
     * @param $active
     * @param bool $multiDimension - if $all and $active form with 3 params of ArrayHelper::map
     * @return array
     */
    public static function fillDisabledOptions($all, $active, $multiDimension = false)
    {
        if ($multiDimension) {
            $fill = [];
            foreach ($all as $class => $map) {
                foreach ((array)$map as $id => $name) {
                    if (!array_key_exists($class, $active) || !array_key_exists($id, $active[$class])) {
                        $fill[$id] = ['disabled' => true];
                    }
                }
            }
        } else {
            $fill = array_fill_keys(array_keys(array_diff_key($all, $active)), ['disabled' => true]);
        }

        return [$all, ['options' => $fill]];
    }

    /**
     * Create a JS for DataPicker cancel propose events
     * register this JS before use \components\helpers\ViewHelper::createdAtDatePicker()
     * like this
     * $this->registerJs(\components\helpers\ViewHelper::dateAtDatePickerJS(), \yii\web\View::POS_READY, 'disable-submit-filter-handler');
     * @return string
     */
    public static function dateAtDatePickerJS()
    {
        return
            "
reload = function(){
    $(document).on('beforeFilter', null, false); //disable submit
    $('.no_submit_filter').off('change.yiiGridView keydown.yiiGridView click.yiiGridView', '**'); //disable listener
    window.setTimeout(function(){
        $('.no_submit_filter').on('change.yiiGridView keydown.yiiGridView click.yiiGridView', function(event) {
            event.stopImmediatePropagation(); //add listener to hide event for top-level listener
        });
        $(document).off('beforeFilter', null); //enable submit
    }, 1000);
    $(document).on('click', '.search-submit-circle', function(event){
        $(event.target).closest('.grid-view').yiiGridView('applyFilter'); //add
    });
}
reload();

$('.grid-view-date').each(function( index ) {
    $(document).on('afterFilter', $(this), reload);//add event relate by id, event still work without original element
});
";
    }

    /**
     * Create a config for dataGrid column with createdAt attribute.
     * MUST use with \components\helpers\ViewHelper::dateAtDatePickerJS()
     * Search model can realize two additional attributes - is a `createdAtAfter` and `createdAtBefore`
     * and class into gridView
     *    'options' => ['class' => 'grid-view grid-view-date'],
     * @param ActiveRecord $searchModel
     * @param string $widgetIdPrefix
     * @return array
     * @throws \Exception
     */
    public static function createdAtDatePicker(ActiveRecord $searchModel, string $widgetIdPrefix = '')
    {
        return self::dateAtDatePicker($searchModel, $widgetIdPrefix);
    }

    /**
     * Create a config for dataGrid column with createdAt attribute.
     * MUST use with \components\helpers\ViewHelper::dateAtDatePickerJS()
     * Search model can realize two additional attributes - is a `{$attribute . 'After'}` and `{$attribute . 'Before'}`
     * and class into gridView
     *    'options' => ['class' => 'grid-view grid-view-date'],
     *
     * also in Search model we need to add additional params to
     * function search()
     * {
     * ...
     *   $this->createdAtAfter && $query->andWhere(['>=', static::tableName() . '.' . static::FIELD_CREATE_AT, $this->createdAtAfter . ' 00:00:00']);
     *   $this->createdAtBefore && $query->andWhere(['<=', static::tableName() . '.' . static::FIELD_CREATE_AT, $this->createdAtBefore . ' 23:59:59']);
     * ...
     * }
     *
     * @param ActiveRecord $searchModel
     * @param string $widgetIdPrefix
     * @param string $attribute
     * @return array
     * @throws \Exception
     */
    public static function dateAtDatePicker(
        ActiveRecord $searchModel,
        string $widgetIdPrefix = '',
        string $attribute = 'createdAt'
    ) {
        $datePickerLayout = <<< HTML
    {input1}
    {separator}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
    <span class="input-group-addon search-submit-circle">
        <i class="glyphicon glyphicon-search"></i>
    </span>
HTML;

        return [
            'format' => 'raw',
            'label' => $searchModel->getAttributeLabel($attribute),
            'attribute' => $attribute,
            'filter' => \kartik\date\DatePicker::widget([
                'id' => $widgetIdPrefix . 'd',
                'type' => \kartik\date\DatePicker::TYPE_RANGE,
                'language' => 'ru',
                'name' => $searchModel->formName() . '[' . $attribute . 'After]',
                'value' => $searchModel->{$attribute . 'After'},
                'name2' => $searchModel->formName() . '[' . $attribute . 'Before]',
                'value2' => $searchModel->{$attribute . 'Before'},
                'layout' => $datePickerLayout,
                'separator' => '<i class="glyphicon glyphicon-resize-horizontal"></i>',
                // https://github.com/kartik-v/yii2-widget-datepicker/issues/150
                'options' => [
                    'placeholder' => Yii::t('main', 'Select date ...'),
                    'class' => 'no_submit_filter form-control'
                ],
                'options2' => [
                    'placeholder' => Yii::t('main', 'Select date ...'),
                    'class' => 'no_submit_filter form-control'
                ],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                    'autoclose' => true,
                ]
            ]),
        ];
    }

    /**
     * DateTime picker for ActiveForm
     * Usage in view:
     *     <?= $form->field($model, 'dateTimeField')->widget(...\components\helpers\ViewHelper::dateTimePickerWidget()); ?>
     * @param bool $readOnly
     * @return array
     */
    public static function dateTimePickerWidget(bool $readOnly = false)
    {
        return [
            \kartik\datetime\DateTimePicker::class,
            [
                'language' => 'ru',
                'options' => [
                    'placeholder' => Yii::t('main', 'Select date and time ...'),
                    'readOnly' => $readOnly,
                ],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd hh:ii:00',
                    'todayHighlight' => true,
                    'autoclose' => true,
                ],
            ],
        ];
    }

    /**
     * Autocomplete for ActiveForm.
     * Usage in view:
     *     <?= $form->field($model, 'relationId')->widget(...\components\helpers\ViewHelper::autoCompleteWidget(['model/autocomplete-relation'], 'id')); ?>
     * Usage in ModelController:
     *     public function actionAutocompleteRelation($term)
     *     {
     *         if (Yii::$app->request->isAjax) {
     *             return Json::encode(RelationModel::autocompleteData($term));
     *         }
     *
     *         return $this->redirect(['index']);
     *     }
     * Usage in RelationModel:
     *     public static function autocompleteData(string $term, int $limit = 10, bool $activeOnly = true): array
     *     {
     *         $results = [];
     *         foreach (static::find()->andWhere([
     *             'OR',
     *             ['like', 'id', $term],
     *             ['like', 'name', $term],
     *         ])->{$activeOnly ? 'active' : 'nothing'}()->limit($limit)->all() ?? [] as $model) {
     *             $results[] = [
     *                 'id' => $model['id'],
     *                 'label' => $model['name'] . ' (' . $model['id'] . ')',
     *             ];
     *         }
     *
     *         return $results;
     *     }
     *
     * @param array $urlTo AJAX action for get data. Default ['ajax/autocomplete']
     * @param string $display name of key in result set for write to field
     * @return array [string widgetClass, array widgetConfig]
     */
    public static function autocompleteWidget(array $urlTo = ['ajax/autocomplete'], string $display = 'id')
    {
        $template = '<div><p class="autocomplete-id">{{id}}</p>' .
            '<p class="autocomplete-label">{{label}}</p></div>';

        return [
            \kartik\typeahead\Typeahead::class,
            [
                'options' => ['placeholder' => Yii::t('main', 'Filter as you type ...')],
                'pluginOptions' => ['highlight' => true],
                'dataset' => [
                    [
                        'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('$display')",
                        'display' => $display,
                        'limit' => 10,
                        'remote' => [
                            'url' => \yii\helpers\Url::to($urlTo) . '?term=%QUERY',
                            'wildcard' => '%QUERY'
                        ],
                        'templates' => [
                            'notFound' => '<div class="text-danger" style="padding:0 8px">' . Yii::t('main',
                                    'Unable to find data for selected query.') . '</div>',
                            'suggestion' => new \yii\web\JsExpression("Handlebars.compile('{$template}')")
                        ],
                    ],
                ],
            ]
        ];
    }
}