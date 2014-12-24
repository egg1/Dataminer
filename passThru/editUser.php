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

$usage = $_GET["u"]; // get the usage

$valid = true; // create a flag variable for form validity

$loginPassEx = "/^([a-zA-Z0-9]){1,}$/"; // create an expression to test against the name


if($usage == "info") // if the useage is editing info
{
	// set variables from the form
	$login  = $_POST["id"];
	$fname  = $_POST["fName"];
	$lname  = $_POST["lName"];
	$email  = $_POST["email"];
	
	if(!preg_match($loginPassEx, $login) || $login == "") // if the name fails the expression or is blank
	{
		$valid = false; // show that the form is not valid
		
		$_SESSION["login"]   = "";                    // clear the name from the form
		
		$error .= "Enter a valid Login Name."; // create the error message
	} // end of if
	
	$nameEx = "/^([a-zA-Z ]){1,}$/"; // create an expression to test against the name

	if(!preg_match($nameEx, $fname) || $fname == "") // if the name fails the expression or is blank
	{
		$valid = false; // show that the form is not valid
		
		if($error != "") // if the error message is not blank
		{
			$error .= "<br />"; // add a break
		} // end of if
		
		$_SESSION["fname"]   = "";                    // clear the name from the form
		
		$error .= "Enter a valid First Name."; // create the error message
	} // end of if

	if(!preg_match($nameEx, $lname) || $lname == "") // if the name fails the expression or is blank
	{
		$valid = false; // show that the form is not valid
		
		if($error != "") // if the error message is not blank
		{
			$error .= "<br />"; // add a break
		} // end of if
		
		$_SESSION["lname"]   = "";                    // clear the name from the form
		
		$error .= "Enter a valid Last Name."; // create the error message
	} // end of if

	$emailEx = "/^([^0-9][a-zA-Z0-9_]+)*([@][a-zA-Z0-9_]+)([.][a-zA-Z0-9_]+)$/"; // create an expression to test against the email

	if(!preg_match($emailEx, $email) || $email == "") // if the email fails the expression or is blank
	{
		$valid = false; // show that the form is not valid
		
		$error = ""; // clear the email from the form
		
		if($error != "") // if the error message is not blank
		{
			$error .= "<br />"; // add a break
		} // end of if
		
		$error .= "Enter a valid email address."; // create the error message
	} // end of if
	
	if($valid)
	{
		include("../includes/openConn.php");
		
		$sql = "UPDATE Admin SET FirstName = '".$fname."', LastName = '".$lname."', Email = '".$email."' WHERE AdminID = '".$login."';";
		
		$result = mysql_query($sql);
		
		$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#0c0;font-weight:bolder;border:3px solid #090;border-radius:10px;padding:5px;\">Administrator Information Changed!</p>";
		
		include("../includes/closeConn.php");
		
		$_SESSION["userFName"] = $fname;
		$_SESSION["userLName"] = $lname;
		$_SESSION["email"]     = $email;
	}
	else
	{
		$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#c00;font-weight:bolder;border:3px solid #900;border-radius:10px;padding:5px;\">".$error."</p>";
	}
} // end of if
else if($usage == "pass") // if the usage is editing the password
{
	// set variables from the form
	$login   = $_POST["idp"];
	$oldPass = $_POST["opass"];
	$pass1   = $_POST["pass1"];
	$pass2   = $_POST["pass2"];
	
	if(!preg_match($loginPassEx, $login) || $login == "") // if the name fails the expression or is blank
	{
		$valid = false; // show that the form is not valid
		
		$_SESSION["login"]   = "";                    // clear the name from the form
		
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
		
		$error .= "Entered Passwords did not match."; // create the error message
	} // end of else
	
	if($valid) // password change is valid
	{
		include("../includes/openConn.php");
		
		$login = str_replace(" ", "", $_POST["idp"]); // get the username
		$salt     = md5($login);                           // create a salt variable
		$oldPass = md5($_POST["opass"].$salt);            // generate a md5 password

		$sql = "SELECT Password FROM Admin WHERE AdminID='".$login."' AND Password='".$oldPass."';"; // check to make sure the passwords and login name match
		
		$result = mysql_query($sql); // get the results
		
		if(empty($result)) // if no results
		{
			$num_result = 0; // show no results
		} // end of if
		else
		{
			$num_result = mysql_num_rows($result); // show results
		} // end of else
		
		if($num_result > 0) // if there are results
		{
			$sql = "UPDATE Admin SET Password = '".$password."' WHERE AdminID = '".$login."';"; // create the sql string
			
			$result = mysql_query($sql); // enter the sql string
			
			$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#0c0;font-weight:bolder;border:3px solid #090;border-radius:10px;padding:5px;\">Password Change Successful!</p>"; // show the success message
		} // end of if
		else
		{
			$error .= "Password Change Failed!"; // show the error message
		} // end of else
		
		include("../includes/closeConn.php");
	} // end of if
	else
	{
		$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#c00;font-weight:bolder;border:3px solid #900;border-radius:10px;padding:5px;\">".$error."</p>";
	}
} // end of else if

include("../includes/cleanUp.php"); // clear variables

header("Location: ../admin.php"); // redirect to contact page
?>