<?php
session_start();
include('db.php');
include('udaje.php');
include('funkcie.php');
hlavicka('Zmena hesla');
include('navigacia.php');
?>

<section>
<?php
if (isset($_SESSION['user'])) {
	if (isset($_POST["submit"])) {
		if (isset($_POST["stare_heslo"]) && (trim($_POST["stare_heslo"]) != '') && ($pouzivatel = over_pouzivatela($_SESSION["user"], $_POST["stare_heslo"]))) {
		
			if (isset($_POST["heslo"]) && isset($_POST["heslo2"]) && nazov_ok($_POST["heslo"]) && ($_POST["heslo"] == $_POST["heslo2"])) {
				zmen_heslo($_SESSION['id_pouz'], $_POST["heslo"]);     
			}
			else echo '<p class="chyba">Nezadali ste 2x rovnaké nové heslo alebo je heslo kratšie ako 3 znaky.</p>';
		} else {
			echo '<p class="chyba">Pôvodné heslo nie je správne, alebo nebolo zadané.</p>';
		}
	}
?>
 
	<form method="post">
		<p> 
		<label for="stare_heslo">Pôvodné heslo:</label> 
		<input name="stare_heslo" type="password" size="30" maxlength="30" id="stare_heslo"><br> 
		<label for="heslo">Nové heslo:</label> 
		<input name="heslo" type="password" size="30" maxlength="30" id="heslo"><br> 
		<label for="heslo2">Nové heslo (znovu):</label> 
		<input name="heslo2" type="password" size="30" maxlength="30" id="heslo2"><br> 
		</p>
		<p>
			<input name="submit" type="submit" id="submit" value="Zmeniť heslo">
		</p>
	</form>
<?php

} else { // ci je prihlaseny nejaky pouzivatel 
	echo '<p><strong>K tejto stránke nemáte prístup.</strong></p>'; 
}

?>
</section>

<?php
include('akcie.php');
include('pata.php');
?>
