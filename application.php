<?php
session_start();

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include("includes/constants.php");

if($_SESSION["loggedAs"] == "" || $_SESSION["userType"] != "Applicant")
{
	header("Location: applicantLogin.php");
}

if($_SESSION["passReset"] == 1)
{
	header("Location: userChangePassword.php");
}

include("includes/openConn.php");
		
$sql = "SELECT FirstName, LastName, Address1, Address2, City, State, Zipcode, Country, Phone, Citizenship, Nationality, Gender, Race, Ethnicity, UnderRepresentedGroup, Organization, Department, CurrentDegree, HighestDegree, AreaOfInterest, FullTimeStudent, FacebookName, LinkedInName, TwitterName, PreviousBPDMAttend, PreviousACMSIGKDDAttend, ACMSIGKDDAttendYear, ACMSIGKDDApplication, OtherWorkshops, DietaryRestrictions, SmokingPreference, Local, HotelRequests, FlightArrival, HotelArrival, FlightDeparture, HotelDeparture, Volunteer, LearnAboutBPDM, FundingNeed, Motivation, Interests, Aspirations FROM ApplicationDraft WHERE Email='".$_SESSION["loggedAs"]."';";

$result = mysql_query($sql); // get the sql request

if(empty($result))
{
	$num_results = 0;
}
else
{
	$num_results = mysql_num_rows($result);
}

if($num_results != 0 && $_SESSION["fName"] == "")
{
	$row = mysql_fetch_array($result);
	
	$_SESSION["fName"]                   = $row["FirstName"];
	$_SESSION["lName"]                   = $row["LastName"];
	$_SESSION["add1"]                    = $row["Address1"];
	$_SESSION["add2"]                    = $row["Address2"];
	$_SESSION["city"]                    = $row["City"];
	$_SESSION["state"]                   = $row["State"];
	$_SESSION["zip"]                     = $row["Zipcode"];
	$_SESSION["country"]                 = $row["Country"];
	$_SESSION["phone"]                   = $row["Phone"];
	$_SESSION["citizenship"]             = $row["Citizenship"];
	$_SESSION["nationality"]             = $row["Nationality"];
	$_SESSION["gender"]                  = $row["Gender"];
	$_SESSION["race"]                    = $row["Race"];
	$_SESSION["ethnicity"]               = $row["Ethnicity"];
	$_SESSION["underrepresentedGroup"]   = $row["UnderRepresentedGroup"];
	$_SESSION["org"]                     = $row["Organization"];
	$_SESSION["dept"]                    = $row["Department"];
	$_SESSION["currDegree"]              = $row["CurrentDegree"];
	$_SESSION["highestDegree"]           = $row["HighestDegree"];
	$_SESSION["areaOfInterest"]          = $row["AreaOfInterest"];
	$_SESSION["fullTimeStudent"]         = $row["FullTimeStudent"];
	$_SESSION["facebookName"]            = $row["FacebookName"];
	$_SESSION["linkedInName"]            = $row["LinkedInName"];
	$_SESSION["twitterName"]             = $row["TwitterName"];
	$_SESSION["previousBPDMAttend"]      = $row["PreviousBPDMAttend"];
	$_SESSION["previousACMSIGKDDAttend"] = $row["PreviousACMSIGKDDAttend"];
	$_SESSION["ACMSIGKDDAttendYear"]     = $row["ACMSIGKDDAttendYear"];
	$_SESSION["ACMSIGKDDApplication"]    = $row["ACMSIGKDDApplication"];
	$_SESSION["otherWorkshops"]          = $row["OtherWorkshops"];
	$_SESSION["dietaryRestrictions"]     = $row["DietaryRestrictions"];
	$_SESSION["smokingPreference"]       = $row["SmokingPreference"];
	$_SESSION["isLocal"]                 = $row["Local"];
	$_SESSION["hotelRequests"]           = $row["HotelRequests"];
	$_SESSION["flightArrival"]           = $row["FlightArrival"];
	$_SESSION["hotelArrival"]            = $row["HotelArrival"];
	$_SESSION["flightDeparture"]         = $row["FlightDeparture"];
	$_SESSION["hotelDeparture"]          = $row["HotelDeparture"];
	$_SESSION["volunteer"]               = $row["Volunteer"];
	$_SESSION["learnAboutBPDM"]          = $row["LearnAboutBPDM"];
	$_SESSION["fundingNeed"]             = $row["FundingNeed"];
	$_SESSION["motivation"]              = $row["Motivation"];
	$_SESSION["interests"]               = $row["Interests"];
	$_SESSION["aspirations"]             = $row["Aspirations"];
	
	if($_SESSION["flightArrival"] == "0000-00-00")
	{
		$_SESSION["flightArrival"] = "";
	}
	
	if($_SESSION["hotelArrival"] == "0000-00-00")
	{
		$_SESSION["hotelArrival"] = "";
	}
	
	if($_SESSION["flightDeparture"] == "0000-00-00")
	{
		$_SESSION["flightDeparture"] = "";
	}
	
	if($_SESSION["hotelDeparture"] == "0000-00-00")
	{
		$_SESSION["hotelDeparture"] = "";
	}
}
?>
<!DOCTYPE html>
<html>
	<head profile="http://gmpg.org/xfn/11">
    	<title>Broadening Participation in Data Mining - Application Form</title>
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1"/>
        <meta name="description" content=""/>
        <meta name="keywords" content=""/>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <script src="js/jquery.js" type="text/javascript"></script>
        <script src="js/main.js" type="text/javascript"></script>
        <script src="js/twitterFeed.js" type="text/javascript"></script>
        <script type="text/javascript">
        	function siteLoaded()
			{
				$.ajax({type:"GET",url:"content/sponsors.html",dataType:"html",cache:false,success:function(data){data = replaceData(data);$("#sponsors-content").html(data);}});
				$.ajax({type:"GET",url:"content/scholarship-applications.html",dataType:"html",cache:false,success:function(data){data = replaceData(data);$("#scholarship-content").html(data);}});
				
				getRss("#rss-edit");
				getRss("#rss-delete");
			}
        </script>
    </head>
    <body onload="siteLoaded()">
    	<?php include("includes/header.php");?>
        <article>
        	<div id="screen-content">
				<?php
				$sql = "SELECT ApplicationID FROM Application WHERE Email='".$_SESSION["loggedAs"]."'";

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
				?>
            	<h1>Application Form</h1>
				<?php echo($_SESSION["error"]);?>
                <form class="application" id="form0" action="passThru/processApp.php" method="post">
                    <fieldset style="">
						<p style="margin-top:0px;text-align:right;">* = Required</p>
						<h2>Demographics Information</h2>
                        <input id="email" name="email" type="hidden" value="<?php echo($_SESSION["loggedAs"]); ?>" />
                        <label for="fName"><b>* </b>First Name: <input id="fName" name="fName" maxlength="25" type="text" value="<?php if($_SESSION["fName"] != ""){echo($_SESSION["fName"]);}else{echo($_SESSION["userFName"]);} ?>" /></label><br /><br />
                        <label for="lName"><b>* </b>Last Name: <input id="lName" name="lName" maxlength="25" type="text" value="<?php if($_SESSION["lName"] != ""){echo($_SESSION["lName"]);}else{echo($_SESSION["userLName"]);} ?>" /></label><br /><br />
                        <label for="add1"><b>* </b>Address 1: <input id="add1" name="add1" maxlength="30" type="text" value="<?php if($_SESSION["add1"] != ""){echo($_SESSION["add1"]);} ?>" /></label><br /><br />
						<label for="add2">&nbsp;&nbsp;Address 2: <input id="add2" name="add2" maxlength="30" type="text" value="<?php if($_SESSION["add2"] != ""){echo($_SESSION["add2"]);} ?>" /></label><br /><br />
						<label for="city"><b>* </b>City: <input id="city" name="city" maxlength="25" type="text" value="<?php if($_SESSION["city"] != ""){echo($_SESSION["city"]);} ?>" /></label><br /><br />
						<label for="state">&nbsp;&nbsp;State:
							<select name="state" id="state" size="1">
								<option value="<?php if(!empty($_SESSION["state"])){echo($_SESSION["state"]);} ?>" selected="selected" style="display:none"><?php if(!empty($_SESSION["state"])){echo($_SESSION["state"]);} ?></option>
								<option value="AL">Alabama</option>
								<option value="AK">Alaska</option>
								<option value="AZ">Arizona</option>
								<option value="AR">Arkansas</option>
								<option value="CA">California</option>
								<option value="CO">Colorado</option>
								<option value="CT">Connecticut</option>
								<option value="DE">Delaware</option>
								<option value="DC">Dist of Columbia</option>
								<option value="FL">Florida</option>
								<option value="GA">Georgia</option>
								<option value="HI">Hawaii</option>
								<option value="ID">Idaho</option>
								<option value="IL">Illinois</option>
								<option value="IN">Indiana</option>
								<option value="IA">Iowa</option>
								<option value="KS">Kansas</option>
								<option value="KY">Kentucky</option>
								<option value="LA">Louisiana</option>
								<option value="ME">Maine</option>
								<option value="MD">Maryland</option>
								<option value="MA">Massachusetts</option>
								<option value="MI">Michigan</option>
								<option value="MN">Minnesota</option>
								<option value="MS">Mississippi</option>
								<option value="MO">Missouri</option>
								<option value="MT">Montana</option>
								<option value="NE">Nebraska</option>
								<option value="NV">Nevada</option>
								<option value="NH">New Hampshire</option>
								<option value="NJ">New Jersey</option>
								<option value="NM">New Mexico</option>
								<option value="NY">New York</option>
								<option value="NC">North Carolina</option>
								<option value="ND">North Dakota</option>
								<option value="OH">Ohio</option>
								<option value="OK">Oklahoma</option>
								<option value="OR">Oregon</option>
								<option value="PA">Pennsylvania</option>
								<option value="RI">Rhode Island</option>
								<option value="SC">South Carolina</option>
								<option value="SD">South Dakota</option>
								<option value="TN">Tennessee</option>
								<option value="TX">Texas</option>
								<option value="UT">Utah</option>
								<option value="VT">Vermont</option>
								<option value="VA">Virginia</option>
								<option value="WA">Washington</option>
								<option value="WV">West Virginia</option>
								<option value="WI">Wisconsin</option>
								<option value="WY">Wyoming</option>
								<option value="Other">Other</option>
							</select>
						</label><br /><br />
						<label for="zip"><b>* </b>Zipcode: <input id="zip" name="zip" maxlength="10" type="text" value="<?php if($_SESSION["zip"] != ""){echo($_SESSION["zip"]);} ?>" /></label><br /><br />
						<label for="country"><b>* </b>Country: <input id="country" name="country" maxlength="25" type="text" value="<?php echo($_SESSION["country"]); ?>" /></label><br /><br />
						<label for="phone"><b>* </b>Phone: <input id="phone" name="phone" maxlength="15" type="text" value="<?php if($_SESSION["phone"] != ""){echo($_SESSION["phone"]);} ?>" /></label><br /><br />
                        <label for="citizenship"><b>* </b>Citizenship Status:
							<select name="citizenship" id="citizenship" size="1">
								<option value="<?php if(!empty($_SESSION["citizenship"])){echo($_SESSION["citizenship"]);} ?>" selected="selected" style="display:none"><?php if(!empty($_SESSION["citizenship"])){echo($_SESSION["citizenship"]);} ?></option>
								<option value="Non-resident Alien">Non-resident Alien</option>
								<option value="U.S. Permanent Resident">U.S. Permanent Resident</option>
								<option value="U.S. Citizen">U.S. Citizen</option>
								<option value="Unknown">Unknown</option>
							</select>
						</label><br /><br />
						<label for="nationality"><b>* </b>Nationality: <input id="nationality" name="nationality" maxlength="25" type="text" value="<?php echo($_SESSION["nationality"]); ?>" /></label><br /><br />
						<label for="gender">&nbsp;&nbsp;Gender: <div style="float:right"><input type="radio" name="gender" value="Male" <?php if($_SESSION["gender"] == "Male"){echo("checked=\"checked\"");}?>>Male&nbsp;&nbsp;<input type="radio" name="gender" value="Female" <?php if($_SESSION["gender"] == "Female"){echo("checked=\"checked\"");}?>>Female</div></label><br /><br />
						<label for="race">&nbsp;&nbsp;Racial Identity:
							<select name="race" id="race" size="1">
								<option value="<?php if(!empty($_SESSION["race"])){echo($_SESSION["race"]);} ?>" selected="selected" style="display:none"><?php if(!empty($_SESSION["race"])){echo($_SESSION["race"]);} ?></option>
								<option value="Black/African-American">Black/African-American</option>
								<option value="White/Caucasian">White/Caucasian</option>
								<option value="Native American or Alaska Native">Native American or Alaska Native</option>
								<option value="Asian/Pacific Islander">Asian/Pacific Islander</option>
								<option value="Other">Other</option>
								<option value="Unknown">Unknown</option>
							</select>
						</label><br /><br />
						<label for="ethnicity">&nbsp;&nbsp;Ethnicity Identity:
							<select name="ethnicity" id="ethnicity" size="1">
								<option value="<?php if(!empty($_SESSION["ethnicity"])){echo($_SESSION["ethnicity"]);} ?>" selected="selected" style="display:none"><?php if(!empty($_SESSION["ethnicity"])){echo($_SESSION["ethnicity"]);} ?></option>
								<option value="Hispanic/Latino">Hispanic/Latino</option>
								<option value="Not Hispanic/Latino">Not Hispanic/Latino</option>
								<option value="Unknown">Unknown</option>
							</select>
						</label><br /><br />
						<label for="underrepresentedGroup"><b>* </b>Which, if any, under-represented group(s) are you part of?<br /><br /><textarea id="underrepresentedGroup" name="underrepresentedGroup" style=""><?php echo($_SESSION["underrepresentedGroup"]); ?></textarea></label><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						<h2>Academic Information</h2>
						<label for="org"><b>* </b>Institution / Organization: <input id="org" name="org" maxlength="25" type="text" value="<?php echo($_SESSION["org"]); ?>" /></label><br /><br />
						<label for="dept"><b>* </b>Department: <input id="dept" name="dept" maxlength="50" type="text" value="<?php echo($_SESSION["dept"]); ?>" /></label><br /><br />
						<label for="currDegree"><b>* </b>Current Degree Program:
							<select name="currDegree" id="currDegree" size="1">
								<option value="<?php if(!empty($_SESSION["currDegree"])){echo($_SESSION["currDegree"]);} ?>" selected="selected" style="display:none"><?php if(!empty($_SESSION["currDegree"])){echo($_SESSION["currDegree"]);} ?></option>
								<option value="Ph. D.">Ph. D.</option>
								<option value="Masters">Masters</option>
								<option value="Postdoc">Postdoc</option>
								<option value="Other">Other</option>
							</select>
						</label><br /><br />
						<label for="highestDegree"><b>* </b>Highest Degree Attained:
							<select name="highestDegree" id="highestDegree" size="1">
								<option value="<?php if(!empty($_SESSION["highestDegree"])){echo($_SESSION["highestDegree"]);} ?>" selected="selected" style="display:none"><?php if(!empty($_SESSION["highestDegree"])){echo($_SESSION["highestDegree"]);} ?></option>
								<option value="Undergraduate - B.S.">Undergraduate - B.S.</option>
								<option value="Undergraduate - B.A.">Undergraduate - B.A.</option>
								<option value="Undergraduate - Other">Undergraduate - Other</option>
								<option value="Graduate - M.S.">Graduate - M.S.</option>
								<option value="Graduate - M.A.">Graduate - M.A.</option>
								<option value="Graduate - M. Ed.">Graduate - M. Ed.</option>
								<option value="Graduate - Ph. D.">Graduate - Ph. D.</option>
								<option value="Graduate - Ph. Ed.">Graduate - Ph. Ed.</option>
								<option value="Graduate - Other">Graduate - Other</option>
								<option value="Other">Other</option>
							</select>
						</label><br /><br />
						<label for="areaOfInterest"><b>* </b>Research Area(s) of Interest: <input id="areaOfInterest" name="areaOfInterest" maxlength="100" type="text" value="<?php echo($_SESSION["areaOfInterest"]); ?>" /></label><br /><br />
						<label for="fullTimeStudent"><b>* </b>Are you a full-time student? <div style="float:right"><input type="radio" name="fullTimeStudent" value="Yes" <?php if($_SESSION["fullTimeStudent"] == "Yes"){echo("checked=\"checked\"");}?>>Yes&nbsp;&nbsp;<input type="radio" name="fullTimeStudent" value="No" <?php if($_SESSION["fullTimeStudent"] == "No"){echo("checked=\"checked\"");}?>>No</div></label><br /><br /><br /><br />
						<h2>Social Media Information</h2>
						<label for="facebookName">&nbsp;&nbsp;Facebook Name: <input id="facebookName" name="facebookName" maxlength="25" type="text" value="<?php echo($_SESSION["facebookName"]); ?>" /></label><br /><br />
						<label for="linkedInName">&nbsp;&nbsp;LinkedIn Name: <input id="linkedInName" name="linkedInName" maxlength="25" type="text" value="<?php echo($_SESSION["linkedInName"]); ?>" /></label><br /><br />
						<label for="twitterName">&nbsp;&nbsp;Twitter Name: <input id="twitterName" name="twitterName" maxlength="25" type="text" value="<?php echo($_SESSION["twitterName"]); ?>" /></label><br /><br /><br /><br />
						<h2>Previous Participation</h2>
						<label for="previousBPDMAttend"><b>* </b>Did you attend Broadening Participation in Data Mining Program last year?<div style="float:right"><input type="radio" name="previousBPDMAttend" value="Yes" <?php if($_SESSION["previousBPDMAttend"] == "Yes"){echo("checked=\"checked\"");}?>>Yes&nbsp;&nbsp;<input type="radio" name="previousBPDMAttend" value="No" <?php if($_SESSION["previousBPDMAttend"] == "No"){echo("checked=\"checked\"");}?>>No</div></label><br /><br />
						<label for="previousACMSIGKDDAttend"><b>* </b>Have you attended ACM SIGKDD before?<div style="float:right"><input type="radio" name="previousACMSIGKDDAttend" value="Yes" <?php if($_SESSION["previousACMSIGKDDAttend"] == "Yes"){echo("checked=\"checked\"");}?>>Yes&nbsp;&nbsp;<input type="radio" name="previousACMSIGKDDAttend" value="No" <?php if($_SESSION["previousACMSIGKDDAttend"] == "No"){echo("checked=\"checked\"");}?>>No</div></label><br /><br />
						<label for="ACMSIGKDDAttendYear">&nbsp;&nbsp;If so, which year(s): <input id="ACMSIGKDDAttendYear" name="ACMSIGKDDAttendYear" maxlength="25" type="text" value="<?php echo($_SESSION["ACMSIGKDDAttendYear"]); ?>" /></label><br /><br /><br /><br />
						<label for="ACMSIGKDDApplication">If you have submitted to ACM SIGKDD 2013, please provide the submission type and title:<br /><br /><textarea id="ACMSIGKDDApplication" name="ACMSIGKDDApplication" style=""><?php echo($_SESSION["ACMSIGKDDApplication"]); ?></textarea></label><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						<label for="otherWorkshops">Have you ever attended any other workshops where the focus was in broadening participation of underrepresented groups? If so, provide titles and years.<br /><br /><textarea id="otherWorkshops" name="otherWorkshops" style=""><?php echo($_SESSION["otherWorkshops"]);?></textarea></label><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						<h2>Emergency & Miscellaneous Information</h2>
						<label for="dietaryRestrictions"><b>* </b>Do you have any dietary restrictions?<br /><br /><textarea id="dietaryRestrictions" name="dietaryRestrictions" style=""><?php echo($_SESSION["dietaryRestrictions"]);?></textarea></label><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						<label for="smokingPreference"><b>* </b>Smoking preference:<div style="float:right"><input type="radio" name="smokingPreference" value="Smoking" <?php if($_SESSION["smokingPreference"] == "Smoking"){echo("checked=\"checked\"");}?>>Smoking&nbsp;&nbsp;<input type="radio" name="smokingPreference" value="Non-Smoking" <?php if($_SESSION["smokingPreference"] == "Non-Smoking"){echo("checked=\"checked\"");}?>>Non-Smoking</div></label><br /><br /><br /><br />
						<label for="isLocal"><b>* </b>I am a local participant and do not need hotel accommodations:<div style="float:right"><input type="radio" name="isLocal" value="Yes" <?php if($_SESSION["isLocal"] == "Yes"){echo("checked=\"checked\"");}?>>Yes&nbsp;&nbsp;<input type="radio" name="isLocal" value="No" <?php if($_SESSION["isLocal"] == "No"){echo("checked=\"checked\"");}?>>No</div></label><br /><br />
						<label for="hotelRequests">Briefly describe any special hotel requests, if any.<br /><br /><textarea id="hotelRequests" name="hotelRequests" style=""><?php echo($_SESSION["hotelRequests"]);?></textarea></label><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						<label for="flightArrival"><b>* </b>Flight Arrival date: <input id="flightArrival" name="flightArrival" onfocus="changeExText('flightArrival')" style="<?php if($_SESSION["flightArrival"] == ""){echo("color:#666666;");}?>" type="date" <?php if($_SESSION["flightArrival"] != ""){echo("value=\"".$_SESSION["flightArrival"]."\"");}else{echo("value=\"YYYY-MM-DD\"");} ?> /></label><br /><br />
						<label for="hotelArrival"><b>* </b>Hotel Arrival date: <input id="hotelArrival" name="hotelArrival" onfocus="changeExText('hotelArrival')" style="<?php if($_SESSION["hotelArrival"] == ""){echo("color:#666666;");}?>" type="date" <?php if($_SESSION["hotelArrival"] != ""){echo("value=\"".$_SESSION["hotelArrival"]."\"");}else{echo("value=\"YYYY-MM-DD\"");} ?> /></label><br /><br />
						<label for="flightDeparture"><b>* </b>Flight Departure date: <input id="flightDeparture" name="flightDeparture" onfocus="changeExText('flightDeparture')" style="<?php if($_SESSION["flightDeparture"] == ""){echo("color:#666666;");}?>" type="date" <?php if($_SESSION["flightDeparture"] != ""){echo("value=\"".$_SESSION["flightDeparture"]."\"");}else{echo("value=\"YYYY-MM-DD\"");} ?> /></label><br /><br />
						<label for="hotelDeparture"><b>* </b>Hotel Departure date: <input id="hotelDeparture" name="hotelDeparture" onfocus="changeExText('hotelDeparture')" style="<?php if($_SESSION["hotelDeparture"] == ""){echo("color:#666666;");}?>" type="date" <?php if($_SESSION["hotelDeparture"] != ""){echo("value=\"".$_SESSION["hotelDeparture"]."\"");}else{echo("value=\"YYYY-MM-DD\"");} ?> /></label><br /><br /><br /><br />
						<h2>Interest in BPDM 2013 & Objectives</h2>
						<label for="volunteer"><b>* </b>Would you also consider volunteering during part of the conference? (volunteers will have a higher chance of being selected for a scholarship)<div style="float:right"><input type="radio" name="volunteer" value="Yes" <?php if($_SESSION["volunteer"] == "Yes"){echo("checked=\"checked\"");}?>>Yes&nbsp;&nbsp;<input type="radio" name="volunteer" value="No" <?php if($_SESSION["volunteer"] == "No"){echo("checked=\"checked\"");}?>>No</div></label><br /><br />
						<label for="learnAboutBPDM"><b>* </b>How did you learn about the Broadening Participation in Data Mining Program?<br /><br /><textarea id="learnAboutBPDM" name="learnAboutBPDM" style=""><?php echo($_SESSION["learnAboutBPDM"]); ?></textarea></label><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						<label for="fundingNeed"><b>* </b>Why do you need the funding?<br /><br /><textarea id="fundingNeed" name="fundingNeed" style=""><?php echo($_SESSION["fundingNeed"]); ?></textarea></label><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						<label for="motivation"><b>* </b>Describe your motivation for attending Broadening Participation in Data Mining. (500 word limit)<br /><br /><textarea id="motivation" name="motivation" style="height:400px;"><?php echo($_SESSION["motivation"]); ?></textarea></label><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						<label for="interests"><b>* </b>Briefly describe your areas of academic and professional interest. (500 word limit)<br /><br /><textarea id="interests" name="interests" style="height:400px;"><?php echo($_SESSION["interests"]); ?></textarea></label><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						<label for="aspirations"><b>* </b>What are your professional aspirations over the next five years? (500 word limit)<br /><br /><textarea id="aspirations" name="aspirations" style="height:400px;"><?php echo($_SESSION["aspirations"]); ?></textarea></label><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						<input id="save" name="save" type="submit" value="Save Your Application" style="width:200px;" /><br /><br />
						<input id="submit" name="submit" type="submit" value="Submit Your Application" style="width:200px;" />
                    </fieldset>
                </form>
				<?php
				}
				else
				{
					$sql = "SELECT Email, FirstName, LastName, Address1, Address2, City, State, Zipcode, Country, Phone, Citizenship, Nationality, Gender, Race, Ethnicity, UnderRepresentedGroup, Organization, Department, CurrentDegree, HighestDegree, AreaOfInterest, FullTimeStudent, FacebookName, LinkedInName, TwitterName, PreviousBPDMAttend, PreviousACMSIGKDDAttend, ACMSIGKDDAttendYear, ACMSIGKDDApplication, OtherWorkshops, DietaryRestrictions, SmokingPreference, Local, HotelRequests, FlightArrival, HotelArrival, FlightDeparture, HotelDeparture, Volunteer, LearnAboutBPDM, FundingNeed, Motivation, Interests, Aspirations FROM Application WHERE Email='".$_SESSION["loggedAs"]."';";
					
					$result = mysql_query($sql); // get the sql request

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
					}
					
					?>
					<h1>Your Application Has Been Submitted</h1>
					<div class="application-data">
					<div class="item"><div class="heading">Applicant Name:</div><div class="data"><?php echo($row["FirstName"]." ".$row["LastName"]);?></div></div>
					<div class="item"><div class="heading">Email:</div><div class="data"><?php echo($row["Email"]);?></div></div>
					<div class="item"><div class="heading">Address 1:</div><div class="data"><?php echo($row["Address1"]);?></div></div>
					<div class="item"><div class="heading">Address 2:</div><div class="data"><?php echo($row["Address2"]);?></div></div>
					<div class="item"><div class="heading">City:</div><div class="data"><?php echo($row["City"]);?></div></div>
					<div class="item"><div class="heading">State:</div><div class="data"><?php echo($row["State"]);?></div></div>
					<div class="item"><div class="heading">Zipcode:</div><div class="data"><?php echo($row["Zipcode"]);?></div></div>
					<div class="item"><div class="heading">Country:</div><div class="data"><?php echo($row["Country"]);?></div></div>
					<div class="item"><div class="heading">Phone:</div><div class="data"><?php echo($row["Phone"]);?></div></div>
					<div class="item"><div class="heading">Citizenship Status:</div><div class="data"><?php echo($row["Citizenship"]);?></div></div>
					<div class="item"><div class="heading">Nationality:</div><div class="data"><?php echo($row["Nationality"]);?></div></div>
					<div class="item"><div class="heading">Gender:</div><div class="data"><?php echo($row["Gender"]);?></div></div>
					<div class="item"><div class="heading">Racial Identity:</div><div class="data"><?php echo($row["Race"]);?></div></div>
					<div class="item"><div class="heading">Ethnicity Identity:</div><div class="data"><?php echo($row["Ethnicity"]);?></div></div>
					<div class="item"><div class="heading">Which, if any, under-represented group(s) are you part of?</div><div class="data"><?php echo($row["UnderRepresentedGroup"]);?></div><br style="clear:both;" /></div>
					<div class="item"><div class="heading">Institution / Organization:</div><div class="data"><?php echo($row["Organization"]);?></div></div>
					<div class="item"><div class="heading">Department:</div><div class="data"><?php echo($row["Department"]);?></div></div>
					<div class="item"><div class="heading">Current Degree Program:</div><div class="data"><?php echo($row["CurrentDegree"]);?></div></div>
					<div class="item"><div class="heading">Highest Degree Attained:</div><div class="data"><?php echo($row["HighestDegree"]);?></div></div>
					<div class="item"><div class="heading">Research Area(s) of Interest:</div><div class="data"><?php echo($row["AreaOfInterest"]);?></div></div>
					<div class="item"><div class="heading">Are you a full-time student?:</div><div class="data"><?php echo($row["FullTimeStudent"]);?></div></div>
					<div class="item"><div class="heading">Facebook Name:</div><div class="data"><?php echo($row["FacebookName"]);?></div></div>
					<div class="item"><div class="heading">LinkedIn Name:</div><div class="data"><?php echo($row["LinkedInName"]);?></div></div>
					<div class="item"><div class="heading">Twitter Name:</div><div class="data"><?php echo($row["TwitterName"]);?></div></div>
					<div class="item"><div class="heading">Did you attend Broadening Participation in Data Mining Program last year?</div><div class="data"><?php echo($row["PreviousBPDMAttend"]);?></div><br style="clear:both;" /></div>
					<div class="item"><div class="heading">Have you attended ACM SIGKDD before?</div><div class="data"><?php echo($row["PreviousACMSIGKDDAttend"]);?></div><br style="clear:both;" /></div>
					<div class="item"><div class="heading">If so, which year(s)?</div><div class="data"><?php echo($row["ACMSIGKDDAttendYear"]);?></div></div>
					<div class="item"><div class="heading">If you have submitted to ACM SIGKDD 2013, please provide the submission type and title:</div><div class="data"><?php echo($row["ACMSIGKDDApplication"]);?></div><br style="clear:both;" /></div>
					<div class="item"><div class="heading">Have you ever attended any other workshops where the focus was in broadening participation of underrepresented groups? If so, provide titles and years.</div><div class="data"><?php echo($row["OtherWorkshops"]);?></div><br style="clear:both;" /></div>
					<div class="item"><div class="heading">Do you have any dietary restrictions?</div><div class="data"><?php echo($row["DietaryRestrictions"]);?></div></div>
					<div class="item"><div class="heading">Smoking preference:</div><div class="data"><?php echo($row["SmokingPreference"]);?></div></div>
					<div class="item"><div class="heading">I am a local participant and do not need hotel accommodations:</div><div class="data"><?php echo($row["Local"]);?></div><br style="clear:both;" /></div>
					<div class="item"><div class="heading">Briefly describe any special hotel requests, if any.</div><div class="data"><?php echo($row["HotelRequests"]);?></div><br style="clear:both;" /></div>
					<div class="item"><div class="heading">Flight Arrival date:</div><div class="data"><?php echo($row["FlightArrival"]);?></div></div>
					<div class="item"><div class="heading">Hotel Arrival date:</div><div class="data"><?php echo($row["HotelArrival"]);?></div></div>
					<div class="item"><div class="heading">Flight Departure date:</div><div class="data"><?php echo($row["FlightDeparture"]);?></div></div>
					<div class="item"><div class="heading">Hotel Departure date:</div><div class="data"><?php echo($row["HotelDeparture"]);?></div></div>
					<div class="item"><div class="heading">Would you also consider volunteering during part of the conference?</div><div class="data"><?php echo($row["Volunteer"]);?></div><br style="clear:both;" /></div>
					<div class="item"><div class="heading">How did you learn about the Broadening Participation in Data Mining Program?</div><div class="data"><?php echo($row["LearnAboutBPDM"]);?></div><br style="clear:both;" /></div>
					<div class="item"><div class="heading">Why do you need the funding?</div><div class="data"><?php echo($row["FundingNeed"]);?></div></div>
					<div class="item"><div class="heading">Describe your motivation for attending Broadening Participation in Data Mining.</div><div class="data"><?php echo($row["Motivation"]);?></div><br style="clear:both;" /></div>
					<div class="item"><div class="heading">Briefly describe your areas of academic and professional interest.</div><div class="data"><?php echo($row["Interests"]);?></div><br style="clear:both;" /></div>
					<div class="item" style="border-bottom:none;"><div class="heading">What are your professional aspirations over the next five years?</div><div class="data"><?php echo($row["Aspirations"]);?></div><br style="clear:both;" /></div>
					</div>
					<?php
					
					include("includes/closeConn.php");
				}
				?>
				<br />
				<a href="passThru/logout.php" style="color:#097cbb;text-decoration:none;font-size:15px;font-weight:bold;">Log Out</a>
            </div>
            <aside>
            	<?php include("includes/aside.php");?>
            </aside>
            <br style="clear:both" />
        </article>
        <?php include("includes/footer-nav.php"); ?>
        <?php include("includes/footer.php"); ?>
        <script src="https://api.twitter.com/1/statuses/user_timeline.json?screen_name=BPDMProgram&include_rts=true&callback=twitterCallback2&count=5"></script>
    </body>
</html>