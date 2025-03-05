<?php
include './include/session.php';
global $session;
/* Login attempt */
$retval = $session->checklogin();
if (!$retval) {
	//echo "<br /><div style='text-align: center; font-size: 16pt; font-weight: bold; padding: 10px; color: navy; background-color: #dddddd; '>You are not logged in!</div>";
	/* Link back to main */
	header('Location: login.php');
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
/************************************************************************************************************************
		Must have 'file_uploads' set to On in php.ini
			file_uploads = On
************************************************************************************************************************/
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ILV México Literacy Delete an Item</title>
<link rel="stylesheet" type="text/css" href="css/all.css" />
<link rel="stylesheet" type="text/css" href="css/main.css" />
<style type="text/css">
<!--
body {
	font: 100% Arial, Verdana, Helvetica, sans-serif;
	background: #666666;
	margin: 0; /* it's good practice to zero the margin and padding of the body element to account for differing browser defaults */
	padding: 0;
	text-align: center; /* this centers the container in IE 5* browsers. The text is then set to the left aligned default in the #container selector */
	color: #000000;
}
.oneColFixCtrHdr #container {
	width: 1000px;  /* using 20px less than a full 800px width allows for browser chrome and avoids a horizontal scroll bar */
	background-color: #FFF;
	margin: 0 auto; /* the auto margins (in conjunction with a width) center the page */
	/*border: 1px solid #000000;*/
	text-align: left; /* this overrides the text-align: center on the body element. */
}
.oneColFixCtrHdr #header {
	background: #DDDDDD; 
	padding: 0 10px 0 20px;  /* this padding matches the left alignment of the elements in the divs that appear beneath it. If an image is used in the #header instead of text, you may want to remove the padding. */
    position: fixed;
    top: 0;
    right: 0;
    width: 100%;
    text-align: center;
}
.oneColFixCtrHdr #header h1 {
	margin: 0; /* zeroing the margin of the last element in the #header div will avoid margin collapse - an unexplainable space between divs. If the div has a border around it, this is not necessary as that also avoids the margin collapse */
	padding: 10px 0; /* using padding instead of margin will allow you to keep the element away from the edges of the div */
}
.oneColFixCtrHdr #mainContent {
	padding: 0 20px; /* remember that padding is the space inside the div box and margin is the space outside the div box */
	background: #FFFFFF;
	/*height: 900px;*/
}
-->
.oneColFixCtrHdr #footer p {
	margin: 0; /* zeroing the margins of the first element in the footer will avoid the possibility of margin collapse - a space between divs */
	padding: 10px 0; /* padding on this element will create space, just as the the margin would have, without the margin collapse issue */
	background-color: #DDDDDD;
    position: fixed;
    bottom: 0;
    right: 0;
    width: 100%;
	text-align: center;
}
<!--h2 {
	padding-top: 50px;
}
input {
	margin-top: 10px;
	margin-bottom: 10px;
	font-size: 14pt;
}
form {
	color: navy;
	font-weight: bold;
	font-size: 14pt;
}
#homeButton {
	color: black;
	top: 22px;
	left: -360px;
	position: relative;
}
-->
</style>
<script type="text/javascript">
function items(no_items) {
	var item = document.getElementById("process_"+no_items).style.display;
	if (item == 'none') {
		document.getElementById("process_"+no_items).style.display = 'block';
	}
	else {
		document.getElementById("process_"+no_items).style.display = 'none';
	}
}
</script>
</head>
<body class="oneColFixCtrHdr">
<?php

if (isset($_POST['submit'])){
	//$db->query("DELETE display_items WHERE iso_num_index = $iso_num_index AND no_items = $no_items");
	echo 'deleted the item.';
}

require_once './include/conn.literacy.inc.php';							// connect to the database named 'scripture'
$db = get_my_db();

?>
<div id="container">
    <div id="header">
        <!--div style="text-align: center; margin-left: auto; margin-right: auto; "-->
			<button id="homeButton" onClick="window.open('literacy_delete.php', '_top')">Inicio</button>
			<!--img id="icon" src="img/ILVicon.png" style="margin-right: 10px; " alt="" /--> <!--class="logo" -->
			<a style='top: 22px; right: -360px; position: relative; font-size: small; background-color: #DDDDDD; ' href='process.php'>[Logout]</a>
			<h1 style='color: navy; text-align: center; margin-top: -24px; '>MXB Literacy — Delete an Item</h1>
			<!--div class="membrete"-->
				<!--img id="bnner" src="img/ILVwebsite-banner.png" alt="" /-->
            <!--/div-->
        <!--/div-->
    </div><!-- end #header -->
    <div class='clearfloat'></div>
    <div id="mainContent">
    <?php
	/**********************************************************************************************************************
			This is item 2 or more. literacy_delete.php is called.
	**********************************************************************************************************************/
	if (isset($_GET['iso_num_index'])) {																	// if "iso_num_index" is present
		$iso_num_index = $_GET['iso_num_index'];
		if (is_numeric($iso_num_index)) {
			$query = "SELECT * FROM literacy_main WHERE iso_num_index = $iso_num_index";						// displays all of the item numbers for a given "iso_num_index"
			$result=$db->query($query) or die ('Query failed: ' . $db->error . '</body></html>');
			if ($result->num_rows <= 0) {
				die ('The literacy languages table is not found.</body></html>');
			}
			$row = $result->fetch_array();
			$sub_family=trim($row['sub_family']);
			$iso = trim($row["iso"]);
			$family=trim($row['family']);
			$spanish_lang=trim($row['spanish_lang']);
			//echo '<h1>&nbsp;</h1>';
    		//echo '<h2>Specific Language</h2>';
			echo "<div style='color: navy; font-size: 1.8em; font-weight: bold; padding-top: 90px; margin-bottom: 20px; text-align: center; '>" . $spanish_lang . ' [' . $iso . ']</div>';
			$query = "SELECT * FROM display_items WHERE iso_num_index = $iso_num_index ORDER BY no_items";
			$result_items=$db->query($query);
			$num = $result_items->num_rows;
			if ($num < 1) {				// this want happen
				//? >
                //<script type="text/javascript">
                	//alert("There are no items under <?php echo $spanishlang; ? >");
					//window.location.href = "literacy_add_edit.php";
				//< /script>
				//< ?php

				echo "There are no items under [$iso] $spanish_lang<br /><br />";
				$no_items = 0;
			}
			else {
				//echo '<h3>Here are the item(s):</h3>';															// here are the item numbers
				while ($row_items = $result_items->fetch_array()) {
//					$row_items = $result_items->fetch_array();
					$no_items = $row_items['no_items'];
					$query = "SELECT * FROM display_items WHERE iso_num_index = $iso_num_index AND no_items = $no_items";
					$result_display=$db->query($query);
					$num = $result_display->num_rows;
//					if ($num < 1) {				// this doesn't happen
//						echo 'There are no items under Items #'.$no_items.'.<br />';
//						continue;
//					}
					$row = $result_display->fetch_array();
					echo "<a href='#' onclick='items($no_items)'>Item #".$no_items.'</a><br />';
					$text = $row['text'];
					if ($text != '') {
						$title = '';
						$sub_title = '';
						$target_dir = 'literacy_data/'.$iso.'/item_'.$no_items;													// set the server folder
						$text_file = file($target_dir.'/'.$text, FILE_USE_INCLUDE_PATH | FILE_SKIP_EMPTY_LINES);			// read a file in an array
						// Loop through our array.
						foreach ($text_file as $line_num => $line) {
							if (preg_match('/t1 (.*)/', $line, $match)) {
								$title = $match[1];
							}
							if (preg_match('/t2 (.*)/', $line, $match)) {
								$sub_title = $match[1];
							}
						}
						if ($title != '') {
							echo '<p style="text-align: left; font-size: 1.1em; ">' . $title . '</p>';
						}
						if ($sub_title != '') {
							echo '<p style="text-align: left; font-size: 1em; font-style: italic; margin-bottom: 4px; ">' . $sub_title . '</p>';
						}
					}
					echo '<form style="display: none; " id="process_'.$no_items.'" method="post" action="literacy_delete_item.php?num='.$no_items.'">';	// the "form"s are hidden
					//																	   				 ========================
					echo '<input type="hidden" name="iso_num_index" value="'.$iso_num_index.'" />';
					echo '<input type="hidden" name="iso" value="'.$iso.'" />';
					echo '<input type="hidden" name="spanish_lang" value="'.$spanish_lang.'" />';
					echo '<input type="hidden" name="no_items_'.$no_items.'" value="'.$no_items.'" />';
//echo '$no_items = ' . $no_items . '<br />';
					$thumbnail = $row['thumbnail'];
					if ($thumbnail == '') {
						echo '<input type="hidden" name="thumbnailCount_'.$no_items.'" id="thumbnailCount_'.$no_items.'" value="0" />';
						//echo 'Thumbnail (image file name): <input type="file" name="item_'.$no_items.'[1]" id="thumbnail_'.$no_items.'" onclick="document.getElementById(\'thumbnailCount_'.$no_items.'\').value = \'1\'" /><br />';
					}
					else {
						echo '<input type="hidden" name="thumbnailCount_'.$no_items.'" id="thumbnailCount_'.$no_items.'" value="0" />';
						echo 'Thumbnail (image file name): <input type="text" name="item_'.$no_items.'[1]" id="thumbnail_'.$no_items.'" size="60" readonly="readonly" value="'.$thumbnail.'" /><br />';
					}
					if ($text  == '') {
						echo '<input type="hidden" name="textCount_'.$no_items.'" id="textCount_'.$no_items.'" value="0" />';
						//echo 'Text file: <input type="file" name="item_'.$no_items.'[2]" id="text_'.$no_items.'" onclick="document.getElementById(\'textCount_'.$no_items.'\').value = \'1\'" /><br />';
					}
					else {
						echo '<input type="hidden" name="textCount_'.$no_items.'" id="textCount_'.$no_items.'" value="0" />';
						echo 'Text file: <input type="text" name="item_'.$no_items.'[2]" id="text_'.$no_items.'" size="60" readonly="readonly" value="'.$text.'" /><br />';
					}
					$spanishLiteracyName = $row['spanishLiteracyName'];
					if ($spanishLiteracyName == '') {
						echo '<input type="hidden" name="spanishLiteracyNameCount_'.$no_items.'" id="spanishLiteracyNameCount_'.$no_items.'" value="0" />';
						//echo 'Spanish Literacy Name: <input type="text" name="item_'.$no_items.'[3]" size="60" id="spanishLiteracyName_'.$no_items.'" value="" onmouseup="document.getElementById(\'spanishLiteracyNameCount_'.$no_items.'\').value = \'1\'; document.getElementById(\'spanishLiteracyName_'.$no_items.'\').style.color = \'green\'; " /><br />';
						//echo '<input type="hidden" name="item_'.$no_items.'[3]" id="spanishLiteracyName_'.$no_items.'" value="" />';
					}
					else {
						echo '<input type="hidden" name="spanishLiteracyNameCount_'.$no_items.'" id="spanishLiteracyNameCount_'.$no_items.'" value="0" />';
						//echo 'Spanish Literacy Name: <input type="text" name="item_'.$no_items.'[3]" id="spanishLiteracyName_'.$no_items.'" size="60" readonly="readonly" value="'.$spanishLiteracyName.'" /><br />';
						echo '<input type="hidden" name="item_'.$no_items.'[3]" id="spanishLiteracyName_'.$no_items.'" value="" />';
					}
					$variantLiteracyName = $row['variantLiteracyName'];
					if ($variantLiteracyName == '') {
						echo '<input type="hidden" name="variantLiteracyNameCount_'.$no_items.'" id="variantLiteracyNameCount_'.$no_items.'" value="0" />';
						//echo 'Variant Literacy Name: <input type="text" name="item_'.$no_items.'[4]" size="60" id="variantLiteracyName_'.$no_items.'" value="" onmouseup="document.getElementById(\'variantLiteracyNameCount_'.$no_items.'\').value = \'1\'" /><br />';
						//echo '<input type="hidden" name="item_'.$no_items.'[4]" id="variantLiteracyName_'.$no_items.'" value="" />';
					}
					else {
						echo '<input type="hidden" name="variantLiteracyNameCount_'.$no_items.'" id="variantLiteracyNameCount_'.$no_items.'" value="0" />';
						//echo 'Variant Literacy Name: <input type="text" name="item_'.$no_items.'[4]" id="variantLiteracyName_'.$no_items.'" size="60" readonly="readonly" value="'.$variantLiteracyName.'" /><br />';
						echo '<input type="hidden" name="item_'.$no_items.'[4]" id="variantLiteracyName_'.$no_items.'" value="" />';
					}
					$PDF_viewing = $row['PDF_viewing'];
					if ($PDF_viewing == '') {
						echo '<input type="hidden" name="PDF_viewingCount_'.$no_items.'" id="PDF_viewingCount_'.$no_items.'" value="0" />';
						//echo 'Viewing PDF: <input type="file" name="item_'.$no_items.'[5]" id="PDF_viewing_'.$no_items.'" onclick="document.getElementById(\'PDF_viewingCount_'.$no_items.'\').value = \'1\'" /><br />';
					}
					else {
						echo '<input type="hidden" name="PDF_viewingCount_'.$no_items.'" id="PDF_viewingCount_'.$no_items.'" value="0" />';
						echo 'Viewing PDF: <input type="text" name="item_'.$no_items.'[5]" id="PDF_viewing_'.$no_items.'" size="60" readonly="readonly" value="'.$PDF_viewing.'" /><br />';
					}
					$PDF_printing = $row['PDF_printing'];
					if ($PDF_printing  == '') {
						echo '<input type="hidden" name="PDF_printingCount_'.$no_items.'" id="PDF_printingCount_'.$no_items.'" value="0" />';
						//echo 'Printing PDF: <input type="file" name="item_'.$no_items.'[6]" id="PDF_printing_'.$no_items.'" onclick="document.getElementById(\'PDF_printingCount_'.$no_items.'\').value = \'1\'" /><br />';
					}
					else {
						echo '<input type="hidden" name="PDF_printingCount_'.$no_items.'" id="PDF_printingCount_'.$no_items.'" value="0" />';
						echo 'Printing PDF: <input type="text" name="item_'.$no_items.'[6]" id="PDF_printing_'.$no_items.'" size="60" readonly="readonly" value="'.$PDF_printing.'" /><br />';
					}
					$PDF_cover = $row['PDF_cover'];
					if ($PDF_cover == '') {
						echo '<input type="hidden" name="PDF_coverCount_'.$no_items.'" id="PDF_coverCount_'.$no_items.'" value="0" />';
						//echo 'Cover PDF: <input type="file" name="item_'.$no_items.'[7]" id="PDF_cover_'.$no_items.'" onclick="document.getElementById(\'PDF_coverCount_'.$no_items.'\').value = \'1\'" /><br />';
					}
					else {
						echo '<input type="hidden" name="PDF_coverCount_'.$no_items.'" id="PDF_coverCount_'.$no_items.'" value="0" />';
						echo 'Cover PDF: <input type="text" name="item_'.$no_items.'[7]" id="PDF_cover_'.$no_items.'" size="60" readonly="readonly" value="'.$PDF_cover.'" /><br />';
					}
					$eBook = $row['eBook'];
					if ($eBook == '') {
						echo '<input type="hidden" name="eBookCount_'.$no_items.'" id="eBookCount_'.$no_items.'" value="0" />';
						//echo 'eBook: <input type="file" name="item_'.$no_items.'[8]" id="eBook_'.$no_items.'" onclick="document.getElementById(\'eBookCount_'.$no_items.'\').value = \'1\'" /><br />';
					}
					else {
						echo '<input type="hidden" name="eBookCount_'.$no_items.'" id="eBookCount_'.$no_items.'" value="0" />';
						echo 'eBook: <input type="text" name="item_'.$no_items.'[8]" id="eBook_'.$no_items.'" size="60" readonly="readonly" value="'.$eBook.'" /><br />';
					}
					$app = $row['app'];
					if ($app == '') {
						echo '<input type="hidden" name="appCount_'.$no_items.'" id="appCount_'.$no_items.'" value="0" />';
						//echo 'app: <input type="file" name="item_'.$no_items.'[9]" id="app" onclick="document.getElementById(\'appCount_'.$no_items.'\').value = \'1\'" /><br />';
					}
					else {
						echo '<input type="hidden" name="appCount_'.$no_items.'" id="appCount_'.$no_items.'" value="0" />';
						echo 'app: <input type="text" name="item_'.$no_items.'[9]" id="app_'.$no_items.'" size="60" readonly="readonly" value="'.$app.'" /><br />';
					}
					$video = $row['video'];
					if ($video == '') {
						echo '<input type="hidden" name="videoCount_'.$no_items.'" id="videoCount_'.$no_items.'" value="0" />';
						//echo 'Video: <input type="file" name="item_'.$no_items.'[10]" id="video_'.$no_items.'" onclick="document.getElementById(\'videoCount_'.$no_items.'\').value = \'1\'" /><br />';
					}
					else {
						echo '<input type="hidden" name="videoCount_'.$no_items.'" id="videoCount_'.$no_items.'" value="0" />';
						echo 'Video: <input type="text" name="item_'.$no_items.'[10]" id="video_'.$no_items.'" size="60" readonly="readonly" value="'.$video.'" /><br />';
					}
					$audio = $row['audio'];
					if ($audio == '') {
						echo '<input type="hidden" name="audioCount_'.$no_items.'" id="audioCount_'.$no_items.'" value="0" />';
						//echo 'Audio: <input type="file" name="item_'.$no_items.'[11]" id="audio_'.$no_items.'" onclick="document.getElementById(\'audioCount_'.$no_items.'\').value = \'1\'" /><br />';
					}
					else {
						echo '<input type="hidden" name="audioCount_'.$no_items.'" id="audioCount_'.$no_items.'" value="0" />';
						echo 'Audio: <input type="text" name="item_'.$no_items.'[11]" id="audio_'.$no_items.'" size="60" readonly="readonly" value="'.$audio.'" /><br />';
					}
					$zip_file = $row['zip_file'];
					if ($zip_file == '') {
						echo '<input type="hidden" name="zip_fileCount_'.$no_items.'" id="zip_fileCount_'.$no_items.'" value="0" />';
						//echo 'Zip file: <input type="file" name="item_'.$no_items.'[12]" id="zip_file_'.$no_items.'" onclick="document.getElementById(\'zip_fileCount_'.$no_items.'\').value = \'1\'" /><br /><br />';
					}
					else {
						echo '<input type="hidden" name="zip_fileCount_'.$no_items.'" id="zip_fileCount_'.$no_items.'" value="0" />';
						echo 'Zip file: <input type="text" name="item_'.$no_items.'[12]" id="zip_file_'.$no_items.'" size="60" readonly="readonly" value="'.$zip_file.'" /><br /><br />';
					}
					echo '<div style="margin-left: auto; margin-right: auto; "><input type="submit" value="Delete" />';
					echo '<br /><br /></div>';
					echo '</form>';
					echo '<br />';
				}
			}
		}
	}
	else {
		/**********************************************************************************************************************
				This is the 1st time literacy_add_edit.php is called.
		**********************************************************************************************************************/
		?>
		<h2 style="margin-top: -10px; ">&nbsp;</h2>
		<?php
		$query = "SELECT * FROM literacy_main ORDER BY sub_family, iso";											// order by "language" (sub family)
		$result=$db->query($query) or die ('Query failed: ' . $db->error . '</body></html>');
		if ($result->num_rows <= 0) {
			die ('The literacy languages table is not found.</body></html>');
		}
		$lang = '';
		while ($row = $result->fetch_array()) {
			$sub_family=trim($row['sub_family']);
			if ($lang != $sub_family) {
				echo '<div style="color: green; font-size: larger; font-weight: bold; margin-top: 12px; ">' . $sub_family . '</div>';
				$lang = $sub_family;
			}
			$iso = trim($row["iso"]);
			$family=trim($row['family']);
			$spanish_lang=trim($row['spanish_lang']);
			$iso_num_index=$row['iso_num_index'];
			//if (is_dir('literacy_data/' . $iso . '/item_1')) {
				echo "<a href='literacy_delete.php?iso_num_index=".$iso_num_index."' target='_top'>[" . $iso . '] ' . $spanish_lang . '</a><br />';
			//}
		}
    }
    ?>
    </div><!-- end #mainContent -->
    <div id="footer" style="margin-top: 32px; ">		<!-- footer up from the bottom -->
        <p style='font-size: .7em; '>&copy; <?php echo Date("Y"); ?> Instituto Lingüístico de Verano</p>
    </div><!-- end #footer -->
</div><!-- end #container -->
</body>
</html>