<?php


class RegisterCest
{
    private $requireFields = array(
        'inputs' => array(
            'firstname',
            'email_address',
            'lastname',
            'street_address',
            'street_number',
            'postcode',
            'city',
            'telephone',
            'password',
            'confirmation',
            'rulesAccept'
        ),
        'selects' => array(
            'country'
        ),
    );

    private $optionalFields = array(
        'inputs' => array(
            'flat_number',
            'personalDataAccept',
            'marketingDataAccept'
        )
    );

    private $sampleValues = array(
        'firstname' => 'Test',
        'lastname' => 'Tescik',
        'street_address' => '1 Maja',
        'street_number' => '57',
        'postcode' => '10-200',
        'city' => 'Aleksandrów Łódzki',
        'telephone' => '500200500',
        'password' => '12345',
        'confirmation' => '12345'
    );

    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    private function _getGeneratedEmail(){
        return 'jc'.date('YMdhis').'@codeception.com';
    }

    private function _tryToRegisterWithout(AcceptanceTester $I, $blankField, $element, $response)
    {
        $values = $this->sampleValues;
        $values['email_address'] = $this->_getGeneratedEmail();
        $values[$blankField] = '';

        $I->amOnPage('/pl/create_account.php');

        foreach ($values as $key => $val){
            $I->fillField($key, $val);
        }

        $I->click('input[name=rulesAccept] + span');


        $I->click('form[name=createAccount] *[type=submit]');

        $I->waitForElement($element,3);
        $I->see($response);

    }

    // tests
    public function checkForRequiredFields(AcceptanceTester $I){

        $I->amOnPage('/pl/create_account.php');

        foreach ($this->requireFields['inputs'] as $field){
            $I->seeElement('input[name='.$field.']');
        }

        foreach ($this->requireFields['selects'] as $field){
            $I->seeElement('select[name='.$field.']');
        }
    }

    public function checkForOptionalFields(AcceptanceTester $I){

        $I->amOnPage('/pl/create_account.php');

        foreach ($this->optionalFields['inputs'] as $field){
            $I->seeElement('input[name='.$field.']');
        }

//        foreach ($this->optionalFields['selects'] as $field){
//            $I->seeElement('select[name='.$field.']');
//        }
    }

    public function tryToRegisterWithUniqueEmail(AcceptanceTester $I){
        $values = $this->sampleValues;
        $values['email_address'] = $this->_getGeneratedEmail();

        echo "\n\nData to put:\n";
        print_r($values);
        echo "\n\n";

        $I->amOnPage('/pl/create_account.php');

        foreach ($values as $key => $val){
            $I->fillField($key, $val);
        }

        $I->click('input[name=rulesAccept] + span');


        $I->click('form[name=createAccount] *[type=submit]');
        $I->see('TWOJE KONTO ZOSTAŁO ZAŁOŻONE!');
    }

    public function tryToRegisterWithoutRulesAccept(AcceptanceTester $I){
        $values = $this->sampleValues;
        $values['email_address'] = $this->_getGeneratedEmail();

        echo "\n\nData to put:\n";
        print_r($values);
        echo "\n\n";

        $I->amOnPage('/pl/create_account.php');

        foreach ($values as $key => $val){
            $I->fillField($key, $val);
        }


        $I->click('form[name=createAccount] *[type=submit]');
        $I->dontSee('TWOJE KONTO ZOSTAŁO ZAŁOŻONE!');
    }

    public function tryToRegisterWithExistingEmail(AcceptanceTester $I){
        $values = $this->sampleValues;
        $values['email_address'] = 'jakub.czerwinski@eengine.pl';

        echo "\n\nData to put:\n";
        print_r($values);
        echo "\n\n";

        $I->amOnPage('/pl/create_account.php');

        foreach ($values as $key => $val){
            $I->fillField($key, $val);
        }

        $I->click('input[name=rulesAccept] + span');


        $I->click('form[name=createAccount] *[type=submit]');

        $I->waitForElement('.inputRequirement_email_address',3);
        $I->see('Twój Adres E-Mail już istnieje w naszej bazie - użyj innego albo zaloguj się.');
    }

    public function tryToRegisterWithoutEmail(AcceptanceTester $I)
    {
        $this->_tryToRegisterWithout($I, 'email_address', '.inputRequirement_email_address', 'Twój Adres E-Mail już istnieje w naszej bazie - użyj innego albo zaloguj się.');
    }

    public function tryToRegisterWithoutFirstName(AcceptanceTester $I)
    {
        $this->_tryToRegisterWithout($I, 'firstname', '.inputRequirement_firstname', 'Podaj imię');
    }

    public function tryToRegisterWithoutLastName(AcceptanceTester $I)
    {
        $this->_tryToRegisterWithout($I, 'lastname', '.inputRequirement_lastname', 'Podaj nazwisko');
    }

    public function tryToRegisterWithoutStreetAddress(AcceptanceTester $I)
    {
        $this->_tryToRegisterWithout($I, 'street_address', '.inputRequirement_street_address', 'Ulica musi mieć min. 3 zn.');
    }

    public function tryToRegisterWithoutStreetNumber(AcceptanceTester $I)
    {
        $this->_tryToRegisterWithout($I, 'street_number', '.inputRequirement_street_number', 'min. 1 zn.');
    }

    public function tryToRegisterWithoutPostCode(AcceptanceTester $I)
    {
        $this->_tryToRegisterWithout($I, 'postcode', '.inputRequirement_postcode', 'Kod Pocztowy musi mieć min. 5 zn.');
    }

    public function tryToRegisterWithoutCity(AcceptanceTester $I)
    {
        $this->_tryToRegisterWithout($I, 'city', '.inputRequirement_city', 'Miasto musi mieć min. 3 zn.');
    }

    public function tryToRegisterWithoutTelephone(AcceptanceTester $I)
    {
        $this->_tryToRegisterWithout($I, 'telephone', '.inputRequirement_telephone', 'Nr Telefonu musi mieć min. 5 zn.');
    }

    public function tryToRegisterWithoutPassword(AcceptanceTester $I)
    {
        $this->_tryToRegisterWithout($I, 'password', '.inputRequirement_password', 'Hasło musi mieć min. 5 zn.');
    }

    public function tryToRegisterWithDifferentConfirmPassword(AcceptanceTester $I){
        $values = $this->sampleValues;
        $values['email_address'] = $this->_getGeneratedEmail();
        $values['confirmation'] = 'smthelse-trustMeItsNotThePassword';

        echo "\n\nData to put:\n";
        print_r($values);
        echo "\n\n";

        $I->amOnPage('/pl/create_account.php');

        foreach ($values as $key => $val){
            $I->fillField($key, $val);
        }

        $I->click('input[name=rulesAccept] + span');


        $I->click('form[name=createAccount] *[type=submit]');
        $I->see('Potwierdzenie Hasła nie zgadza się z Hasłem.');
    }


}
