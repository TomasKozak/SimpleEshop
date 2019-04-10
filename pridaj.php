<?php
session_start();
include('db.php');
include('udaje.php');
include('funkcie.php');
hlavicka('Pridaj tovar');
include('navigacia.php');
?>

<section>
<?php 
if (isset($_SESSION['user']) && $_SESSION['admin']) {

$chyby = array();
if (isset($_POST["posli"])) {
	$nazov = osetri($_POST['nazov']);
	$popis = osetri($_POST['popis']);
	$cena = osetri($_POST['cena']);
	$na_sklade = osetri($_POST['na_sklade']);
	
	if (!nazov_ok($nazov)) $chyby['nazov'] = 'Názov tovaru nemá správnu dĺžku (3-100 znakov)';
	if (empty($nazov)) $chyby['nazov'] = 'Nezadali ste názov';
	if (!popis_ok($popis)) $chyby['popis'] = 'Popis nemá aspoň 10 znakov';
	if (empty($popis)) $chyby['popis'] = 'Nezadali ste popis';
	if (!cena_ok($cena)) $chyby['cena'] = 'Cena musí byť > 0';
	if (empty($cena)) $chyby['cena'] = 'Nezadali ste cenu';
	if (!sklad_ok($na_sklade)) $chyby['na_sklade'] = 'Počet kusov musí byť > 0';
	if (empty($na_sklade)) $chyby['na_sklade'] = 'Nezadali ste počet kusov';
}

if(empty($chyby) && isset($_POST["posli"])) {
	pridaj_tovar($nazov, $popis, $cena, $na_sklade, isset($_FILES) ? $_FILES['obrazok'] : '' );
} else {
	// ak bol odoslaný formulár, ale neboli zadané alebo boli zle zadané všetky povinné položky 
	if (!empty($chyby)) {
		echo '<p class="chyba">Nevyplnili ste všetky povinné údaje (názov, popis, cena, počet kusov na sklade)</p>';
		echo '<p class="chyba"><strong>Chyby pri pridávaní tovaru</strong>:<br>';
		foreach($chyby as $ch) {
			echo "$ch<br>\n";
		}
		echo '</p>';
	}
?>
	<form method="post" enctype="multipart/form-data">
		<p>
		<label for="nazov">Názov tovaru (3-100 znakov):</label>
		<input type="text" name="nazov" id="nazov" size="30" value="<?php if (isset($nazov)) echo $nazov; ?>">
		<br>
		<label for="popis">Popis (min. 10 znakov):</label>
		<br>
		<textarea cols="40" rows="4" name="popis" id="popis"><?php if (isset($popis)) echo $popis; ?></textarea>
		<br>
		<label for="cena">Cena (&gt;0):</label>
		<input type="text" name="cena" id="cena" size="5" maxlength="5" value="<?php if (isset($cena)) echo $cena; ?>">
		<br>
		<label for="na_sklade">Počet ks na sklade (&gt;0):</label>
		<input type="text" name="na_sklade" id="na_sklade" size="5" maxlength="5" value="<?php if (isset($na_sklade)) echo $na_sklade; ?>"> <br>
		<label for="obrazok">Obrázok:</label>
		<input type="file" name="obrazok" id="obrazok"> <br>
    <input type="submit" name="posli" value="Pridaj tovar">
		</p>  
  </form>
<?php
}

} else { // ci je prihlaseny nejaky pouzivatel (typu administrator)
	echo '<p><strong>K tejto stránke nemáte prístup.</strong></p>'; 
}
?>	
</section>

<?php
include('akcie.php');
include('pata.php');
?>
