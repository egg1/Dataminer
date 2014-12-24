<?php
session_start();

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include("includes/constants.php");

$cur_time = time();

if($_SESSION["loggedAs"] == "")
{
	header("Location: login.php");
}

if($_SESSION["userType"] != "Reviewer")
{
	header("Location: index.php");
}

if($_SESSION["passReset"] == 1)
{
	header("Location: userChangePassword.php");
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

$appId = $_GET["id"];
?>
<!DOCTYPE html>
<html>
	<head profile="http://gmpg.org/xfn/11">
    	<title>Broadening Participation in Data Mining - Applicant Review</title>
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
			if($appId == "")
			{
				include("includes/openConn.php");
				
				$sql = "SELECT ApplicationID, Rating FROM ReviewerApplication WHERE Email='".$_SESSION["loggedAs"]."';";
				
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
						
						$idNumber[$i][0] = $row["ApplicationID"]; // add the row numbers to an array
						$idNumber[$i][1] = $row["Rating"];
					} // end of for
					
					?>
						<h1>Review Applications</h1>
						<div class="application-header">
							<div class="heading"><a style="color:#fff;">Email Address</a></div>
							<div class="heading"><a style="color:#fff;">First Name</a></div>
							<div class="heading"><a style="color:#fff;">Last Name</a></div>
							<div class="heading"><a style="color:#fff;">Submission Date</a></div>
							<div class="heading"><a style="color:#fff;">Review Status</a></div>
						</div>
					<?php
				}
				else
				{
					?>
						<h2>You have no applications assigned to you at this time.</h2>
					<?php
				}
				for($i=0; $i<count($idNumber); $i++)
				{
					$sql = "SELECT Email, FirstName, LastName, SubmissionDate FROM Application WHERE ApplicationID = '".$idNumber[$i][0]."'";
					
					$result = mysql_query($sql); // send the sql request
		
					if(empty($result)) // if the result is empty
					{
						$num_results = 0; // set the number of results as zero
					} // end of if
					else
					{
						$num_results = mysql_num_rows($result); // set the number of results
					} // end of else
					
					if($num_results != 0)
					{
						$row = mysql_fetch_array($result); // get the data from the row
						?>
						<a style="color:#000;text-decoration:none;" class="application" href="review.php?id=<?php echo($idNumber[$i][0]);?>">
							<div class="application-item">
								<div class="heading"><?php echo($row["Email"]); ?></div>
								<div class="heading"><?php echo($row["FirstName"]); ?></div>
								<div class="heading"><?php echo($row["LastName"]); ?></div>
								<div class="heading"><?php echo($row["SubmissionDate"]); ?></div>
								<?php
								if($idNumber[$i][1] == 999)
								{
									echo("<div style=\"color:#f00;font-weight:bold;\" class=\"heading\">Pending</div>");
								}
								else
								{
									echo("<div style=\"color:#090;font-weight:bold;\" class=\"heading\">Completed</div>");
								}
								?>
							</div>
						</a>
						<?php
					}
				}
				include("includes/closeConn.php");
			}
			else
			{
				include("includes/openConn.php");
				
				$sql = "SELECT ApplicationID, Rating, ReviewJustification FROM ReviewerApplication WHERE Email='".$_SESSION["loggedAs"]."' AND ApplicationID='".$appId."';";
				
				$result = mysql_query($sql); // send the sql request
				
				if(empty($result)) // if the result is empty
				{
					$num_results = 0; // set the number of results as zero
				} // end of if
				else
				{
					$num_results = mysql_num_rows($result); // set the number of results
				} // end of else
				
				if($num_results != 0)
				{
					$row = mysql_fetch_array($result);
					
					if($row["Rating"] != 999)
					{
						$_SESSION["rating"]        = $row["Rating"];
						$_SESSION["justification"] = $row["ReviewJustification"];
					}
					
					$sql = "SELECT Email, FirstName, LastName, Address1, Address2, City, State, Zipcode, Country, Phone, Citizenship, Nationality, Gender, Race, Ethnicity, UnderRepresentedGroup, Organization, Department, CurrentDegree, HighestDegree, AreaOfInterest, FullTimeStudent, FacebookName, LinkedInName, TwitterName, PreviousBPDMAttend, PreviousACMSIGKDDAttend, ACMSIGKDDAttendYear, ACMSIGKDDApplication, OtherWorkshops, DietaryRestrictions, SmokingPreference, Local, HotelRequests, FlightArrival, HotelArrival, FlightDeparture, HotelDeparture, Volunteer, LearnAboutBPDM, FundingNeed, Motivation, Interests, Aspirations FROM Application WHERE ApplicationID='".$appId."';";
					
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
                    <h1>Review Application</h1>
					<?php echo($_SESSION["error"]); ?>
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
                    <h2>Review This Application</h2>
                    <form id="form0" class="application" action="passThru/rateApplication.php" method="post">
                            <fieldset style="">
                            	<input id="application" name="application" value="<?php echo($appId); ?>" type="hidden" />
                            	<label for="rating"><b>* </b>Rate This Application:<br /><br />
                                <div>
                                    <input type="radio" name="rating" value="3" <?php if($_SESSION["rating"] == "3"){echo("checked=\"checked\"");}?>>Strong Accept<br />
                                    <input type="radio" name="rating" value="2" <?php if($_SESSION["rating"] == "2"){echo("checked=\"checked\"");}?>>Accept<br />
                                    <input type="radio" name="rating" value="1" <?php if($_SESSION["rating"] == "1"){echo("checked=\"checked\"");}?>>Weak Accept<br />
                                    <input type="radio" name="rating" value="0" <?php if($_SESSION["rating"] == "0" || $_SESSION["rating"] == ""){echo("checked=\"checked\"");}?>>No Opinion<br />
                                    <input type="radio" name="rating" value="-1" <?php if($_SESSION["rating"] == "-1"){echo("checked=\"checked\"");}?>>Weak Reject<br />
                                    <input type="radio" name="rating" value="-2" <?php if($_SESSION["rating"] == "-2"){echo("checked=\"checked\"");}?>>Reject<br />
                                    <input type="radio" name="rating" value="-3" <?php if($_SESSION["rating"] == "-3"){echo("checked=\"checked\"");}?>>Strong Reject<br />
                                </div><br /><br />
                                <label for="justification"><b>* </b>Please provide a detailed review, including justification for your score. Explain why the applicant is/is not fit for BPDM 2013<br /><br /><textarea id="justification" name="justification" style=""><?php echo($_SESSION["justification"]);?></textarea></label><br /><br />
                                <input id="submit" name="submit" type="submit" style="width:150px;" value="Submit Review" />
                            </fieldset>
                        </form>
					<?php
				}
				else
				{
					echo("You Don't Have Access To This Application");
				}
				
				include("includes/closeConn.php");
			}
			?>
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