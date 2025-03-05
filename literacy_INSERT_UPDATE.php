<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>literacy INSERT/UPDATE record</title>
</head>
<body>
<?php
	if (isset($_GET['iso'])) {
		$iso = $_GET['iso'];
		$temp = preg_match('/^([a-z]{3}[A-Z]{0,3})/', $iso, $match);
		if ($temp == 0) {
			die ('HACK!</body></html>');
		}
		$iso = $match[1];
		$query = "SELECT * FROM literacy_main WHERE iso = '$iso'";
	}
	else {
		$query = "SELECT * FROM literacy_main ORDER BY iso";
	}

	require_once './include/conn.literacy.inc.php';							// connect to the database named 'scripture'
	$db = get_my_db();

	$result=$db->query($query) or die ('Query failed: ' . $db->error . '</body></html>');
	if ($result->num_rows <= 0) {
		die ('The literacy_main table is not found.</body></html>');
	}

	$photos = Array('jpg', 'png', 'gif');
	while ($row = $result->fetch_array()) {
		//$sub_family=trim($row['sub_family']);
		$iso = trim($row['iso']);
		//$family=trim($row['family']);
		//$spanish_lang=trim($row['spanish_lang']);
		$iso_num_index=$row['iso_num_index'];
		$rod_code = $row['rod_code'];
		$dirname = 'literacy_data/' . $iso . '/';
		if (!is_dir($dirname)) {
			echo "<span style='color: blue; font-weight: bold; '>The directory $dirname is not there.</span><br />";
			continue;
		}
		else {			//if (!file_exists($dirname.'video/'.$PlaylistFilename)) {					// if not playlist filename
			if (is_dir($dirname.'item_1')) {
				$item_count = 0;
				echo '<span style="color: green; font-size: 14pt; font-weight: bold; ">'.$iso . ': ' . $dirname.'</span><br />';
				foreach (glob($dirname.'item_[0-9]*') as $filenameItem) {
//echo $iso . ': ' . $filenameItem . '<br />';
					if (preg_match('/_([0-9]+)$/', $filenameItem, $match)) {
						$item_count++;
						$no_items = (int)$match[1];
					}
					else {
						echo '<span style="color: red; font-weight: bold; ">preg_match is not supposed to happen!<.span></br >';
						continue;
					}
					if ($handle = opendir($filenameItem)) {											// read directory item_zz
						while (false !== ($filename = readdir($handle))) {							// reads the files in the directory
							if ($filename != "." && $filename != "..") {
								$file = basename($filename);
//echo "$filename<br />";
								$file_parts = pathinfo($filename);
								if ($file_parts['extension'] == 'pdf') {																// pdf
									$query = "SELECT * FROM display_items WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items";
									$result_text=$db->query($query) or die ('Query failed: ' . $db->error . '</body></html>');
									if ($result_text->num_rows >= 1) {
										if (strpos($file, '-imprimir.') !== false) {
											// true
											echo 'UPDATE: PDF_printing = '. $file. '<br />';
											$db->query("UPDATE display_items SET PDF_printing = '$file', iso = '$iso', rod_code = '$rod_code' WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items");
										}
										elseif (strpos($file, '-pasta.') !== false) {
											// true
											echo 'UPDATE: PDF_cover = '. $file. '<br />';
											$db->query("UPDATE display_items SET PDF_cover = '$file', iso = '$iso', rod_code = '$rod_code' WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items");
										}
										else {		// leer or nothing
											echo 'UPDATE: PDF_viewing = '. $file. '<br />';
											$db->query("UPDATE display_items SET PDF_viewing = '$file', iso = '$iso', rod_code = '$rod_code' WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items");
										}
										continue;
									}
									else {
										if (strpos($file, '-imprimir.') !== false) {
											// true
											echo 'INSERT: PDF_printing = '. $file. '<br />';
											$db->query("INSERT INTO display_items (iso_num_index, iso, rod_code, no_items, thumbnail, spanishLiteracyName, variantLiteracyName, PDF_viewing, PDF_printing, PDF_cover, eBook, app, video, audio, text, zip_file) VALUES ($iso_num_index, '$iso', '$rod_code', $no_items, '', '', '', '', '$file', '', '', '', '', '', '', '')");
										}
										elseif (strpos($file, '-pasta.') !== false) {
											// true
											echo 'INSERT: PDF_cover = '. $file. '<br />';
											$db->query("INSERT INTO display_items (iso_num_index, iso, rod_code, no_items, thumbnail, spanishLiteracyName, variantLiteracyName, PDF_viewing, PDF_printing, PDF_cover, eBook, app, video, audio, text, zip_file) VALUES ($iso_num_index, '$iso', '$rod_code', $no_items, '', '', '', '', '', '$file', '', '', '', '', '', '')");
										}
										else {		// leer or nothing
											echo 'INSERT: PDF_viewing = '. $file. '<br />';
											$db->query("INSERT INTO display_items (iso_num_index, iso, rod_code, no_items, thumbnail, spanishLiteracyName, variantLiteracyName, PDF_viewing, PDF_printing, PDF_cover, eBook, app, video, audio, text, zip_file) VALUES ($iso_num_index, '$iso', '$rod_code', $no_items, '', '', '', '$file', '', '', '', '', '', '', '', '')");
										}
										continue;
									}
								}
								elseif (in_array($file_parts['extension'], $photos)) {													// photo
									$query = "SELECT * FROM display_items WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items";
									$result_text=$db->query($query) or die ('Query failed: ' . $db->error . '</body></html>');
									if ($result_text->num_rows >= 1) {
										echo 'UPDATE: photo = '. $file. '<br />';
										$db->query("UPDATE display_items SET thumbnail = '$file', iso = '$iso', rod_code = '$rod_code' WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items");
										continue;
									}
									else {
										echo 'INSERT: photo = '. $file. '<br />';
										$db->query("INSERT INTO display_items (iso_num_index, iso, rod_code, no_items, thumbnail, spanishLiteracyName, variantLiteracyName, PDF_viewing, PDF_printing, PDF_cover, eBook, app, video, audio, text, zip_file) VALUES ($iso_num_index, '$iso', '$rod_code', $no_items, '$file', '', '', '', '', '', '', '', '', '', '', '')");
										continue;
									}
								}
								elseif ($file_parts['extension'] == 'txt') {															// text
									$query = "SELECT * FROM display_items WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items";
									$result_text=$db->query($query) or die ('Query failed: ' . $db->error . '</body></html>');
									if ($result_text->num_rows >= 1) {
										echo 'UPDATE: text = '. $file. '<br />';
										$db->query("UPDATE display_items SET text = '$file', iso = '$iso', rod_code = '$rod_code' WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items");
										continue;
									}
									else {
										echo 'INSERT: text = '. $file. '<br />';
										$db->query("INSERT INTO display_items (iso_num_index, iso, rod_code, no_items, thumbnail, spanishLiteracyName, variantLiteracyName, PDF_viewing, PDF_printing, PDF_cover, eBook, app, video, audio, text, zip_file) VALUES ($iso_num_index, '$iso', '$rod_code', $no_items, '', '', '', '', '', '', '', '', '', '', '$file', '')");
										continue;
									}
								}
								elseif ($file_parts['extension'] == 'apk') {															// apk
									$query = "SELECT * FROM display_items WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items";
									$result_apk=$db->query($query) or die ('Query failed: ' . $db->error . '</body></html>');
									if ($result_apk->num_rows >= 1) {
										echo 'UPDATE: App (apk) = '. $file. '<br />';
										$db->query("UPDATE display_items SET app = '$file', iso = '$iso', rod_code = '$rod_code' WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items");
										continue;
									}
									else {
										echo 'INSERT: App (apk) = '. $file. '<br />';
										$db->query("INSERT INTO display_items (iso_num_index, iso, rod_code, no_items, thumbnail, spanishLiteracyName, variantLiteracyName, PDF_viewing, PDF_printing, PDF_cover, eBook, app, video, audio, text, zip_file) VALUES ($iso_num_index, '$iso', '$rod_code', $no_items, '', '', '', '', '', '', '', '$file', '', '', '', '')");
										continue;
									}
								}
								elseif ($file_parts['extension'] == 'zip') {															// zip
									$query = "SELECT * FROM display_items WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items";
									$result_zip=$db->query($query) or die ('Query failed: ' . $db->error . '</body></html>');
									if ($result_zip->num_rows >= 1) {
										echo 'UPDATE: zip_file = '. $file. '<br />';
										$db->query("UPDATE display_items SET zip_file = '$file', iso = '$iso', rod_code = '$rod_code' WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items");
										continue;
									}
									else {
										echo 'INSERT: text = '. $file. '<br />';
										$db->query("INSERT INTO display_items (iso_num_index, iso, rod_code, no_items, thumbnail, spanishLiteracyName, variantLiteracyName, PDF_viewing, PDF_printing, PDF_cover, eBook, app, video, audio, text, zip_file) VALUES ($iso_num_index, '$iso', '$rod_code', $no_items, '', '', '', '', '', '', '', '', '', '', '', '$file')");
										continue;
									}
								}
								else {
									echo $file_parts['extension'] . ' is not a literacy extension. ' . $iso . ': ' . $file . '<br />';
								}
							}
						}
						closedir($handle);
					}
				}
				$query = "SELECT * FROM display_items WHERE iso = '$iso' ORDER BY iso";
				$result_delete=$db->query($query) or die ('Query failed: ' . $db->error . '</body></html>');
				if ($result_delete->num_rows <= 0) {
					die ('The ISO display_items table is not found.</body></html>');
				}
				$delete_count = $result_delete->num_rows;
				if ($delete_count > $item_count) {
					while ($delete_count > $item_count) {
						$item_count++;
						echo 'Deleting from display_items table ' . $iso . ' item_' . $item_count . '.<br />';
						$db->query("DELETE FROM display_items WHERE iso = '$iso' AND no_items = $item_count");
					}
				}
			}
		}
	}
?>
</body>
</html>