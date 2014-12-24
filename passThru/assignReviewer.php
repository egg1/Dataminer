<?php
session_start();

$appID = $_POST["application"];
$user  = $_POST["user"];

if($_SESSION["loggedAs"] == "" || $_SESSION["adminType"] != "Super" || $appID == "" || $user == "") // if the user is not logged in, does not have the proper clearance, or is using a blank id
{
	header("Location: ../organizer.php?id=".$appID); // redirect to the login page
} // end of if

include("../includes/openConn.php");

$sql = "SELECT Email FROM ReviewerApplication WHERE Email='".$user."' AND ApplicationID='".$appID."';";

$result = mysql_query($sql);

if(empty($result))
{
	$num_results = 0;	
}
else
{
	$num_results = mysql_num_rows($result);	
}

if($num_results == 0)
{
	$sql = "INSERT INTO ReviewerApplication(Email, ApplicationID, Rating, ReviewJustification) VALUES ('".$user."', '".$appID."', '999', '');";
	
	$result = mysql_query($sql);
	
	$_SESSION["error"] = "This application has been assigned successfully.";
}
else
{
	$_SESSION["error"] = "This application is already assigned to this reviewer.";	
}

include("../includes/closeConn.php"); // close the database
include("../includes/cleanUp.php");   // delete the variables

header("Location: ../organizer.php?id=".$appID); // redirect to the login page
?>