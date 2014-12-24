<?php
session_start();

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include("../includes/constants.php");

if($_SESSION["loggedAs"] == "" || $_SESSION["userType"] != "Applicant")
{
	header("Location: ../applicantLogin.php");
}

if($_SESSION["loggedAs"] != $_POST["email"])
{
	header("Location: ../applicationLogin.php");
}

$email                   = $_POST["email"];
$fName                   = $_POST["fName"];
$lName                   = $_POST["lName"];
$add1                    = $_POST["add1"];
$add2                    = $_POST["add2"];
$city                    = $_POST["city"];
$state                   = $_POST["state"];
$zip                     = $_POST["zip"];
$country                 = $_POST["country"];
$phone                   = $_POST["phone"];
$citizenship             = $_POST["citizenship"];
$nationality             = $_POST["nationality"];
$gender                  = $_POST["gender"];
$race                    = $_POST["race"];
$ethnicity               = $_POST["ethnicity"];
$underrepresentedGroup   = replaceText($_POST["underrepresentedGroup"]);
$org                     = $_POST["org"];
$dept                    = $_POST["dept"];
$currDegree              = $_POST["currDegree"];
$highestDegree           = $_POST["highestDegree"];
$areaOfInterest          = replaceText($_POST["areaOfInterest"]);
$fullTimeStudent         = $_POST["fullTimeStudent"];
$facebookName            = $_POST["facebookName"];
$linkedInName            = $_POST["linkedInName"];
$twitterName             = $_POST["twitterName"];
$previousBPDMAttend      = $_POST["previousBPDMAttend"];
$previousACMSIGKDDAttend = $_POST["previousACMSIGKDDAttend"];
$ACMSIGKDDAttendYear     = $_POST["ACMSIGKDDAttendYear"];
$ACMSIGKDDApplication    = replaceText($_POST["ACMSIGKDDApplication"]);
$otherWorkshops          = replaceText($_POST["otherWorkshops"]);
$dietaryRestrictions     = replaceText($_POST["dietaryRestrictions"]);
$smokingPreference       = $_POST["smokingPreference"];
$isLocal                 = $_POST["isLocal"];
$hotelRequests           = replaceText($_POST["hotelRequests"]);
$flightArrival           = $_POST["flightArrival"];
$hotelArrival            = $_POST["hotelArrival"];
$flightDeparture         = $_POST["flightDeparture"];
$hotelDeparture          = $_POST["hotelDeparture"];
$volunteer               = $_POST["volunteer"];
$learnAboutBPDM          = replaceText($_POST["learnAboutBPDM"]);
$fundingNeed             = replaceText($_POST["fundingNeed"]);
$motivation              = replaceText($_POST["motivation"]);
$interests               = replaceText($_POST["interests"]);
$aspirations             = replaceText($_POST["aspirations"]);

if($zip == "#####")
{
	$zip = "";
}

if($phone == "###-###-####")
{
	$phone = "";
}

$zip   = preg_replace('/[^0-9]/i', '', $zip);
$phone = preg_replace('/[^0-9]/i', '', $phone);

if($flightArrival == "YYYY-MM-DD")
{
	$flightArrival = "";
}

if($hotelArrival == "YYYY-MM-DD")
{
	$hotelArrival = "";
}

if($flightDeparture == "YYYY-MM-DD")
{
	$flightDeparture = "";
}

if($hotelDeparture == "YYYY-MM-DD")
{
	$hotelDeparture = "";
}

$_SESSION["fName"]                   = $fName;
$_SESSION["lName"]                   = $lName;
$_SESSION["add1"]                    = $add1;
$_SESSION["add2"]                    = $add2;
$_SESSION["city"]                    = $city;
$_SESSION["state"]                   = $state;
$_SESSION["zip"]                     = $zip;
$_SESSION["country"]                 = $country;
$_SESSION["phone"]                   = $phone;
$_SESSION["citizenship"]             = $citizenship;
$_SESSION["nationality"]             = $nationality;
$_SESSION["gender"]                  = $gender;
$_SESSION["race"]                    = $race;
$_SESSION["ethnicity"]               = $ethnicity;
$_SESSION["underrepresentedGroup"]   = $underrepresentedGroup;
$_SESSION["org"]                     = $org;
$_SESSION["dept"]                    = $dept;
$_SESSION["currDegree"]              = $currDegree;
$_SESSION["highestDegree"]           = $highestDegree;
$_SESSION["areaOfInterest"]          = $areaOfInterest;
$_SESSION["fullTimeStudent"]         = $fullTimeStudent;
$_SESSION["facebookName"]            = $facebookName;
$_SESSION["linkedInName"]            = $linkedInName;
$_SESSION["twitterName"]             = $twitterName;
$_SESSION["previousBPDMAttend"]      = $previousBPDMAttend;
$_SESSION["previousACMSIGKDDAttend"] = $previousACMSIGKDDAttend;
$_SESSION["ACMSIGKDDAttendYear"]     = $ACMSIGKDDAttendYear;
$_SESSION["ACMSIGKDDApplication"]    = $ACMSIGKDDApplication;
$_SESSION["otherWorkshops"]          = $otherWorkshops;
$_SESSION["dietaryRestrictions"]     = $dietaryRestrictions;
$_SESSION["smokingPreference"]       = $smokingPreference;
$_SESSION["isLocal"]                 = $isLocal;
$_SESSION["hotelRequests"]           = $hotelRequests;
$_SESSION["flightArrival"]           = $flightArrival;
$_SESSION["hotelArrival"]            = $hotelArrival;
$_SESSION["flightDeparture"]         = $flightDeparture;
$_SESSION["hotelDeparture"]          = $hotelDeparture;
$_SESSION["volunteer"]               = $volunteer;
$_SESSION["learnAboutBPDM"]          = $learnAboutBPDM;
$_SESSION["fundingNeed"]             = $fundingNeed;
$_SESSION["motivation"]              = $motivation;
$_SESSION["interests"]               = $interests;
$_SESSION["aspirations"]             = $aspirations;
$_SESSION["error"]                   = "";

$valid = true;

//$nameEx         = "/^([a-zA-Z]){1,}$/";
//$phoneEx        = "/^([\d]){3}([\-]){1}([\d]){3}([\-]){1}([\d]){4}$/";
//$addressEx      = "/^([a-zA-Z0-9. ]){1,}$/";
//$cityEx         = "/^([a-zA-Z ]){1,}$/";
//$zipEx          = "/^([0-9]{5})$/";
$dateEx         = "/^([\d]){4}([\-]){1}([\d]){2}([\-]){1}([\d]){2}$/";
//$commentEx      = "/^([a-zA-Z0-9.,;:?!&# ]{1,})$/";
$states         = Array("", "AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "DC", "FL", "GA", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY", "Other");
$citizenships   = Array("", "Non-resident Alien", "U.S. Permanent Resident", "U.S. Citizen", "Unknown");
$races          = Array("", "Black/African-American", "White/Caucasian", "Native American or Alaska Native", "Asian/Pacific Islander", "Other", "Unknown");
$ethnicities    = Array("", "Hispanic/Latino", "Not Hispanic/Latino", "Unknown");
$currDegrees    = Array("", "Ph. D.", "Masters", "Postdoc", "Other");
$highestDegrees = Array("", "Undergraduate - B.S.", "Undergraduate - B.A.", "Undergraduate - Other", "Graduate - M.S.", "Graduate - M.A.", "Graduate - M. Ed.", "Graduate - Ph. D.", "Graduate - Ph. Ed.", "Graduate - Other", "Other");

$today = getdate(); // get today's date

if($today[mon] < 10) // if the month is less than october
{
	if($today[mday] < 10) // if the day of the month is less than 10
	{
		$todayDate = $today[year]."-0".$today[mon]."-0".$today[mday]; // add a zero to the month and day of the month
	} // end of if
	else
	{
		$todayDate = $today[year]."-0".$today[mon]."-".$today[mday]; // add a zero to the month
	} // end of else
} // end of if
else
{
	if($today[mday] < 10) // if the day of the month is less than 10
	{
		$todayDate = $today[year]."-".$today[mon]."-0".$today[mday]; // add a zero to the day of the month
	} // end of if
	else
	{
		$todayDate = $today[year]."-".$today[mon]."-".$today[mday]; // do not add a zero to anything
	} // end of else
} // end of else

if(isset($_POST["submit"]))
{
	$valid = true;
	
	if($fName == "")
	{
		$_SESSION["fName"]  = "";
		
		$error .= "Enter a valid First name.<br />";
		
		$valid              = false;
	}

	if($lName == "")
	{
		$_SESSION["lName"]  = "";
		$error .= "Enter a valid Last name.<br />";
		$valid              = false;
	}

	if($add1 == "")
	{
		$_SESSION["add1"]   = "";
		$error .= "Enter a valid Address 1.<br />";
		$valid              = false;
	}

	if($city == "")
	{
		$_SESSION["city"]   = "";
		$error .= "Enter a valid City.<br />";
		$valid              = false;
	}

	if($zip == "")
	{
		$_SESSION["zip"]    = "";
		$error .= "Enter a valid Zipcode.<br />";
		$valid              = false;
	}

	if($country == "")
	{
		$_SESSION["country"] = "";
		$error  .= "Enter a valid City.<br />";
		$valid               = false;
	}

	if($phone == "")
	{
		$_SESSION["phone"]   = "";
		$error  .= "Enter a valid Phone Number.<br />";
		$valid               = false;
	}

	if($gender != "")
	{
		if($gender != "Male" && $gender != "Female")
		{
			$_SESSION["gender"]  = "";
			$error  .= "Enter a valid Gender.<br />";
			$valid               = false;
		}
	}

	if($underrepresentedGroup == "")
	{
		$_SESSION["underrepresentedGroup"] = "";
		$error                .= "Enter a text for the Under Represented Group Field.<br />";
		$valid                             = false;
	}

	if($org == "")
	{
		$_SESSION["org"]     = "";
		$error  .= "Enter a valid Organization Name.<br />";
		$valid               = false;
	}

	if($dept == "")
	{
		$_SESSION["dept"]    = "";
		$error  .= "Enter a valid Department Name.<br />";
		$valid               = false;
	}

	if($areaOfInterest == "")
	{
		$_SESSION["areaOfInterest"] = "";
		$error         .= "Enter a valid Area of Interest.<br />";
		$valid                      = false;
	}

	if($fullTimeStudent != "Yes" && $fullTimeStudent != "No")
	{
		$_SESSION["fullTimeStudent"] = "";
		$error          .= "Answer Whether or not you are a Full Time Student.<br />";
		$valid                       = false;
	}

	if($previousBPDMAttend != "Yes" && $previousBPDMAttend != "No")
	{
		$_SESSION["previousBPDMAttend"] = "";
		$error             .= "Answer Whether or not you attended Broadening Participation in Data Mining Program last year.<br />";
		$valid                          = false;
	}

	if($previousACMSIGKDDAttend != "Yes" && $previousACMSIGKDDAttend != "No")
	{
		$_SESSION["previousACMSIGKDDAttend"] = "";
		$error                  .= "Answer Whether or not you attended ACM SIGKDD before.<br />";
		$valid                               = false;
	}

	if($previousACMSIGKDDAttend == "Yes")
	{
		if($ACMSIGKDDAttendYear == "")
		{
			$_SESSION["ACMSIGKDDAttendYear"] = "";
			$error                .= "Enter valid text your submission type and title for ACM SIGKDD 2013.<br />";
			$valid                             = false;
		}
	}

	if($dietaryRestrictions == "")
	{
		$_SESSION["dietaryRestrictions "] = "";
		$error               .= "Enter valid text for any Dietary Restrictions you have.<br />";
		$valid                            = false;
	}

	if($smokingPreference != "Smoking" && $smokingPreference != "Non-Smoking")
	{
		$_SESSION["smokingPreference"] = "";
		$error             .= "Answer Whether or not you have a Smoking or Non-Smoking Preference.<br />";
		$valid                          = false;
	}

	if($isLocal != "Yes" && $isLocal != "No")
	{
		$_SESSION["isLocal"] = "";
		$_SESSION["error"]  .= "Answer Whether or not you are local.<br />";
		$valid               = false;
	}

	if(!preg_match($dateEx, $flightArrival ))
	{
		$_SESSION["flightArrival "] = "";
		$error         .= "Enter your Flight Arrival date in the correct format.<br />";
		$valid                      = false;
	}

	if(!preg_match($dateEx, $hotelArrival ))
	{
		$_SESSION["hotelArrival "] = "";
		$error         .= "Enter your Hotel Arrival date in the correct format.<br />";
		$valid                      = false;
	}

	if(!preg_match($dateEx, $flightDeparture ))
	{
		$_SESSION["flightDeparture "] = "";
		$error         .= "Enter your Flight Departure date in the correct format.<br />";
		$valid                      = false;
	}

	if(!preg_match($dateEx, $hotelDeparture ))
	{
		$_SESSION["hotelDeparture "] = "";
		$error         .= "Enter your Hotel Departure date in the correct format.<br />";
		$valid                      = false;
	}

	if($volunteer != "Yes" && $volunteer != "No")
	{
		$_SESSION["volunteer"] = "";
		$error  .= "Answer Whether or not you are willing to Volunteer for BPDM.<br />";
		$valid                 = false;
	}

	if($learnAboutBPDM == "")
	{
		$_SESSION["learnAboutBPDM "] = "";
		$error         .= "Enter valid text for how you learned about BPDM.<br />";
		$valid                      = false;
	}

	if($fundingNeed == "")
	{
		$_SESSION["fundingNeed "] = "";
		$error         .= "Enter valid text for describing you Funding Need.<br />";
		$valid                      = false;
	}

	if($motivation == "")
	{
		$_SESSION["motivation "] = "";
		$error         .= "Enter valid text for your Motivation for attending BPDM.<br />";
		$valid                      = false;
	}

	if($interests == "")
	{
		$_SESSION["interests "] = "";
		$error         .= "Enter valid text for your Professional Interests.<br />";
		$valid                      = false;
	}

	if($aspirations == "")
	{
		$_SESSION["aspirations "] = "";
		$error       .= "Enter valid text for your Professional Aspirations.<br />";
		$valid                    = false;
	}

	if(!validate($state, $states))
	{
		$_SESSION["state"]  = "";
		$error .= "Enter a valid State.<br />";
		$valid              = false;
	}

	if(!validate($citizenship, $citizenships) || $citizenship == "")
	{
		$_SESSION["citizenship"] = "";
		$error      .= "Enter a valid Citizenship Status.<br />";
		$valid                   = false;
	}

	if(!validate($race, $races))
	{
		$_SESSION["race"]   = "";
		$error .= "Enter a valid Race.<br />";
		$valid              = false;
	}

	if(!validate($ethnicity, $ethnicities))
	{
		$_SESSION["ethnicity"] = "";
		$error    .= "Enter a Ethnicity.<br />";
		$valid                 = false;
	}

	if(!validate($currDegree, $currDegrees) || $currDegree == "")
	{
		$_SESSION["currDegree"] = "";
		$error     .= "Enter a Current Degree Program.<br />";
		$valid                  = false;
	}

	if(!validate($highestDegree, $highestDegrees) || $highestDegree == "")
	{
		$_SESSION["highestDegree"] = "";
		$error        .= "Enter a Highest Degree.<br />";
		$valid                     = false;
	}

	if(count(str_word_count($motivation, 1)) > 500)
	{
		$error        .= "Please limit your response to 500 words when describing your Motivation.<br />";
		$valid                     = false;
	}

	if(count(str_word_count($interests, 1)) > 500)
	{
		$error        .= "Please limit your response to 500 words when describing your Interests.<br />";
		$valid                     = false;
	}

	if(count(str_word_count($aspirations, 1)) > 500)
	{
		$error        .= "Please limit your response to 500 words when describing your Aspirations.<br />";
		$valid                     = false;
	}
	
	if($valid)
	{
		include("../includes/openConn.php");
		
		$sql = "SELECT ApplicationID FROM Application WHERE Email='".$_SESSION["loggedAs"]."';";
		
		$result = mysql_query($sql); // get the sql request
		
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
			$sql = "SELECT ApplicationID FROM Application"; // get the id numbers from all of the inquiries
	
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
				$idNumber = array(); // create an array of id numbers
				
				for($i=0; $i < $num_results; $i++) 
				{
					$row = mysql_fetch_array($result); // get the data from the row
					
					$idNumber[$i] = $row["ApplicationID"]; // add the row numbers to an array
				} // end of for
				
				sort($idNumber); // sort the array
				
				$maxId = 0; // set the max id to zero
				
				for($i=0; $i<count($idNumber); $i++)
				{
					if($maxId == $idNumber[$i]) // if the maximum id number is the same as a number from the row
					{
						$maxId++; // increment the id number
					} // end of if
				} // end of for
			} // end of if
			else
			{
				$maxId = 0; // set the max id to zero
			} // end of else
	
			$sql = "INSERT INTO Application(ApplicationID, Email, FirstName, LastName, Address1, Address2, City, State, Zipcode, Country, Phone, Citizenship, Nationality, Gender, Race, Ethnicity, UnderRepresentedGroup, Organization, Department, CurrentDegree, HighestDegree, AreaOfInterest, FullTimeStudent, FacebookName, LinkedInName, TwitterName, PreviousBPDMAttend, PreviousACMSIGKDDAttend, ACMSIGKDDAttendYear, ACMSIGKDDApplication, OtherWorkshops, DietaryRestrictions, SmokingPreference, Local, HotelRequests, FlightArrival, HotelArrival, FlightDeparture, HotelDeparture, Volunteer, LearnAboutBPDM, FundingNeed, Motivation, Interests, Aspirations, SubmissionDate, AverageRating) VALUES('".$maxId."', '".$_SESSION["loggedAs"]."', '".$fName."', '".$lName."', '".$add1."', '".$add2."', '".$city."', '".$state."', '".$zip."', '".$country."', '".$phone."', '".$citizenship."', '".$nationality."', '".$gender."', '".$race."', '".$ethnicity."', '".$underrepresentedGroup."', '".$org."', '".$dept."', '".$currDegree."', '".$highestDegree."', '".$areaOfInterest."', '".$fullTimeStudent."', '".$facebookName."', '".$linkedInName."', '".$twitterName."', '".$previousBPDMAttend."', '".$previousACMSIGKDDAttend."', '".$ACMSIGKDDAttendYear."', '".$ACMSIGKDDApplication."', '".$otherWorkshops."', '".$dietaryRestrictions."', '".$smokingPreference."', '".$isLocal."', '".$hotelRequests."', '".$flightArrival."', '".$hotelArrival."', '".$flightDeparture."', '".$hotelDeparture."', '".$volunteer."', '".$learnAboutBPDM."', '".$fundingNeed."', '".$motivation."', '".$interests."', '".$aspirations."', '".$todayDate."', '0.0')"; // create the sql statement
			
			$result = mysql_query($sql); // get the sql request
		}
		else
		{
			$row = mysql_fetch_array($result);
			
			$id = $row["ApplicationID"];
			
			$sql = "UPDATE Application SET FirstName='".$fName."', LastName='".$lName."', Address1='".$add1."', Address2='".$add2."', City='".$city."', State='".$state."', Zipcode='".$zip."', Country='".$country."', Phone='".$phone."', Citizenship='".$citizenship."', Nationality='".$nationality."', Gender='".$gender."', Race='".$race."', Ethnicity='".$ethnicity."', UnderRepresentedGroup='".$underrepresentedGroup."', Organization='".$org."', Department='".$dept."', CurrentDegree='".$currDegree."', HighestDegree='".$highestDegree."', AreaOfInterest='".$areaOfInterest."', FullTimeStudent='".$fullTimeStudent."', FacebookName='".$facebookName."', LinkedInName='".$linkedInName."', TwitterName='".$twitterName."', PreviousBPDMAttend='".$previousBPDMAttend."', PreviousACMSIGKDDAttend='".$previousACMSIGKDDAttend."', ACMSIGKDDAttendYear='".$ACMSIGKDDAttendYear."', ACMSIGKDDApplication='".$ACMSIGKDDApplication."', OtherWorkshops='".$otherWorkshops."', DietaryRestrictions='".$dietaryRestrictions."', SmokingPreference='".$smokingPreference."', Local='".$isLocal."', HotelRequests='".$hotelRequests."', FlightArrival='".$flightArrival."', HotelArrival='".$hotelArrival."', FlightDeparture='".$flightDeparture."', HotelDeparture='".$hotelDeparture."', Volunteer='".$volunteer."', LearnAboutBPDM='".$learnAboutBPDM."', FundingNeed='".$fundingNeed."', Motivation='".$motivation."', Interests='".$interests."', Aspirations='".$aspirations."', SubmissionDate='".$todayDate."' WHERE ApplicationID='".$id."' AND Email='".$_SESSION["loggedAs"]."';";
			
			$result = mysql_query($sql);
		}
						
		include("../includes/closeConn.php"); // close the database
	
		$_SESSION["error"] = "<p style=\"color:#fff;background:#0c0;font-weight:bolder;border:3px solid #090;border-radius:10px;padding:5px;\">Your Application has been submitted!</p>";
	}
	else
	{
		$_SESSION["error"] = "<p style=\"color:#fff;background:#c00;font-weight:bolder;border:3px solid #900;border-radius:10px;padding:5px;\">".$error."</p>";
	}
}
else if(isset($_POST["save"]))
{
	$valid = true;

	if($fullTimeStudent != "")
	{
		if($fullTimeStudent != "Yes" && $fullTimeStudent != "No")
		{
			$_SESSION["fullTimeStudent"] = "";
			$error          .= "Answer Whether or not you are a Full Time Student.<br />";
			$valid                       = false;
		}
	}
	
	if($previousBPDMAttend != "")
	{
		if($previousBPDMAttend != "Yes" && $previousBPDMAttend != "No")
		{
			$_SESSION["previousBPDMAttend"] = "";
			$error             .= "Answer Whether or not you attended Broadening Participation in Data Mining Program last year.<br />";
			$valid                          = false;
		}
	}

	if($previousACMSIGKDDAttend != "")
	{
		if($previousACMSIGKDDAttend != "Yes" && $previousACMSIGKDDAttend != "No")
		{
			$_SESSION["previousACMSIGKDDAttend"] = "";
			$error                  .= "Answer Whether or not you attended ACM SIGKDD before.<br />";
			$valid                               = false;
		}
	}
	
	if($smokingPreference != "")
	{
		if($smokingPreference != "Smoking" && $smokingPreference != "Non-Smoking")
		{
			$_SESSION["smokingPreference"] = "";
			$error             .= "Answer Whether or not you have a Smoking or Non-Smoking Preference.<br />";
			$valid                          = false;
		}
	}

	if($isLocal != "")
	{
		if($isLocal != "Yes" && $isLocal != "No")
		{
			$_SESSION["isLocal"] = "";
			$error  .= "Answer Whether or not you are local.<br />";
			$valid               = false;
		}
	}

	if($hotelArrival != "" && $hotelArrival != "YYYY-MM-DD")
	{
		if(!preg_match($dateEx, $hotelArrival ))
		{
			$_SESSION["hotelArrival "] = "";
			$error         .= "Enter your Hotel Arrival date in the correct format.<br />";
			$valid                      = false;
		}
	}

	if($flightDeparture != "" && $flightDeparture != "YYYY-MM-DD")
	{
		if(!preg_match($dateEx, $flightDeparture ))
		{
			$_SESSION["flightDeparture "] = "";
			$error         .= "Enter your Flight Departure date in the correct format.<br />";
			$valid                      = false;
		}
	}

	if($hotelDeparture != "" && $hotelDeparture != "YYYY-MM-DD")
	{
		if(!preg_match($dateEx, $hotelDeparture ))
		{
			$_SESSION["hotelDeparture "] = "";
			$error         .= "Enter your Hotel Departure date in the correct format.<br />";
			$valid                      = false;
		}
	}

	if($volunteer != "")
	{
		if($volunteer != "Yes" && $volunteer != "No")
		{
			$_SESSION["volunteer"] = "";
			$error  .= "Answer Whether or not you are willing to Volunteer for BPDM.<br />";
			$valid                 = false;
		}
	}

	if(!validate($state, $states))
	{
		$_SESSION["state"]  = "";
		$error .= "Enter a valid State.<br />";
		$valid              = false;
	}

	if(!validate($citizenship, $citizenships))
	{
		$_SESSION["citizenship"] = "";
		$error      .= "Enter a valid Citizenship Status.<br />";
		$valid                   = false;
	}

	if(!validate($race, $races))
	{
		$_SESSION["race"]   = "";
		$error .= "Enter a valid Race.<br />";
		$valid              = false;
	}

	if(!validate($ethnicity, $ethnicities))
	{
		$_SESSION["ethnicity"] = "";
		$error    .= "Enter a Ethnicity.<br />";
		$valid                 = false;
	}

	if(!validate($currDegree, $currDegrees))
	{
		$_SESSION["currDegree"] = "";
		$error     .= "Enter a Current Degree Program.<br />";
		$valid                  = false;
	}

	if(!validate($highestDegree, $highestDegrees))
	{
		$_SESSION["highestDegree"] = "";
		$error        .= "Enter a Highest Degree.<br />";
		$valid                     = false;
	}

	if(count(str_word_count($motivation, 1)) > 500)
	{
		$error        .= "Please limit your response to 500 words when describing your Motivation.<br />";
		$valid                     = false;
	}

	if(count(str_word_count($interests, 1)) > 500)
	{
		$error        .= "Please limit your response to 500 words when describing your Interests.<br />";
		$valid                     = false;
	}

	if(count(str_word_count($aspirations, 1)) > 500)
	{
		$error        .= "Please limit your response to 500 words when describing your Aspirations.<br />";
		$valid                     = false;
	}
	
	if($valid)
	{
		include("../includes/openConn.php");
		
		$sql = "SELECT ApplicationID FROM ApplicationDraft WHERE Email='".$_SESSION["loggedAs"]."';";
		
		$result = mysql_query($sql); // get the sql request
		
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
			$sql = "SELECT ApplicationID FROM ApplicationDraft"; // get the id numbers from all of the inquiries
	
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
				$idNumber = array(); // create an array of id numbers
				
				for($i=0; $i < $num_results; $i++) 
				{
					$row = mysql_fetch_array($result); // get the data from the row
					
					$idNumber[$i] = $row["ApplicationID"]; // add the row numbers to an array
				} // end of for
				
				sort($idNumber); // sort the array
				
				$maxId = 0; // set the max id to zero
				
				for($i=0; $i<count($idNumber); $i++)
				{
					if($maxId == $idNumber[$i]) // if the maximum id number is the same as a number from the row
					{
						$maxId++; // increment the id number
					} // end of if
				} // end of for
			} // end of if
			else
			{
				$maxId = 0; // set the max id to zero
			} // end of else
	
			$sql = "INSERT INTO ApplicationDraft(ApplicationID, Email, FirstName, LastName, Address1, Address2, City, State, Zipcode, Country, Phone, Citizenship, Nationality, Gender, Race, Ethnicity, UnderRepresentedGroup, Organization, Department, CurrentDegree, HighestDegree, AreaOfInterest, FullTimeStudent, FacebookName, LinkedInName, TwitterName, PreviousBPDMAttend, PreviousACMSIGKDDAttend, ACMSIGKDDAttendYear, ACMSIGKDDApplication, OtherWorkshops, DietaryRestrictions, SmokingPreference, Local, HotelRequests, FlightArrival, HotelArrival, FlightDeparture, HotelDeparture, Volunteer, LearnAboutBPDM, FundingNeed, Motivation, Interests, Aspirations, SaveDate) VALUES('".$maxId."', '".$_SESSION["loggedAs"]."', '".$fName."', '".$lName."', '".$add1."', '".$add2."', '".$city."', '".$state."', '".$zip."', '".$country."', '".$phone."', '".$citizenship."', '".$nationality."', '".$gender."', '".$race."', '".$ethnicity."', '".$underrepresentedGroup."', '".$org."', '".$dept."', '".$currDegree."', '".$highestDegree."', '".$areaOfInterest."', '".$fullTimeStudent."', '".$facebookName."', '".$linkedInName."', '".$twitterName."', '".$previousBPDMAttend."', '".$previousACMSIGKDDAttend."', '".$ACMSIGKDDAttendYear."', '".$ACMSIGKDDApplication."', '".$otherWorkshops."', '".$dietaryRestrictions."', '".$smokingPreference."', '".$isLocal."', '".$hotelRequests."', '".$flightArrival."', '".$hotelArrival."', '".$flightDeparture."', '".$hotelDeparture."', '".$volunteer."', '".$learnAboutBPDM."', '".$fundingNeed."', '".$motivation."', '".$interests."', '".$aspirations."', '".$todayDate."')"; // create the sql statement
			
			$result = mysql_query($sql); // get the sql request
		}
		else
		{
			$row = mysql_fetch_array($result);
			
			$id = $row["ApplicationID"];
			
			$sql = "UPDATE ApplicationDraft SET FirstName='".$fName."', LastName='".$lName."', Address1='".$add1."', Address2='".$add2."', City='".$city."', State='".$state."', Zipcode='".$zip."', Country='".$country."', Phone='".$phone."', Citizenship='".$citizenship."', Nationality='".$nationality."', Gender='".$gender."', Race='".$race."', Ethnicity='".$ethnicity."', UnderRepresentedGroup='".$underrepresentedGroup."', Organization='".$org."', Department='".$dept."', CurrentDegree='".$currDegree."', HighestDegree='".$highestDegree."', AreaOfInterest='".$areaOfInterest."', FullTimeStudent='".$fullTimeStudent."', FacebookName='".$facebookName."', LinkedInName='".$linkedInName."', TwitterName='".$twitterName."', PreviousBPDMAttend='".$previousBPDMAttend."', PreviousACMSIGKDDAttend='".$previousACMSIGKDDAttend."', ACMSIGKDDAttendYear='".$ACMSIGKDDAttendYear."', ACMSIGKDDApplication='".$ACMSIGKDDApplication."', OtherWorkshops='".$otherWorkshops."', DietaryRestrictions='".$dietaryRestrictions."', SmokingPreference='".$smokingPreference."', Local='".$isLocal."', HotelRequests='".$hotelRequests."', FlightArrival='".$flightArrival."', HotelArrival='".$hotelArrival."', FlightDeparture='".$flightDeparture."', HotelDeparture='".$hotelDeparture."', Volunteer='".$volunteer."', LearnAboutBPDM='".$learnAboutBPDM."', FundingNeed='".$fundingNeed."', Motivation='".$motivation."', Interests='".$interests."', Aspirations='".$aspirations."', SaveDate='".$todayDate."' WHERE ApplicationID='".$id."' AND Email='".$_SESSION["loggedAs"]."';";
			
			$result = mysql_query($sql);
		}
						
		include("../includes/closeConn.php"); // close the database
	
		$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#0c0;font-weight:bolder;border:3px solid #090;border-radius:10px;padding:5px;\">Your Application has been saved!</p>";
	}
	else
	{
		$_SESSION["error"] = "<p style=\"width:290px;color:#fff;background:#c00;font-weight:bolder;border:3px solid #900;border-radius:10px;padding:5px;\">".$error."</p>";
	}
}

header("Location: ../application.php");

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
	$d = str_replace("-", "&#45;", $d);
	$d = str_replace("/", "&#47;", $d);
	$d = str_replace("\\", "", $d);
	$d = str_replace("\"", "&quot;", $d);
	$d = str_replace("'", "&apos;",$d);
	$d = str_replace("(", "&#40;", $d);
	$d = str_replace(")", "&#41;", $d);
	return $d;
}
?>