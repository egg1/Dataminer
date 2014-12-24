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

$usage = $_GET["u"]; // get the usage

$valid = true; // create a flag variable for form validity

$loginPassEx = "/^([a-zA-Z0-9]){1,}$/"; // create an expression to test against the name

$login   = $_POST["username"];
$pass1   = $_POST["pass1"];
$pass2   = $_POST["pass2"];

if(!preg_match($loginPassEx, $pass1) || $pass1 == "") // if the password fails the expression or is blank
{
	$valid = false; // show that the form is not valid
	
	if($error != "") // if the error message is not blank
	{
		$error .= "<br />"; // add a break
	} // end of if
	
	$error .= "Enter a valid Password."; // create the error message
} // end of if

if($pass1 == $pass2) // if the two passwords do not match
{
	$salt     = md5($login);       // create a salt variable
	$password = md5($pass1.$salt); // generate a md5 password
} // end of if
else
{
	$valid = false; // show that the form is not valid
	
	if($error != "") // if the error message is not blank
	{
		$error .= "<br />"; // add a break
	} // end of if
	
	$error .= "Entered Passwords did not match."; // create the error message
} // end of else

if($valid) // password change is valid
{
	include("../includes/openConn.php");
	
	$sql = "UPDATE User SET Password = '".$password."', PasswordReset='0' WHERE Email = '".$login."';"; // create the sql string
	
	$result = mysql_query($sql); // enter the sql string
	
	$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#0c0;font-weight:bolder;border:3px solid #090;border-radius:10px;padding:5px;\">Password Change Successful!</p>"; // show the success message
	
	$_SESSION["passReset"] = 0;
	
	include("../includes/closeConn.php");
	
	include("../includes/cleanUp.php"); // clear variables

	header("Location: ../application.php"); // redirect to contact page
} // end of if
else
{
	$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#c00;font-weight:bolder;border:3px solid #900;border-radius:10px;padding:5px;\">".$error."</p>";
	
	include("../includes/cleanUp.php"); // clear variables

	header("Location: ../userChangePassword.php"); // redirect to contact page
}
?>