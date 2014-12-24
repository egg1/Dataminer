<nav id="footer-nav">
    <div class="menu">
        <?php if($_SESSION["loggedAs"] != "" && $_SESSION["adminType"] != ""){?><a href="admin.php"><p>Admin</p></a><?php }?>
        <?php if($_SESSION["loggedAs"] != "" && $_SESSION["adminType"] == "Super"){?><a href="organizer.php"><p>Organizer</p></a><?php }?>
        <?php if($_SESSION["loggedAs"] != "" && $_SESSION["userType"] == "Reviewer"){?><a href="review.php"><p>Applicant Review</p></a><?php }?>
        <a href="index.php"><p>Home</p></a>
        <a href="agenda.php"><p>Agenda</p></a>
        <a href="<?php if($_SESSION["loggedAs"] != "" && $_SESSION["userType"] == "Applicant"){echo("application.php");}else{echo("applicantLogin.php");}?>"><p>Apply</p></a>
        <a href="faq.php"><p>FAQ</p></a>
        <a href="links.php"><p>Links</p></a>
        <a href="news.php"><p>News</p></a>
        <a href="contact.php"><p>Contact</p></a>
    </div>
</nav>
