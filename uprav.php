<?php
session_start();
include('db.php');
include('udaje.php');
include('funkcie.php');
hlavicka('Uprav tovar');
include('navigacia.php');
?>

<section>
<?php 
if (isset($_SESSION['user']) && $_SESSION['admin']) {

$zobraz_form = false;
$chyby = array();
if (!(isset($_GET['kod']) && ((int)$_GET['kod'] > 0))) {
	// chybny kod
	echo '<p class="chyba">Chybne odoslaný kód tovaru</p>';
} else { // isset($_GET['kod']) && ((int)$_GET['kod'] > 0))
	$kod = osetri($_GET['kod']);
	if (isset($_POST["posli"])) {
		$data['nazov'] = osetri($_POST['nazov']);
		$data['popis'] = osetri($_POST['popis']);
		$data['cena'] = osetri($_POST['cena']);
		$data['na_sklade'] = osetri($_POST['na_sklade']);
		
		if (!nazov_ok($data['nazov'])) $chyby['nazov'] = 'Názov tovaru nemá správnu dĺžku (3-100 znakov)';
		if (empty($data['nazov'])) $chyby['nazov'] = 'Nezadali ste názov';
		if (!popis_ok($data['popis'])) $chyby['popis'] = 'Popis nemá aspoň 10 znakov';
		if (empty($data['popis'])) $chyby['popis'] = 'Nezadali ste popis';
		if (!cena_ok($data['cena'])) $chyby['cena'] = 'Cena musí byť > 0';
		if (empty($data['cena'])) $chyby['cena'] = 'Nezadali ste cenu';
		if (!sklad_ok($data['na_sklade'])) $chyby['na_sklade'] = 'Počet kusov musí byť > 0';
		if (empty($data['na_sklade'])) $chyby['na_sklade'] = 'Nezadali ste počet kusov';
	} else {
		if ($data = daj_udaje_tovaru($kod)) { // zisti údaje o tovare z databázy
			$zobraz_form = true;
		} else {
			echo '<p class="chyba">Zadané číslo tovaru neexistuje</p>';
		}
	}
}

if(!$zobraz_form && empty($chyby) && isset($_POST["posli"])){
	uprav_tovar($kod, $data, isset($_FILES) ? $_FILES['obrazok'] : '' );
} else {
	// ak bol odoslaný formulár, ale neboli zadané alebo boli zle zadané všetky povinné položky 
	if (!empty($chyby)) {
		$zobraz_form = true;
		echo '<p class="chyba"><strong>Chyby pri úprave tovaru</strong>:<br>';
		foreach($chyby as $ch) {
			echo "$ch<br>\n";
		}
		echo '</p>';
	}
}

if ($zobraz_form) {
?>
	<p class="chyba">Musíte zadať všetky povinné údaje: názov, popis, cena, počet kusov na sklade</p>
	<form method="post" enctype="multipart/form-data">
		<p>
		<label for="nazov">Názov tovaru (3-100 znakov):</label>
		<input type="text" name="nazov" id="nazov" size="30" value="<?php if (isset($data['nazov'])) echo $data['nazov']; ?>">
		<br>
		<label for="popis">Popis (min. 10 znakov):</label>
		<br>
		<textarea cols="40" rows="4" name="popis" id="popis"><?php if (isset($data['popis'])) echo $data['popis']; ?></textarea>
		<br>
		<label for="cena">Cena (&gt;0):</label>
		<input type="text" name="cena" id="cena" size="5" maxlength="5" value="<?php if (isset($data['cena'])) echo $data['cena']; ?>">
		<br>
		<label for="na_sklade">Počet ks na sklade (&gt;0):</label>
		<input type="text" name="na_sklade" id="na_sklade" size="5" maxlength="5" value="<?php if (isset($data['na_sklade'])) echo $data['na_sklade']; ?>"> <br>
		<?php
		$subor = 'tovar-obrazky/' . $kod . '.png'; 
		if (file_exists($subor)) 
			echo 'Aktuálny obrázok: <img src="' . $subor . '" alt="' . $data['nazov'] . '"><br>'; 
		?>
		<label for="obrazok">Obrázok:</label>
		<input type="file" name="obrazok" id="obrazok"> <br>
    <input type="submit" name="posli" value="Aktualizuj tovar">
		</p>  
  </form>
<?php
} else {} // end if ($zobraz_form) {

} else { // ci je prihlaseny nejaky pouzivatel (typu administrator)
	echo '<p><strong>K tejto stránke nemáte prístup.</strong></p>'; 
}
?>	
</section>

<?php
include('akcie.php');
include('pata.php');
?>
