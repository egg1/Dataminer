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
    	<title>Broadening Participation in Data Mining - Agenda</title>
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1"/>
        <meta name="description" content="This website describes an ongoing project to broaden participation of underrepresented students in Data Mining.  We also aim at fostering guidance and mentorship of such students."/>
        <meta name="keywords" content="data mining workshop machine learning text mining database high performance computing BPDM BPDM12 BPDM2012"/>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <script src="js/jquery.js" type="text/javascript"></script>
        <script src="js/main.js" type="text/javascript"></script>
        <script src="js/twitterFeed.js" type="text/javascript"></script>
        <script type="text/javascript">
        	function siteLoaded()
			{
				var maxRss = 5;
				
				$.ajax({type:"GET",url:"content/agenda.html",dataType:"html",cache:false,success:function(data){data = replaceData(data);$("#screen-content").html(data);}});
				$.ajax({type:"GET",url:"content/sponsors.html",dataType:"html",cache:false,success:function(data){data = replaceData(data);$("#sponsors-content").html(data);}});
				$.ajax({type:"GET",url:"content/scholarship-applications.html",dataType:"html",cache:false,success:function(data){data = replaceData(data);$("#scholarship-content").html(data);}});
			}
        </script>
    </head>
    <body onload="siteLoaded()">
    	<?php include("includes/header.php");?>
        <article>
        	<div id="screen-content">
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