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

if($_SESSION["adminType"] != "Super")
{
	header("Location: index.php");
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

include("includes/openConn.php");
?>
<!DOCTYPE html>
<html>
	<head profile="http://gmpg.org/xfn/11">
    	<title>Broadening Participation in Data Mining - Administrative Site</title>
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
            	<h1>Organizer Panel</h1>
            	<?php
				if($appId == "")
				{
					$sql = "SELECT ApplicationID, Email, FirstName, LastName, SubmissionDate, AverageRating FROM Application";
					
					$result = mysql_query($sql); // send the sql request
					
					if(empty($result)) // if the result is empty
					{
						$num_results = 0; // set the number of results as zero
					} // end of if
					else
					{
						$num_results = mysql_num_rows($result); // set the number of results
					} // end of else
					
					if($num_results == 0)
					{
						echo("<h2>There are currently no applications.</h2>");	
					}
					else
					{
						?>
						<h1>Review Applications</h1>
						<div class="application-header-org">
							<div class="heading"><a style="color:#fff;">Email Address</a></div>
							<div class="heading"><a style="color:#fff;">First Name</a></div>
							<div class="heading"><a style="color:#fff;">Last Name</a></div>
							<div class="heading"><a style="color:#fff;">Submission Date</a></div>
							<div class="heading"><a style="color:#fff;">Average Review</a></div>
							<div class="heading" style="font-weight:bold;color:#fff;">Review Status</div>
						</div>
						<?php
						for($i=0; $i<$num_results; $i++)
						{
							$sql = "SELECT Rating FROM ReviewerApplication WHERE ApplicationID='".$i."';";
							
							$ratingResult = mysql_query($sql);
							
							if(empty($ratingResult)) // if the result is empty
							{
								$num_ratingResult = 0; // set the number of results as zero
							} // end of if
							else
							{
								$num_ratingResult = mysql_num_rows($ratingResult); // set the number of results
							} // end of else
							
							$ratingPending = "<div style=\"color:#f00;font-weight:bold;\" class=\"heading\">Pending</div>";
							
							if($num_ratingResult != 0)
							{
								$ratingNum = 0;
								
								for($k=0; $k<$num_ratingResult; $k++)
								{
									$rating = mysql_fetch_array($ratingResult);
									
									if($rating["Rating"] != 999)
									{
										$ratingNum++;	
									}
								}
								
								if($ratingNum >= 3)
								{
									$ratingPending = "<div style=\"color:#090;font-weight:bold;\" class=\"heading\">Completed</div>";
								}
							}
							
							$row = mysql_fetch_array($result); // get the data from the row
							?>
							<a style="color:#000;text-decoration:none;" class="application" href="organizer.php?id=<?php echo($row["ApplicationID"]);?>">
								<div class="application-item-org">
									<div class="heading"><?php echo($row["Email"]); ?></div>
									<div class="heading"><?php echo($row["FirstName"]); ?></div>
									<div class="heading"><?php echo($row["LastName"]); ?></div>
									<div class="heading"><?php echo($row["SubmissionDate"]); ?></div>
									<div class="heading"><?php echo(getRatingText($row["AverageRating"])); ?></div>
									<?php echo($ratingPending); ?>
								</div>
							</a>
							<?php
						}
					}
				}
				else
				{
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
						?>
                        <form id="form0" action="passThru/assignReviewer.php" method="post">
                            <fieldset style="width:300px;">
                            	<input id="application" name="application" type="hidden" value="<?php echo($appId); ?>" />
                                <label for="user">Select Reviewer: 
                                <select name="user" id="user" style="float:right;width:150px;">
                                <?php
									$sql = "SELECT Email, FirstName, LastName, UserType FROM User;";
									
									$userResult = mysql_query($sql);
									
									if(empty($userResult))
									{
										$num_userResults = 0;
									}
									else
									{
										$num_userResults = mysql_num_rows($userResult);
									}
									
                                    for($k=0; $k<$num_userResults; $k++)
                                    {
                                        $userRow = mysql_fetch_array($userResult);
                                        
                                        if($userRow["UserType"] == "Reviewer")
                                        {
                                            ?>
                                <option value="<?php echo($userRow["Email"]); ?>"><?php echo($userRow["FirstName"]." ".$userRow["LastName"]);?></option>
                                            <?php
                                        }
                                    }
                                ?>
                                </select></label><br /><br />
                                <input id="submit" name="submit" type="submit" style="width:150px;" value="Assign Reviewer" />
                                <br /><br /><div style="field_error"><?php echo($_SESSION["error"]); ?></div>
                            </fieldset>
                        </form><br /><br />
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
							$sql = "SELECT Email, Rating, ReviewJustification FROM ReviewerApplication WHERE ApplicationID='".$appId."';";
							
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
							?>
							<br />
							<h1>Application Reviews</h1>
							<?php
								$found = false;
								
								for($i=0; $i<$num_results; $i++)
								{
									$row = mysql_fetch_array($result);
									
									if($row["Rating"] != 999)
									{
										$found = true;
										
										$sql = "SELECT FirstName, LastName FROM User WHERE Email='".$row["Email"]."';";
										
										$userResult = mysql_query($sql);
										
										if(!empty($userResult))
										{
											$userRow = mysql_fetch_array($userResult);
									?>
						<h2>Review <?php echo($i+1); ?>:</h2>
						<div class="application-data">
							<div class="item"><div class="heading">Reviewed By:</div><div class="data"><a style="color:#fff;" href="mailto:<?php echo($row["Email"]);?>"><?php echo($userRow["FirstName"]." ".$userRow["LastName"]);?></a></div></div>
							<div class="item"><div class="heading">Review Rating:</div><div class="data"><?php echo(getRatingText($row["Rating"]));?></div></div>
							<div class="item"><div class="heading">Justification For Review:</div><div class="data"><?php echo($row["ReviewJustification"]);?></div><br style="clear:both;" /></div>
						</div>
									<?php
										}
									}
								}
								
								if(!$found)
								{
									echo("No completed reviews were found.");
								}
							}
						}
						else
						{
							echo("Application could not be found.");
						}
					}
				?>
            </div>
            <aside>
            	<?php include("includes/aside.php");?>
            </aside>
            <br style="clear:both" />
        </article>
        <?php include("includes/closeConn.php"); ?>
        <?php include("includes/footer-nav.php"); ?>
        <?php include("includes/footer.php"); ?>
		<?php
			function getRatingText($d)
			{
				if($d == 3)
				{
					return "Strong Accept";
				}
				else if($d < 3 && $d >= 2)
				{
					return "Accept";
				}
				else if($d < 2 && $d >= 1)
				{
					return "Weak Accept";
				}
				else if($d < 1 && $d >= 0)
				{
					return "No Opinion";
				}
				else if($d < 0 && $d >= -1)
				{
					return "Weak Reject";
				}
				else if($d < -1 && $d >= -2)
				{
					return "Reject";
				}
				else
				{
					return "Strong Reject";
				}
			}
		?>
        <script src="https://api.twitter.com/1/statuses/user_timeline.json?screen_name=BPDMProgram&include_rts=true&callback=twitterCallback2&count=5"></script>
    </body>
</html>