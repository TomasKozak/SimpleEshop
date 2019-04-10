<?php
date_default_timezone_set('Europe/Bratislava');

function vypis_select($zac, $kon, $default = 0) {
	for($i = $zac; $i <= $kon; $i++) {
		echo "<option value='$i'";
		if ($i == $default) echo ' selected';
		echo ">$i</option>\n";
	}
}
	
function hlavicka($nadpis) {
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $nadpis; ?></title>
<link href="styly.css" rel="stylesheet">
</head>

<body>

<header>
<h1><?php echo $nadpis; ?></h1>
</header>
<?php
}

function vypis_select_tovar($default = 0) {
	global $mysqli;
	if (!$mysqli->connect_errno) {
		$sql = "SELECT * FROM elektro_tovar WHERE na_sklade > 0 ORDER BY nazov ASC"; // definuj dopyt
		if ($result = $mysqli->query($sql)) {  // vykonaj dopyt
			// dopyt sa podarilo vykonať
			while ($row = $result->fetch_assoc()) {
				echo "<option value='" . $row['kod'] . "'";
				if ($row['kod'] == $default) echo ' selected';
				echo '>' . $row['nazov'] . ' (' . $row['cena'] . " &euro;)</option>\n";
			}
			$result->free();
		}
	}
}

/* kontroluje meno (meno a priezvisko)
vráti TRUE, ak celé meno ($m) obsahuje práve 1 medzeru, pred a za medzerou sú časti aspoň dĺžky 3 znaky
*/
function spravne_meno($m) {
  $medzera = strpos($m, ' ');
  if (!$medzera) return false;       
  $priezvisko = substr($m, $medzera+1);  
  return ($medzera > 2 && (strpos($priezvisko, ' ') === FALSE) && strlen($priezvisko) > 2);
}

function vypis_kosik() {
	global $vyrobky;
	echo '<p><strong>Obsah košíka:</strong></p>';
	echo '<p>Adresa doručenia: ' . $_SESSION["adresa"] . '</p>';
	$cena = 0;
	foreach ($_SESSION['tovar'] as $kluc => $hodn) {
		echo '<p>Tovar: <strong>' . $vyrobky[$kluc]['nazov'] . '</strong> v počte kusov <strong>' . $_SESSION['tovar'][$kluc] . '</strong></p>'; 
		$cena += cena_objednavky($kluc, $hodn); 
	}
	echo '<p>Cena: ' . $cena . ' &euro;</p>';  
?>
	<form method="post">
		<p><input type="submit" name="zrus" value="Zruš obsah košíka"></p>
	</form>
<?php
}

function osetri($co) {
	return trim(strip_tags($co));
}

// vráti TRUE, ak má adresa aspoň 10 znakov
function spravna_adresa($a) {
//  return $a != '';
  return strlen($a) >= 10;
}

// vráti celkovú cenu objednávky pre tovar $ind s počtom kusov $poc 
function cena_objednavky($ind, $poc) {
  global $vyrobky;
  return $vyrobky[$ind]['cena'] * $poc;
}

function nazov_ok ($nazov) {
	return strlen($nazov) >= 3 && strlen($nazov) <= 100;
}

function popis_ok ($popis) {
	return strlen($popis) >= 10;
}

function cena_ok ($cena) {
	return (1 * $cena) > 0;
}

function sklad_ok ($sklad) {
	return (1 * $sklad) > 0;
}

// funkcia by mala dostať vstupy (názov, popis, cena, na_sklade) buď samostatne, alebo ako pole
function pridaj_tovar($nazov, $popis, $cena, $na_sklade, $subor) {
	global $mysqli;
	if (!$mysqli->connect_errno) {
		$nazov = $mysqli->real_escape_string($nazov);
		$popis = $mysqli->real_escape_string($popis);
		$cena = $mysqli->real_escape_string($cena);
		$na_sklade = $mysqli->real_escape_string($na_sklade);

		$sql = "INSERT INTO elektro_tovar SET nazov='$nazov', popis='$popis', cena='$cena', na_sklade='$na_sklade'"; // definuj dopyt
	
		if ($result = $mysqli->query($sql)) {  // vykonaj dopyt
	    $kod_tovaru = $mysqli->insert_id;
 	    echo '<p>Tovar s kódom <strong>'. $kod_tovaru .'</strong> bol pridaný.</p>'. "\n"; 

			// spracuj uploadovaný súbor 
			if (!empty($subor) && !empty($subor['name'])) {
				//echo 'nieco sa odosiela';
				
				if ($subor['error'] == UPLOAD_ERR_OK) {
					if (is_uploaded_file($subor['tmp_name'])) {
						if ($subor['type'] == 'image/png') {
							$novy_nazov = 'tovar-obrazky/' . $kod_tovaru . '.png';
							$ok = move_uploaded_file($subor['tmp_name'], $novy_nazov);
							if ($ok) {
								echo '<p>Súbor bol nahratý na server.</p>';
                exec("chmod a+r $novy_nazov");
							} else {
								echo '<p class="chyba">Súbor NEbol nahratý na server.</p>';
							}
						} else {
							echo '<p class="chyba">Súbor nie je požadovaného typu (image/png).</p>';
						}
					}
				} else { 
					// nastane, ak bol uploadovaný súbor väcší ako upload_max_filesize (chyba 2)
					// nastane aj vtedy, ak bol uploadovaný súbor väcší ako post_max_size (chyba 2)
					echo '<p class="chyba">Nastal problém pri uploadovaní súboru ' . $subor['name'] . ' - ' . $subor['error'] . '</p>';
				}
			}

		} elseif ($mysqli->errno) {
			echo '<p class="chyba">Nastala chyba pri pridávaní tovaru. (' . $mysqli->error . ')</p>';
		}
	}
}	// koniec funkcie

function vypis_tovar_uprav_zrus() {
?>
	<p><a href="pridaj.php">pridaj tovar</a></p>
<?php
	global $mysqli;
	if (!$mysqli->connect_errno) {
		$sql="SELECT * FROM elektro_tovar ORDER BY nazov ASC";
		if ($result = $mysqli->query($sql)) {  // vykonaj dopyt
			// dopyt sa podarilo vykonať
			echo '<form method="post">';
			echo '<p>'; 
			while ($row = $result->fetch_assoc()) {
    		echo "<input type='checkbox' name='tovar[]' value='{$row['kod']}' id='tovar{$row['kod']}'><label for='tovar{$row['kod']}'><a href='uprav.php?kod={$row['kod']}'>{$row['nazov']}</a></label><br>\n";
			} 
			echo '</p>'; 
  		echo '<p><input type="submit" name="zrus" value="Zruš tovary"></p>';
  		echo '</form>';
			$result->free();
  	} else {
			// NEpodarilo sa vykonať dopyt!
    	echo '<p class="chyba">Nastala chyba pri získavaní údajov z DB.</p>' . "\n";
    }
	}
}

// zrusi tovar c. $idt z tabulky elektro_tovar
function zrus_tovar($idt) {
	global $mysqli;
	if (!$mysqli->connect_errno) {
		$sql="DELETE FROM elektro_tovar WHERE kod='{$mysqli->real_escape_string($idt)}'"; // definuj dopyt
		if ($result = $mysqli->query($sql) && ($mysqli->affected_rows > 0)) {  // vykonaj dopyt
			// dopyt sa podarilo vykonať
	    echo "<p>Tovar č. $idt bol zrušený.</p>\n"; 
			// treba zistiť, či mal tovar obrázok, ak áno, treba ho zrušiť
			$subor = 'tovar-obrazky/' . $idt . '.png';
			if (file_exists($subor)) {
				if (unlink($subor)) echo '<p>Obrázok tovaru bol vymazaný.</p>'. "\n";
				else echo '<p class="chyba">Obrázok tovaru sa nepodarilo vymazať. Treba ho vymazať ručne.</p>'. "\n";
			} 
  	} else {
			// NEpodarilo sa vykonať dopyt!
    	echo "<p class='chyba'>Nastala chyba pri rušení tovaru č. $idt.</p>\n";
    }
	}
} 	// koniec funkcie

// vypise tabulku vsetkych objednavok s odkazom na podrobne udaje o konkretnej objednavke
function vypis_objednavky() {
	global $mysqli;
	if (!$mysqli->connect_errno) {
		$sql = "SELECT * FROM elektro_objednavky, elektro_pouzivatelia WHERE elektro_objednavky.id_pouz = elektro_pouzivatelia.id_pouz ORDER BY id ASC"; // definuj dopyt
//		echo "sql = $sql <br>";
		if ($result = $mysqli->query($sql)) {  // vykonaj dopyt
			// dopyt sa podarilo vykonať
			echo '<table>';
			echo '<tr><th>číslo objednávky</th><th>meno a priezvisko</th><th>dátum odberu</th><th>cena</th></tr>';
			while ($row = $result->fetch_assoc()) {
				echo '<tr><td><a href="administracia.php?kod=' . $row['id'] . '">' . $row['id'] . '</a></td><td>' . $row['meno'] . ' ' . $row['priezvisko'] . '</td><td>' . $row['datum_odberu'] . '</td><td>' . $row['cena_spolu'] . '&euro;</td>';
				echo "</tr>\n";
			}
			echo '</table>';
			$result->free();
		} else {
			// dopyt sa NEpodarilo vykonať!
			echo '<p class="chyba">NEpodarilo sa získať údaje z databázy</p>';
		}
	}
}

function vypis_objednavku($id) {
	// do premennej $row treba priradiť jednotlivé položky objednávky $id 
	global $mysqli;
	if (!$mysqli->connect_errno) {
		$id = $mysqli->real_escape_string($id);
		$sql = "SELECT * FROM elektro_objednavky, elektro_tovar, elektro_pouzivatelia WHERE elektro_objednavky.tovar = elektro_tovar.kod AND elektro_objednavky.id='$id' AND elektro_objednavky.id_pouz = elektro_pouzivatelia.id_pouz"; // definuj dopyt
//		echo "sql = $sql <br>";
		if (($result = $mysqli->query($sql)) && ($row = $result->fetch_assoc()) ) {  // vykonaj dopyt
			echo '<table border="1">';
			echo "<tr><th>číslo objednávky</th><td>{$row['id']}</td></tr>\n";
			echo "<tr><th>meno a priezvisko</th><td>{$row['meno']} {$row['priezvisko']}</td></tr>\n";
			echo "<tr><th>adresa doručenia</th><td>{$row['adresa']}</td></tr>\n";
			echo "<tr><th>názov tovaru</th><td>{$row['nazov']}</td></tr>\n";
			echo "<tr><th>počet ks</th><td>{$row['pocet_ks']}</td></tr>\n";
			echo "<tr><th>dátum odberu</th><td>{$row['datum_odberu']}</td></tr>\n";
			echo "<tr><th>doprava</th><td>{$row['doprava']}</td></tr>\n";
			echo "<tr><th>cena</th><td>{$row['cena_spolu']} &euro;</td></tr>\n";
			echo '</table>';
		} else {
			// dopyt sa NEpodarilo vykonať!
			echo '<p class="chyba">NEpodarilo sa získať údaje z databázy, resp. objednávka neexistuje</p>' . $mysqli->error ;
		}
	}
}

// vrati udaje pouzivatela ako asociativne pole, ak existuje pouzivatel $username s heslom $pass, inak vrati FALSE
function over_pouzivatela($username, $pass) {
	global $mysqli;
	if (!$mysqli->connect_errno) {
		$sql = "SELECT * FROM elektro_pouzivatelia WHERE prihlasmeno='$username' AND heslo=MD5('$pass')";  // definuj dopyt
//		echo "sql = $sql <br>";
		if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {  // vykonaj dopyt
			// dopyt sa podarilo vykonať
			$row = $result->fetch_assoc();
			$result->free();
			return $row;
		} else {
			// dopyt sa NEpodarilo vykonať, resp. používateľ neexistuje!
			return false;
		}
	} else {
		// NEpodarilo sa spojiť s databázovým serverom!
		return false;
	}
}

// zmeni heslo $pass pouzivatelovi s id cislom $id
function zmen_heslo($id, $pass) {
	global $mysqli;
	if (!$mysqli->connect_errno) {
	  $sql="UPDATE elektro_pouzivatelia SET heslo=MD5('$pass') WHERE id_pouz='$id'"; // definuj dopyt   
//		echo "sql = $sql <br>";
		if ($result = $mysqli->query($sql)) {  // vykonaj dopyt
			// dopyt sa podarilo vykonať
      echo '<p>Heslo bolo zmenené.</p>'. "\n"; 
    } else {
			// NEpodarilo sa vykonať dopyt!
      echo '<p class="chyba">Nastala chyba pri zmene hesla.</p>'. "\n"; 
		}
	} else {
		// NEpodarilo sa spojiť s databázovým serverom alebo vybrať databázu!
		echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom!</p>';
	}
}	// koniec funkcie

// funkcia by mala dostať vstupy (prihlasovacie meno, heslo, meno, priezvisko, admin) buď samostatne, alebo ako pole
function pridaj_pouzivatela($prihlasmeno, $heslo, $meno, $priezvisko, $admin) {
	global $mysqli;
	if (!$mysqli->connect_errno) {
		$prihlasmeno = $mysqli->real_escape_string($prihlasmeno);
		$heslo = $mysqli->real_escape_string($heslo);
		$meno = $mysqli->real_escape_string($meno);
		$priezvisko = $mysqli->real_escape_string($priezvisko);
		$admin = $mysqli->real_escape_string($admin);
		$sql = ""; // definuj dopyt
		if ($result = $mysqli->query($sql)) {  // vykonaj dopyt
			// dopyt sa podarilo vykonať
	    echo '<p>Používateľ bol pridaný.</p>'. "\n"; 
			return true;
	 	} else {
			// NEpodarilo sa vykonať dopyt!
			echo '<p class="chyba">Nastala chyba pri pridávaní používateľa';
			// kontrola, či nenastala duplicita kľúča (číslo chyby 1062) - prihlasovacie meno už existuje
			if ($mysqli->errno == 1062) echo ' (zadané prihlasovacie meno už existuje)';
			echo '.</p>' . "\n";
			return false;
	  }
	} else {
		// NEpodarilo sa spojiť s databázovým serverom alebo vybrať databázu!
		echo '<p class="chyba">NEpodarilo sa spojiť s databázovým serverom!</p>';
		return false;
	}
}	// koniec funkcie

function daj_udaje_tovaru($kod) {
	global $mysqli;
	if (!$mysqli->connect_errno) {
		$sql = "SELECT * FROM elektro_tovar WHERE kod='$kod'"; // definuj dopyt
//		echo "sql = $sql <br>";
		if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {  // vykonaj dopyt
			// dopyt sa podarilo vykonať
			return $result->fetch_assoc();
		} else {
			// dopyt sa NEpodarilo vykonať!
			return false;
		}
	} else {
		// NEpodarilo sa spojiť s databázovým serverom!
		return false;
	}
}

// funkcia by mala dostať vstupy (ID tovaru, $data - asociatívne pole)
// $subor - $_FILES['obrazok']
function uprav_tovar($idt, $data, $subor) {	
	global $mysqli;
	if (!$mysqli->connect_errno) {
		$sql = "UPDATE elektro_tovar SET "; // definuj dopyt
		foreach ($data as $key => $val) {
			$sql .= "$key='{$mysqli->real_escape_string($val)}', ";	// apostrofy okolo hodnoty + čiarka + medzera
		}
		// odstránime poslednú čiarku s medzerou
		$sql = substr($sql, 0, -2);
		$sql .= " WHERE kod='$idt'";
//		echo "sql - $sql";
		if ($result = $mysqli->query($sql)) {  // vykonaj dopyt
			// dopyt sa podarilo vykonať
			echo '<p><strong>Tovar bol zmenený</strong></p>';

			// spracuj uploadovaný súbor 
			if (!empty($subor) && !empty($subor['name'])) {
				//echo 'nieco sa odosiela';
				
				if ($subor['error'] == UPLOAD_ERR_OK) {
					if (is_uploaded_file($subor['tmp_name'])) {
						if ($subor['type'] == 'image/png') {
							$novy_nazov = 'tovar-obrazky/' . $idt . '.png';
							$ok = move_uploaded_file($subor['tmp_name'], $novy_nazov);
							if ($ok) {
								echo '<p>Súbor bol nahratý na server.</p>';
                exec("chmod a+r $novy_nazov");
							} else {
								echo '<p class="chyba">Súbor NEbol nahratý na server.</p>';
							}
						} else {
							echo '<p class="chyba">Súbor nie je požadovaného typu (image/png).</p>';
						}
					}
				} else { 
					// nastane, ak bol uploadovaný súbor väcší ako upload_max_filesize (chyba 2)
					// nastane aj vtedy, ak bol uploadovaný súbor väcší ako post_max_size (chyba 2)
					echo '<p class="chyba">Nastal problém pri uploadovaní súboru ' . $subor['name'] . ' - ' . $subor['error'] . '</p>';
				}
			}

		} else {
			// dopyt sa NEpodarilo vykonať!
			echo '<p class="chyba">Nastala chyba pri zmene tovaru. (' . $mysqli->error . ')</p>';
		}
	}
}

?>
