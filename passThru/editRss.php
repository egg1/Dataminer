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

$isValid = true;

$id       = $_POST["rss-edit"];
$title    = $_POST["e-title"];
$desc     = $_POST["e-desc"];
$link     = $_POST["e-link"];
$author   = $_SESSION["email"];

$doc = new DOMDocument();        // creates new document object model
$doc->load("../rss/news.rss"); // loads the xml file into the document
$xpath = new DOMXpath($doc);     // loads the document into the xpath
$root = $xpath->query("channel");   // sets the root to the document element

$idList = $xpath->query("channel/item");

$idNumber = array();
$counter  = 0;

if($title == "")
{
	$isValid = false;
	$error .= "Enter a valid Title.<br />";
}

if($desc == "")
{
	$isValid = false;
	$error .= "Enter a valid Description.<br />";
}

if($link == "")
{
	$isValid = false;
	$error .= "Enter a valid Link.<br />";
}

if($isValid && $id != "")
{
	foreach($idList as $item)
	{
		if($item->getElementsByTagName("guid")->item(0)->nodeValue == $id)
		{
			$editNode = $item;
		}
	}

	$today    = getdate(); // get today's date
	$pub_date = $today[mon]."-".$today[mday]."-".$today[year]; // add a zero to the month and day of the month
	
	$editNode->getElementsByTagName("title")->item(0)->nodeValue       = $title;
	$editNode->getElementsByTagName("description")->item(0)->nodeValue = $desc;
	$editNode->getElementsByTagName("link")->item(0)->nodeValue        = $link;
	$editNode->getElementsByTagName("author")->item(0)->nodeValue      = $author;
	$editNode->getElementsByTagName("pubDate")->item(0)->nodeValue     = $pub_date;
	
	$doc->save("../rss/news.rss");
	
	include("../includes/cleanUp.php");
	
	$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#0c0;font-weight:bolder;border:3px solid #090;border-radius:10px;padding:5px;\">RSS Message Edited Successfully!</p>";
	
	header("Location: ../admin.php");
}
else
{
	$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#c00;font-weight:bolder;border:3px solid #900;border-radius:10px;padding:5px;\">".$error."</p>";
	
	header("Location: ../admin.php");	
}
?>