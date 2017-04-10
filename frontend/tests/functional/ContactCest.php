<?php
namespace frontend\tests\functional;

use frontend\models\ContactForm;
use frontend\tests\FunctionalTester;
use Yii;

/* @var $scenario \Codeception\Scenario */

class ContactCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage(['site/contact']);
    }

    public function checkContact(FunctionalTester $I)
    {
        $I->see(Yii::t('main', 'Contact', 'h1'));
    }

    public function checkContactSubmitNoData(FunctionalTester $I)
    {
        $model = new ContactForm;
        $I->submitForm('#contact-form', []);
        $I->see(Yii::t('main', 'Contact', 'h1'));
        $I->seeValidationError(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('name')]));
        $I->seeValidationError(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('email')]));
        $I->seeValidationError(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('subject')]));
        $I->seeValidationError(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('body')]));
        $I->seeValidationError(Yii::t('yii', 'The verification code is incorrect.'));
    }

    public function checkContactSubmitNotCorrectEmail(FunctionalTester $I)
    {
        $I->submitForm('#contact-form', [
            'ContactForm[name]' => 'tester',
            'ContactForm[email]' => 'tester.email',
            'ContactForm[subject]' => 'test subject',
            'ContactForm[body]' => 'test content',
            'ContactForm[verifyCode]' => 'testme',
        ]);
        $I->seeValidationError(Yii::t('yii', '{attribute} is not a valid email address.', ['attribute' => $model->getAttributeLabel('email')]));
        $I->dontSeeValidationError(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('name')]));
        $I->dontSeeValidationError(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('subject')]));
        $I->dontSeeValidationError(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $model->getAttributeLabel('body')]));
        $I->dontSeeValidationError(Yii::t('yii', 'The verification code is incorrect.'));
    }

    public function checkContactSubmitCorrectData(FunctionalTester $I)
    {
        $I->submitForm('#contact-form', [
            'ContactForm[name]' => 'tester',
            'ContactForm[email]' => 'tester@example.com',
            'ContactForm[subject]' => 'test subject',
            'ContactForm[body]' => 'test content',
            'ContactForm[verifyCode]' => 'testme',
        ]);
        $I->seeEmailIsSent();
        $I->see(Yii::t('main', 'Thank you for contacting us. We will respond to you as soon as possible.'));
    }
}
