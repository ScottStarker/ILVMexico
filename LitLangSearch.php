<?php
/*
Created by Scottt Starker

$stmt_alt->get_result() won't work because it needs the MySQL Native Driver (mysqlnd) driver in PHP (extension=php_mysqli_mysqlnd.dll) but using bind_result and fetch works instead of get_result.

Problems: TryLanguage ' should be \' 

MySQL: utf8_general_ci flattens accents as well as lower-case:
You must ensure that all parties (your app, mysql connection, your table or column) have set utf8 as charset.
- header('Content-Type: text/html; charset=utf-8'); (or <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />)
- ensure your mysqli connection use utf8 before any operation:
	- $mysqli->set_charset('utf8')
- create your table or column using utf8_general_ci
*/

/*
	These are defined at the end of $response:
	langNotFound = "The language is not found.";
	colLN = "Language Name";
	colAlt = "Alternate Language Names";
	colCode = "Code";
*/

// display all of the language names, ROD codes and variant codes from a major and alternate languages names

if (isset($_GET['language'])) $TryLanguage = $_GET['language']; else { die('Hack!'); }
if (preg_match("/^[-. ,'ꞌ()A-Za-záéíóúÑñçãõâêîôûäëöüï&]+/", $TryLanguage)) {
}
else {
	die('Hack!');
}

$response = '';

if (strlen($TryLanguage) > 2) {
	$hint = 0;
	include './include/conn.literacy.inc.php';
	$db = get_my_db();
	
	$langisorod = [];
	$iso_only = '';

	$stmt_alt = $db->prepare("SELECT alt_lang_name FROM alt_lang_names_literacy WHERE iso = ?");

	// try iso:
	if (strlen($TryLanguage) >= 3 && strlen($TryLanguage) <= 6) {
		$query="SELECT DISTINCT iso_num_index, iso, spanish_lang FROM literacy_main WHERE iso LIKE '$TryLanguage%'";
		if ($result = $db->query($query)) {
			$LN = '';
			while ($row = $result->fetch_assoc()) {
				$iso_num_index = $row['iso_num_index'];
				$iso = $row['iso'];
				//$rod_code = $row['rod_code'];
				$LN = $row['spanish_lang'];
				/******************************************************************************
						alternate language names
				*******************************************************************************/
				$alt = '';
				//$query="SELECT alt_lang_name FROM alt_lang_names_literacy WHERE iso_num_index = $iso_num_index";
				$stmt_alt->bind_param('s', $iso);										// bind parameters for markers
				$stmt_alt->execute();															// execute query
				$stmt_alt->bind_result($alt_lang_name);
				//if ($result_alt = $stmt_alt->get_result()) {
					$bool = 0;
					//while ($row_alt = $result_alt->fetch_assoc()) {
					while ($stmt_alt->fetch()) {
						//$alt_temp = $row_alt['alt_lang_name'];
						$alt_temp = $alt_lang_name;
						if (preg_match("/(\s|-|^)".$TryLanguage."/i", $alt_temp)) {
							if ($bool == 0) {
								$alt = $alt_temp;
								$bool = 1;
								continue;
							}
							$alt .= ', '.$alt_temp;
						}
					}
				//}
				if ($hint == 0) {
					$response = $LN.'|'.$alt.'|'.$iso.'|'.$iso_num_index;
					$hint = 1;
				}
				else {
					$response .= '<br />'.$LN.'|'.$alt.'|'.$iso.'|'.$iso_num_index;
				}
				$langisorod[] = $iso_num_index;
				$iso_only = $iso;
			}
		}
	}
	
	$RD = ['�' => 'a', '�' => 'A', '�' => 'a', '�' => 'A', '�' => 'a', '�' => 'A', '�' => 'a', '�' => 'A', '�' => 'a', '�' => 'A', '�' => 'ae', '�' => 'AE', '�' => 'ae', '�' => 'AE', '�' => 'c', '�' => 'C', '�' => 'D', '�' => 'dh', '�' => 'Dh', '�' => 'e', '�' => 'E', '�' => 'e', '�' => 'E', '�' => 'e', '�' => 'E', '�' => 'e', '�' => 'E', '�' => 'i', '�' => 'I', '�' => 'i', '�' => 'I', '�' => 'i', '�' => 'I', '�' => 'i', '�' => 'I', '�' => 'n', '�' => 'N', '�' => 'o', '�' => 'O', '�' => 'o', '�' => 'O', '�' => 'o', '�' => 'O', '�' => 'o', '�' => 'O', '�' => 'oe', '�' => 'OE', '�' => 'oe', '�' => 'OE', '�' => 's', '�' => 'S', '�' => 'SS', '�' => 'u', '�' => 'U', '�' => 'u', '�' => 'U', '�' => 'u', '�' => 'U', '�' => 'ue', '�' => 'UE', '�' => 'y', '�' => 'Y', '�' => 'y', '�' => 'Y', '�' => 'z', '�' => 'Z'];
	if (preg_match('/[��������������������������]/', $TryLanguage)) {							// diacritic removal
		$TryLanguage = strtr($TryLanguage, $RD);												// PHP: strtr - Translate characters ($addr = strtr($addr, "���", "aao");)
	}
	
	$TryLanguage = str_replace("'", "\'", $TryLanguage);

	// Try languages names:
	$query="SELECT DISTINCT iso_num_index, iso, spanish_lang FROM literacy_main ORDER BY iso";
	if ($result = $db->query($query)) {
		$LN = '';
		while ($row = $result->fetch_assoc()) {													// All isos + ROD codes + variants
			$iso_num_index = $row['iso_num_index'];
			// Author: 'ChickenFeet'
			$LN = $row['spanish_lang'];
			$temp_LN = CheckLetters($LN);														// diacritic removal
			$temp_LN = mb_strtolower($temp_LN);													// lower case language name without the diacritics
			$test = preg_match("/\b".$TryLanguage.'/ui', $temp_LN, $match);						// match the beginning of the word(s) with TryLanguage from the user

			if ($test === 1) {
				$iso = $row['iso'];
				if (strlen($TryLanguage) >= 3 && strlen($TryLanguage) <= 6 && $iso == $iso_only) {							// if the length of $TryLanguage is 3 and the top section is there
					continue;
				}
				//$rod_code = $row['rod_code'];
				/******************************************************************************
						alternate language names
				*******************************************************************************/
				$alt = '';
				//$query="SELECT alt_lang_name FROM alt_lang_names_literacy WHERE iso_num_index = $iso_num_index";
				$stmt_alt->bind_param('s', $iso);										// bind parameters for markers								// 
				$stmt_alt->execute();															// execute query
				$stmt_alt->bind_result($alt_lang_name);
				//if ($result_alt = $stmt_alt->get_result()) {
					$bool = 0;
					//while ($row_alt = $result_alt->fetch_assoc()) {
					while ($stmt_alt->fetch()) {
						//$alt_temp = $row_alt['alt_lang_name'];
						$alt_temp = $alt_lang_name;
						if (preg_match_all("/(\s|-|^)".$TryLanguage."/ui", mb_strtolower($alt_temp))) {
							if ($bool == 0) {
								$alt = $alt_temp;
								$bool = 1;
								continue;
							}
							$alt .= ', '.$alt_temp;
						}
					}
				//}
				
				if ($hint == 0) {
					$response = $LN.'|'.$alt.'|'.$iso.'|'.$iso_num_index;
					$hint = 1;
				}
				else {
					$response .= '<br />'.$LN.'|'.$alt.'|'.$iso.'|'.$iso_num_index;
				}
				$langisorod[] = $iso_num_index;
			}
		}
	}

	// Try alt_lang_names_literacys:
	// REGEXP '[[:<:]]... = in PHP '\b... (word boundries)
	if (empty($langisorod)) {
		$query="SELECT DISTINCT iso FROM alt_lang_names_literacy WHERE alt_lang_name REGEXP '[[:<:]]$TryLanguage'";
	}
	else {
		//$query="SELECT DISTINCT iso_num_index FROM alt_lang_names_literacys WHERE alt_lang_names_literacy REGEXP '[[:<:]]$TryLanguage' AND iso_num_index NOT IN (".implode(',', $langisorod).")";		// won't quick work under MariaDB 10.1.44
		$query="SELECT DISTINCT iso FROM alt_lang_names_literacy WHERE alt_lang_name REGEXP '(^| )$TryLanguage' AND iso_num_index NOT IN (".implode(',', $langisorod).")";
	}
	if ($result = $db->query($query)) {
		while ($r = $result->fetch_assoc()) {
			//$iso_num_index = $r['iso_num_index'];
			$iso = $r['iso'];
			$query="SELECT spanish_lang FROM literacy_main WHERE iso = '$iso'";
			if ($result_SM = $db->query($query)) {
				if ($row = $result_SM->fetch_assoc()) {
					//$iso = $row['iso'];
					//$rod_code = $row['rod_code'];
					$LN = $row['spanish_lang'];
					/******************************************************************************
							alternate language names
					*******************************************************************************/
					$alt = '';
					//$query="SELECT alt_lang_name FROM alt_lang_names_literacy WHERE iso_num_index = $iso_num_index";
					$stmt_alt->bind_param('s', $iso);										// bind parameters for markers
					$stmt_alt->execute();															// execute query
					$stmt_alt->bind_result($alt_lang_name);
					//if ($result_alt = $stmt_alt->get_result()) {
						$bool = 0;
						//while ($row_alt = $result_alt->fetch_assoc()) {
						while ($stmt_alt->fetch()) {
							//$alt_temp = $row_alt['alt_lang_name'];
							$alt_temp = $alt_lang_name;
//echo 'alt_temp: ' . $alt_temp . '<br />';
//echo 'TryLanguage: ' . $TryLanguage . '<br />';
							if (preg_match_all("/(\s|-|^)".$TryLanguage."/ui", mb_strtolower($alt_temp))) {
								if ($bool == 0) {
									$alt = $alt_temp;
									$bool = 1;
									continue;
								}
								$alt .= ', '.$alt_temp;
							}
						}
					//}
					
					if ($hint == 0) {
						$response = $LN.'|'.$alt.'|'.$iso.'|'.$iso_num_index;
						$hint = 1;
					}
					else {
						$response .= '<br />'.$LN.'|'.$alt.'|'.$iso.'|'.$iso_num_index;
					}
				}
			}
		}
	}
	
	if ($hint == 0) {
		$response = "No se encuentra esta lengua.";
		echo $response;
	}
	else {
		$temp = explode('<br />', $response);										
		sort($temp);
		$response = implode('<br />', $temp);
		$response .= '<br />'."Nombre del idioma";
		$response .= '<br />'."Nombres alternativos del idioma";
		$response .= '<br />'."Código";
		//$response .= '<br />'.translate("Country", $st, "sys");
		echo $response;
	}
}


function removeDiacritics($txt) {
    $transliterationTable = ['�' => 'a', '�' => 'A', '�' => 'a', '�' => 'A', '�' => 'a', '�' => 'A', '�' => 'a', '�' => 'A', '�' => 'a', '�' => 'A', '�' => 'ae', '�' => 'AE', '�' => 'ae', '�' => 'AE', '�' => 'c', '�' => 'C', '�' => 'D', '�' => 'dh', '�' => 'Dh', '�' => 'e', '�' => 'E', '�' => 'e', '�' => 'E', '�' => 'e', '�' => 'E', '�' => 'e', '�' => 'E', '�' => 'i', '�' => 'I', '�' => 'i', '�' => 'I', '�' => 'i', '�' => 'I', '�' => 'i', '�' => 'I', '�' => 'n', '�' => 'N', '�' => 'o', '�' => 'O', '�' => 'o', '�' => 'O', '�' => 'o', '�' => 'O', '�' => 'o', '�' => 'O', '�' => 'oe', '�' => 'OE', '�' => 'oe', '�' => 'OE', '�' => 's', '�' => 'S', '�' => 'SS', '�' => 'u', '�' => 'U', '�' => 'u', '�' => 'U', '�' => 'u', '�' => 'U', '�' => 'ue', '�' => 'UE', '�' => 'y', '�' => 'Y', '�' => 'y', '�' => 'Y', '�' => 'z', '�' => 'Z'];
	return strtr($txt, $transliterationTable);
}    // or, return str_replace(array_keys($transliterationTable), array_values($transliterationTable), $txt);


// Author: 'ChickenFeet'
function CheckLetters($field){
	// global $letters;										// won't work
    $letters = [
        0 => "a à á â ä æ ã å ā",
        1 => "c ç ć č",
        2 => "e é è ê ë ę ė ē",
        3 => "i ī į í ì ï î",
        4 => "l ł",
        5 => "n ñ ń",
        6 => "o ō ø œ õ ó ò ö ô",
        7 => "s ß ś š",
        8 => "u ū ú ù ü û",
        9 => "w ŵ",
        10 => "y ŷ ÿ",
        11 => "z ź ž ż",
    ];
    foreach ($letters as &$values){
        $newValue = substr($values, 0, 1);
        $values = substr($values, 2, strlen($values));
        $values = explode(' ', $values);
        foreach ($values as &$oldValue){
            while (strpos($field, $oldValue) !== false){
                $field = preg_replace('/' . $oldValue . '/', $newValue, $field, 1);
            }
        }
    }
    return $field;
}

?>