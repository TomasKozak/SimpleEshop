<?php
session_start();
include('db.php');
include('udaje.php');
include('funkcie.php');
hlavicka('Objednávka');
include('navigacia.php');
?>

<section>
<?php
$chyby = array();
if (isset($_POST["posli"])) {
	$meno = osetri($_POST['meno']);
	$adresa = osetri($_POST['adresa']);
	$tovar = osetri($_POST['tovar']);
	$pocet = osetri($_POST['pocet']);
	$doprava = osetri($_POST['doprava']);
	$odberD = osetri($_POST['odberD']);
	$odberM = osetri($_POST['odberM']);
	
	if (!spravne_meno($meno)) $chyby['meno'] = 'Meno nie je v správnom formáte';
	if (empty($meno)) $chyby['meno'] = 'Nezadali ste meno';
	if (!spravna_adresa($adresa)) $chyby['adresa'] = 'Adresa nemá aspoň 10 znakov';
	if (empty($adresa)) $chyby['adresa'] = 'Nezadali ste adresu';
	if (empty($tovar)) $chyby['tovar'] = 'Nezvolili ste žiadny tovar';
	if (empty($pocet)) $chyby['pocet'] = 'Počet ks tovaru musí byť > 0';
	if (empty($doprava)) $chyby['doprava'] = 'Nezvolili ste dopravu';
} else if (isset($_POST['zrus'])) {
	session_unset();
	session_destroy();
}

if(empty($chyby) && isset($_POST["posli"])) {
	$_SESSION['meno'] = $meno;
	$_SESSION['adresa'] = $adresa;
	$_SESSION['tovar'][$tovar] = $pocet;
} else {
	// ak bol odoslaný formulár, ale neboli zadané alebo boli zle zadané všetky povinné položky 
	if (!empty($chyby)) {
		echo '<p class="chyba">Nevyplnili ste všetky povinné údaje objednávky (meno, adresa, tovar, počet kusov, doprava)</p>';
		echo '<p class="chyba"><strong>Chyby v objednávke</strong>:<br>';
		foreach($chyby as $ch) {
			echo "$ch<br>\n";
		}
		echo '</p>';
	}
}

if (isset($_SESSION['meno'])) {
	vypis_kosik();
} else {
?>
<p>Objednajte si skvelé elektrospotrebiče značky UWT priamo z tepla Vašej kuchyne!</p>
<form method="post">
<fieldset>
	<legend>Kontaktné údaje</legend>
	<label for="meno">Meno a priezvisko:</label> <input type="text" name="meno" id="meno" size="40" maxlength="30" value="<?php if (isset($meno)) echo $meno; ?>"><br>
	<label for="adresa">Adresa doručenia:</label><br>
	<textarea name="adresa" id="adresa" rows="3" cols="35"><?php if (isset($adresa)) echo $adresa; ?></textarea>
</fieldset>
<fieldset>
	<legend>Údaje o objednávke</legend>
	<label for="tovar">Tovar:</label> 
	<select name="tovar" id="tovar">
		<option value=''>--- Zvoľte tovar ---</option>
<?php 
if (isset($tovar)) 
	vypis_select_tovar($tovar); 
else 
	vypis_select_tovar(); 
?>
	</select>
	<label for="pocet">počet kusov:</label>
	<select name="pocet" id="pocet">
<?php 
if (isset($pocet)) 
	vypis_select(0, 20, $pocet); 
else 
	vypis_select(0, 20); 
?>
  </select><br>
	Doprava: 
	<input type="radio" name="doprava" id="doprava_kurier" value="kurier"<?php if (isset($doprava) && $doprava=="kurier") echo ' checked'; ?>> <label for="doprava_kurier">kuriér</label>
	<input type="radio" name="doprava" id="doprava_taxi" value="taxi"<?php if (isset($doprava) && $doprava=="taxi") echo ' checked'; ?>> <label for="doprava_taxi">taxi</label>
	<input type="radio" name="doprava" id="doprava_vlastna" value="vlastna"<?php if (isset($doprava) && $doprava=="vlastna") echo ' checked'; ?>> <label for="doprava_vlastna">vlastná</label>
	<br>
	Odber tovaru: 
	<select name="odberD" id="odberD">
<?php 
if (isset($odberD)) 
	vypis_select(1, 31, $odberD); 
else 
	vypis_select(1, 31, date("j")); 
?>
	</select>.
	<select name="odberM" id="odberM">
<?php 
if (isset($odberM)) 
	vypis_select(1, 12, $odberM); 
else 
	vypis_select(1, 12, date("n")); 
?>
	</select> . <?php echo date("Y"); ?><br>
	<label for="spolu">spolu:</label>
	<input name="spolu" type="text" id="spolu" value="0" size="10" maxlength="10" readonly>
</fieldset>
<input type="submit" name="posli" value="Odošli objednávku">
</form>

<?php
}
?>
</section>

<?php
include('akcie.php');
include('pata.php');
?>
