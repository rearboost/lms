<?php
	$severname = "localhost";
	$username = "rearboos_demo";
	$password = "p-X^7=d=8jt{";
	$db = "rearboos_demo";

	$conn = mysqli_connect($severname,$username,$password);
	mysqli_select_db($conn,$db);

	$dbh = new PDO("mysql:dbname={$db};host={$severname};port={3306}", $username, $password);

   if(!$dbh){
      echo "unable to connect to database";
   }
?>
