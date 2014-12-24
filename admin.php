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

if($_SESSION["adminType"] != "Super" && $_SESSION["adminType"] != "Regular")
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
            	<h1>Administrative Panel</h1>
				<?php echo($_SESSION["error"]); ?>
                <h2>Change Administrative Profile</h2>
                <form id="form0" action="passThru/editUser.php?u=info" method="post">
                    <fieldset style="width:300px;">
                        <input id="id" name="id" type="hidden" value="<?php echo($_SESSION["loggedAs"]); ?>" />
                        <label for="fName"><b>* </b>First Name: <input id="fName" name="fName" maxlength="25" type="text" value="<?php echo($_SESSION["userFName"]); ?>" /></label><br /><br />
                        <label for="lName"><b>* </b>Last Name: <input id="lName" name="lName" maxlength="25" type="text" value="<?php echo($_SESSION["userLName"]); ?>" /></label><br /><br />
                        <label for="email"><b>* </b>Email: <input id="email" name="email" maxlength="40" type="email" value="<?php echo($_SESSION["email"]); ?>" /></label><br /><br />
                        <input id="submit" name="submit" type="submit" value="Edit Admin" />
                    </fieldset>
                </form>
                <h2>Change Administrative Password</h2>
                <form id="form1" action="passThru/editUser.php?u=pass" method="post">
                    <fieldset style="width:300px;">
                        <input id="idp" name="idp" type="hidden" value="<?php echo($_SESSION["loggedAs"]); ?>" />
                        <label for="opass"><b>* </b>Old Password: <input id="opass" name="opass" maxlength="25" type="password" value="" /></label><br /><br />
                        <label for="pass1"><b>* </b>Password: <input id="pass1" name="pass1" maxlength="25" type="password" value="" /></label><br /><br />
                        <label for="pass2"><b>* </b>Confirm Password: <input id="pass2" name="pass2" maxlength="25" type="password" value="" /></label><br /><br />
                        <input id="pass-submit" name="pass-submit" type="submit" style="width:150px;" value="Change Password" />
                    </fieldset>
                </form>
				<?php
					if($_SESSION["adminType"] == "Super")
					{
						?>
				<h2>Create New Administrator</h2>
				<form id="form6" action="passThru/createUser.php" method="post">
				<fieldset style="width:300px;">
					<label for="login_can"><b>* </b>Login: <input id="login_can" name="login_can" maxlength="25" type="text" value="<?php if($_SESSION["loginCan"] != ""){echo($_SESSION["loginCan"]);} ?>" /></label><br /><br />
					<label for="pass1_can"><b>* </b>Password: <input id="pass1_can" name="pass1_can" maxlength="25" type="password" value="" /></label><br /><br />
					<label for="pass2_can"><b>* </b>Confirm Password: <input id="pass2_can" name="pass2_can" maxlength="25" type="password" value="" /></label><br /><br />
					<label for="fName_can"><b>* </b>First Name: <input id="fName_can" name="fName_can" maxlength="25" type="text" value="<?php if($_SESSION["fnameCan"] != ""){echo($_SESSION["fnameCan"]);} ?>" /></label><br /><br />
					<label for="lName_can"><b>* </b>Last Name: <input id="lName_can" name="lName_can" maxlength="25" type="text" value="<?php if($_SESSION["lnameCan"] != ""){echo($_SESSION["lnameCan"]);} ?>" /></label><br /><br />
					<label for="email_can"><b>* </b>Email: <input id="email_can" name="email_can" maxlength="40" type="email" value="<?php if($_SESSION["emailCan"] != ""){echo($_SESSION["emailCan"]);} ?>" /></label><br /><br />
					<input id="submit-new-admin" name="submit-new-admin" type="submit" style="width:120px;" value="Create Admin" />
				</fieldset>
				</form>
				<?php
						include("includes/openConn.php");
									
						$sql = "SELECT AdminID, FirstName, LastName, AdminType FROM Admin WHERE AdminType='Regular'";
							
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
				?>
				<h2>Delete Administrator</h2>
				<form id="form7" action="passThru/deleteUser.php" method="post">
                    <fieldset style="width:300px;">
                    	<label for="admin-delete">Select Admin to Delete: 
						<select name="admin-delete" id="admin-delete" style="float:right;width:130px;">
						<?php
							
							
							for($i=0; $i<$num_results; $i++)
							{
								$row = mysql_fetch_array($result);
								
								if($row["AdminType"] != "Super")
								{
									?>
						<option value="<?php echo($row["AdminID"]); ?>"><?php echo($row["FirstName"]." ".$row["LastName"]);?></option>
									<?php
								}
							}
							
							include("includes/closeConn.php");
						?>
						</select></label><br /><br />
                        <input id="delete-admin-submit" name="delete-admin-submit" type="submit" style="width:120px;" value="Delete Admin" />
                    </fieldset>
                </form>
						<?php
						}
					}
				?>
                <h2>Create New RSS</h2>
                <form id="form2" action="passThru/newRss.php" method="post">
                    <fieldset style="width:550px;">
                        <label for="title"><b>* </b>RSS Title: <input id="title" name="title" maxlength="50" type="text" value="<?php if($_SESSION["titleCan"] != ""){echo($_SESSION["titleCan"]);} ?>" style="width:400px;" /></label><br /><br />
                        <label for="desc"><b>* </b>RSS Description: <textarea id="desc" name="desc" style="float:right;width:400px;"><?php if($_SESSION["descCan"] != ""){echo($_SESSION["descCan"]);} ?></textarea></label><br /><br /><br /><br />
                        <label for="link"><b>* </b>Add Link: <input id="link" name="link" maxlength="25" type="text" value="<?php if($_SESSION["linkCan"] != ""){echo($_SESSION["linkCan"]);} ?>" style="width:400px;" /></label><br /><br />
                        <input id="rss-submit" name="rss-submit" type="submit" style="" value="Create RSS" />
                    </fieldset>
                </form>
                <h2>Edit RSS</h2>
                <form id="form3" action="passThru/editRss.php" method="post">
                    <fieldset style="width:550px;">
                    	<label for="rss-edit">Select RSS to Edit: <select name="rss-edit" id="rss-edit" style="float:right;width:160px;" onchange="showRssEdit()"></select></label><br /><br />
                        <label for="e-title"><b>* </b>RSS Title: <input id="e-title" name="e-title" maxlength="50" type="text" value="" style="width:400px;" /></label><br /><br />
                        <label for="e-desc"><b>* </b>RSS Description: <textarea id="e-desc" name="e-desc" style="float:right;width:400px;"></textarea></label><br /><br /><br /><br />
                        <label for="e-link"><b>* </b>Add Link: <input id="e-link" name="e-link" maxlength="25" type="text" value="" style="width:400px;" /></label><br /><br />
                        <input id="edit-ress-submit" name="edit-rss-submit" type="submit" style="" value="Edit RSS" />
                    </fieldset>
                </form>
                <h2>Delete RSS</h2>
                <form id="form4" action="passThru/deleteRss.php" method="post">
                    <fieldset style="width:300px;">
                    	<label for="rss-delete">Select RSS to Delete: <select name="rss-delete" id="rss-delete" style="float:right;width:160px;"></select></label><br /><br />
                        <input id="delete-ress-submit" name="delete-rss-submit" type="submit" style="" value="Delete RSS" />
                    </fieldset>
                </form>
                <h2>Edit a Page</h2>
                <form id="form5" action="passThru/redirectEdit.php" method="post">
                    <fieldset style="width:300px;">
                    	<label for="page">Select Page to Edit: 
                        <select name="page" id="page" style="float:right;width:160px;">
                        	<option value="index">Home</option>
                            <option value="agenda">Agenda</option>
                            <option value="apply">Apply</option>
                            <option value="faq">FAQ</option>
                            <option value="links">Links</option>
                            <option value="contact">Contact</option>
                            <option value="scholarship-applications">Scholarships Applications</option>
                            <option value="sponsors">Sponsors</option>
                    	</select></label><br /><br />
                        <input id="edit-page-submit" name="edit-page-submit" type="submit" value="Edit Page" />
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