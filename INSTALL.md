<h1>Namestitev</h1>
Vse datoteke razen INSTALL.md in README.md prenesite na strežnik.
<h1>Uporaba</h1>
* Za namestitev najprej vpišite kodo:<br>
<code><?php </code><br>
<code>include(''); //Med ' in ' vpišite pot do php-config.php datoteke.</code><br>
<code>$config = new Config; </code><br>
<code>$config->language(''); //Med ' in ' vpišite jezik (slovenian ali english).</code><br>
<code>$config->showClass(''); //Med ' in ' vpišite ali naj prikaže razred (1 ali 0).</code><br>
<code>$config->showOther(1); //Med ' in ' vpišite ali naj prikaže vrednosti zunaj razreda (1 ali 0).</code><br>

* Dodajate lahko tudi komentarje:<br>
<code>$config->comment(''); //Med ' in ' vpišite komentar.</code><br>

* Vrednosti dodate s to kodo:<br>
<code>$config->set('', '',''); //Prvi argument je ime vrednosti, drugi vrednost, tretji pa komentar (po želji).</code><br>

* Za zapis v datoteko napišite še:<br>
<code>$data = $config->toString('','',''); //Prvi argument je ime razreda (po želji drugače ''), drugi je kaj naj bo v razredu (po želji drugače ''), tretji pa dodatna koda izven razreda (po želji drugače ''). </code><br>
<br><code>$config->toFile('',$data); //Prvi argument je ime datoteke v katero se naj zapiše, drugega pustite privzeto.</code><br>

<h2>Prebiranje podatkov</h2>
* Če ste uporabili razred vpišite:<br>
<code>include('pot_do_datoteke'); //Namesto pot_do_datoteke vpišite pot do datoteke.</code><br><br>
<code>$class = new class; //Namesto class vpišite ime razreda.</code><br><br>
<code>$class->$ime_vrednosti; //Namesto class vpišite ime razreda, namesto ime_vrednosti vpišite ime vrednosti. Ta koda ne vrača vrednosti.Za izpis uporabite echo ali print.</code><br>

* Če niste uporabili razreda vpišite:<br>
<code>include('pot_do_datoteke'); //Namesto pot_do_datoteke vpišite pot do datoteke.</code><br>
<code>$ime_vrednosti; //Namesto ime_vrednosti vpišite ime vrednosti. Ta koda ne vrača vrednosti.Za izpis uporabite echo ali print.</code><br>
