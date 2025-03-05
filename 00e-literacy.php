<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type"				content="text/html; charset=utf-8" />
<meta name="viewport" 						content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>ILV México</title>
<link rel="stylesheet" type="text/css" href="css/all.css" />
<link rel="stylesheet" type="text/css" href="css/main.css" />
<style type="text/css"> 
@charset "utf-8";
body  {
	font: 100% Arial, Verdana, Helvetica, sans-serif;
	background: url("img/Teita_enero.jpg");
	background-color: #4b7bc3;
	/*	background-color: #908CA5; */
	background-position: bottom;
	background-attachment: fixed;
	background-repeat: no-repeat;
	background-size: 100%; 
	margin: 0; /* it's good practice to zero the margin and padding of the body element to account for differing browser defaults */
	padding: 0;
	/*text-align: center; / * this centers the container in IE 5* browsers. The text is then set to the left aligned default in the #container selector */
	color: #000000;
}
a {
	color: #1D48CA;
	background-color: #EBEBEB;
}
a:link, a:visited, a:active {
	text-decoration: none;
	line-height: 17pt;
	color: #1D48CA;
	background-color: #EBEBEB;
}
a:hover {
	/*color: #20381D;*/
	color: #EC0000;
	text-decoration: underline;
}
#icon {
	 /*width: 7%;
	 height: 7%;*/
	 width: 90px;
	 height: 93px;
}
#bnner {
	vertical-align: 6px;
	/*width: 30%;
	height: 30%;*/
	width: 400px;
	width: 400px;
}
#homeButton {
	color: black;
	top: -20px;
	left: -100px;
	position: relative;
}
input[type=text] {
	border: solid 2px #5F5254;
	vertical-align: middle;
    width: 100%;
    box-sizing: border-box;
    border-radius: 12px;
    font-size: 1.4em;
    /*background-color: #4079b0;*/
	background-color: #716164;
    color: white;
    background-image: url('../img/search-vector-36-high.png');
	
    background-position: 10px 10px; 
    background-repeat: no-repeat;
    padding: 10px 18px 10px 18px;
	background-position: right;
}
#allSearch {
	float: right;
	width: 60%;
	margin-top: 120px;
	margin-left: 0px;
	margin-right: 30px;
}
.twoColFixLtHdr #container { 
	width: 1000px;  /* using 20px less than a full 800px width allows for browser chrome and avoids a horizontal scroll bar */
	/*background: url("img/Teita_enero.jpg") #908CA5 center fixed;*/
	margin: 0 auto; /* the auto margins (in conjunction with a width) center the page */
	/*border: 1px solid #000000;*/
	text-align: left; /* this overrides the text-align: center on the body element. */
	/*background-color: #dddddd;*/
} 
.twoColFixLtHdr #header {
	background: white; 
	padding: 0 10px 0 20px;  /* this padding matches the left alignment of the elements in the divs that appear beneath it. If an image is used in the #header instead of text, you may want to remove the padding. */
	/*margin-top: 20px;*/
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
	margin-top: 100px;
	/*min-height: 750px;*/
	opacity: 0.85;
}
.twoColFixLtHdr #mainContent { 
	margin: 0 0 0 350px; /* the left margin on this div element creates the column down the left side of the page - no matter how much content the sidebar1 div contains, the column space will remain. You can remove this margin if you want the #mainContent div's text to fill the #sidebar1 space when the content in #sidebar1 ends. */
	padding: 0 20px; /* remember that padding is the space inside the div box and margin is the space outside the div box */
} 
.twoColFixLtHdr #footer { 
	clear: both;
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
/* Smartphone Layout: max-width: 480px. Inherits styles from Smartphone Layout. */
@media only screen and (max-width: 480px) {							/* (max-width: 412px) for Samsung S8+ 2/20/2019 */
	body  {
		background-image: none;
		background-color: #DDDDDD;
	}
	#icon {
		 width: 16%;
		 height: 16%;
	}
	#bnner {
		/*vertical-align: 13px;*/
		width: 60%;
		height: 60%;
	}
	#homeButton {
		top: -15px;
		left: 0px;
		font-size: x-small;
	}
	#allSearch {
		float: none;
		width: 100%;
		margin-top: 90px;
		margin-left: 0px;
		margin-right: 0px;
	}
	.twoColFixLtHdr #container { 
		width: 100%;  /* using 20px less than a full 800px width allows for browser chrome and avoids a horizontal scroll bar */
		/*background: url("img/Teita_enero.jpg") #908CA5 center fixed;*/
		margin: 0 auto; /* the auto margins (in conjunction with a width) center the page */
	}
	.twoColFixLtHdr #sidebar1 {
		float: none;
		width: 100%; /* the actual width of this div, in standards-compliant browsers, or standards mode in Internet Explorer will include the padding and border in addition to the width */
		margin: 0;
		margin-top: 16px;
		padding-right: 20px;
		padding-left: 20px;
		background-color: white;
	}
	a {
		background-color: white;
	}
	a:link, a:visited, a:active {
		background-color: white;
	}
}
</style>
<!--[if IE 5]>
<style type="text/css"> 
/* place css box model fixes for IE 5* in this conditional comment */
.twoColFixLtHdr #sidebar1 { width: 230px; }
</style>
<![endif]--><!--[if IE]>
<style type="text/css"> 
/* place css fixes for all versions of IE in this conditional comment */
.twoColFixLtHdr #sidebar1 { padding-top: 30px; }
.twoColFixLtHdr #mainContent { zoom: 1; }
/* the above proprietary zoom property gives IE the hasLayout it needs to avoid several bugs */
</style>
<![endif]-->
<script src="js/literacy.js?v=0.0.2" type="text/javascript" language="javascript"></script>
</head>
<body class="twoColFixLtHdr">
<div id="container">

<div id="header">
	<div style="text-align: center; margin-left: auto; margin-right: auto; ">
	<button id="homeButton" onClick="window.open('00e-literacy.php', '_top')">Inicio</button>
		<img id="icon" src="img/ILVicon.png" style="margin-right: 10px; " alt="" /> <!--class="logo" -->
		<!--div class="membrete"-->
		 <img id="bnner" src="img/ILVwebsite-banner.png" alt="" />
		<!--/div-->
	</div>
</div><!-- end #header -->
<div class='clearfloat'></div>
	
<?php
// literacy_main table: iso_num_index	iso	rod_code	family	sub_family	minority_lang	spanish_lang
// display_items table: display_item_index	iso_num_index	iso	rod_code	no_items	thumbnail	spanishLiteracyName	variantLiteracyName	PDF_viewing	PDF_printing	PDF_cover	eBook	app	video	audio	text	zip_file

require_once './include/conn.literacy.inc.php';							// connect to the database named 'scripture'
$db = get_my_db();
//$idx = 0;
//$iso='qqq';

if (isset($_GET["iso"]) || isset($_GET["idx"])) {
    echo '<div id="allSearch"></div>';
    echo '<div id="display"></div>';
	if (isset($_GET["iso"])) {
		$iso = strtolower($_GET["iso"]);
		echo '<script>';
			echo 'display("'.$iso.'", 0);';
		echo '</script>';
	}
	else {
		$iso_num_index = $_GET["idx"];
		$iso_num_index = (int) $iso_num_index;
		echo '<script>';
			echo 'display("qqq", ' . $iso_num_index.');';
		echo '</script>';
	}
}
else {
?>
    
    <div id="allSearch">
		<?php /* -----------------------------------------------------------------------------
                        after 3 letter display the languages/alternate languages/ISO button
        -------------------------------------------------------------------------------------- */ ?>
        <div id="showLanguageID" name="showLanguageID">
            <input type="text" id="ID" title="Para buscar la página de un idioma: escriba por lo menos las primeras 3 letras del nombre o el código del idioma (ISO 639-3)." placeholder="Idioma (o código)" onKeyUp="showLanguage(this.value)" value="" />
        </div>
		<?php // language search display ?>
        <p id="LangSearch"></p>
    </div>
  
    <div id='display'>
        <div id="sidebar1">
        <?php
            $query = "SELECT * FROM literacy_main ORDER BY sub_family, iso";
            $result=$db->query($query) or die ('Query failed: ' . $db->error . '</body></html>');
            if ($result->num_rows <= 0) {
                die ('The literacy languages table is not found.</body></html>');
            }
            $lang = '';
            while ($row = $result->fetch_array()) {
                $iso = trim($row["iso"]);
                if (is_dir('literacy_data/' . $iso . '/item_1')) {
					$language=trim($row['sub_family']);
					if ($lang != $language) {
						echo '<span style="color: #716164; font-size: 1.4em; font-weight: bold;">' . $language . '</span><br />';		//#908CA5
						$lang = $language;
					}
					$family=trim($row['family']);
					$spanish_lang=trim($row['spanish_lang']);
					$iso_num_index=$row['iso_num_index'];
                    echo "<a href='#' onclick='display(\"$iso\", $iso_num_index)'>[" . $iso . '] ' . $spanish_lang . '</a><br />';
                }
            }
			echo '<br /><br />';
        ?>
        <!-- end #sidebar1 --></div>
	<!-- end #display --></div>
<?php
	echo '<script language="javascript" type="text/javascript">';
	echo 'document.getElementById("ID").focus();';
	echo '</script>';
}
?>

<!-- end #container --></div>
<div id="footer">
	<p style='background-color: white; font-size: .7em; position: fixed; left: 0; bottom: 0; width: 100%; '>&copy; <?php echo Date("Y"); ?> Instituto Lingüístico de Verano</p>
</div>
</body>
</html>