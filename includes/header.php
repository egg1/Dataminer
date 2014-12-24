<header>
	<?php
			if($_SESSION["loggedAs"] != "")
			{
				echo("<p style=\"position:absolute;margin-left:860px;color:#fff;\">Welcome, ".$_SESSION["userFName"]." <a href=\"passThru/logout.php\" style=\"color:#67a1de;text-decoration:none;\">Log Out</a></p>");	
			}
		?>
    <a href="index.php" class="header">
        <div class="title">
        	<img src="images/BPDM_logo.jpg" alt="BPDM Logo" width="152" height="160" style="float:left;margin-top:5px;margin-right:10px;" />
            <p style="font-size:30px;">Broadening Participation in Data Mining - BPDM 2013</p>
        </div>
    </a>
    <div class="soc-logos">
    	<a href="mailto:bpdmprogram@gmail.com"><img src="images/email.png" width="35" height="35" alt="Email" /></a>
    	<a href="http://www.facebook.com/groups/BPDMprogram/" target="_blank"><img src="images/fb-logo.png" width="35" height="35" alt="Facebook" /></a>
        <a href="https://twitter.com/BPDMProgram" target="_blank"><img src="images/tw-logo.png" width="35" height="35" alt="Twitter" /></a>
		<a href="http://www.linkedin.com/groups/Broadening-Participation-in-Data-Mining-4833613" target="_blank"><img src="images/linkedin-logo.png" width="35" height="35" alt="LinkedIn" /></a>
        <a href="rss/news.rss" target="_blank"><img src="images/rss-logo.png" width="35" height="35" alt="RSS Feed" /></a>
    </div>
    <nav id="main">
        <div class="menu">
            <?php if($_SESSION["loggedAs"] != "" && $_SESSION["adminType"] != ""){?><a href="admin.php"><p>Admin</p></a><?php }?>
            <?php if($_SESSION["loggedAs"] != "" && $_SESSION["adminType"] == "Super"){?><a href="organizer.php"><p style="padding-left:10px;padding-right:10px;">Organizer</p></a><?php }?>
            <?php if($_SESSION["loggedAs"] != "" && $_SESSION["userType"] == "Reviewer"){?><a href="review.php"><p>Applicant Review</p></a><?php }?>
            <a href="index.php"><p>Home</p></a>
            <a href="agenda.php"><p>Agenda</p></a>
            <a href="<?php if($_SESSION["loggedAs"] != "" && $_SESSION["userType"] == "Applicant"){echo("application.php");}else{echo("applicantLogin.php");}?>"><p>Apply</p></a>
            <a href="faq.php"><p>FAQ</p></a>
            <a href="links.php"><p>Links</p></a>
	   <a href="news.php"><p>News</p></a>
            <a href="contact.php"><p>Contact</p></a>
            <a href="membership locations.php"><p>membership locations</p></a>
        </div>
        <div class="search">
			<form id="search-form" method="get" action="http://www.google.com/search" target="_blank">
				<input id="q" name="q" type="text" value="Google site search"
onfocus="if(this.value==this.defaultValue)this.value=''; this.style.color='black';" onblur="if(this.value=='')this.value=this.defaultValue; " style="color:#999;" />
				<input value="Go" id="searchsubmit" type="submit" />
				<input type="hidden" name="sitesearch" value="dataminingshop.com" />
			</form>
        </div>
    </nav>
</header>
