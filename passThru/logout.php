<?php
session_start();

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// delete session variables
$_SESSION["loggedAs"]  = "";
$_SESSION["userFName"] = "";
$_SESSION["userLName"] = "";
$_SESSION["email"]     = "";
$_SESSION["adminType"] = "";
	
// cleanup variables
include("../includes/cleanUp.php");

//End the session
session_unset();
session_destroy();
//Redirect

header("Location: ../index.php");
?>