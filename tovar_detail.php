<?php
include('db.php');
include('udaje.php');
include('funkcie.php');
hlavicka('Detail','Ponuka');
include('navigacia.php');
?>

<section>
<?php
if (!$mysqli->connect_errno) {
$sql = "SELECT * FROM `elektro_tovar` WHERE kod=".$_GET['tovar'];
	

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
			echo $row['popis'] . "</p>\n";
			//echo '<p><a href="tovar_detail.php?tovar=' . $row["kod"] . '">detail</a></p>';
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
