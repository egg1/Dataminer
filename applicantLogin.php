<?php
session_start();

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include("includes/constants.php");

$cur_time = time();

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
?>
<!DOCTYPE html>
<html>
	<head profile="http://gmpg.org/xfn/11">
    	<title>Broadening Participation in Data Mining - User Login</title>
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
				var maxRss = 5;
				
				$.ajax({type:"GET",url:"content/sponsors.html",dataType:"html",cache:false,success:function(data){data = replaceData(data);$("#sponsors-content").html(data);}});
				$.ajax({type:"GET",url:"content/scholarship-applications.html",dataType:"html",cache:false,success:function(data){data = replaceData(data);$("#scholarship-content").html(data);}});
			}
        </script>
    </head>
    <body onload="siteLoaded()">
    	<?php include("includes/header.php");?>
        <article>
        	<div id="screen-content">
            	<h1>User Login</h1>
				<?php echo($_SESSION["error"]); ?>
                <form id="form0" action="passThru/applicantLogin.php" method="post">
                    <fieldset style="width:250px;">
                        <label for="username"><b>* </b>Email: <input id="username" name="username" maxlength="40" type="text" /></label><br /><br />
                        <label for="password"><b>* </b>Password: <input id="password" name="password" maxlength="25" type="password" /></label><br /><br />
                        <input id="submit" name="submit" type="submit" value="Login" />
                    </fieldset>
                </form>
				<br />
				<p> <a href="applicantSignUp.php">New User? Register here.</a></p>
				<p><a href="retrievePass.php">Forgot Password?</a></p>
				<p></p>
				<p><br />Other questions such as eligibility, deadlines, etc? Refer to our <a href="faq.php">FAQ page</a>.</p>
				
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
