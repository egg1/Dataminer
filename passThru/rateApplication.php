<?php
session_start();

$appID         = $_POST["application"];
$rating        = $_POST["rating"];
$justification = replaceText($_POST["justification"]);

$_SESSION["rating"]        = $rating;
$_SESSION["justification"] = $justification;

if($_SESSION["loggedAs"] == "" || $_SESSION["userType"] != "Reviewer" || $appID == "") // if the user is not logged in, does not have the proper clearance, or is using a blank id
{
	header("Location: ../review.php?id=".$appID); // redirect to the login page
} // end of if

$valid = true;

$commentEx = "/^([a-zA-Z0-9.,;?!&# ]{1,})$/";
$ratings   = Array(-3,-2,-1,0,1,2,3);

if(!validate($rating, $ratings))
{
	$_SESSION["rating"] = "";
	$error .= "Enter a valid Rating.<br />";
	$valid              = false;
}

if(!preg_match($commentEx, $justification))
{
	$_SESSION["justification"] = "";
	$error        .= "Enter a valid Justification.<br />";
	$valid                     = false;
}

if($valid)
{
	include("../includes/openConn.php");
	
	$sql = "SELECT ApplicationID FROM ReviewerApplication WHERE Email='".$_SESSION["loggedAs"]."' AND ApplicationID='".$appID."';";
	
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
		$sql = "UPDATE ReviewerApplication SET Rating='".$rating."', ReviewJustification='".$justification."' WHERE Email='".$_SESSION["loggedAs"]."' AND ApplicationID='".$appID."';";
		
		$result = mysql_query($sql);
		
		$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#0c0;font-weight:bolder;border:3px solid #090;border-radius:10px;padding:5px;\">This application has been successfully reviewed.</p>";
		
		$sql = "SELECT Rating FROM ReviewerApplication WHERE ApplicationID='".$appID."';";
		
		$result = mysql_query($sql);
		
		if(empty($result))
		{
			$num_results = 0;	
		}
		else
		{
			$num_results = mysql_num_rows($result);	
		}
		
		$sum   = 0;
		$count = 0;
		
		for($i=0; $i<$num_results; $i++)
		{
			$row = mysql_fetch_array($result);
			
			if($row["Rating"] != 999)
			{
				$sum += $row["Rating"];
				$count++;
			}
		}
		
		$average = $sum / $count;
		
		$sql = "UPDATE Application Set AverageRating='".$average."' WHERE ApplicationID='".$appID."';";
		
		$result = mysql_query($sql);
	}
	else
	{
		$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#c00;font-weight:bolder;border:3px solid #900;border-radius:10px;padding:5px;\">You do not access to review this application.</p>";
	}
	
	include("../includes/closeConn.php"); // close the database
}
else
{
	$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#c00;font-weight:bolder;border:3px solid #900;border-radius:10px;padding:5px;\">".$error."</p>";	
}

include("../includes/cleanUp.php");   // delete the variables

header("Location: ../review.php?id=".$appID); // redirect to the login page


function validate($data, $array)
{
	$valid = false;
	
	for($i=0; $i<count($array); $i++)
	{
		if($array[$i] == $data)
		{
			$valid = true; // the input is valid
		} // end of if
	} // end of for

	return $valid;
}

function replaceText($d)
{
	$d = str_replace("\\", "", $d);
	$d = str_replace("\"", "&quot;", $d);
	$d = str_replace("'", "&apos;",$d);
	$d = str_replace("(", "&#40;", $d);
	$d = str_replace(")", "&#41;", $d);
	return $d;
}
?>