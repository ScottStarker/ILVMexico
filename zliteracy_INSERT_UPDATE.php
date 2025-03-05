<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>literacy_INSERT_UPDATE</title>
</head>
<body>
<?php
	
	$iso = '';
	$query = '';

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
		die ('The ISO literacy_main table is not found.</body></html>');
	}
	
	$query_display = "SELECT * FROM display_items WHERE (iso = ? OR iso_num_index = ?) AND no_items = ?";
	$stmt_display = $db->prepare($query_display);
	$stmt_U_PDF=$db->prepare("UPDATE display_items SET PDF_viewing = ?, iso = ?, rod_code = ? WHERE (iso = ? OR iso_num_index = ?) AND no_items = ?");
	$stmt_L_PDF=$db->prepare("INSERT INTO display_items (iso_num_index, iso, rod_code, no_items, thumbnail, spanishLiteracyName, variantLiteracyName, PDF_viewing, PDF_printing, PDF_cover, eBook, app, video, audio, text) VALUES (?, ?, ?, ?, '', '', '', ?, '', '', '', '', '', '', '')");
	$stmt_U_photo=$db->prepare("UPDATE display_items SET thumbnail = ?, iso = ?, rod_code = ? WHERE (iso = ? OR iso_num_index = ?) AND no_items = ?");
	$stmt_L_photo=$db->prepare("INSERT INTO display_items (iso_num_index, iso, rod_code, no_items, thumbnail, spanishLiteracyName, variantLiteracyName, PDF_viewing, PDF_printing, PDF_cover, eBook, app, video, audio, text) VALUES (?, ?, ?, ?, ?, '', '', '', '', '', '', '', '', '', '')");
	$stmt_U_text=$db->prepare("UPDATE display_items SET text = ?, iso = ?, rod_code = ? WHERE (iso = ? OR iso_num_index = ?) AND no_items = ?");
	$stmt_L_text=$db->prepare("INSERT INTO display_items (iso_num_index, iso, rod_code, no_items, thumbnail, spanishLiteracyName, variantLiteracyName, PDF_viewing, PDF_printing, PDF_cover, eBook, app, video, audio, text) VALUES (?, ?, ?, ?, '', '', '', '', '', '', '', '', '', '', ?)");
	
	$photos = Array('jpg', 'png', 'gif');
	while ($row = $result->fetch_array()) {
		//$sub_family=trim($row['sub_family']);
		$iso = trim($row['iso']);
		//$family=trim($row['family']);
		//$spanish_lang=trim($row['spanish_lang']);
		$iso_num_index=$row['iso_num_index'];
		$rod_code = $row['rod_code'];
		$dirname = 'literacy_data/' . $iso . '/';													// directory name
		if (!is_dir($dirname)) {
			echo "<span style='color: blue; font-weight: bold; '>The directory $dirname is not there.</span><br />";
			continue;
		}
		else {			//if (!file_exists($dirname.'video/'.$PlaylistFilename)) {					// if not playlist filename
			if (is_dir($dirname.'item_1')) {
				echo '<span style="color: green; font-size: 14pt; font-weight: bold; ">'.$iso . ': ' . $dirname.'</span><br />';
				foreach (glob($dirname.'item_[0-9]*') as $filenameItem) {							// foreach glob()
//echo $iso . ': ' . $filenameItem . '<br />';
					if (preg_match('/_([0-9]+)$/', $filenameItem, $match)) {
						$no_items = (int)$match[1];
					}
					else {
						echo '<span style="color: red; font-weight: bold; ">preg_match is not supposed to happen!<.span></br >';
						continue;
					}
					if ($handle = opendir($filenameItem)) {											// read directory item_zz
						while (false !== ($filename = readdir($handle))) {							// reads the files in the directory
							if ($filename != "." && $filename != "..") {
								$file = basename($filename);										// get just the filename and NOT path name
//echo "$filename<br />";
								$file_parts = pathinfo($filename);
								if ($file_parts['extension'] == 'pdf') {							// pdf
									//$query = "SELECT * FROM display_items WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items";
									//$result_text=$db->query($query) or die ('Query failed: ' . $db->error . '</body></html>');
									$stmt_display->bind_param("sii", $iso, $iso_num_index, $no_items);											// bind parameters for markers								// 
									$stmt_display->execute();																// execute query
									//$result_text = $stmt_display->get_result();												// instead of bind_result (used for only 1 record): will return a resultset on success of a SELECT statement or false (boolean) in case of any other query
									if ($stmt_display->num_rows >= 1) {
										echo 'UPDATE: PDF_viewing = '. $file. '<br />';
										//$db->query("UPDATE display_items SET PDF_viewing = '$file', iso = '$iso', rod_code = '$rod_code' WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items");
										$stmt_U_PDF->bind_param("ssssii", $file, $iso, $rod_code, $iso, $iso_num_index, $no_items);	// bind parameters for markers								// 
										$stmt_U_PDF->execute();																	// execute query
										continue;
									}
									else {
										echo 'INSERT: PDF_viewing = '. $file. '<br />';
										//$db->query("INSERT INTO display_items (iso_num_index, iso, rod_code, no_items, thumbnail, spanishLiteracyName, variantLiteracyName, PDF_viewing, PDF_printing, PDF_cover, eBook, app, video, audio, text) VALUES ($iso_num_index, '$iso', '$rod_code', $no_items, '', '', '', '$file', '', '', '', '', '', '', '')");
										$stmt_L_PDF->bind_param("issis", $iso_num_index, $iso, $rod_code, $no_items, $file);	// bind parameters for markers								// 
										$stmt_L_PDF->execute();																	// execute query
										continue;
									}
								}
								elseif (in_array($file_parts['extension'], $photos)) {			// photo
									//$query = "SELECT * FROM display_items WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items";
									//$result_text=$db->query($query) or die ('Query failed: ' . $db->error . '</body></html>');
									$stmt_display->bind_param("sii", $iso, $iso_num_index, $no_items);											// bind parameters for markers								// 
									$stmt_display->execute();																// execute query
									//$result_text = $stmt_display->get_result();												// instead of bind_result (used for only 1 record):
									if ($stmt_display->num_rows >= 1) {
										echo 'UPDATE: photo = '. $file. '<br />';
										//$db->query("UPDATE display_items SET thumbnail = '$file', iso = '$iso', rod_code = '$rod_code' WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items");
										$stmt_U_photo->bind_param("ssssii", $file, $iso, $rod_code, $iso, $iso_num_index, $no_items);	// bind parameters for markers								// 
										$stmt_U_photo->execute();																	// execute query
										continue;
									}
									else {
										echo 'INSERT: photo = '. $file. '<br />';
										//$db->query("INSERT INTO display_items (iso_num_index, iso, rod_code, no_items, thumbnail, spanishLiteracyName, variantLiteracyName, PDF_viewing, PDF_printing, PDF_cover, eBook, app, video, audio, text) VALUES ($iso_num_index, '$iso', '$rod_code', $no_items, '$file', '', '', '', '', '', '', '', '', '', '')");
										$stmt_L_photo->bind_param("issis", $iso_num_index, $iso, $rod_code, $no_items, $file);		// bind parameters for markers								// 
										$stmt_L_photo->execute();																	// execute query
										continue;
									}
								}
								else {															// text
									//$query = "SELECT * FROM display_items WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items";
									//$result_text=$db->query($query) or die ('Query failed: ' . $db->error . '</body></html>');
									$stmt_display->bind_param("sii", $iso, $iso_num_index, $no_items);											// bind parameters for markers								// 
									$stmt_display->execute();																// execute query
									//$result_text = $stmt_display->get_result();												// instead of bind_result (used for only 1 record):
									if ($stmt_display->num_rows >= 1) {
										echo 'UPDATE: text = '. $file. '<br />';
										//$db->query("UPDATE display_items SET text = '$file', iso = '$iso', rod_code = '$rod_code' WHERE (iso = '$iso' OR iso_num_index = $iso_num_index) AND no_items = $no_items");
										$stmt_U_text->bind_param("ssssii", $file, $iso, $rod_code, $iso, $iso_num_index, $no_items);	// bind parameters for markers								// 
										$stmt_U_text->execute();																	// execute query
										continue;
									}
									else {
										echo 'INSERT: text = '. $file. '<br />';
										//$db->query("INSERT INTO display_items (iso_num_index, iso, rod_code, no_items, thumbnail, spanishLiteracyName, variantLiteracyName, PDF_viewing, PDF_printing, PDF_cover, eBook, app, video, audio, text) VALUES ($iso_num_index, '$iso', '$rod_code', $no_items, '', '', '', '', '', '', '', '', '', '', '$file')");
										$stmt_L_text->bind_param("issis", $iso_num_index, $iso, $rod_code, $no_items, $file);		// bind parameters for markers								// 
										$stmt_L_text->execute();																	// execute query
										continue;
									}
								}
							}
						}
						closedir($handle);
					}
				}
			}
		}
	}
?>
</body>
</html>