<?php
session_start();
include('db.php');
include('udaje.php');
include('funkcie.php');
hlavicka('Administrácia');
include('navigacia.php');
?>

<section>
<?php
if (isset($_POST["prihlasmeno"]) && isset($_POST["heslo"]) && $pouzivatel = over_pouzivatela($_POST["prihlasmeno"], $_POST["heslo"])) {
	$_SESSION['id_pouz'] = $pouzivatel['id_pouz'];
	$_SESSION['user'] = $pouzivatel['prihlasmeno'];
	$_SESSION['meno'] = $pouzivatel['meno'];
	$_SESSION['priezvisko'] = $pouzivatel['priezvisko'];
	$_SESSION['admin'] = $pouzivatel['admin'];
} elseif (isset($_POST['odhlas'])) { // bol odoslany formular s odhlasenim
	session_unset();
	session_destroy();
}

if (isset($_SESSION['user'])) {
?>
<p>Vitajte v systéme <strong><?php echo $_SESSION['meno'] . ' ' . $_SESSION['priezvisko']; ?></strong>.</p>
<p>Ak chceš, môžeš si <a href="zmen_heslo.php">zmeniť heslo</a>.</p>
<?php
	if ($_SESSION['admin']) {
		echo '<p>Máš práva administrátora. Môžeš <a href="pridaj_pouzivatela.php">pridať nových používateľov</a>.</p>';

		if (isset($_POST['zrus']) && isset($_POST['tovar'])) {
			foreach($_POST['tovar'] as $tov) 
				zrus_tovar($tov);
		}
		
		if (isset($_GET['kod']) && ((int)$_GET['kod'] > 0)) { 
			vypis_objednavku($_GET['kod']);
		} else {
			vypis_tovar_uprav_zrus();
		
			vypis_objednavky();
		}
	}	else echo '<p>NEmáš práva administrátora.</p>';
?>
<form method="post"> 
  <p> 
    <input name="odhlas" type="submit" id="odhlas" value="Odhlás ma"> 
  </p> 
</form> 
<?php

}  else {
?>
	<form method="post">
		<p><label for="prihlasmeno">Prihlasovacie meno:</label> 
		<input name="prihlasmeno" type="text" size="30" maxlength="30" id="prihlasmeno" value="<?php if (isset($_POST["prihlasmeno"])) echo $_POST["prihlasmeno"]; ?>" ><br>
		<label for="heslo">Heslo:</label> 
		<input name="heslo" type="password" size="30" maxlength="30" id="heslo"> 
		</p>
		<p>
			<input name="submit" type="submit" id="submit" value="Prihlás ma">
		</p>
	</form>
<?php
}

?>
</section>

<?php
include('akcie.php');
include('pata.php');
?>
