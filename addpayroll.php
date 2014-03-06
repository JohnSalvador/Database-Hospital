<?php
require_once 'php/connect.php';
require_once 'php/core.php';


$err = false;
$errmessage = '';
$redirect = false;
$fetchtable1 = 'Employee';
$fetchtable2 = 'Job';
$fetchtable3 = 'Profession';
$date = date("Y-m-d");

if(isset($_GET['id'])&&!empty($_GET['id'])){
	$id = adjust($_GET['id']);
} else {
	$id = '';
	$errmessage = 'No employee selected for payroll';
	$redirect = true;
}


//Creating Fetch Query
$fields= "Id as 'Employee ID',";
$fieldarray = $_POST['Fields'];
if(empty($fieldarray)) {
	$fieldarray = array(0=>"FirstName AS 'First Name'", 1=>"LastName AS 'Last Name'",2=>"SocialSecurity AS 'Social Security'", 3=>"JobTitle AS 'Job'", 4=>"Wage", 5=>"ProfessionTitle AS 'Profession'", 6=>"BaseSalary AS 'Base Salary'", 7=>"DateHired AS 'Date Hired'", 8=>"StreetAddress AS 'Street Address'", 9=>"City", 10=>"State", 11=>"ZipCode", 12=>"Job.JobId AS 'Job ID'", 13=>"Profession.ProfessionId AS 'Profession ID'");
}
$count = count($fieldarray);
for($i=0; $i < $count; $i++) {
	$fields .= $fieldarray[$i];
	if($i<$count-1) {
		$fields .=', ';
	}
}

$fetchquery1 = "SELECT $fields FROM $fetchtable1 JOIN $fetchtable2 ON $fetchtable1.JobId=$fetchtable2.JobId JOIN $fetchtable3 ON $fetchtable1.ProfessionId=$fetchtable3.ProfessionId WHERE Id='$id';";


//Insert Query New Payroll Passed via Hidden Post
if(isset($_POST['hoursworked'])&&!empty($_POST['hoursworked'])) {
	$hoursworked = adjust($_POST['hoursworked']);
	$jobid = $_POST['jobid'];
	$professionid = $_POST['professionid'];
	$yearweek = $_POST['yearweek'];
	$basesalary = $_POST['basesalary'];
	$wage = $_POST['wage'];
	
	if($profession!="N/A"){
		$weekly = $basesalary/52;
		if($hoursworked>40) {
			$overtime = $hoursworked-40;
			
			$overtimepay = $overtime * $wage;
		}
		$amount = $weekly+$overtimepay;
	} else {
		$weekly = $wage*$hoursworked;
		if($hoursworked>40) {
			$overtime = $hoursworked-40;
			$hoursworked = 40;
			$overtimepay = $overtime * $wage * 2.0;
		}
		$weekly = $wage*$hoursworked;
		$amount = $weekly+$overtimepay;
	}
					
	$insertquery1 = "INSERT INTO PayRoll (EmployeeId, JobId, ProfessionId, HoursWorked, YearWeek, Amount) VALUES ('$id', '$jobid', '$professionid', '$hoursworked', '$yearweek', '$amount')";
}


//MySQL Transaction
try{
	$con->query("START TRANSACTION;");
	//echo '<br />START TRANSACTION;';
	$result = $con->query($fetchquery1);
	$result2 = $con->query($fetchquery1);;
	//echo '<br />'.$fetchquery1;
	if(!empty($insertquery1)) {
		//echo $insertquery1;
		$con->query($insertquery1);
	}
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
    <title>Pay Roll Systems~</title>
    <link rel="stylesheet" type="text/css" href="css/hospital.css" />
    <?php
    if($err)
    echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('$errmessage');
    </SCRIPT>");
	if($redirect)
    echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('$errmessage')
    window.location.href='index.php';
    </SCRIPT>");
	?>
</head>

<body>
<div id="wrapper">
    	
  	<div id="indexheader">
	<?php include 'header.php';?>
    	<br />
        <br />
    	<h1 id="title">Pay Roll Systems~</h1>        
    
   	</div>
   	<?php
	include 'sidenav.php';
	?>
    <div id="content">
    	<form action="addpayroll.php?id=<?php echo $id;?>" method="POST">
            <br />
        	<?php
			$counter = 0;
			
			echo '
				<table border="1">
					<tr>
						<th colspan="'.$n.'">Please Verify the information below:</th>
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
					if($fieldname2[$counter]=='Job ID') {
						$jobid = $row[$fieldname2[$counter]];
					} else if($fieldname2[$counter]=='Profession ID') {
						$professionid = $row[$fieldname2[$counter]];
					} else if($fieldname2[$counter]=='Profession') {
						$profession = $row[$fieldname2[$counter]];
					} else if($fieldname2[$counter]=='Base Salary') {
						$basesalary = $row[$fieldname2[$counter]];
					} else if($fieldname2[$counter]=='Wage') {
						$wage = $row[$fieldname2[$counter]];
					}
					echo '<td>'.$row[$fieldname2[$counter]].'</td>';
					$counter++;
				}
				echo '</tr>';
			}
			
			echo '</table> <br />';
			
			print_r($row);
			?>
            <br /><br />
            For this week,<br /><br />
            Enter Hours Worked:<br />
            <input type="number" name="hoursworked" size="30" maxlength="11"/> <br /><br />
            Date for Week: <br />
            <?php echo $date; ?> <br /><br />
            Amount (Will be calculated once Hours Worked is entered): <br />
            
            	<?php
				echo '<table border="1">';
				if(isset($_POST['hoursworked'])&&!empty($_POST['hoursworked'])) {
					$hoursworked = adjust($_POST['hoursworked']);
					if($profession!="N/A"){
						$weekly = $basesalary/52;
						if($hoursworked>40) {
							$overtime = $hoursworked-40;
							$overtimepay = $overtime * $wage;
						}
						$amount = $weekly+$overtimepay;
						echo '<tr>';
						echo '<th>Salary/52</th>';
						echo '<th>Overtime</th>';
						echo '<th>Overtime * Wage * 1.5</th>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>'. $weekly .'</td>';
						echo '<td>'. $overtime .'</td>';
						echo '<td>'. $overtimepay .'</td>';
						echo '</tr><br />';
						
					} else {
						$weekly = $wage*$hoursworked;
						if($hoursworked>40) {
							$overtime = $hoursworked-40;
							$hoursworked = 40;
							$overtimepay = $overtime * $wage * 2.0;
						}
						$weekly = $wage*$hoursworked;
						$amount = $weekly+$overtimepay;
						echo '<tr>';
						echo '<th>Wage * Hours</th>';
						echo '<th>Overtime</th>';
						echo '<th>Overtime * Wage * 1.5</th>';
						echo '</tr>';
						echo '<td>'. $weekly .'</td>';
						echo '<td>'. $overtime .'</td>';
						echo '<td>'. $overtimepay .'</td>';
						echo '</tr><br />';
						
					}
				}
				echo '</table>';
				echo 'Total Amount: $'.number_format($amount,2);
				
				
            	?>
                <br /><br />
            <input type="hidden" name="jobid" value="<?php echo $jobid; ?>" />
            <input type="hidden" name="professionid" value="<?php echo $professionid;?>" />
            <input type="hidden" name="yearweek" value="<?php echo $date;?>" />
            <input type="hidden" name="profession" value="<?php echo $profession;?>" />
            <input type="hidden" name="basesalary" value="<?php echo $basesalary;?>" />
            <input type="hidden" name="wage" value="<?php echo $wage;?>" />
            
    		<input type="submit" value="Add Payroll & Hours" />
    	</form> <br /><br />
    </div>
    <?php include 'footer.php';?>
</div>
<?php
//if(){

//}
?>
</body>
</html>