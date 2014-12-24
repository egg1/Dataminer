<?php
session_start();

$id = $_POST["admin-delete"]; // get the id from the url query string

if($_SESSION["loggedAs"] == "" || $_SESSION["adminType"] != "Super" || $id == "") // if the user is not logged in, does not have the proper clearance, or is using a blank id
{
	header("Location: ../admin.php"); // redirect to the login page
} // end of if

if($_SESSION["loggedAs"] == $id)
{
	header("Location: ../admin.php");
}

include("../includes/openConn.php"); // open the connection

$sql = "SELECT AdminID, FirstName, LastName, Email, Password FROM Admin WHERE AdminID='".$id."'"; // create the query string

$result = mysql_query($sql); // enter the query string

if(empty($result)) // if no results are found
{
	$num_results = 0; // show no results
} // end of if
else
{
	$num_results = mysql_num_rows($result); // show results
} // end of else

if($num_results != 0) // if results are found
{
	$sql = "DELETE FROM Admin WHERE AdminID='".$id."'"; // create the query string
	
	$result = mysql_query($sql); // enter the query string
	
	$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#0c0;font-weight:bolder;border:3px solid #090;border-radius:10px;padding:5px;\">Admin deleted successfully.</p>";
} // end of if

include("../includes/closeConn.php"); // close the database
include("../includes/cleanUp.php");   // delete the variables

header("Location: ../admin.php"); // redirect to inquiry page
?>