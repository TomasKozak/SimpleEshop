<?php
session_start();
include('db.php');
include('udaje.php');
include('funkcie.php');
hlavicka('Pridaj používateľa');
include('navigacia.php');
?>

<section>
<?php 
if (isset($_SESSION['user']) && $_SESSION['admin']) {

$chyby = array();
if (isset($_POST["posli"])) {
	$prihlasmeno = osetri($_POST['prihlasmeno']);
	$heslo = $_POST['heslo'];
	$heslo2 = $_POST['heslo2'];
	$meno = osetri($_POST['meno']);
	$priezvisko = osetri($_POST['priezvisko']);
	if (isset($_POST['admin'])) $admin = osetri($_POST['admin']); else $admin = 0;	
	
	if (!nazov_ok($prihlasmeno)) $chyby['prihlasmeno'] = 'Prihlasovacie meno nie je v správnom formáte';
	if (empty($prihlasmeno)) $chyby['prihlasmeno'] = 'Nezadali ste prihlasovacie meno';
	if (!nazov_ok($heslo)) $chyby['heslo'] = 'Heslo nie je v správnom formáte';
	if (!nazov_ok($heslo2)) $chyby['heslo2'] = 'Heslo (znovu) nie je v správnom formáte';
	if ($heslo != $heslo2) $chyby['heslo2'] = 'Nezadali ste 2x rovnaké nové heslo';
	if (empty($heslo)) $chyby['heslo'] = 'Nezadali ste heslo';
	if (empty($heslo2)) $chyby['heslo2'] = 'Nezopakovali ste heslo';
	if (!nazov_ok($meno)) $chyby['meno'] = 'Meno nie je v správnom formáte';
	if (empty($meno)) $chyby['meno'] = 'Nezadali ste meno';
	if (!nazov_ok($priezvisko)) $chyby['priezvisko'] = 'Priezvisko nie je v správnom formáte';
	if (empty($priezvisko)) $chyby['priezvisko'] = 'Nezadali ste priezvisko';
}

if(empty($chyby) && isset($_POST["posli"])) {
	pridaj_pouzivatela($prihlasmeno, $heslo, $meno, $priezvisko, $admin);
} else {
	// ak bol odoslaný formulár, ale neboli zadané alebo boli zle zadané všetky povinné položky 
	if (!empty($chyby)) {
		echo '<p class="chyba">Nevyplnili ste všetky povinné údaje (prihlasovacie meno, heslo, meno, priezvisko, admin)</p>';
		echo '<p class="chyba"><strong>Chyby vo formulári</strong>:<br>';
		foreach($chyby as $ch) {
			echo "$ch<br>\n";
		}
		echo '</p>';
	}
?>
	<form method="post">
		<p><label for="prihlasmeno">Prihlasovacie meno (3-20 znakov):</label> 
		<input name="prihlasmeno" type="text" size="20" maxlength="20" id="prihlasmeno" value="<?php if (isset($prihlasmeno)) echo $prihlasmeno; ?>" ><br>
		<label for="heslo">Heslo (3-30 znakov):</label> 
		<input name="heslo" type="password" size="30" maxlength="30" id="heslo"> 
		<br>
		<label for="heslo2">Heslo (znovu):</label> 
		<input name="heslo2" type="password" size="30" maxlength="30" id="heslo2">
		<br> 
		<label for="meno">Meno (3-20 znakov):</label>
		<input type="text" name="meno" id="meno" size="20" value="<?php if (isset($meno)) echo $meno; ?>">
		<br>
		<label for="priezvisko">Priezvisko (3-30 znakov):</label>
		<input type="text" name="priezvisko" id="priezvisko" size="30" value="<?php if (isset($priezvisko)) echo $priezvisko; ?>">
		<br>
		Práva administrátora: <input type="radio" name="admin" id="admin_ano" value="1"<?php if (isset($admin) && $admin==1) echo ' checked'; ?>> <label for="admin_ano">áno</label>
		<input type="radio" name="admin" id="admin_nie" value="0"<?php if (empty($admin)) echo ' checked'; ?>> <label for="admin_nie">nie</label>
		</p>
		<p>
			<input name="posli" type="submit" id="posli" value="Pridaj používateľa">
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
