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

$id = $_POST["page"];

if($id != "")
{
	header("Location: ../editPage.php?id=".$id);
}
else
{
	header("Location: ../admin.php");	
}
?>