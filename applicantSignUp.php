<?php
session_start();

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

?>
<!DOCTYPE html>
<html>
	<head profile="http://gmpg.org/xfn/11">
    	<title>Broadening Participation in Data Mining - User Account Creation</title>
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
				<h2>User Account Creation</h2>
				<?php echo($_SESSION["error"]); ?>
				<form id="form6" action="passThru/createApplicant.php" method="post">
				<fieldset style="width:300px;">
					<label for="email_can"><b>* </b>Email: <input id="email_can" name="email_can" maxlength="40" type="email" value="<?php if($_SESSION["emailCan"] != ""){echo($_SESSION["emailCan"]);} ?>" /></label><br /><br />
					<label for="pass1_can"><b>* </b>Password: <input id="pass1_can" name="pass1_can" maxlength="25" type="password" value="" /></label><br /><br />
					<label for="pass2_can"><b>* </b>Confirm Password: <input id="pass2_can" name="pass2_can" maxlength="25" type="password" value="" /></label><br /><br />
					<label for="fName_can"><b>* </b>First Name: <input id="fName_can" name="fName_can" maxlength="25" type="text" value="<?php if($_SESSION["fnameCan"] != ""){echo($_SESSION["fnameCan"]);} ?>" /></label><br /><br />
					<label for="lName_can"><b>* </b>Last Name: <input id="lName_can" name="lName_can" maxlength="25" type="text" value="<?php if($_SESSION["lnameCan"] != ""){echo($_SESSION["lnameCan"]);} ?>" /></label><br /><br />
                    <label for="reviewer">Create as reviewer? <input id="reviewer" name="reviewer" type="checkbox" <?php if($_SESSION["reviewer"] != ""){echo("checked=\"checked\"");}?> /></label><br /><br />
					<input id="submit-new-admin" name="submit-new-admin" type="submit" style="width:120px;" value="Sign Up" />
				</fieldset>
				</form>
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