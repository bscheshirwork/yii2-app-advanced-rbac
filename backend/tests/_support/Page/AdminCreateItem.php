<?php
namespace backend\tests\Page;

use yii\helpers\Url;

class AdminCreateItem
{
    public static $URL = '/admin/role/create';

    /**
     * @var \Codeception\Actor
     */
    protected $tester;

    public function __construct(\Codeception\Actor $I)
    {
        $this->tester = $I;
    }

    public function amOnPage(){
        $I = $this->tester;
        $I->amOnPage(Url::toRoute(self::$URL));
    }

    /**
     * @param array $roleData
     */
    public function submit(array $roleData)
    {
        $I = $this->tester;

        $I->amOnPage(Url::toRoute(self::$URL));

        $inputTypes = [
            'name' => 'input',
            'description' => 'textarea',
            'ruleName' => 'select',
            'data' => 'textarea',
        ];
        foreach ($roleData as $field => $value) {
            $inputType = isset($inputTypes[$field]) ? $inputTypes[$field] : 'input';
            $I->fillField($inputType . '[name="AuthItem[' . $field . ']"]', $value);
        }
        $I->click('submit-button');
    }
}
