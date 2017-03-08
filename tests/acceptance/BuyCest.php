<?php
use \Codeception\Util\Locator;

class BuyCest
{
    private $existingEmail = 'jakub.czerwinski@eengine.pl';
    private $existingPass = '12345';

    private $sampleValues = array(
        'customer_firstname' => 'Test',
        'customer_lastname' => 'Tescik',
        'customer_street_address' => '1 Maja',
        'customer_street_number' => '57',
        'customer_postcode' => '10-200',
        'customer_city' => 'Aleksandrów Łódzki',
        'customer_telephone' => '500200500',
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

    private function _logIn(AcceptanceTester $I)
    {
        $I->amOnPage('/pl/login.php');
        $I->fillField('email_address', $this->existingEmail);
        $I->fillField('password', $this->existingPass);
        $I->click('#submitLogin');
        $I->see('Moja Gatta');
    }

    private function _selectOneItem(AcceptanceTester $I){
        if($I->seePageHasElement('.productInListing')){
            $I->click('a', '.productInListing');
            echo "\n\nURL: ".$I->grabFromCurrentUrl()."\n\n";

            $I->click('span', '.js-color-element');
            $I->click('.dropdown-toggle', '.sizes');
            $I->waitForElement('.dropdown-menu', 1);
            $I->click('li', '.sizes');
            $I->click('.productInCartButton');

            $I->moveMouseOver('#BoxCart');
            $I->waitForElement('#quickCartPreview', 8);
            $I->seeElement('.goToBasket');
            $I->click('#quickCartPreview .btn');
        }
        else {
            exit(1);
        }
    }

    // tests
    public function tryToBuyOneItemWithAccount(AcceptanceTester $I)
    {
        $this->_logIn($I);
        $I->moveMouseOver('#menu ul li:first-child');

        $I->seeElement('#menu ul.categories');
        $I->click('a.item', '#menu');
        echo "\n\nURL: ".$I->grabFromCurrentUrl()."\n\n";

        if($I->seePageHasElement('.productInListing')){
            $I->click('a', '.productInListing');
            echo "\n\nURL: ".$I->grabFromCurrentUrl()."\n\n";

            $I->click('span', '.js-color-element');
            $I->click('.dropdown-toggle', '.sizes');
            $I->waitForElement('.dropdown-menu', 1);
            $I->click('li', '.sizes');
            $I->click('.productInCartButton');

            $I->moveMouseOver('#BoxCart');
            $I->waitForElement('#quickCartPreview', 8);
            $I->seeElement('.goToBasket');
            $I->click('#quickCartPreview .btn');

            echo "\n\nURL: ".$I->grabFromCurrentUrl()."\n\n";

            $I->wait(5);

            $I->click('#_paczkapocztowa + label');
            $I->click('#platneprzyodbiorze + label');
            $I->click('#customer_rulesAccept + label');

            $I->seeElement('button[type=submit]');
            $I->submitForm('.f-ajaxForm-checkSteps', []); // $I->click('button[type=submit] NOT WORKING : Codeception bug

            $I->wait(15);
            $I->see('DZIĘKUJEMY ZA ZAKUPY W SKLEPIE GATTA.PL');
        }
        else {
            echo "\n\nNot found\n\n";
        }
    }

    public function tryToBuyOneItemWithoutAccount(AcceptanceTester $I)
    {
        $values = $this->sampleValues;
        $values['customer_customer_email_address'] = $this->_getGeneratedEmail();

        $I->amOnPage('/');
        $I->moveMouseOver('#menu ul li:first-child');

        $I->seeElement('#menu ul.categories');
        $I->click('a.item', '#menu');
        echo "\n\nURL: ".$I->grabFromCurrentUrl()."\n\n";

        if($I->seePageHasElement('.productInListing')){
            $I->click('a', '.productInListing');
            echo "\n\nURL: ".$I->grabFromCurrentUrl()."\n\n";

            $I->click('span', '.js-color-element');
            $I->click('.dropdown-toggle', '.sizes');
            $I->waitForElement('.dropdown-menu', 1);
            $I->click('li', '.sizes');
            $I->click('.productInCartButton');

            $I->moveMouseOver('#BoxCart');
            $I->waitForElement('#quickCartPreview', 10);
            $I->seeElement('.goToBasket');
            $I->click('#quickCartPreview .btn');

            echo "\n\nURL: ".$I->grabFromCurrentUrl()."\n\n";

            $I->seeElement('a', ['href' => 'http://gatta.dev/pl/zamowienie-bez-rejestracji.html']);
            $I->wait(10);
            $I->click('a', '.col-md-4.col-sm-6'); //Guest by link

            echo "\n\nURL: ".$I->grabFromCurrentUrl()."\n\n";

            $I->wait(5);

            $I->click('#_paczkapocztowa + label');
            $I->click('#platneprzyodbiorze + label');

            foreach ($values as $key => $val){
                $I->fillField($key, $val);
            }

            $I->click('#customer_rulesAccept + label');

            $I->seeElement('button[type=submit]');
            $I->submitForm('.f-ajaxForm-checkSteps', []); // $I->click('button[type=submit] NOT WORKING : Codeception bug

            $I->wait(15);
            $I->see('DZIĘKUJEMY ZA ZAKUPY W SKLEPIE GATTA.PL');
        }
        else {
            echo "\n\nNot found\n\n";
        }
    }
}
