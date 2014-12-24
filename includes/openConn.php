<?php
	@ $db = mysql_pconnect("mysql1.000webhost.com", "a6770153_datamin", "ShaFoLiZe7783");
	mysql_select_db("a6770153_datamin");
	
	if(!$db)
	{
		echo "Error: Could not connect to database.  Please try again later.";
		exit;
	}
?>