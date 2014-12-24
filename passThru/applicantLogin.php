<?php
session_start();

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include("../includes/constants.php");

$isValid  = false; // create a flag variable to show that the form has not been validated

$userName = str_replace(" ", "", $_POST["username"]); // get the username
$userName = strtolower($userName);
$salt     = md5($userName);                           // create a salt variable
$password = md5($_POST["password"].$salt);            // generate a md5 password

$error = ""; // clear the login error message

if($userName == "") // if the username is blank
{
	$error .= "* User ID is Blank.<br />"; // create the login error message
} // end of if

if($password == "") // if the password is blank
{
	$error .= "* Password is Blank.<br />"; // create the login error message
} // end of if

include("../includes/openConn.php"); // open the database connection

$sql = "SELECT Email, FirstName, LastName, UserType, PasswordReset FROM User WHERE Email='".$userName."' AND Password='".$password."'"; // create the sql statement

$result = mysql_query($sql); // send in the request

if(empty($result)) // if the request is empty
{
	$num_results = 0; // show that no results were found login failed
} // end of if
else
{
	$num_results = mysql_num_rows($result); // get the number of results
} // end of else

if($num_results != 0) // if the number of results is not zero
{
	$row = mysql_fetch_array($result); // get the data from the row
	
	$isValid = true; // show that the user got in
	
	// add table data as login information
	$_SESSION["loggedAs"]  = $row["Email"];
	$_SESSION["userFName"] = $row["FirstName"];
	$_SESSION["userLName"] = $row["LastName"];
	$_SESSION["userType"]  = $row["UserType"];
	$_SESSION["passReset"] = $row["PasswordReset"];
} // end of if
else
{
	$error = "* Invalid Credentials.</br >"; // create the login error message
} // end of else

include("../includes/closeConn.php"); // close the database connection

if($isValid) // if the entry was valid
{
	$_SESSION["error"] = ""; // clear the login error message
	
	include("../includes/cleanUp.php"); // clean up variables
	
	$_SESSION["timeout"]   = time() + $TIME_OUT;
	
	if($_SESSION["last_page"] == "")
	{
		if($_SESSION["passReset"] != 1)
		{
			if($_SESSION["userType"] == "Applicant")
			{
				header("Location: ../application.php"); // redirect to index page
			}
			else if($_SESSION["userType"] == "Reviewer")
			{
				header("Location: ../review.php");	
			}
		}
		else
		{
			header("Location: ../userChangePassword.php");
		}
	}
	else
	{
		header("Location: ../".$_SESSION["last_page"]);
	}
} // end of if
else
{
	$error .= "* Login Failed!<br />"; // create the login error message
	
	$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#c00;font-weight:bolder;border:3px solid #900;border-radius:10px;padding:5px;\">".$error."</p>";
	
	include("../includes/cleanUp.php"); // clean up variables

	header("Location: ../applicantLogin.php"); // redirect to index page
} // end of else
exit;
?>