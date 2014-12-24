<?php
session_start();

if($_SESSION["loggedAs"] == "" || $_SESSION["adminType"] != "Super") // if the user is not logged in, does not have the proper clearance, or is using a blank id
{
	header("Location: ../admin.php"); // redirect to the login page
} // end of if

$valid = true; // create a flag variable for form validity

// set variables from the form
$login    = $_POST["login_can"];
$pass1    = $_POST["pass1_can"];
$pass2    = $_POST["pass2_can"];
$fname    = $_POST["fName_can"];
$lname    = $_POST["lName_can"];
$email    = $_POST["email_can"];

// add variables as session variables
$_SESSION["loginCan"]      = $login;
$_SESSION["fnameCan"]      = $fname;
$_SESSION["lnameCan"]      = $lname;
$_SESSION["emailCan"]      = $email;
$_SESSION["isReviewer"]    = $reviewer;

$loginPassEx = "/^([a-zA-Z0-9]){1,}$/"; // create an expression to test against the name

if(!preg_match($loginPassEx, $login) || $login == "") // if the name fails the expression or is blank
{
	$valid = false; // show that the form is not valid
	
	$_SESSION["loginCan"]       = "";                    // clear the name from the form
	
	$error .= "Enter a valid Login Name."; // create the error message
} // end of if

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
	
	$error .= "Enter a Passwords did not match."; // create the error message
} // end of else

$nameEx = "/^([a-zA-Z ]){1,}$/"; // create an expression to test against the name

if(!preg_match($nameEx, $fname) || $fname == "") // if the name fails the expression or is blank
{
	$valid = false; // show that the form is not valid
	
	if($error != "") // if the error message is not blank
	{
		$error .= "<br />"; // add a break
	} // end of if
	
	$_SESSION["fnameCan"]       = "";                    // clear the name from the form
	
	$error .= "Enter a valid First Name."; // create the error message
} // end of if

if(!preg_match($nameEx, $lname) || $lname == "") // if the name fails the expression or is blank
{
	$valid = false; // show that the form is not valid
	
	if($error != "") // if the error message is not blank
	{
		$error .= "<br />"; // add a break
	} // end of if
	
	$_SESSION["lnameCan"]       = "";                    // clear the name from the form
	
	$error .= "Enter a valid Last Name."; // create the error message
} // end of if

$emailEx = "/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/"; // create an expression to test against the email

if(!preg_match($emailEx, $email) || $email == "") // if the email fails the expression or is blank
{
	$valid = false; // show that the form is not valid
	
	$_SESSION["emailCan"] = ""; // clear the email from the form
	
	if($error != "") // if the error message is not blank
	{
		$error .= "<br />"; // add a break
	} // end of if
	
	$error .= "Enter a valid email address."; // create the error message
} // end of if

$phoneEx = "/^([\d]){3}([\-]){1}([\d]){3}([\-]){1}([\d]){4}$/"; // create an expression to test against the phone

if($valid) // if the form is valid
{
	include("../includes/openConn.php"); // open the connection
	
	$sql = "SELECT AdminID FROM Admin WHERE AdminID='".$login."'"; // get the id numbers from all of the inquiries
	
	$result = mysql_query($sql); // send the sql request
	
	if(empty($result)) // if the result is empty
	{
		$num_results = 0; // set the number of results as zero
	} // end of if
	else
	{
		$num_results = mysql_num_rows($result); // set the number of results
	} // end of else
	
	if($num_results != 0) // if the number of results is greater than zero
	{
		$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#c00;font-weight:bolder;border:3px solid #900;border-radius:10px;padding:5px;\">Username is already in use. Please select another.</p>"; // set the message
		
		include("../includes/closeConn.php");
	} // end of if
	else
	{
		$sql = "INSERT INTO Admin(AdminID, FirstName, LastName, Email, Password, AdminType) VALUES('".$login."', '".$fname."', '".$lname."', '".$email."', '".$password."', 'Regular')"; // create the sql statement
		
		$result = mysql_query($sql); // get the sql request
		
		include("../includes/closeConn.php"); // close the database connection

		//mail($to, $subject, $message, $headers); // send the email

		// clear session variables
		$_SESSION["loginCan"] = "";
		$_SESSION["fnameCan"]    = "";
		$_SESSION["lnameCan"]    = "";
		$_SESSION["emailCan"]    = "";
		$_SESSION["error"]       = "<p style=\"width:290px;color:#fff;background:#0c0;font-weight:bolder;border:3px solid #090;border-radius:10px;padding:5px;\">Admin created successfully.</p>";
	}
} // end of if
else
{
	$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#c00;font-weight:bolder;border:3px solid #900;border-radius:10px;padding:5px;\">".$error."</p>";
}

include("../includes/cleanUp.php"); // clear variables

header("Location: ../admin.php"); // redirect to contact page
?>