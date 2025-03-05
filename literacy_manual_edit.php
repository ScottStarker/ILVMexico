<?php
include './include/session.php';
global $session;
// Login attempt
$retval = $session->checklogin();
if (!$retval) {
	//echo "<br /><div style='text-align: center; font-size: 16pt; font-weight: bold; padding: 10px; color: navy; background-color: #dddddd; '>You are not logged in!</div>";
	// Link back to main
	header('Location: login.php');
	exit;
}
require_once './include/conn.literacy.inc.php';							// connect to the database named 'scripture'
$db = get_my_db();

if (isset($_POST['Edit'])) {
    $iso = trim($_POST['iso']);
    //echo 'iso ' . $iso . '<br />';
    $no_items=(int)$_POST['no_items'];
    //echo 'no_items ' . $no_items . '<br />';
    $iso_num_index = (int)$_POST['iso_num_index'];
    //echo 'iso_num_index ' . $iso_num_index . '<br />';
    $rod_code = $_POST['rod_code'];
    //echo 'rod_code ' . $rod_code . '<br />';
    $thumbnail = trim($_POST['thumbnail']);
    //echo 'thumbnail ' . $thumbnail . '<br />';
    $text = trim($_POST['text']);
    //echo 'text ' . $text . '<br />';
    $spanishLiteracyName = trim($_POST['spanishLiteracyName']);
    //echo 'spanishLiteracyName ' . $spanishLiteracyName . '<br />';
    $variantLiteracyName = trim($_POST['variantLiteracyName']);
    //echo 'variantLiteracyName ' . $variantLiteracyName . '<br />';
    $PDF_viewing = trim($_POST['PDF_viewing']);
    //echo 'PDF_viewing ' . $PDF_viewing . '<br />';
    $PDF_printing = trim($_POST['PDF_printing']);
    //echo 'PDF_printing ' . $PDF_printing . '<br />';
    $PDF_cover = trim($_POST['PDF_cover']);
    //echo 'PDF_cover ' . $PDF_cover . '<br />';
    $eBook = trim($_POST['eBook']);
    //echo 'eBook ' . $eBook . '<br />';
    $app = trim($_POST['app']);
    //echo 'app ' . $app . '<br />';
    $video = trim($_POST['video']);
    //echo 'video ' . $video . '<br />';
    $audio = trim($_POST['audio']);
    //echo 'audio ' . $audio . '<br />';
    $zip_file = trim($_POST['zip_file']);
    //echo 'zip_file ' . $zip_file . '<br />';
	$db->query("UPDATE display_items SET rod_code = '$rod_code', thumbnail = '$thumbnail', `text` = '$text', spanishLiteracyName = '$spanishLiteracyName', variantLiteracyName = '$variantLiteracyName', PDF_viewing = '$PDF_viewing', PDF_printing = '$PDF_printing', PDF_cover = '$PDF_cover', eBook = '$eBook', app = '$app', video = '$video', audio = '$audio', zip_file = '$zip_file' WHERE iso = '$iso' AND no_items = $no_items");
    header('Location: literacy_manual_edit.php');			// If you haven't output (echo) ANYTHING you can always do this.
	echo $iso . ' items # ' . $no_items .   ' edited.';
	exit;
}

$no_items = 0;
if (isset($_POST["no_items"]) && isset($_POST["Almost"])) {
	$no_items = $_POST["no_items"];
}
/*else {
	die('no num or no_items');
}*/

if (isset($_POST["iso"]) && isset($_POST["Almost"])) {
	$iso = $_POST["iso"];
}
/*else {
	die('no iso');
}*/

/*if (isset($_POST["iso_num_index"])) {
	$iso_num_index = $_POST["iso_num_index"];
}
else {
	die('no iso_num_index');
}*/

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ILV México Literacy Manual Edit an Item</title>
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
.oneColFixCtrHdr #footer {
	background:#DDDDDD;
    position: fixed;
    bottom: 0;
    right: 0;
    width: 100%;
    text-align: center;
}
.oneColFixCtrHdr #footer p {
	margin: 0; /* zeroing the margins of the first element in the footer will avoid the possibility of margin collapse - a space between divs */
	padding: 10px 0; /* padding on this element will create space, just as the the margin would have, without the margin collapse issue */
}
h2 {
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
</head>
<body class="oneColFixCtrHdr">
<div id="container">
    <div id="header">
        <button id="homeButton" onClick="window.open('literacy_add_edit.php', '_top')">Inicio</button>
	    <a style='top: 22px; right: -360px; position: relative; font-size: small; background-color: #DDDDDD; ' href='process.php'>[Logout]</a>
    	<h1 style='color: navy; text-align: center; margin-top: -24px; '>MXB Literacy — Manual Edit an Item</h1>
    </div><!-- end #header -->
    <div class='clearfloat'></div>
    <div id="mainContent">
<?php

if ($no_items == 0) {
    echo '<br /><br /><br /><br /><br /><br />';
    echo '<form action="literacy_manual_edit.php" method="post">';
    //					========================
    echo 'iso: <input type="text" size="8" name="iso" value="" autofocus /><br />';                        // 3-6 letters
    echo 'Items #: <input type="text" size="2" name="no_items" value="" />';                     // numbers only
    echo '<input type="hidden" name="Almost" value="Almost" />';                        // numbers only
    echo '<br /><br /><div style="margin-left: auto; margin-right: auto; "><input type="submit" value="OK" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    echo '<input type="reset"></div><br /><br />';
    echo '</form>';
	echo '<br />';
}
elseif (isset($_POST["Almost"])) {
    $iso = trim($_POST['iso']);
    $no_items=trim($_POST['no_items']);
    $query="SELECT * FROM display_items WHERE iso = '$iso' AND no_items = $no_items";
    $result=$db->query($query);
    $num=$result->num_rows;
    if ($num == 0) {
        ?>
        <script>
            alert("ISO <?php echo $iso ?> and Item # <?php echo $no_items ?> does't exist.");
            location.href = "literacy_manual_edit.php";
        </script>
        <?php
    }
    $row = $result->fetch_array();
    //$iso = trim($row["iso"]);
    //$no_items=trim($row['no_items']);
    $iso_num_index = $row['iso_num_index'];
    $rod_code = $row['rod_code'];
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

    echo '<form action="literacy_manual_edit.php" method="post">';
        echo '<div style="font-size: 1.6em; color: navy; font-weight: bold; margin-bottom: 12px; ">Manually edit of Item #'.$no_items.' ['.$iso.']</div>';
        echo '<input type="hidden" name="Edit" value="Edit" />';
        echo '<input type="hidden" name="iso" value="'.$iso.'" /><br />';
        echo '<input type="hidden" name="no_items" value="'.$no_items.'" /><br />';
        echo '<input type="hidden" name="iso_num_index" value="'.$iso_num_index.'" /><br />';
        echo '<input type="hidden" name="rod_code" value="'.$rod_code.'" /><br />';
        echo 'Thumbnail (image file name): <input type="text" size="50" name="thumbnail" placeholder="Enter thumbnail" value="'.$thumbnail.'" /><br />';
        echo 'Text file: <input type="text" name="text" size="50" value="'.$text.'" /><br />';
        echo 'Spanish Literacy Name: <input type="text" size="50" name="spanishLiteracyName" value="'.$spanishLiteracyName.'" /><br />';
        echo 'Variant Literacy Name: <input type="text" size="50" name="variantLiteracyName" value="'.$variantLiteracyName.'" /><br />';
        echo 'Viewing PDF: <input type="text" size="50" name="PDF_viewing" value="'.$PDF_viewing.'" /><br />';
        echo 'Printing PDF: <input type="text" size="50" name="PDF_printing" value="'.$PDF_printing.'" /><br />';
        echo 'Cover PDF: <input type="text" size="50" name="PDF_cover" value="'.$PDF_cover.'" /><br />';
        echo 'eBook: <input type="text" size="50" name="eBook" value="'.$eBook.'" /><br />';
        echo 'app: <input type="text" size="50" name="app" value="'.$app.'" /><br />';
        echo 'Video: <input type="text" size="50" name="video" value="'.$video.'" /><br />';
        echo 'Audio: <input type="text" size="50" name="audio" value="'.$audio.'" /><br />';
        echo 'Zip File: <input type="text" size="50" name="zip_file" value="'.$zip_file.'" /><br /><br />';
        echo '<div style="margin-left: auto; margin-right: auto; "><input type="submit" value="Do it" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        echo '<input type="reset"><br /><br />';
        echo '<input type="button" onclick="location.href=\'literacy_manual_edit.php\'" value="Cancel" /></div>';
    echo '</form>';
	echo '<br />';
}
else {
	die('Hack!');
}

//header('Location: literacy_manual_edit.php');			// If you haven't output (echo) ANYTHING you can always do this.

// If exit was not here, the form page would be appended to the confirmation page.
//exit;
?>
    </div><!-- end #mainContent -->
    <div id="footer">
        <p style='font-size: small; text-align: center; '>&copy; <?php echo Date("Y"); ?> Instituto Lingüístico de Verano</p>
    </div><!-- end #footer -->
</div><!-- end #container -->
</body>
</html>