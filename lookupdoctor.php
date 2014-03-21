<?php
require_once 'php/connect.php';
require_once 'php/core.php';


$err = false;
$errmessage = '';
$fetchtable1 = 'Employee';
$defaultfield = 'LastName';
$fetchtable2 = 'Job';
$fetchtable3 = 'Profession';



//Creating Fetch Query
$fields= '';
if(isset($_POST['Fields']))
	$fieldarray = $_POST['Fields'];
if(empty($fieldarray)) {
	$fieldarray = array(0=>"FirstName AS 'First Name'", 1=>"LastName AS 'Last Name'", 2=>"Gender", 3=>"PhoneNumber AS 'Phone Number'", 4=>"Degree", 5=>"ProfessionTitle AS 'Profession Title'");
}
$count = count($fieldarray);
for($i=0; $i < $count; $i++) {
	$fields .= $fieldarray[$i];
	if($i<$count-1) {
		$fields .=', ';
	}
}

$fetchquery1 = "SELECT $fields FROM $fetchtable1 JOIN $fetchtable2 ON $fetchtable1.JobId=$fetchtable2.JobId JOIN $fetchtable3 ON $fetchtable1.ProfessionId=$fetchtable3.ProfessionId WHERE Employee.JobId='19'";

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
//" WHERE $infield LIKE '%$search%' ORDER BY $fieldsort;";
//echo $fetchquery1;

//Field Sort
if(isset($_POST['fieldsort'])&&!empty($_POST['fieldsort'])) {
	$fieldsort = adjust($_POST['fieldsort']);
	
} else {
	//input a default
	$fieldsort = $defaultfield;
}
$fetchquery1 .= " ORDER BY $fieldsort;";
$fetchqueryfield1 = "SHOW COLUMNS FROM $fetchtable1";

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
if(isset($rowfield2)&&!empty($rowfield2))
	foreach($rowfield2 as $key=>$value){
		$fieldname2[$counter++] = $key;
	}
$n=$counter;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Doctor Lookup</title>
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
    	<h1 id="title">Doctor Lookup</h1>        
    
   	</div>
   	<?php
	include 'sidenav.php';
	?>
    <div id="content">
    	<form action="lookupdoctor.php" method="POST">
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
                    <td><input type="checkbox" name="Fields[]" value="FirstName AS 'First Name'" checked/> First Name</td>
                    <td><input type="checkbox" name="Fields[]" value="LastName AS 'Last Name'" checked/> Last Name</td>
                    <td><input type="checkbox" name="Fields[]" value="Gender" checked/> Gender</td>
                    <td><input type="checkbox" name="Fields[]" value="PhoneNumber AS 'Phone Number'" checked/> Phone Number</td>
                </tr>
                <tr>
                    <td><input type="checkbox" name="Fields[]" value="Degree" checked/> Degree</td>
                    
                    <td><input type="checkbox" name="Fields[]" value="ProfessionTitle AS 'Profession'" checked/> Profession</td>
                    <td><input type="checkbox" name="Fields[]" value="DateHired AS 'Date Hired'" checked/> Date Hired</td>
                </tr>
            </table>
        	<br /><br />
            <input type="submit" value="Search Doctor"/>
        
    	</form> <br /><br />
        
        <?php
		$counter = 0;
		
		echo '
			<table border="1">
				<tr>
					<th colspan="'.$n.'">Doctors in our Hospital:</th>
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
