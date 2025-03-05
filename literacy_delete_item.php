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

if (isset($_GET['num'])) {	
	$no_items = $_GET["num"];
}
elseif (isset($_POST["no_items"])) {
	$no_items = $_POST["no_items"];
}
else {
	die('no num or no_items');
}
//echo $no_items . '<br />';

if (isset($_GET['iso'])) {	
	$iso = $_GET["iso"];
}
elseif (isset($_POST["iso"])) {
	$iso = $_POST["iso"];
}
else {
	die('no iso');
}

if (isset($_GET['iso_num_index'])) {	
	$iso_num_index = $_GET["iso_num_index"];
}
elseif (isset($_POST["iso_num_index"])) {
	$iso_num_index = $_POST["iso_num_index"];
}
else {
	die('no iso_num_index');
}

require_once './include/conn.literacy.inc.php';							// connect to the database named 'scripture'
$db = get_my_db();

if (isset($_POST['Delete']) || isset($_GET['Delete'])) {
	// folder to item_[number] to item_[letter]
	$globFiles=glob('./literacy_data/'.$iso.'/item_*');
	//print_r($globFiles);
	//echo '<br /><br />';
	
	foreach($globFiles as $files) {
		preg_match('/.\/literacy_data\/'.$iso.'\/item_([a-z]*)/', $files, $match);
		if ($match[1] != '') {
			$globLetters[] = $match[1];
		}
	}
	//print_r($globLetters);
	//echo '<br /><br />';
	if (!$globLetters) {
		rename('./literacy_data/'.$iso.'/item_'.$no_items, './literacy_data/'.$iso.'/item_a');
	}
	else {																// ($globLetters)
		sort($globLetters);
		$s = $globLetters[count($globLetters)-1];						// highest letter
		$letter = ++$s;													// letter + 1 = ++$s !!
		rename('./literacy_data/'.$iso.'/item_'.$no_items, './literacy_data/'.$iso.'/item_'.$letter);
	}

	foreach($globFiles as $files) {
		preg_match('/.\/literacy_data\/'.$iso.'\/item_([0-9]*)/', $files, $match);
		if ($match[1] != '') {
			$globNumbers[] = $match[1];
		}
	}
	rsort($globNumbers);												// reverse sort order
	//print_r($globNumbers);
	//echo '<br /><br />';
	$itemNumber = $no_items;
	$itemNumber++;
	while ($itemNumber <= $globNumbers[0]) {							// no_items + 1 to last no_items
		//echo $itemNumber . ' ' .  $globNumbers[0] . '<br />';
		rename('./literacy_data/'.$iso.'/item_'.(string)$itemNumber, './literacy_data/'.$iso.'/item_'.(string)($itemNumber-1));
		$itemNumber++;
	}
	
	$db->query("DELETE FROM display_items WHERE iso_num_index = $iso_num_index AND no_items = $no_items");
	$query="SELECT * FROM display_items WHERE iso_num_index = $iso_num_index AND no_items >= $no_items ORDER BY no_items";
	$result_submit=$db->query($query) or die ('Query failed: ' . $db->error . '</body></html>');
	if ($result_submit->num_rows >= 1) {
		while ($row = $result_submit->fetch_array()) {
			$no_items_temp=trim($row['no_items']);
			$db->query("UPDATE display_items SET no_items = $no_items_temp - 1 WHERE iso_num_index = $iso_num_index AND no_items = $no_items_temp ORDER BY no_items");
			//echo 'db->query("UPDATE display_items SET no_items = $no_items_temp - 1 ...<br />';
		}
	}
	header('Location: literacy_delete.php?iso_num_index='.$iso_num_index.'&iso='.$iso.'&submit=Delete');	// If you haven't output (echo) ANYTHING you can always do this.
	echo 'Deleted the item.';
	exit;
}

$query="SELECT * FROM display_items WHERE iso_num_index = $iso_num_index AND no_items = $no_items";
$result=$db->query($query);
$num=$result->num_rows;

if ($num >= 1) {
	?>
	<!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>ILV México Literacy Delete an Item</title>
	<link rel="stylesheet" type="text/css" href="css/all.css" />
	<link rel="stylesheet" type="text/css" href="css/main.css" />
	</head>
	<body>
	<?php
	echo '<form action="literacy_delete_item.php" method="post">';		// the "form"s are hidden
	echo '<input type="hidden" name="iso_num_index" value="'.$iso_num_index.'" />';
	echo '<input type="hidden" name="iso" value="'.$iso.'" />';
	echo '<input type="hidden" name="no_items" value="'.$no_items.'" />';
	echo '<div style="font-size: 1.6em; color: navy; font-weight: bold; margin-bottom: 12px; ">Are you sure that you want to delete Item #'.$no_items.' ['.$iso.']?</div>';
	$row = $result->fetch_array();
	$text = $row['text'];
	if ($text != '') {
		$title = '';
		$sub_title = '';
		$target_dir = 'literacy_data/'.$iso.'/item_'.$no_items;										// set the server folder
		$text_file = file($target_dir.'/'.$text, FILE_USE_INCLUDE_PATH | FILE_SKIP_EMPTY_LINES);	// read a file in an array
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
			echo '<div style="font-size: 1.5em; color: red; ">' . $title . '</div>';
		}
		if ($sub_title != '') {
			echo '<div style="font-size: 1.4em; color: red; font-style: italic; margin-bottom: 4px; ">' . $sub_title . '</div>';
		}
	}
	echo '<div >Note: Every item up from Item #' . $no_items . ' will automatically have the item number go down one.</div><br />';
	echo '<div style="margin-left: auto; margin-right: auto; "><input type="submit" name="Delete" value="Delete" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '<input type="button" onclick="location.href="literacy_delete.php?iso_num_index='.$iso_num_index.'" value="Cancel" /><br /><br /></div>';
	echo '</form>';
	echo '<br />';
	echo '</body>';
	echo '</html>';
}
else {
	die('Hack!');
}

//header('Location: literacy_delete.php?iso_num_index='.$iso_num_index);			// If you haven't output (echo) ANYTHING you can always do this.

// If exit was not here, the form page would be appended to the confirmation page.
//exit;
?>
