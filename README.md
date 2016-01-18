# PHP config
S tem paketom boste lahko enostavno zapisovali konfiguracijske datoteke v obliki razredov ali pa v navadni.
#Namestitev
Kopirajte vse datoteke na strežnik. README.md lahko po želji odstranite. php-config.php lahko preimenujete. Zapomnite si njeno ime, saj ga boste potrebovali.
#Uporaba
*V vašo datoteko kopirajte nasljednjo kodo:

include(''); //med ' in ' vstavite pot do datoteke php-config.php

$config = new Config;

$config->language(''); //med ' in ' vpišite jezik (trenutno na voljo slovenian in english)

$config->showClass(); //med ( in ) vpišite ali želite imeti prikazan razred (1 ali 0)

$config->showOther(); //med ( in ) vpišite ali želite imeti prikazano kodo izven razreda (1 ali 0)


*Komentarje lahko dodajate s kodo:

$config->comment(''); //med ' in ' vpišite komentar


*Vrednosti dodajate tako:

$config->set('','',''); //prvi argument je ime vrednosti, drugi vrednost, tretji pa komentar (po želji)


*Za zapisovanje v datoteko vpišite še:

$data = $config->toString('','',''); //prvi argument je ime razreda (če ste določili, da se prikaže, drugače pustite prazno), drugi dodatna koda v razredu (če ste določili, da se razred prikaže, drugače pustite prazno), tretji pa dodatna koda izven razreda (po želji, drugače pustite prazno)

$config->toFile('',$data); //prvi argument je ime datoteke, v katero se bo zapisalo, druga pa podatki (pustite privzeto)


**Pridobitev podatkov iz datoteke

*Če ste uporanili razred vpišite:

include(''); //med ' in ' vpišite ime datoteke v katero so se zapisali podatki

$class = new class; //namesto class vpišite ime razreda

$class->ime_vrednosti; //namesto class napišite ime razreda, namesto ime_vrednosti vpišite ime vrednosti. Prikazana koda ne izpiše ničesar če želite izpisati uporabite echo ali print


*Če niste uporabili razreda vpišite:

$ime_vrednosti; //namesto ime_vrednosti vpišite ime vrednosti. Prikazana koda ne izpiše ničesar če želite izpisati uporabite echo ali print
