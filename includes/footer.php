<footer>
    <div class="legal">
        <p>Copyright (c)2013 Broadening Participation in Data Mining - BPDM 2013</p>
        <p>Created by <a href="http://jamesjjonescgpro.com" target="_blank">James J. Jones</a></p>
    </div>
    <div class="admin">
    	<?php
			if($_SESSION["loggedAs"] == "")
			{
				echo("<a href=\"login.php\">Administrative Login</a>");	
			}
		?>
    </div>
</footer>
<?php
	include("includes/cleanUp.php");
	$_SESSION["error"]                   = "";
	$_SESSION["passerror"]               = "";
	$_SESSION["rsserror"]                = "";
	$_SESSION["rssediterror"]            = "";
	$_SESSION["rssdeleteerror"]          = "";
	$_SESSION["loginCan"]                = "";
	$_SESSION["fnameCan"]                = "";
	$_SESSION["lnameCan"]                = "";
	$_SESSION["emailCan"]                = "";
	$_SESSION["isReviewer"]              = "";
	$_SESSION["errorNewAdmin"]           = "";
	$_SESSION["adminDeleteError"]        = "";
	$_SESSION["titleCan"]                = "";
	$_SESSION["descCan"]                 = "";
	$_SESSION["linkCan"]                 = "";
	$_SESSION["fName"]                   = "";
	$_SESSION["lName"]                   = "";
	$_SESSION["add1"]                    = "";
	$_SESSION["add2"]                    = "";
	$_SESSION["city"]                    = "";
	$_SESSION["state"]                   = "";
	$_SESSION["zip"]                     = "";
	$_SESSION["country"]                 = "";
	$_SESSION["phone"]                   = "";
	$_SESSION["citizenship"]             = "";
	$_SESSION["nationality"]             = "";
	$_SESSION["gender"]                  = "";
	$_SESSION["race"]                    = "";
	$_SESSION["ethnicity"]               = "";
	$_SESSION["underrepresentedGroup"]   = "";
	$_SESSION["org"]                     = "";
	$_SESSION["dept"]                    = "";
	$_SESSION["currDegree"]              = "";
	$_SESSION["highestDegree"]           = "";
	$_SESSION["areaOfInterest"]          = "";
	$_SESSION["fullTimeStudent"]         = "";
	$_SESSION["facebookName"]            = "";
	$_SESSION["linkedInName"]            = "";
	$_SESSION["twitterName"]             = "";
	$_SESSION["previousBPDMAttend"]      = "";
	$_SESSION["previousACMSIGKDDAttend"] = "";
	$_SESSION["ACMSIGKDDAttendYear"]     = "";
	$_SESSION["ACMSIGKDDApplication"]    = "";
	$_SESSION["otherWorkshops"]          = "";
	$_SESSION["dietaryRestrictions"]     = "";
	$_SESSION["smokingPreference"]       = "";
	$_SESSION["isLocal"]                 = "";
	$_SESSION["hotelRequests"]           = "";
	$_SESSION["flightArrival"]           = "";
	$_SESSION["hotelArrival"]            = "";
	$_SESSION["flightDeparture"]         = "";
	$_SESSION["hotelDeparture"]          = "";
	$_SESSION["volunteer"]               = "";
	$_SESSION["learnAboutBPDM"]          = "";
	$_SESSION["fundingNeed"]             = "";
	$_SESSION["motivation"]              = "";
	$_SESSION["interests"]               = "";
	$_SESSION["aspirations"]             = "";
	$_SESSION["rating"]                  = "";
	$_SESSION["justification"]           = "";
	$_SESSION["reviewer"]                = "";
	$_SESSION["isReviewer"]              = "";
?>