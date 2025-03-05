<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type"				content="text/html; charset=utf-8" />
<meta name="viewport" 						content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>AJAX - Create html subpage for literacy database items -- from literacy.js which is from 00e-literacy.php</title>
<style type="text/css"> 
@charset "utf-8";
body  {
	font: 100% Arial, Verdana, Helvetica, sans-serif;
	background-image: url("img/Teita_enero.jpg");
	background-color: #4b7bc3;
	background-position: bottom;
	background-attachment: fixed;
	background-repeat: no-repeat;
	background-size: 100%; 
	margin: 0; /* it's good practice to zero the margin and padding of the body element to account for differing browser defaults */
	padding: 0;
	text-align: center; /* this centers the container in IE 5* browsers. The text is then set to the left aligned default in the #container selector */
	color: #000000;
}
  a {
	background-color: white;
  }
  a:link, a:visited, a:active {
	background-color: white;
  }
.twoColFixLtHdr #container { 
	width: 1000px;  /* using 20px less than a full 800px width allows for browser chrome and avoids a horizontal scroll bar */
	background-color: white;
	margin: 0 auto; /* the auto margins (in conjunction with a width) center the page */
	border: 1px solid #000000;
	text-align: left; /* this overrides the text-align: center on the body element. */
} 
.twoColFixLtHdr #header { 
	background-color: white; 
	padding: 0 10px 0 20px;  /* this padding matches the left alignment of the elements in the divs that appear beneath it. If an image is used in the #header instead of text, you may want to remove the padding. */
    position: fixed;
    top: 0;
    right: 0;
    width: 100%;
    text-align: center;
	z-index: 10;
} 
.twoColFixLtHdr #header h1 {
	margin: 0; /* zeroing the margin of the last element in the #header div will avoid margin collapse - an unexplainable space between divs. If the div has a border around it, this is not necessary as that also avoids the margin collapse */
	padding: 10px 0; /* using padding instead of margin will allow you to keep the element away from the edges of the div */
}
.twoColFixLtHdr #sidebar1 {
	float: left; /* since this element is floated, a width must be given */
	width: 300px; /* the actual width of this div, in standards-compliant browsers, or standards mode in Internet Explorer will include the padding and border in addition to the width */
	background-color: #EBEBEB; /* the background color will be displayed for the length of the content in the column, but no further */
	padding: 15px 10px 15px 20px;
	margin-top: 90px;
	min-height: 750px;
	opacity: 0.85;
}
.twoColFixLtHdr #mainContent { 
	margin: 0 0 0 350px; /* the left margin on this div element creates the column down the left side of the page - no matter how much content the sidebar1 div contains, the column space will remain. You can remove this margin if you want the #mainContent div's text to fill the #sidebar1 space when the content in #sidebar1 ends. */
	padding: 0 20px; /* remember that padding is the space inside the div box and margin is the space outside the div box */
} 
.twoColFixLtHdr #footer { 
	padding: 0 10px 0 20px; /* this padding matches the left alignment of the elements in the divs that appear above it. */
	background-color: #DDDDDD;
} 
.twoColFixLtHdr #footer p {
	margin: 0; /* zeroing the margins of the first element in the footer will avoid the possibility of margin collapse - a space between divs */
	padding: 10px 0; /* padding on this element will create space, just as the the margin would have, without the margin collapse issue */
	text-align: center;
}
.clearfloat { /* this class should be placed on a div or break element and should be the final element before the close of a container that should fully contain a float */
	clear:both;
    height:0;
    font-size: 1px;
    line-height: 0px;
}
div.contacts {
	margin-left: 5px;
	margin-right: 5px;
	margin-top: 10px;
	margin-bottom: 14px;
}
div.clearfix::after {
	content: "";
	clear: both;
	display: table;
}
div.ERROR {
	margin-top: 110px;
	text-align: center;
	font-size: 1.2em;
	background-color: red;
	color: white;
	padding-top: 10px;
	padding-bottom: 10px;
}
/* Smartphone Layout: max-width: 480px. Inherits styles from Smartphone Layout. */
@media only screen and (max-width: 480px) {							/* (max-width: 412px) for Samsung S8+ 2/20/2019 */
	body {
		background-image: none;
		background-color: #908CA5;
	}
	.twoColFixLtHdr #container { 
		width: 100%;  /* using 20px less than a full 800px width allows for browser chrome and avoids a horizontal scroll bar */
		background-color: white;
		margin: 0 auto; /* the auto margins (in conjunction with a width) center the page */
	}
	div.contacts {
		float: none;
		width: 100%;
	}
}
@media only screen and (min-width: 481px) {
	div.contacts {
		float: left;
		width: 46%;
	}
}
</style>
</head>
<body>
<?php
// literacy_main table: iso_num_index	iso	rod_code	family	sub_family	minority_lang	spanish_lang
// display_items table: display_item_index	iso_num_index	iso	rod_code	no_items	thumbnail	spanishLiteracyName	variantLiteracyName	PDF_viewing	PDF_printing	PDF_cover	eBook	app	video	audio	text	zip_file

require_once './include/conn.literacy.inc.php';							// connect to the database named 'scripture'
$db = get_my_db();

$no_items = 0;

if (isset($_GET["iso"]) || isset($_GET["idx"])) {
	if (isset($_GET["iso"]) && $_GET["iso"] != 'qqq') {	
		$iso = $_GET["iso"];
		$iso_num_index = 0;
	}
	else {
		$iso_num_index = $_GET["idx"];
		$iso_num_index = (int) $iso_num_index;
		$iso = 'qqq';
		if ($iso_num_index == NULL) {
			die ('‘iso index’ is empty.</body></html>');
		}
	}
}
else
	die('No ‘iso/index’ was found.');
	
$query = "SELECT * FROM literacy_main WHERE iso_num_index = $iso_num_index OR iso = '$iso'";							// SELECT one ISO code
$result=$db->query($query);
$num = $result->num_rows;
if ($num == 0) {						// this wasn't suppose to happen
	return;
}
$row = $result->fetch_array();
$spanishlang = $row['spanish_lang'];
$iso = $row['iso'];
$iso_num_index = $row['iso_num_index'];

$query = "SELECT * FROM display_items WHERE iso_num_index = $iso_num_index ORDER BY ABS(no_items) DESC";				// one ISO code ORDER BY item number
$result_items=$db->query($query);
$num = $result_items->num_rows;
if ($num == 0) {						// this wasn't suppose to happen
	echo '<div class="ERROR">No hay elementos de alfabetización bajo <span style="font-weight: bold; ">'.$spanishlang.'</span>.</div>';
	return;
}
$mod = 0;
if ($num%2 == 0) { $mod = 1; }

//echo '<h1 style="text-align: center; margin-top: 110px; "> Literacy items </h1>';
//echo '<h3>Here are the item(s):</h3>';																					// with the one ISO code by item number(s)
echo '<div style="margin-right: 30px; margin-left: 30px; ">';
	echo '<p style="margin-top: 70px; ">&nbsp;</p>';
	echo '<p style="margin-top: 30px; margin-bottom: 20px; text-align: center; color: #716164; font-weigth: bold; font-size: 1.5em; ">'.$spanishlang.'</p>';		//#908CA5
	echo '[' . $iso . ']<br />';
	$query='SELECT * FROM alt_lang_names_literacy WHERE ISO = "'.$iso.'"';
	$result_alt=$db->query($query);
	$num_alt = $result_alt->num_rows;
	$alt_lang_name = '';
	$alt_num = 0;
	if ($num_alt >= 1) {
		while ($rowAlt = $result_alt->fetch_array()) {
			if ($alt_num == 0) {
				$alt_lang_name = $rowAlt['alt_lang_name'];
				$alt_num = 1;
				continue;
			}
			$alt_lang_name .= ', ' . $rowAlt['alt_lang_name'];
		}
		echo $alt_lang_name . '<br />';
	}
	//$stmt = $db->prepare("SELECT * FROM display_items WHERE iso_num_index = $iso_num_index AND no_items = ?");			// SELECT with the ISO code by the one item number. OO set in php.ini????????
	while ($row_items = $result_items->fetch_array()) {																	// iterate the one ISO code and all the the item number(s)
		$no_items = $row_items['no_items'];
		$query = "SELECT thumbnail, spanishLiteracyName, variantLiteracyName, PDF_viewing, PDF_printing, PDF_cover, eBook, app, video, audio, text, zip_file FROM display_items WHERE iso_num_index = $iso_num_index AND no_items = $no_items";	// SELECT with the ISO code by the one item number
		$result_display=$db->query($query);
		//$stmt->bind_param('i', $no_items);
		/* execute prepared statement */
		//$stmt->execute();
		//$stmt->fetch(); which requires $numberofrows = $stmt->num_rows;
		//$result_display = $stmt->get_result();
		$num = $result_display->num_rows;
		if ($num < 1) {					// this wasn't suppose to happen
			echo 'There are no literacy items under Items #'.$no_items.'.<br />';
			continue;
		}
		$target_dir = 'literacy_data/'.$iso.'/item_'.$no_items;															// set the server folder
		$rowDisplay = $result_display->fetch_array(MYSQLI_ASSOC);
		$variantLiteracyName = $rowDisplay['variantLiteracyName'];
		$spanishLiteracyName = $rowDisplay['spanishLiteracyName'];
	
		if ($no_items%2 == $mod) {
			//echo $no_items . '<br />';
			echo "<div class='clearfix'>";
		}
		echo "<div class='contacts'>";
			echo '<div style="color: navy; background-color: white; font-weight: normal; margin-top: 30px;">';
				echo '<div style="">';			///height: 270px; ">';
				$text = $rowDisplay['text'];
				if ($text != '') {
					$title = '';
					$sub_title = '';
					$text_file = file($target_dir.'/'.$text, FILE_USE_INCLUDE_PATH | FILE_SKIP_EMPTY_LINES);			// read a file in an array
					// Loop through our array.
					foreach ($text_file as $line_num => $line) {
			//echo $line;
			//echo $target_dir.'/'.$text;
						if (preg_match('/t1 (.*)/', $line, $match)) {
							$title = $match[1];
						}
						if (preg_match('/t2 (.*)/', $line, $match)) {
							$sub_title = $match[1];
						}
					}
					if ($title != '') {
						echo '<p style="text-align: left; font-size: 1.2em; ">' . $title . '</p>';
					}
					if ($sub_title != '') {
						echo '<p style="text-align: left; font-size: 1em; font-style: italic; margin-bottom: 4px; ">' . $sub_title . '</p>';
					}
			//print_r($match);
				}
				//echo '<h2 style=""><span style="color: red; ">Items #'.$no_items.'</span>';
				//if ($spanishLiteracyName != '') {
				//	echo ': ' . $spanishLiteracyName;
				//	if ($variantLiteracyName != '') {
				//		echo ' (' . $variantLiteracyName . ')';
				//	}
				//}
				//else if ($variantLiteracyName != '') {
				//	echo ': ' . $variantLiteracyName;
				//}
				//echo '</h2>';
				echo '<p style="float: left; ">';
				$thumbnail = $rowDisplay['thumbnail'];
				$PDF_viewing = $rowDisplay['PDF_viewing'];
				$PDF_printing = $rowDisplay['PDF_printing'];
				if ($thumbnail != '') {
					if ($PDF_viewing != '' || $PDF_printing != '') {
						if ($PDF_viewing != '') {
							echo '<a href="'.$target_dir.'/'.$PDF_viewing.'" target="_blank">';
						}
						else {
							echo '<a href="'.$target_dir.'/'.$PDF_printing.'" target="_blank">';
						}
					}
					echo '<img src="'.$target_dir.'/'.$thumbnail.'" style="margin-right: 15px; width: 200px; height: 280px; border: 1px solid black; " />';
					if ($PDF_viewing != '' || $PDF_printing != '') {
						echo '</a>';
					}
				}
				else {
					echo '<img src="img/no_picture.gif" style="margin-right: 15px; " />';
				}
				echo '</p>';
				
				echo '<p style="font-size: 1.1em; clear: left; ">';
				//$thumbnail = $rowDisplay['thumbnail'];
				//echo 'Thumbnail (image file name): <span style="font-weight: bold; ">' . $thumbnail . '</span><br />';
				//echo 'Text: <a style="font-weight: bold; " href="'.$target_dir.'/'.$text.'" target="_blank">' . $text . '</a><br />';
				//echo 'Spanish Literacy Name: <span style="font-weight: bold; ">' . $spanishLiteracyName . '</span><br />';
				//echo 'Variant Literacy Name: <span style="font-weight: bold; ">' . $variantLiteracyName . '</span><br />';
				$app = $rowDisplay['app'];
				if ($app != '') {
					echo "App (apk): <a href='dl.php?d=".$target_dir."&z=".$app."'>" . $app . '</a><br />';
				}
				if ($PDF_viewing != '') {
					//echo 'PDF viewing: <a style="font-weight: bold; " href="'.$target_dir.'/'.$PDF_viewing.'" target="_blank">' . $PDF_viewing . '</a><br />';
					echo '<a style="font-weight: normal; " href="'.$target_dir.'/'.$PDF_viewing.'" target="_blank">' . $PDF_viewing . '</a><br />';
				}
				if ($PDF_printing != '') {
					//echo 'PDF printing: <a style="font-weight: bold; " href="'.$target_dir.'/'.$PDF_printing.'" target="_blank">' . $PDF_printing . '</a><br />';
					echo '<a style="font-weight: normal; " href="'.$target_dir.'/'.$PDF_printing.'" target="_blank">' . $PDF_printing . '</a><br />';
				}
				$PDF_cover = $rowDisplay['PDF_cover'];
				if ($PDF_cover != '') {
					//echo 'PDF cover: <a style="font-weight: bold; " href="'.$target_dir.'/'.$PDF_cover.'" target="_blank">' . $PDF_cover . '</a><br />';
					echo '<a style="font-weight: normal; " href="'.$target_dir.'/'.$PDF_cover.'" target="_blank">' . $PDF_cover . '</a><br />';
				}
				$eBook = $rowDisplay['eBook'];
				if ($eBook != '') {
					//echo 'eBook: <span style="font-weight: bold; ">' . $eBook . '</span><br />';
					echo '<span style="font-weight: normal; ">' . $eBook . '</span><br />';
				}
				$video = $rowDisplay['video'];
				if ($video != '') {
					echo '<span style="font-weight: normal; ">' . $video . '</span><br />';
				}
				$audio = $rowDisplay['audio'];
				if ($audio != '') {
					echo '<span style="font-weight: normal; ">' . $audio . '</span><br />';
				}
				$zip_file = $rowDisplay['zip_file'];
				if ($zip_file != '') {
					echo "<a href='dl.php?d=".$target_dir."&z=".$zip_file."'>" . $zip_file . "</a><br />";
				}
				//echo '</p>';
				//echo '<p style="margin-bottom: 0px; font-size: 1em; ">';
				//if ($spanishLiteracyName != '') {
				//	echo ': ' . $spanishLiteracyName;
				//}
				//if ($variantLiteracyName != '') {
				//	if ($spanishLiteracyName != '') {
				//		echo '<br />';
				//	}
				//	echo ': ' . $variantLiteracyName;
				//}
				//echo '</p>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		if ($no_items%2 == $mod) {
			echo '</div>';
		}
//if ($no_items%2 == 1) { exit; }
	}
	if ($no_items%2 == 1) {
		echo "<div class='contacts'>";
			echo '<p>&nbsp;</p>';
		echo '</div>';
		echo '</div>';
	}
echo '</div>';
echo '<p style="clear: both; ">&nbsp;</p>';
?>
<br /><br />
</body>
</html>