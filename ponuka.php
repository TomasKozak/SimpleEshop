<?php
include('db.php');
include('udaje.php');
include('funkcie.php');
hlavicka('Detail','Ponuka');
include('navigacia.php');
?>

<section>
	<form method="post">
		Zoradiť podľa: 
  	<input type="submit" name="nazov1" value="názvu (A-Z)">
  	<input type="submit" name="nazov2" value="názvu (Z-A)">
  	<input type="submit" name="cena1" value="ceny (od najnižšej)">
  	<input type="submit" name="cena2" value="ceny (od najvyššej)">
  </form> 
<?php

if (!$mysqli->connect_errno) {
	$sql = "SELECT * FROM elektro_tovar WHERE na_sklade>0 "; // definuj dopyt
	if (isset($_POST['nazov2'])) $sql .= 'ORDER BY nazov DESC'; 
	elseif (isset($_POST['cena1'])) $sql .= 'ORDER BY cena ASC'; 
	elseif (isset($_POST['cena2'])) $sql .= 'ORDER BY cena DESC';
	else $sql .= 'ORDER BY nazov ASC'; // definuj dopyt

	if ($result = $mysqli->query($sql)) {  // vykonaj dopyt
		while ($row = $result->fetch_assoc()) {
			echo '<h2>' . $row['nazov'];
			echo ' (' . $row['cena'] . "&euro;)</h2>\n";
			// treba zistiť, či má tovar obrázok, ak áno, treba ho zobraziť
			echo '<p>';
			$subor = 'tovar-obrazky/' . $row['kod'] . '.png';
			if (file_exists($subor)) {
				echo '<img src="' . $subor . '" alt="' . $row['nazov'] . '">'. "\n";
			} 
			//echo $row['popis'] . "</p>\n";
			echo '<p><a href="tovar_detail.php?tovar=' . $row["kod"] . '">detail</a></p>';
		}
		$result->free();
	} elseif ($mysqli->errno) {
		echo '<p class="chyba">NEpodarilo sa vykonať dopyt! (' . $mysqli->error . ')</p>';
	}
}

?>
</section>

<?php
include('akcie.php');
include('pata.php');
?>
