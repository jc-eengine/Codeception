<?php


class LoginCest
{
    private $existingEmail = 'jakub.czerwinski@eengine.pl';
    private $existingPass = '12345';

    private $fakeEmail = 'fake@mail.com';
    private $fakePass = 'fakePass';

    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/pl/login.php');
        $I->seeElement('input[name=email_address]');
        $I->seeElement('input[name=password]');
        $I->seeElement('#submitLogin');
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToLogOnExistingAccount(AcceptanceTester $I)
    {
        $I->amOnPage('/pl/login.php');
        $I->fillField('email_address', $this->existingEmail);
        $I->fillField('password', $this->existingPass);
        $I->click('#submitLogin');
        $I->see('Moja Gatta');
    }

    public function tryToLogOnWithoutPassword(AcceptanceTester $I)
    {
        $I->amOnPage('/pl/login.php');
        $I->fillField('email_address', $this->existingEmail);
        $I->click('#submitLogin');
        $I->dontSee('Moja Gatta');
    }

    public function tryToLogOnWithoutEmail(AcceptanceTester $I)
    {
        $I->amOnPage('/pl/login.php');
        $I->fillField('password', $this->existingPass);
        $I->click('#submitLogin');
        $I->dontSee('Moja Gatta');
    }

    public function tryToLogOnWithoutEmailPass(AcceptanceTester $I)
    {
        $I->amOnPage('/pl/login.php');
        $I->click('#submitLogin');
        $I->dontSee('Moja Gatta');
    }

    public function tryToLogOnFakeAccount(AcceptanceTester $I)
    {
        $I->amOnPage('/pl/login.php');
        $I->fillField('email_address', $this->fakeEmail);
        $I->fillField('password', $this->fakePass);
        $I->click('#submitLogin');
        $I->dontSee('Moja Gatta');
    }

    public function tryToLogWithFakePass(AcceptanceTester $I)
    {
        $I->amOnPage('/pl/login.php');
        $I->fillField('email_address', $this->existingEmail);
        $I->fillField('password', $this->fakePass);
        $I->click('#submitLogin');
        $I->dontSee('Moja Gatta');
    }

    public function tryToLogOff(AcceptanceTester $I){
        $this->tryToLogOnExistingAccount($I);

        $I->moveMouseOver('.text.logged-user-name');
        $I->click('a[href=\'http://gatta.dev/pl/logoff.php\']');
        $I->see('Zostałeś wylogowany konta. Teraz można bezpiecznie zostawić komputer. ');
    }
}
