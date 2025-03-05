<?php
// from literacy_add_edit.php
if (!function_exists('check_input')) {
	function check_input($value) {						// used for ' and " that find it in the input
		$value = trim($value);
		/* Automatic escaping is highly deprecated, but many sites do it anyway. */
		// Stripslashes
		//if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
			$value = stripslashes($value);
		//}
		// Quote if not a number
		if (!is_numeric($value)) {
			$db = get_my_db();
			$value = $db->real_escape_string($value);
		}
		return $value;
	}
}

//print_r($_POST);

if (isset($_GET['num'])) {	
	$no_items = check_input($_GET["num"]);
}
else {
	$no_items = check_input($_POST["no_items"]);
}
//echo $no_items . '<br />';

require_once './include/conn.literacy.inc.php';							// connect to the database named 'scripture'
$db = get_my_db();

$iso = check_input($_POST["iso"]);
$iso_num_index = check_input($_POST["iso_num_index"]);
$spanish_lang = check_input($_POST["spanish_lang"]);

$query="SELECT * FROM display_items WHERE iso_num_index = $iso_num_index AND no_items = $no_items";
$result=$db->query($query);
$num=$result->num_rows;

if ($num >= 1) {
	# D:\Users\Scott\Documents\UniServer\tmp\phpDB95.tmp
	# literacy_items/jmx/item_1/Burro_Lindo_jmx_viewing.pdf
	
	$target_dir = 'literacy_data/'.$iso.'/item_'.$no_items.'/';
	
	$row = $result->fetch_assoc();
	$display_item_index = $row['display_item_index'];
	$thumbnail = $row['thumbnail'];
	$text = $row['text'];
	$spanishLiteracyName = $row['spanishLiteracyName'];
	$variantLiteracyName = $row['variantLiteracyName'];
	$PDF_viewing = $row['PDF_viewing'];
	$PDF_printing = $row['PDF_printing'];
	$PDF_cover = $row['PDF_cover'];
	$eBook = $row['eBook'];
	$app = $row['app'];
	$video = $row['video'];
	$audio = $row['audio'];
	$zip_file = $row['zip_file'];
//echo $PDF_viewing;
//print_r($_FILES);
//exit;
	foreach ($_FILES["item_${no_items}"]["error"] as $key => $error) {		// $_FILES returns items (e.g. "item') uploaded to the current script via the HTTP POST method. The $_FILES contains 5 items:
																	//		[name] => e.g. MyFile.txt (comes from the browser, so treat as tainted)
																	//		[type] => text/plain  (not sure where it gets this from - assume the browser, so treat as tainted)
																	//		[tmp_name] => e.g. /tmp/php/php1h4j1o (could be anywhere on your system, depending on your config settings, but the user has no control, so this isn't tainted)
																	//		[error] => UPLOAD_ERR_OK  (= 0)
																	//		[size] => e.g. 123	(the size in bytes)
#echo "$key $inputs[$key] $error<br />";
		if ($error == 0) {
			$tmp_name = $_FILES["item_${no_items}"]["tmp_name"][$key];
#echo "# $tmp_name #<br />";
			$name = basename($_FILES["item_${no_items}"]["name"][$key]);
#echo $name . '<br />';
			#$target_file = $target_dir . $name;
#echo $target_file . '# #' . $target_dir . '# #' . $name . '#<br />';
			$item_name[] = $name;
			move_uploaded_file("$tmp_name", "literacy_data/$iso/item_$no_items/$name");			// Moves an uploaded file to a new location - from file to to file
//echo '$name = ' . $name . '<br />';
		}
	}
	$count = 0;
	$thumbnailCount = check_input($_POST["thumbnailCount_${no_items}"]);
	$textCount = check_input($_POST["textCount_${no_items}"]);
	$spanishLiteracyNameCount = check_input($_POST["spanishLiteracyNameCount_${no_items}"]);
//echo 'spanishLiteracyNameCount = ' . $spanishLiteracyNameCount . '<br />';
	$variantLiteracyNameCount = check_input($_POST["variantLiteracyNameCount_${no_items}"]);
//echo 'variantLiteracyNameCount = ' . $variantLiteracyNameCount . '<br />';
	$PDF_viewingCount = check_input($_POST["PDF_viewingCount_${no_items}"]);
	$PDF_printingCount = check_input($_POST["PDF_printingCount_${no_items}"]);
	$PDF_coverCount = check_input($_POST["PDF_coverCount_${no_items}"]);
	$eBookCount = check_input($_POST["eBookCount_${no_items}"]);
	$appCount = check_input($_POST["appCount_${no_items}"]);
	$videoCount = check_input($_POST["videoCount_${no_items}"]);
	$audioCount = check_input($_POST["audioCount_${no_items}"]);
	$zip_fileCount = check_input($_POST["zip_fileCount_${no_items}"]);
	if (isset($_POST["thumbnail_${no_items}"])) {
		$thumbnail = check_input($_POST["thumbnail_${no_items}"]);
	}
	if (isset($_POST["text_${no_items}"])) {
		$text = check_input($_POST["text_${no_items}"]);
	}
//echo 'temp = ' . $_POST["item_$no_items"][3] . '<br />';		// $spanishLiteracyName
//foreach ($_POST['item_6'] as $key => $value) {
 //   echo $key . ' = ' . $value . "<br />";
//}
	if (isset($_POST["item_$no_items"][3])) {
		$spanishLiteracyName = check_input($_POST["item_$no_items"][3]);
	}
//echo 'spanishLiteracyName = ' . $spanishLiteracyName . '<br />';
	if (isset($_POST["item_$no_items"][4])) {
		$variantLiteracyName = check_input($_POST["item_$no_items"][4]);
	}
//echo 'variantLiteracyName = ' . $variantLiteracyName . '<br />';
	if (isset($_POST["PDF_viewing_${no_items}"])) {
		$PDF_viewing = check_input($_POST["PDF_viewing_${no_items}"]);
	}
	if (isset($_POST["PDF_printing__${no_items}"])) {
		$PDF_printing = check_input($_POST["PDF_printing_${no_items}"]);
	}
	if (isset($_POST["PDF_cover_${no_items}"])) {
		$PDF_cover = check_input($_POST["PDF_cover_${no_items}"]);
	}
	if (isset($_POST["eBook_${no_items}"])) {
		$eBook = check_input($_POST["eBook_${no_items}"]);
	}
	if (isset($_POST["app_${no_items}"])) {
		$app = check_input($_POST["app_${no_items}"]);
	}
	if (isset($_POST["video_${no_items}"])) {
		$video = check_input($_POST["video_${no_items}"]);
	}
	if (isset($_POST["audio_${no_items}"])) {
		$audio = check_input($_POST["audio_${no_items}"]);
	}
	if (isset($_POST["zip_file_${no_items}"])) {
		$zip_file = check_input($_POST["zip_file_${no_items}"]);
	}
//	echo '<br />thumbnailCount = ' . $thumbnailCount;
	if ($thumbnailCount == '1') {
		$thumbnail = $item_name[$count];
		$count++;
	}
//	echo '<br />thumbnail = ' . $thumbnail;
//	echo '<br />textCount = ' . $textCount;
	if ($textCount == '1') {
		$text = $item_name[$count];
		$count++;
	}
//	echo '<br />text = ' . $text;
/*	echo '<br />spanishLiteracyNameCount = ' . $spanishLiteracyNameCount;
	if ($spanishLiteracyNameCount == '1') {
		$spanishLiteracyName = $item_name[$count];
		$count++;
	}
	echo '<br />spanishLiteracyName = ' . $spanishLiteracyName;
	echo '<br />variantLiteracyNameCount = ' . $variantLiteracyNameCount;
	if ($variantLiteracyNameCount == '1') {
		$variantLiteracyName = $item_name[$count];
		$count++;
	}
	echo '<br />variantLiteracyName = ' . $variantLiteracyName;*/
//	echo '<br />PDF_viewingCount = ' . $PDF_viewingCount;
	if ($PDF_viewingCount == '1') {
		$PDF_viewing = $item_name[$count];
		$count++;
	}
//	echo '<br />PDF_viewing = ' . $PDF_viewing;
//	echo '<br />PDF_printingCount = ' . $PDF_printingCount;
	if ($PDF_printingCount == '1') {
		$PDF_printing = $item_name[$count];
		$count++;
	}
//	echo '<br />PDF_printing = ' . $PDF_printing;
//	echo '<br />PDF_coverCount = ' . $PDF_coverCount;
	if ($PDF_coverCount == '1') {
		$PDF_cover = $item_name[$count];
		$count++;
	}
//	echo '<br />PDF_cover = ' . $PDF_cover;
//	echo '<br />eBookCount = ' . $eBookCount;
	if ($eBookCount == '1') {
		$eBook = $item_name[$count];
		$count++;
	}
//	echo '<br />eBook = ' . $eBook;
//	echo '<br />appCount = ' . $appCount;
	if ($appCount == '1') {
		$app = $item_name[$count];
		$count++;
	}
///	echo '<br />app = ' . $app;
//	echo '<br />videoCount  = ' . $videoCount;
	if ($videoCount == '1') {
		$video = $item_name[$count];
		$count++;
	}
//	echo '<br />video  = ' . $video;
//	echo '<br />audioCount = ' . $audioCount;
	if ($audioCount == '1') {
		$audio = $item_name[$count];
		$count++;
	}
//	echo '<br />audio = ' . $audio;
//	echo '<br />zip_fileCount = ' . $zip_fileCount;
	if ($zip_fileCount == '1') {
		$zip_file = $item_name[$count];
		$count++;
	}
//exit;
//	echo '<br />zip_file = ' . $zip_file;
	$db->query("UPDATE display_items SET thumbnail = '$thumbnail', spanishLiteracyName = '$spanishLiteracyName', variantLiteracyName = '$variantLiteracyName', PDF_viewing = '$PDF_viewing', PDF_printing = '$PDF_printing', PDF_cover = '$PDF_cover', eBook = '$eBook', app = '$app', video = '$video', audio = '$audio', text = '$text', zip_file = '$zip_file' WHERE display_item_index = $display_item_index");
	//$db->query("UPDATE display_items SET variantLiteracyName = '$variantLiteracyName', spanishLiteracyName = '$spanishLiteracyName' WHERE display_item_index = $display_item_index");
//echo $display_item_index . '<br />';
}
else {
	error_reporting(-1);					// all messages
	ini_set('display_errors', 'ON');
	//$root = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
	$old = umask(0);
	if (!is_dir('./literacy_data/'.$iso.'/item_'.$no_items)) {
		$retVal = mkdir('./literacy_data/'.$iso.'/item_'.$no_items.'/', 0755);
		//echo 'retVal = ' . $retVal . '<br />';
	}
	umask($old);
	foreach ($_FILES["item"]["error"] as $key => $error) {
#echo "$key $error<br />";
		if ($error == 0) {
			$tmp_name = $_FILES["item"]["tmp_name"][$key];
#echo $tmp_name . '<br />';
			//$item_tmp_name[] = $tmp_name;
			// basename() may prevent filesystem traversal attacks;
			// further validation/sanitation of the filename may be appropriate
			$name = basename($_FILES["item"]["name"][$key]);
#echo "literacy_items/$iso/item_$no_items/$name" . '<br />';
			$item_name[] = $name;
			move_uploaded_file("$tmp_name", "literacy_data/$iso/item_$no_items/$name");			// Moves an uploaded file to a new location - from file to to file
		}
		//else {
			//$item_tmp_name[] = '';
			//$item_name[] = '';
		//}
	}
	
//print_r($item_name);
	
	$thumbnail = '';
	$text = '';
	$spanishLiteracyName = '';
	$variantLiteracyName = '';
	$PDF_viewing = '';
	$PDF_printing = '';
	$PDF_cover = '';
	$eBook = '';
	$app = '';
	$video = '';
	$audio = '';
	$zip_file = '';
	$count = 0;
	$thumbnailCount = check_input($_POST["thumbnailCount"]);
	$textCount = check_input($_POST["textCount"]);
	
	$spanishLiteracyNameCount = check_input($_POST["spanishLiteracyNameCount"]);
	$variantLiteracyNameCount = check_input($_POST["variantLiteracyNameCount"]);
	$PDF_viewingCount = check_input($_POST["PDF_viewingCount"]);
	$PDF_printingCount = check_input($_POST["PDF_printingCount"]);
	$PDF_coverCount = check_input($_POST["PDF_coverCount"]);
	$eBookCount = check_input($_POST["eBookCount"]);
	$appCount = check_input($_POST["appCount"]);
	$videoCount = check_input($_POST["videoCount"]);
	$audioCount = check_input($_POST["audioCount"]);
	$zip_fileCount = check_input($_POST["zip_fileCount"]);
//echo 'temp = ' . $_POST["item"][3] . '<br />';		// $spanishLiteracyName
//foreach ($_POST['item'] as $key => $value) {
//  echo $key . ' = ' . $value . "<br />";
//}
//	echo '<br />thumbnailCount = ' . $thumbnailCount;
	if ($thumbnailCount == '1') {
		$thumbnail = $item_name[$count];
		$count++;
	}
//echo 'count = ' . $count . '<br />';
	//echo '<br />thumbnail = ' . $thumbnail;
//	echo '<br />textCount = ' . $textCount;
	if ($textCount == '1') {
		$text = $item_name[$count];
		$count++;
	}
//echo 'count = ' . $count . '<br />';
	//echo '<br />text = ' . $text;
//	echo '<br />spanishLiteracyNameCount = ' . $spanishLiteracyNameCount;
	if ($spanishLiteracyNameCount == '1') {
		$spanishLiteracyName = $_POST["item"][3];
//		$spanishLiteracyName = $item_name[$count];
	}
//echo 'count = ' . $count . '<br />';
//	echo '<br />spanishLiteracyName = ' . $spanishLiteracyName;
//	echo '<br />variantLiteracyNameCount = ' . $variantLiteracyNameCount;
	if ($variantLiteracyNameCount == '1') {
		$variantLiteracyName = $_POST["item"][4];
//		$variantLiteracyName = $item_name[$count];
	}
//	echo '<br />variantLiteracyName = ' . $variantLiteracyName;
	//echo '<br />PDF_viewingCount = ' . $PDF_viewingCount;
	if ($PDF_viewingCount == '1') {
		$PDF_viewing = $item_name[$count];
		$count++;
	}
//echo 'count = ' . $count . '<br />';
	//echo '<br />PDF_viewing = ' . $PDF_viewing;
//	echo '<br />PDF_printingCount = ' . $PDF_printingCount;
	if ($PDF_printingCount == '1') {
		$PDF_printing = $item_name[$count];
		$count++;
	}
//echo 'count = ' . $count . '<br />';
	//echo '<br />PDF_printing = ' . $PDF_printing;
//	echo '<br />PDF_coverCount = ' . $PDF_coverCount;
	if ($PDF_coverCount == '1') {
		$PDF_cover = $item_name[$count];
		$count++;
	}
//echo 'count = ' . $count . '<br />';
	//echo '<br />PDF_cover = ' . $PDF_cover;
//	echo '<br />eBookCount = ' . $eBookCount;
	if ($eBookCount == '1') {
		$eBook = $item_name[$count];
		$count++;
	}
//echo 'count = ' . $count . '<br />';
	//echo '<br />eBook = ' . $eBook;
//	echo '<br />appCount = ' . $appCount;
	if ($appCount == '1') {
		$app = $item_name[$count];
		$count++;
	}
//echo 'count = ' . $count . '<br />';
	//echo '<br />app = ' . $app;
//	echo '<br />videoCount  = ' . $videoCount;
	if ($videoCount == '1') {
		$video = $item_name[$count];
		$count++;
	}
//echo 'count = ' . $count . '<br />';
	//echo '<br />video  = ' . $video;
//	echo '<br />audioCount = ' . $audioCount;
	if ($audioCount == '1') {
		$audio = $item_name[$count];
		$count++;
	}
//echo 'count = ' . $count . '<br />';
	//echo '<br />audio = ' . $audio;
//	echo '<br />zip_fileCount = ' . $zip_fileCount;
	if ($zip_fileCount == '1') {
		$zip_file = $item_name[$count];
		$count++;
	}
//echo 'count = ' . $count . '<br />';
//exit;
//	echo '<br />zip_file = ' . $zip_file;
	//$db->query("INSERT INTO display_items (iso_num_index, iso, rod_code, no_items, thumbnail, spanishLiteracyName, variantLiteracyName, PDF_viewing, PDF_printing, PDF_cover, eBook, app, video, audio, text, zip_file) VALUES ($iso_num_index, '$iso', '', $no_items, '$item_name[1]', '$spanishLiteracyName', '$variantLiteracyName', '$item_name[1]', '$item_name[2]', '$item_name[3]', '$item_name[4]', '$item_name[5]', '$item_name[6]', '$item_name[7]', '$item_name[8]', '$item_name[9]')");
	$db->query("INSERT INTO display_items (iso_num_index, iso, rod_code, no_items, thumbnail, spanishLiteracyName, variantLiteracyName, PDF_viewing, PDF_printing, PDF_cover, eBook, app, video, audio, text, zip_file) VALUES ($iso_num_index, '$iso', '', $no_items, '$thumbnail', '$spanishLiteracyName', '$variantLiteracyName', '$PDF_viewing', '$PDF_printing', '$PDF_cover', '$eBook', '$app', '$video', '$audio', '$text', '$zip_file')");
}

//include 'literacy_add_edit.php';

?>
<script>
	window.location.href = "literacy_add_edit.php?iso_num_index=<?php echo $iso_num_index; ?>";
</script>
<?php

// If exit was not here, the form page would be appended to the confirmation page.
exit;
?>
