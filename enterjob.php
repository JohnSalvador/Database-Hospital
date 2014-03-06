<?php
require_once 'php/connect.php';
require_once 'php/core.php';


$err = false;
$errmessage = '';
$fetchtable1='Job';
$defaultfield = 'JobId';

//Set up Insert Query
if(isset($_POST['jobtitle'])& isset($_POST['wage'])) {
	$jobtitle = adjust($_POST['jobtitle']);
	$wage = adjust($_POST['wage']);
	if(!empty($jobtitle)&&!empty($wage)) {
		$insertquery1 = "INSERT INTO Job(JobTitle,Wage) VALUES('$jobtitle','$wage');";
	} else {
		$errmessage .= "Please fill in all required(*) fields!\n";
		$err = true;
	}
}

//Set Up Table Fetch Query
if(isset($_POST['fieldsort'])&&!empty($_POST['fieldsort'])) {
	$fieldsort = adjust($_POST['fieldsort']);
} else {
	//input a default
	$fieldsort = $defaultfield;
}
	
	$fetchquery1 = "SELECT * FROM $fetchtable1 ORDER BY $fieldsort;";
	$fetchqueryfield1 = "SHOW COLUMNS FROM $fetchtable1";
	
//Transaction Block
try{
	$result = $con->query($fetchquery1);
	$resultfield = $con->query($fetchqueryfield1);
	
	if(!empty($insertquery1)) {
		$con->query($insertquery1);
		$con->commit();
		$errmessage .= "$jobtitle is registered!";
		$err = true;
	}
	
} catch (exception $e) {
	$errmessage = 'Database error: ' . adjust($con->error) . ' & \n'.$e;
	$err = true;
	$con->rollback();
}

//Move MySQLi Fieldname Resource
$fieldname = Array();
$counter = 0;
while($rowfield = $resultfield-> fetch_assoc()) {
	$fieldname[$counter] = $rowfield['Field'];
	$counter++;
}
$n=$counter;
$counter=0;



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Job Entry</title>
    <link rel="stylesheet" type="text/css" href="css/hospital.css" />
    <?php
    if($err)
    echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('$errmessage');
    </SCRIPT>");
	?>
</head>

<body>
<div id="wrapper">
    	
  	<div id="indexheader">
	<?php include 'header.php';?>
    	<br />
        <br />
    	<h1 id="title">Job Entry</h1>        
    
   	</div>
   	<?php
	include 'sidenav.php';
	?>
    <div id="content">
    
        <div>
        <form action="enterjob.php" method="POST">
            Job Title: *<br />
            <input type="text" name="jobtitle" size="30" maxlength="30"/> <br /><br />
            Wage: *<br />
            $ <input type="text" name="wage" size="28" maxlength="11"/> <br /><br />
            
            <input type ="submit" value="Enter Job"/>
            
        </form>
        <br />
        </div>
        <br />
        <div>
        <form action="enterjob.php" method="POST">
        	Sort By: <br />
            <select name="fieldsort">
            	<?php
				$counter = 0;
				while($counter<$n) {
					echo '<option value="'.$fieldname[$counter].'">'.$fieldname[$counter].'</option>';
					$counter++;
				}
				?>
            </select> 
            <input type ="submit" value="Sort"/>
        </form>
        <?php
		$counter = 0;
		echo '
			<table border="1">
				<tr>
					<th colspan="'.$n.'">Jobs Entered:</th>
				</tr>
				<tr>';
				while($counter<$n){
				echo '<th>'.$fieldname[$counter].'</th>';
				$counter++;
				}
		echo '	</tr>';
		while($row = $result->fetch_assoc()) {
			echo '<tr>';
			$counter=0;
			while($counter<$n){
				echo '<td>'.$row[$fieldname[$counter]].'</td>';
				$counter++;
			}
			echo '</tr>';
		}
		
		echo '</table>';
		?>
        </div>
    </div>
    <?php include 'footer.php';?>
</div>
</body>
</html>