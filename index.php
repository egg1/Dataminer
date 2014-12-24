<!DOCTYPE html>
<html>
	<head profile="http://gmpg.org/xfn/11">
    	<title>Broadening Participation in Data Mining - BPDM 2013</title>
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
				$.ajax({type:"GET",url:"content/index.html",dataType:"html",cache:false,success:function(data){data = replaceData(data);$("#screen-content").html(data);addRss(5);}});
				$.ajax({type:"GET",url:"content/sponsors.html",dataType:"html",cache:false,success:function(data){data = replaceData(data);$("#sponsors-content").html(data);}});
				$.ajax({type:"GET",url:"content/scholarship-applications.html",dataType:"html",cache:false,success:function(data){data = replaceData(data);$("#scholarship-content").html(data);}});
			}
        </script>
    </head>
    <body onload="siteLoaded()">
    	<header>
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
                                                <a href="index.php"><p>Home</p></a>
            <a href="agenda.php"><p>Agenda</p></a>
            <a href="applicantLogin.php"><p>Apply</p></a>
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
        <article>
        	<div id="screen-content">
            </div>
            <aside>
            	<div id="sponsors-content"></div>
<a href="applicantLogin.php"><div id="scholarship-content"></div></a>
<div id="twitter-content">
    <h2><a href="https://twitter.com/BPDMProgram" target="_blank">BPDM on Twitter</a></h2>
    <div id="twitter-messages"></div>
</div>            </aside>
            <br style="clear:both" />
        </article>
        <nav id="footer-nav">
    <div class="menu">
                                <a href="index.php"><p>Home</p></a>
        <a href="agenda.php"><p>Agenda</p></a>
        <a href="applicantLogin.php"><p>Apply</p></a>
        <a href="faq.php"><p>FAQ</p></a>
        <a href="links.php"><p>Links</p></a>
        <a href="news.php"><p>News</p></a>
        <a href="contact.php"><p>Contact</p></a>
        <a href="membership locations.php"><p>membership locations</p></a>
    </div>
</nav>
        <footer>
    <div class="legal">
        <p>Copyright (c)2013 Broadening Participation in Data Mining - BPDM 2013</p>
        <p>Created by <a href="http://jamesjjonescgpro.com" target="_blank">James J. Jones</a></p>
    </div>
    <div class="admin">
    	<a href="login.php">Administrative Login</a>    </div>
</footer>
        <script src="https://api.twitter.com/1/statuses/user_timeline.json?screen_name=BPDMProgram&include_rts=true&callback=twitterCallback2&count=5"></script>
    </body>
</html>
<!-- Hosting24 Analytics Code -->
<script type="text/javascript" src="http://stats.hosting24.com/count.php"></script>
<!-- End Of Analytics Code -->
