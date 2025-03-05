<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="Created-by"				content="Scott Starker" />
<meta http-equiv="Content-Type"		content="text/html; charset=utf-8" />
<title>Build Cual Idioma spreadsheet file using SE scripture database</title>
</head>
<body>
<?php

/************************************'*************************************************************************************

			Build sfm files from SE scripture database to be imported to 'Scripture App Builder'
			
			The ScriptureEarth.org tables it SELECTs from are:
				scripture_main (fields are [5/2018]):
					1	ISO_ROD_index
					2	ISO
					3	ROD_Code
					4	Variant_Code
					5	LN_English
					6	LN_Spanish
					7	LN_Portuguese
					8	LN_French
					9	LN_Dutch
					11	OT_PDF
					12	NT_PDF
					14	OT_Audio
					15	NT_Audio
					16	links
					17	other_titles
					18	watch
					19	buy
					20	study
					21	viewer
					22	CellPhone
					26	BibleIs
					27	YouVersion
					28	Bibles_org
					29	PlaylistAudio
					30	PlaylistVideo
					31	SAB
					32	eBible
					33	SILlink
					34	GRN
				alt_lang_names
				LN_English
				LN_Spanish
				OT_PDF_Media
				NT_PDF_Media
				OT_Audio_Media
				NT_Audio_Media
				links
				watch
				SAB
				PlaylistVideo
				CellPhone (including apk)
				
			The pueblos_idiomas tables (which are tables from SE) it SELECTs from are:
				SIL_pueblos_idiomas_mexico
				GRN_SIL_Similar_ISO_ROD_Code
				GRN_pueblos_idiomas_mexico
				GRN_Lang_No

**************************************************************************************************************************/

require_once './include/conn.inc.php';																					// connect to the database named 'scripture'
$db = get_my_db();

//$query="SELECT * FROM ISO_countries WHERE ISO_countries = 'MX'";
//$result=$db->query($query) or die ('Query failed: ' . $db->error . '</body></html>');
//if (!$result) {
//	die ('ISO_countries table is not found.</body></html>');
//}

$Languages = [];
$Family = [];
$ML = [];

$query="SELECT DISTINCT Language, Family FROM SIL_pueblos_idiomas_mexico";														// Languages from SIL_pueblos_idiomas_mexico table
$resultLanguage=$db->query($query) or die ('Query failed: ' . $db->error . '</body></html>');
if (!$resultLanguage) {
	die ('SIL_pueblos_idiomas_mexico table is not found.</body></html>');
}
while ($row_Language = $resultLanguage->fetch_array()) {
	$Languages[] = $row_Language['Language'];
	$Family[] = $row_Language['Family'];
}
//sort($Languages, 4);						// SORT_NATURAL
//mysql_query("SET NAMES `utf8`");
$SIL_ROD_Code = '';

$INSERT_language = '';
$INSERT_family = '';
$INSERT_spanish = '';
$INSERT_ROD_Code = '';
$INSERT_ISO = '';

$handle = fopen('literacy_main.csv', 'w');															// Open for writing only; placing the file pointer at the end of the file. 
foreach ($Languages as $key => $f) {																							// iterate field 'Languages' from SIL_pueblos_idiomas_mexico table
	$query="CREATE TEMPORARY TABLE temp1 ENGINE=MEMORY AS (SELECT ISO, GRN_ROD_Code, SIL_ROD_Code, Similar, GRNno, Idioma FROM SIL_pueblos_idiomas_mexico WHERE Language = '$f')";	// copies SIL_pueblos_idiomas_mexico
	$db->query($query);
	
	//$db->query("INSERT INTO temp1 (ISO, GRN_ROD_Code, SIL_ROD_Code, GRNno, Idioma) SELECT DISTINCT ISO, GRN_ROD_Code, SIL_ROD_Code, GRNno, Idioma FROM SIL_pueblos_idiomas_mexico WHERE Language = '$f'");
	$db->query("INSERT INTO temp1 (ISO, GRN_ROD_Code, SIL_ROD_Code, Similar, GRNno, Idioma) SELECT DISTINCT ISO, GRN_ROD_Code, 'GRN', Similar, GRNno, Idioma FROM GRN_pueblos_idiomas_mexico WHERE Language = '$f'");
	
	$result_MX=$db->query("SELECT DISTINCT ISO, GRN_ROD_Code, SIL_ROD_Code, Similar, GRNno, Idioma FROM temp1 ORDER BY Idioma");	// SELECT temp1 table just created from GRN_pueblos_idiomas_mexico table

	$filename = $f;
	echo '<div style="font-size: 14pt; font-weight: bold; ">Creating ' . $filename  . ' SFM file. Please wait...</div>';	// echo Creating "$filename.sfm"
	$INSERT_language = $f[0] . substr($f, 1);
	$INSERT_family = $Family[$key];
	$filename = html_entity_decode(utf8_decode($filename));
	$filename = str_replace("?", "", $filename);

	$c = 0;
	while ($row_MX = $result_MX->fetch_array()) {																		// from temp1 table
		$ISO = $row_MX['ISO'];																							// and iterate ISO and Idioma from pueblos_idiomas_mexico table based on 'Languages'
		$GRN_ROD_Code = $row_MX['GRN_ROD_Code'];
		$SIL_ROD_Code = $row_MX['SIL_ROD_Code'];
		$Similar = $row_MX['Similar'];																					// a sub-dialect
		$GRNno = trim($row_MX['GRNno']);
		$Idioma = trim($row_MX['Idioma']);
		
		$c++;
		$ML[$f] = $c;
		$query="SELECT LN_Spanish FROM LN_Spanish WHERE ISO = '$ISO' AND ROD_Code = '$SIL_ROD_Code'";					// Spanish language name
		$result_Spa=$db->query($query);
		$num_rows_Spa = mysqli_num_rows($result_Spa);
		if ($num_rows_Spa > 0) {
			$row_Spa = $result_Spa->fetch_array();
			$INSERT_spanish = $row_Spa['LN_Spanish'];
		}
		else {
			$INSERT_spanish = $Idioma;
		}
		$INSERT_ISO = $ISO;
		$INSERT_ROD_Code = '';
		
		$query="SELECT * FROM scripture_main WHERE ISO = '$ISO' AND ROD_Code = '$SIL_ROD_Code'";						// ROD_Code from SIL
		$result_SM=$db->query($query);
		$num_rows = mysqli_num_rows($result_SM);
		if ($num_rows == 0) {																							// still in new table or old table!
			if ($GRN_ROD_Code != '' && $GRN_ROD_Code != '00000') {
				$INSERT_ROD_Code = $GRN_ROD_Code;
			}
			
			$query="SELECT alt_lang_name FROM alt_lang_names WHERE ISO = '$ISO' AND ROD_Code = '$SIL_ROD_Code'";		// alternate names of the language
			$result_alt=$db->query($query);
			$num_alt = mysqli_num_rows($result_alt);
			if ($num_alt != 0) {
				$count = 0;
				while ($row_alt = $result_alt->fetch_array()) {
					$count++;
					$alt_lang_name = $row_alt['alt_lang_name'];
					$alt_lang_name = str_replace('<u>u</u>', 'u̱', $alt_lang_name);
					//fwrite($handle, $alt_lang_name);
					if ($count < $num_alt) {
						//fwrite($handle, ', ');
					}
				}
			}
		}
		else {
			if ($SIL_ROD_Code != '00000') {
				$INSERT_ROD_Code = $SIL_ROD_Code;
			}
			elseif ($GRN_ROD_Code != '00000') {
				$INSERT_ROD_Code = $GRN_ROD_Code;
			}
			else {
				// $SIL_ROD_Code and $GRN_ROD_Code are both '00000' so don't display 'Código de ROD:...'
			}
			$row_SM = $result_SM->fetch_array();																		// Scripture Resources for a specified ISO and ROD_Code from scripture_main table
			//$ROD_Code = $row_SM['ROD_Code'];
			$ISO_ROD_index = $row_SM['ISO_ROD_index'];																	// get all of the fields from scripture_main
			
			$query="SELECT alt_lang_name FROM alt_lang_names WHERE ISO_ROD_index = '$ISO_ROD_index'";					// alternate names of the language
			$result_alt=$db->query($query);
			$num_alt = mysqli_num_rows($result_alt);
			if ($num_alt != 0) {
				$count = 0;
				while ($row_alt = $result_alt->fetch_array()) {
					$count++;
					$alt_lang_name = $row_alt['alt_lang_name'];
					$alt_lang_name = str_replace('<u>u</u>', 'u̱', $alt_lang_name);
					//fwrite($handle, $alt_lang_name);
					if ($count < $num_alt) {
						//fwrite($handle, ', ');
					}
				}
			}
		}
		if ($INSERT_ROD_Code == '') $INSERT_ROD_Code = '00000';
		fwrite($handle, $INSERT_ISO . '	' . $INSERT_ROD_Code . '	' . $INSERT_family . '	' . $INSERT_language .	'	' . '[variant language]' . '	' . $INSERT_spanish . PHP_EOL);
	}
	$db->query("DROP TABLE temp1");
	
	echo '<div style="font-size: 14pt; font-weight: bold; ">End.</div><br />';
}
fclose($handle);

?>
</body>
</html>