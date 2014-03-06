<?php
require_once 'php/connect.php';
require_once 'php/core.php';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Project 1 - Intro to Database</title>
    <link rel="stylesheet" type="text/css" href="css/hospital.css" />
</head>

<body>
<div id="wrapper">
    	
  	<div id="indexheader">
	<?php include 'header.php';?>
    	<br />
        <br />
    	<h1 id="title">Welcome to the Hospital Database</h1>        
    
   	</div>
   	<?php
	include 'sidenav.php';
	?>
    <div id="content" style="height: 500px; text-align: center;">
    	<h2>Bringing the Best Healthcare Everywhere</h2>
    	<p>This is the online database resource for The Hospital. </p>
        <img src="files/arcm5.gif" />
        <p></p>
    </div>
    <?php include 'footer.php';?>
    
</div>
</body>
</html>