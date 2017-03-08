# Testy codeception

**Wymagane**

- VirtualBox min. ver. 5.0.26
- Vagrant  min. ver. 1.8.6
- Testowane na firmowej wersji boxa 'puphpet/ubuntu1404-x64'


# Przykład dla projektu Gatta

**Instalacja codeception i phantomjs**

* wejść do katalogu ./vagrant/
* wykonać
```
vagrant up
```
* po uruchomieniu się wirtualnej maszyny należy się na nią zalogować
```
vagrant ssh
```
* doinstalować wymagane pakiety
```
apt-get install snmp-mibs-downloader
```
* pobrać i wypakować phantomjs w katalogu głównym projektu
```
http://phantomjs.org/download.html
```

* wejść do katalogu z projektem
```
cd /var/www/project/
```
* wejść do katalogu lib/tools, zainstalować zależności composer'owe + uruchomić migracje
```
cd /var/www/project/
cd ./lib/tools/
php composer.phar install
```
* powrócić do katalogu głównego projektu i zainstalować codeception
```
cd /var/www/project/
composer require "codeception/codeception"
alias codecept='./vendor/bin/codecept'
codecept bootstrap
```
* skonfigurować poprawnie ./tests/acceptance.suite.yml
```
class_name: AcceptanceTester
modules:
    enabled:
        - WebDriver:
            url: http://gatta.dev
            browser: phantomjs
            window_size: 1024x768
        - \Helper\Acceptance
```
* dodać funkcję do klassy AcceptanceTester w pliku ./tests/\_support/AcceptanceTester.php
```
function seePageHasElement($element)
    {
        try {
            $this->seeElement($element);
        } catch (\PHPUnit_Framework_AssertionFailedError $f) {
            return false;
        }
        return true;
    }
```
* skopiować dołączene pliki \*.php do ./tests/acceptance/

**Uruchomienie testów**
* komendy wpisywać po stronie vagranta w katalogu /var/www/project/
* w pierwszym terminalu wykonać następujące komendy:
```
cd phantomjs/bin/
./phantomjs --webdriver=4444
```
* w drugim terminalu za pomocą codecept wywoływać testy. Przykłady użycia:
```
codecept run acceptance       - uruchamia wszystkie testy
codecept run acceptance LoginCest - uruchamia testy logowania
codecept run acceptance LoginCest::tryToLogOnExistingAccount - uruchamia konkretny test o podanej nazwie
```
**Uwaga, manual zakłada że użytkownik pomyślnie skonfigurował projekt Gatta**
