<?php
require_once 'php/connect.php';
require_once 'php/core.php';


$err = false;
$errmessage = '';
$defaultfield = 'PatientId';
$fetchtable1 = 'Patient';
$fetchtable2 = 'BloodType';
$fetchtable3 = 'Insurance';


//Creating Fetch Query
$fields= '';
$fieldarray = $_POST['Fields'];
if(empty($fieldarray)) {
	$fieldarray = array(0=>"FirstName AS 'First Name'", 1=>"LastName AS 'Last Name'", 2=>"Gender", 3=>"Patient.PhoneNumber AS 'Phone Number'", 4=>"SocialSecurity AS 'Social Security'", 5=>"Type AS 'Blood Type'", 6=>"RH");
}
$count = count($fieldarray);
for($i=0; $i < $count; $i++) {
	$fields .= $fieldarray[$i];
	if($i<$count-1) {
		$fields .=', ';
	}
}

$fetchquery1 = "SELECT $fields FROM $fetchtable1 JOIN $fetchtable2 ON $fetchtable1.BloodType=$fetchtable2.BloodTypeId JOIN $fetchtable3 ON $fetchtable1.InsuranceProviderId = $fetchtable3.HPId";

if(isset($_POST['search'])&&!empty($_POST['search'])){
	$search = adjust($_POST['search']);
	$counter = 0;
	$searchedfield = " AND(";
	foreach($fieldarray as $key=>$data) {
		if($offset=strpos($data,' ')) {
			$string = substr($data,0,$offset);
		} else {
			$string = $data;
		}
		$searchedfield .= " $string LIKE '%$search%'";
		if(!(++$counter===$count)) {
			$searchedfield .= " OR";
		}
	}
	//print_r($fieldarray);
	
	$fetchquery1 .= $searchedfield.")";
}

//Field Sort
if(isset($_POST['fieldsort'])&&!empty($_POST['fieldsort'])) {
	$fieldsort = adjust($_POST['fieldsort']);
	
} else {
	//input a default
	$fieldsort = $defaultfield;
}
$fetchquery1 .= " ORDER BY $fieldsort;";
$fetchqueryfield1 = "SHOW COLUMNS FROM $fetchtable1";


//MySQL Transaction
try{
	$con->query("START TRANSACTION;");
	//echo '<br />START TRANSACTION;';
	$result = $con->query($fetchquery1);
	$result2 = $con->query($fetchquery1);;
	//echo '<br />'.$fetchquery1;
	$con->commit();
	//echo '<br />COMMIT;';
} catch (exception $e) {
	$errmessage = 'Database error: ' . adjust($con->error) . ' & \n'.$e;
	$err = true;
	$con->rollback();
}	


//New MySQLi Fieldname Resource
$rowfield2 = $result2->fetch_assoc();

$counter=0;
foreach($rowfield2 as $key=>$value){
	$fieldname2[$counter++] = $key;
}
$n=$counter;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Patient Lookup</title>
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
    	<h1 id="title">Patient Lookup</h1>        
    
   	</div>
   	<?php
	include 'sidenav.php';
	?>
    <div id="content">
    	<form action="lookuppatient.php" method="POST">
        	Look For: *<br />
            <input type="text" name="search" size="30" maxlength="30"/> <br /><br />
            Sort By: <br />
            <select name="fieldsort">
            	<?php
					$counter = 0;
					foreach($fieldarray as $key=>$data) {
						if($offset=strpos($data,' ')){
							$string = substr($data,0,strpos($data,' '));
						} else {
							$string = $data;
						}
						echo '<option value="'.$string.'">'.$string.'</option>';
					}
				?>
            </select> <br /><br />
            Show Fields: (default everything) <br />
            <table>
            	<tr>
                	<td><input type="checkbox" name="Fields[]" value="PatientId AS 'Patient ID'" />Patient ID</td>
                    <td><input type="checkbox" name="Fields[]" value="FirstName AS 'First Name'" checked/> First Name</td>
                    <td><input type="checkbox" name="Fields[]" value="LastName AS 'Last Name'" checked/> Last Name</td>
                    <td><input type="checkbox" name="Fields[]" value="DOB AS 'Date of Birth'" checked/> Date of Birth</td>
                    <td><input type="checkbox" name="Fields[]" value="Gender" checked/> Gender</td>
                </tr>
                <tr>
                	<td><input type="checkbox" name="Fields[]" value="Patient.PhoneNumber AS 'Phone Number'" checked/> Phone Number</td>
                    <td><input type="checkbox" name="Fields[]" value="Patient.StreetAddress AS 'Street Address'" checked/> Street Address</td>
                    <td><input type="checkbox" name="Fields[]" value="Patient.City" checked/> City</td>
                    <td><input type="checkbox" name="Fields[]" value="Patient.State" checked/> State</td>
                    <td><input type="checkbox" name="Fields[]" value="Patient.ZipCode AS 'Zip Code'" checked/> Zip Code</td>
                </tr>
                <tr>
                	<td><input type="checkbox" name="Fields[]" value="SocialSecurity AS 'Social Security'" checked/> Social Security</td>
                    <td><input type="checkbox" name="Fields[]" value="CompanyName AS 'Insurance Provider'" checked/> Insurance Provider</td>
                    <td><input type="checkbox" name="Fields[]" value="InsuranceNumber AS 'Insurance Number'" checked/> Insurance Number</td>
                    <td><input type="checkbox" name="Fields[]" value="BloodType.Type AS 'Blood Type'" checked/> Blood Type</td>
                    <td><input type="checkbox" name="Fields[]" value="RH" checked/> RH</td>
                </tr>
                <tr>
                	<td><input type="checkbox" name="Fields[]" value="Allergies" checked/> Allergies</td>
                    <td><input type="checkbox" name="Fields[]" value="Smoke" checked/> Smoke</td>
                    <td><input type="checkbox" name="Fields[]" value="Patient.Work AS 'Work'" checked/> Work</td>
                </tr>
            </table>
            
        	<br /><br />
            <input type="submit" value="Search Patient"/> <br /><br />
        </form>
        
        <?php
		$counter = 0;
		
		echo '
			<table border="1">
				<tr>
					<th colspan="'.$n.'">Patients in our Records:</th>
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
