# PHP config
S tem paketom boste lahko enostavno zapisovali konfiguracijske datoteke v obliki razredov ali pa v navadni.
<h2>Namestitev</h2>
Vse datoteke razen README.md prenesite na strežnik.
<h2>Uporaba</h2>
* Najprej vpišite kodo:<br>
```php
<?php
include(''); //Med ' in ' vpišite pot do php-config.php datoteke.
$config = new Config;
$config->language(''); //Med ' in ' vpišite jezik (slovenian ali english).
$config->showClass(''); //Med ' in ' vpišite ali naj prikaže razred (1 ali 0).+
$config->showOther(1); //Med ' in ' vpišite ali naj prikaže vrednosti zunaj razreda (1 ali 0).
```
* Dodajate lahko tudi komentarje:<br>
```php
$config->comment(''); //Med ' in ' vpišite komentar.
```
* Vrednosti dodate s to kodo:<br>
```php
$config->set('', '',''); //Prvi argument je ime vrednosti, drugi vrednost, tretji pa komentar (po želji).
```
* Za zapis v datoteko napišite še:<br>
```php
$data = $config->toString('','',''); //Prvi argument je ime razreda (po želji drugače ''), drugi je kaj naj bo v razredu (po želji drugače ''), tretji pa dodatna koda izven razreda (po želji drugače ''). 
$config->toFile('',$data); //Prvi argument je ime datoteke v katero se naj zapiše, drugega pustite privzeto.
?>
```
<h3>Prebiranje podatkov</h3>
* Če ste uporabili razred vpišite:<br>
```php
<?php
include('pot_do_datoteke'); //Namesto pot_do_datoteke vpišite pot do datoteke.
$class = new class; //Namesto class vpišite ime razreda.
$class->$ime_vrednosti; //Namesto class vpišite ime razreda, namesto ime_vrednosti vpišite ime vrednosti. Ta koda ne vrača vrednosti.Za izpis uporabite echo ali print.
?>
```
* Če niste uporabili razreda vpišite:<br>
```php
<?php
include('pot_do_datoteke'); //Namesto pot_do_datoteke vpišite pot do datoteke.
$ime_vrednosti; //Namesto ime_vrednosti vpišite ime vrednosti. Ta koda ne vrača vrednosti.Za izpis uporabite echo ali print.
?>
```
<h3>Napake</h3>
* Če jih želite napake v datoteko, ki zapisuje config datoteko vpišite:
```php
<?php
echo $config->status_message();
?>
```
* Če želite izpisati status v datoteko, ki zapisuje config datoteko vpišite:
```php
<?php
echo $config->status(); //1 pomeni dokončano, 0 pomeni napaka.
?>
```
