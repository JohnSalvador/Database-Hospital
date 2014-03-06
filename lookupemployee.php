<?php
require_once 'php/connect.php';
require_once 'php/core.php';


$err = false;
$errmessage = '';
$fetchtable1 = 'Employee';
$defaultfield = 'LastName';
$fetchtable2 = 'Job';
$fetchtable3 = 'Profession';


$fetchqueryfield1 = "SHOW COLUMNS FROM $fetchtable1";
	
//Creating Fetch Query
$fields= '';
$fieldarray = $_POST['Fields'];
if(empty($fieldarray)) {
	$fields = '*';
} else {
	$count = count($fieldarray);
	for($i=0; $i < $count; $i++) {
		$fields .= $fieldarray[$i];
		if($i<$count-1) {
			$fields .=', ';
		}
	}
}
//print_r($fieldarray);
$fetchquery1 = "SELECT $fields FROM $fetchtable1 JOIN $fetchtable2 ON $fetchtable1.JobId=$fetchtable2.JobId JOIN $fetchtable3 ON $fetchtable1.ProfessionId=$fetchtable3.ProfessionId";

//WHERE $infield LIKE '%$search%' ORDER BY $fieldsort;";
if(isset($_POST['search'])&&!empty($_POST['search'])) {
		$infield = adjust($_POST['infield']);
		$search = adjust($_POST['search']);

$fetchquery1 .= " WHERE $infield LIKE '%$search%'";
}

//Field Sort
if(isset($_POST['fieldsort'])&&!empty($_POST['fieldsort'])) {
	$fieldsort = adjust($_POST['fieldsort']);
} else {
	//input a default
	$fieldsort = $defaultfield;
}
$fetchquery1 .= " ORDER BY $fieldsort;";


try{
	$con->query("START TRANSACTION");
	$resultfield = $con->query($fetchqueryfield1);
	
	
	//echo $fetchquery1;
	$result = $con->query($fetchquery1);
	$result2 = $con->query($fetchquery1);
	
	$con->commit();
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


//New MySQLi Fieldname Resource
$fieldname2 = Array();

$rowfield2 = $result2->fetch_assoc();

$counter=0;
foreach($rowfield2 as $key=>$value){
	$fieldname2[$counter++] = $key;
}
$n=$counter;


//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Employee Lookup</title>
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
    	<h1 id="title">Employee Lookup</h1>        
    
   	</div>
   	<?php
	include 'sidenav.php';
	?>
    <div id="content">
 
    	<form action="lookupemployee.php" method="POST">
            Look For: *<br />
            <input type="text" name="search" size="30" maxlength="30"/> <br /><br />
            In Field: *<br />
            <select name="infield">
            	<?php
				$counter = 0;
				while($counter<$n) {
					echo '<option value="'.$fieldname[$counter].'">'.$fieldname[$counter].'</option>';
					$counter++;
				}
				?>
            </select> <br /><br />
            Show Fields: (default everything) <br />
            <table>
            	<tr>
                	<td><input type="checkbox" name="Fields[]" value="Id AS ID" />Id</td>
                    <td><input type="checkbox" name="Fields[]" value="FirstName AS 'First Name'" checked/> First Name</td>
                    <td><input type="checkbox" name="Fields[]" value="LastName AS 'Last Name'" checked/> Last Name</td>
                    <td><input type="checkbox" name="Fields[]" value="DOB AS 'Date of Birth'" checked/> Date of Birth</td>
                    <td><input type="checkbox" name="Fields[]" value="Gender" checked/> Gender</td>
                </tr>
                <tr>
                	<td><input type="checkbox" name="Fields[]" value="PhoneNumber AS 'Phone Number'" checked/> Phone Number</td>
                    <td><input type="checkbox" name="Fields[]" value="StreetAddress AS 'Street Address'" checked/> Street Address</td>
                    <td><input type="checkbox" name="Fields[]" value="City" checked/> City</td>
                    <td><input type="checkbox" name="Fields[]" value="State" checked/> State</td>
                    <td><input type="checkbox" name="Fields[]" value="ZipCode" checked/> Zip Code</td>
                </tr>
                <tr>
                	<td><input type="checkbox" name="Fields[]" value="SocialSecurity AS 'Social Security'" checked/> Social Security</td>
                    <td><input type="checkbox" name="Fields[]" value="Degree" checked/> Degree</td>
                    <td><input type="checkbox" name="Fields[]" value="JobTitle AS 'Job'" checked/> Job</td>
                    <td><input type="checkbox" name="Fields[]" value="ProfessionTitle AS 'Profession'" checked/> Profession</td>
                    <td><input type="checkbox" name="Fields[]" value="DateHired AS 'Date Hired'" checked/> Date Hired</td>
                </tr>
            </table>
            Sort By: <br />
            <select name="fieldsort">
            	<?php
				$counter = 0;
				while($counter<$n) {
					echo '<option value="'.$fieldname[$counter].'">'.$fieldname[$counter].'</option>';
					$counter++;
				}
				?>
            </select> <br /><br />
            <input type ="submit" value="Search"/> <br /><br />
            
        </form>
        <?php
		
		$counter = 0;
		echo '
			<table border="1">
				<tr>
					<th colspan="'.$n.'">Employees From Your Query:</th>
				</tr>
				<tr>';
				$counter=0;
				while($counter<$n) {
					echo '<th>'.$fieldname2[$counter++].'</th>';
				}
				
		echo '	</tr>';
		while($row = $result->fetch_assoc()) {
			echo '<tr>';
			$counter=0;
			while($counter<$n){
				echo '<td>'.$row[$fieldname2[$counter]].'</td>';
				$counter++;
			}
			echo '</tr>';
		}
		
		echo '</table>';
		
		?>
    
    
    </div>
    <?php include 'footer.php'; ?>
</div>

</body>

</html>
