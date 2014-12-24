<?php
session_start();

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include("../includes/constants.php");

$cur_time = time();

if($_SESSION["loggedAs"] == "")
{
	header("Location: ../login.php");
}

if($_SESSION["adminType"] != "Super" && $_SESSION["adminType"] != "Regular")
{
	header("Location: ../index.php");
}

if($_SESSION["loggedAs"] != "" && $cur_time > $_SESSION["timeout"])
{
	//destroy the session (reset)
	session_destroy();
	header("Location: login.php");
}
else
{
	//set new time
	$_SESSION["last_page"] = "";
	$_SESSION["timeout"]   = time() + $TIME_OUT;
}

$id = $_POST["rss-delete"];

if($id == "")
{
	header("Location: ../admin.php");
}

$doc = new DOMDocument();        // creates new document object model
$doc->load("../rss/news.rss"); // loads the xml file into the document
$xpath = new DOMXpath($doc);     // loads the document into the xpath
$root = $xpath->query("channel");   // sets the root to the document element

$idList = $xpath->query("channel/item");

foreach($idList as $item)
{
	if($item->getElementsByTagName("guid")->item(0)->nodeValue == $id)
	{
		$deleteNode = $item;
	}
}

$root->item(0)->removeChild($deleteNode);

$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#0c0;font-weight:bolder;border:3px solid #090;border-radius:10px;padding:5px;\">RSS Message Deleted Successfully!</p>";

$doc->save("../rss/news.rss");

include("../includes/cleanUp.php");

header("Location: ../admin.php");
?>