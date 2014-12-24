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

$title    = $_POST["title"];
$desc     = $_POST["desc"];
$link     = $_POST["link"];
$author   = $_SESSION["email"];

$_SESSION["titleCan"] = $title;
$_SESSION["descCan"]  = $desc;
$_SESSION["linkCan"]  = $link;

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

if($isValid)
{
	$counter = 0;
	
	foreach($idList as $item)
	{
		$idNumber[$counter] = $item->getElementsByTagName("guid")->item(0)->nodeValue;
		/*
		if(intval($item->getAttributeNode('id')->value) > $maxId)
		{
			$maxId = intval($item->getAttributeNode('id')->value);	
		}*/
		$counter++;
	}
	
	sort($idNumber);
	
	$maxId = 0;
	
	for($i=0; $i<count($idNumber); $i++)
	{
		if($maxId == $idNumber[$i])
		{
			$maxId++;
		}
	}
	
	$today    = getdate(); // get today's date
	$pub_date = $today[mon]."-".$today[mday]."-".$today[year]; // add a zero to the month and day of the month
	
	$newRss = $doc->createElement("item");
	
	$newTitle  = $doc->createElement("title", $title);
	$newDesc   = $doc->createElement("description", $desc);
	$newGuid   = $doc->createElement("guid", $maxId);
	$newLink   = $doc->createElement("link", $link);
	$newAuthor = $doc->createElement("author", $author);
	$newDate   = $doc->createElement("pubDate", $pub_date);
	
	$newRss->appendChild($newTitle);
	$newRss->appendChild($newDesc);
	$newRss->appendChild($newGuid);
	$newRss->appendChild($newLink);
	$newRss->appendChild($newAuthor);
	$newRss->appendChild($newDate);
	
	$root->item(0)->appendChild($newRss);
	
	$doc->save("../rss/news.rss");
	
	include("../includes/cleanUp.php");
	
	$_SESSION["error"]    = "<p style=\"width:290px;color:#fff;background:#0c0;font-weight:bolder;border:3px solid #090;border-radius:10px;padding:5px;\">RSS Message Created Successfully!</p>";
	$_SESSION["titleCan"] = "";
	$_SESSION["descCan"]  = "";
	$_SESSION["linkCan"]  = "";

	header("Location: ../admin.php");
}
else
{
	$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#c00;font-weight:bolder;border:3px solid #900;border-radius:10px;padding:5px;\">".$error."</p>";
	
	header("Location: ../admin.php");	
}
?>