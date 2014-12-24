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

$validString = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

$newPassString = get_random_string($validString, 6);

$salt     = md5($userName);            // create a salt variable
$password = md5($newPassString.$salt); // generate a md5 password

include("../includes/openConn.php");

$sql = "SELECT FirstName FROM User WHERE Email='".$userName."';";

$result = mysql_query($sql);

if(empty($result))
{
	$num_results = 0;
}
else
{
	$num_results = mysql_num_rows($result);
}

if($num_results != 0)
{
	$row = mysql_fetch_array($result);
	
	$sql = "UPDATE User SET Password='".$password."', PasswordReset='1' WHERE Email = '".$userName."';";
	
	$result = mysql_query($sql);
	
	$to      = $userName;
	$subject = "BPDM 2013 User Account Password Reset";
	
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: Data Mining Workshop Admin <webmaster@dataminingshop.com>\r\n";
	
	$body = "Hi ".$row["FirstName"].", <br /><br />";
	
	$body .= "The passord for your BPDM 2013 user account has been reset.  Your new account password is ".$newPassString.".";
	
	$body .= "<br />You can log in with your information here:<br />";
	$body .= "<a href = \"http://www.dataminingshop.com/2013/applicantLogin.php\">http://www.dataminingshop.com/2013/applicantLogin.php</a>.<br /><br /><br />";
	$body .= "<br />After logging in you will be prompted to create a new password.<br />";
	$body .= "Best regards, <br /><br />";
	$body .= "Workshop organizers";
	
	mail($to, $subject, $body, $headers);
	
	$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#0c0;font-weight:bolder;border:3px solid #090;border-radius:10px;padding:5px;\">Your password has been reset and a confirmation email has been sent.</p>";

	header("Location: ../applicantLogin.php");
}
else
{
	$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#c00;font-weight:bolder;border:3px solid #900;border-radius:10px;padding:5px;\">Your account could not be found.</p>";

	header("Location: ../retrievePass.php");
}

include("../includes/closeConn.php");

function get_random_string($valid_chars, $length)
{
    // start with an empty random string
    $random_string = "";

    // count the number of chars in the valid chars string so we know how many choices we have
    $num_valid_chars = strlen($valid_chars);

    // repeat the steps until we've created a string of the right length
    for ($i = 0; $i < $length; $i++)
    {
        // pick a random number from 1 up to the number of valid chars
        $random_pick = mt_rand(1, $num_valid_chars);

        // take the random character out of the string of valid chars
        // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
        $random_char = $valid_chars[$random_pick-1];

        // add the randomly-chosen char onto the end of our string so far
        $random_string .= $random_char;
    }

    // return our finished random string
    return $random_string;
}
?>