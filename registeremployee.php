<?php
require_once 'php/connect.php';
require_once 'php/core.php';

//Default Fields
$err = false;
$errmessage = '';
$inserttable1 = 'Employee';
$fetchtable1='Job';
$defaultfield1 = 'JobId';
$fetchtable2='Profession';
$defaultfield2 = 'ProfessionId';

//For Inserting New Job
if(isset($_POST['jobtitle'])&&isset($_POST['wage'])) {
	$jobtitle = adjust($_POST['jobtitle']);
	$wage = adjust($_POST['wage']);
	
	if(!empty($jobtitle)&&!empty($wage)) {
		$insertquery1 = "INSERT INTO Job(JobTitle,Wage) VALUES('$jobtitle','$wage');";
	} /*else {
		$errmessage .= 'Please enter both Job Title and Wage\nJob entered will be N/A';
		$err = true;
	}*/
}

//For Inserting New Profession
if(isset($_POST['professiontitle'])& isset($_POST['basesalary'])) {
	$professiontitle = adjust($_POST['professiontitle']);
	$basesalary = adjust($_POST['basesalary']);
	if(!empty($professiontitle)&&!empty($basesalary)) {
		$insertquery2 = "INSERT INTO Profession(ProfessionTitle,BaseSalary) VALUES('$professiontitle','$basesalary');";
	} /*else {
		$errmessage .= 'Please enter both Profession and Base Salary\nProfession entered will be N/A';
		$err = true;
	}*/
}

//For Insert Query
if(isset($_POST['firstname'])&&isset($_POST['lastname'])&&isset($_POST['dob'])&&isset($_POST['gender'])&&isset($_POST['socialsecurity'])&&isset($_POST['degree'])&&isset($_POST['jobid'])&&isset($_POST['professionid'])) {
	
	$firstname = adjust($_POST['firstname']);
	$lastname = adjust($_POST['lastname']);
	$dob = adjust($_POST['dob']);
	$gender = adjust($_POST['gender']);
	$socialsecurity = adjust($_POST['socialsecurity']);
	$jobid = adjust($_POST['jobid']);
	$professionid = adjust($_POST['professionid']);
	$degree = adjust($_POST['degree']);
	
	if(isset($_POST['phonenumber'])) {
		$phonenumber = adjust($_POST['phonenumber']);
	} else {
		$phonenumber = '';
	}
	if(isset($_POST['streetaddress'])) {
		$streetaddress = adjust($_POST['streetaddress']);
	} else {
		$streetaddress = '';
	}
	if(isset($_POST['city'])) {
		$city = adjust($_POST['city']);
	} else {
		$city = '';
	}
	if(isset($_POST['state'])) {
		$state = adjust($_POST['state']);
	} else {
		$state = '';
	}
	if(isset($_POST['zipcode'])) {
		$zipcode = adjust($_POST['zipcode']);
	} else {
		$zipcode = '';
	}
	
	if(!empty($firstname)&&!empty($lastname)&&!empty($dob)&&!empty($gender)&&!empty($socialsecurity)&&!empty($jobid)&&!empty($professionid)) {
		$insertquery3 = "INSERT INTO $inserttable1(FirstName, LastName, DOB, Gender, PhoneNumber, StreetAddress, City, State, ZipCode, SocialSecurity, Degree, JobId, ProfessionId) VALUES('$firstname', '$lastname', '$dob', '$gender', '$phonenumber', '$streetaddress', '$city', '$state', '$zipcode', '$socialsecurity', '$degree', '$jobid', '$professionid')";
	} else {
		$errmessage .= "Please fill in all required(*) fields!\n";
		$err = true;
	}
}

//Fetching field1
if(isset($_POST['fieldsort1'])&&!empty($_POST['fieldsort1'])) {
	$fieldsort1 = adjust($_POST['fieldsort1']);
} else {
	$fieldsort1 = $defaultfield1;
}
$fetchquery1 = "SELECT * FROM $fetchtable1 ORDER BY $fieldsort1;";
$fetchqueryfield1 = "SHOW COLUMNS FROM $fetchtable1";
	
//Fetching field2
if(isset($_POST['fieldsort2'])&&!empty($_POST['fieldsort2'])) {
	$fieldsort1 = adjust($_POST['fieldsort2']);
} else {
	$fieldsort2 = $defaultfield2;
}
$fetchquery2 = "SELECT * FROM $fetchtable2 ORDER BY $fieldsort2;";
$fetchqueryfield2 = "SHOW COLUMNS FROM $fetchtable2";


//MySQL Transaction
try{
	//echo '<br />START TRANSACTION;';
	$con -> query("START TRANSACTION");
	//echo $fetchquery1.'<br />'.$fetchquery2.'<br />';
	//echo $fetchqueryfield1.'<br />'.$fetchqueryfield2.'<br />';
	$result1 = $con->query($fetchquery1);
	$result2 = $con->query($fetchquery2);
	$resultfield1 = $con->query($fetchqueryfield1);
	$resultfield2 = $con->query($fetchqueryfield2);
	
	if(!empty($insertquery1)) {
		$con->query($insertquery1);
		//echo $insertquery1.'<br />';
		$jobidfetch = $con->query("SELECT LAST_INSERT_ID()");
		$jobrow = $jobidfetch->fetch_assoc();
		$jobid = $jobrow['LAST_INSERT_ID()'];
		$errmessage .= $jobtitle . ' is added... \n';
		$err = true;
	}
	
	if(!empty($insertquery2)) {
		$con->query($insertquery2);
		//echo $insertquery2.'<br />';
		$professionidfetch = $con->query("SELECT LAST_INSERT_ID()");
		$professionrow = $professionidfetch->fetch_assoc();
		$professionid = $professionrow['LAST_INSERT_ID()'];
		$errmessage .= $professiontitle . ' is added... \n';
		$err = true;
	}
	
	if(!empty($insertquery3)) {
		$insertquery3 = "INSERT INTO $inserttable1(FirstName, LastName, DOB, Gender, PhoneNumber, StreetAddress, City, State, ZipCode, SocialSecurity, Degree, JobId, ProfessionId) VALUES('$firstname', '$lastname', '$dob', '$gender', '$phonenumber', '$streetaddress', '$city', '$state', '$zipcode', '$socialsecurity', '$degree', '$jobid', '$professionid');";
		$con->query($insertquery3);
		$errmessage .= $firstname . ' '. $lastname . ' is registered\n';
		$err = true;
		/*
		if(!empty($insertquery2)) {
			$con -> query("");
		}
		if() {
			
		}
		*/
	}
	
	$con->query("COMMIT;");
	//echo '<br />COMMIT;';
} catch (exception $e) {
	//echo '<br />ROLLBACK;';
	echo $errmessage = 'Database error: ' . adjust($con->error) . ' & \n'.$e;
	$err = true;
	$con->query("ROLLBACK;");
}

//Fieldnames1
$fieldname1 = Array();
$counter = 0;
while($rowfield1 = $resultfield1 -> fetch_assoc()) {
	$fieldname1[$counter] = $rowfield1['Field'];
	$counter++;
}
$n1=$counter;

//Fieldnames2
$fieldname2 = Array();
$counter = 0;
while($rowfield2 = $resultfield2 -> fetch_assoc()) {
	$fieldname2[$counter] = $rowfield2['Field'];
	$counter++;
}
$n2=$counter;

$counter = 0;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Employee Registration</title>
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
    	<h1 id="title">Employee Registration</h1>        
    
   	</div>
   	<?php
	include 'sidenav.php';
	?>
    <div id="content">
    
    <form action="registeremployee.php" method="POST">
        First Name: *<br />
        <input type="text" name="firstname" size="30" maxlength="20"/> <br /><br />
        Last Name: *<br />
        <input type="text" name="lastname" size="30" maxlength="20"/> <br /><br />
        Date of Birth: *<br />
        <input type="date" name="dob" size="30" maxlength="11" /> <br /><br />
        Gender: *<br />
        <input type="radio" name="gender" value="M"/> Male<br />
        <input type="radio" name="gender" value="F"/> Female<br /><br />
        Phone Number: (10 digits only)<br />
        <input type="text" name="phonenumber" size="30" maxlength="10"/> <br /><br />
        Street Address: <br />
        <input type="text" name="streetaddress" size="30" maxlength="40"/> <br /><br />
        City: <br />
        <input type="text" name="city" size="30" maxlength="15"/> <br /><br />
        State: <br />
        <input type="text" name="state" size="10" maxlength="2"/> <br /><br />
        Zip Code: <br />
        <input type="text" name="zipcode" size="10" maxlength="5"/> <br /><br />
        Social Security: *<br />
        <input type="text" name="socialsecurity" size="30" maxlength="9"/> <br /><br />
        Degree: *<br />
        <input type="text" name="degree" size="30" maxlength="15" /> <br /><br />
        <div id="employeejobdisplay">
        Job: * (select one or add below)<br />
        <select name="jobid">
        <?php
			while($row1=$result1->fetch_assoc()) {
				$counter = 0;
				echo '<option value="'.$row1['JobId'].'">';
				while($counter<$n1) {
					echo '- '.$row1[$fieldname1[$counter]].' -';
					$counter++;
				}
				echo '</option>';
			}
		?>
        </select>
        <br />
        <br />
        - OR add: <br />
        Job Title: *<br />
        <input type="text" name="jobtitle" size="30" maxlength="20"/> <br />
        Wage: * (per hour)<br />
        $ <input type="text" name="wage" size="28" maxlength="11"/> <br />
            
        </div> <br /><br />
        
        <div id="employeeprofessiondisplay">
        Profession: * (select one or add below)<br />
        <select name="professionid">
        <?php
			while($row2=$result2->fetch_assoc()) {
				$counter = 0;
				echo '<option value="'.$row2['ProfessionId'].'">';
				while($counter<$n2) {
					echo '- '.$row2[$fieldname2[$counter]].' -';
					$counter++;
				}
				echo '</option>';
			}
		?>
        </select>
        <br />
        <br />
        - OR add: <br />
        Profession Title: *<br />
        <input type="text" name="professiontitle" size="30" maxlength="20"/> <br />
        Base Salary: * (per year)<br />
        $ <input type="text" name="basesalary" size="28" maxlength="11"/> <br />
        
        </div> <br /><br />
        
        <input type ="submit" value="Add Employee"/>
        
    </form>
    </div>
    <?php include 'footer.php';?>
</div>
</body>
</html>