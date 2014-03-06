<?php
require_once 'php/connect.php';
require_once 'php/core.php';


$err = false;
$errmessage = '';
$inserttable1 = 'Patient';
$inserttable2 = 'Insurance';
$defaultfield1 = 'HPId';
$fetchtable1 = 'Insurance';
$defaultfield2 = 'BloodTypeId';
$fetchtable2 = 'BloodType';


//For Inserting Query New Insurance
if(isset($_POST['companyname'])&&isset($_POST['phonenumber2'])) {
	$companyname = adjust($_POST['companyname']);
	$phonenumber2 = adjust($_POST['phonenumber2']);
	
	if(isset($_POST['streetaddress2'])) {
		$streetaddress2 = adjust($_POST['streetaddress2']);
	} else {
		$streetaddress2 = '';
	}
	if(isset($_POST['city2'])) {
		$city2 = adjust($_POST['city2']);
	} else {
		$city2 = '';
	}
	if(isset($_POST['state2'])) {
		$state2 = adjust($_POST['state2']);
	} else {
		$state2 = '';
	}
	if(isset($_POST['zipcode2'])) {
		$zipcode2 = adjust($_POST['zipcode2']);
	} else {
		$zipcode2 = '';
	}
	
	if(!empty($companyname)&&!empty($phonenumber2)) {
		$insertquery1 = "INSERT INTO $inserttable2(CompanyName, PhoneNumber, StreetAddress, City, State, ZipCode) VALUES('$companyname','$phonenumber2', '$streetaddress2', '$city2', '$state2', '$zipcode2');";
	} /*else {
		$errmessage .= 'Please enter both Job Title and Wage\nJob entered will be N/A';
		$err = true;
	}*/
}

//For Insert Query Patient
if(isset($_POST['firstname'])&&isset($_POST['lastname'])&&isset($_POST['dob'])&&isset($_POST['gender'])&&isset($_POST['socialsecurity'])&&isset($_POST['bloodtype'])&& isset($_POST['smoke'])) {
	'<br /> Entered Isset';
	$firstname = adjust($_POST['firstname']);
	$lastname = adjust($_POST['lastname']);
	$dob = adjust($_POST['dob']);
	$gender = adjust($_POST['gender']);
	$socialsecurity = adjust($_POST['socialsecurity']);
	$bloodtype = adjust($_POST['bloodtype']);
	$smoke = adjust($_POST['smoke']);
	
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
	if(isset($_POST['insuranceproviderid'])) {
		$insuranceproviderid = adjust($_POST['insuranceproviderid']);
	} else {
		$insuranceproviderid = '';
	}
	if(isset($_POST['insurancenumber'])) {
		$insurancenumber = adjust($_POST['insurancenumber']);
	} else {
		$insurancenumber = '';
	}
	if(isset($_POST['allergies'])) {
		$allergies = adjust($_POST['allergies']);
	} else {
		$allergies = '';
	}
	if(isset($_POST['work'])) {
		$work = adjust($_POST['work']);
	} else {
		$work = '';
	}
	
	if(!empty($firstname)&&!empty($lastname)&&!empty($dob)&&!empty($gender)&&!empty($socialsecurity)&&!empty($bloodtype)&&!empty($smoke)) {
		
		$insertquery2 = "INSERT INTO $inserttable1(FirstName, LastName, DOB, Gender, PhoneNumber, StreetAddress, City, State, ZipCode, SocialSecurity, InsuranceProviderId, InsuranceNumber, BloodType, Allergies, Smoke, Work) VALUES ('$firstname', '$lastname', '$dob', '$gender', '$phonenumber', '$streetaddress', '$city', '$state', '$zipcode', '$socialsecurity', '$insuranceproviderid', '$insurancenumber', '$bloodtype', '$allergies', '$smoke', '$work');";
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
		$insuranceidfetch = $con->query("SELECT LAST_INSERT_ID()");
		$insuranceidrow = $insuranceidfetch->fetch_assoc();
		$insuranceproviderid = $insuranceidrow['LAST_INSERT_ID()'];
		$errmessage .= $companyname . ' is added... \n';
		$err = true;
	}
	
	if(!empty($insertquery2)) {
		$insertquery2 = "INSERT INTO $inserttable1(FirstName, LastName, DOB, Gender, PhoneNumber, StreetAddress, City, State, ZipCode, SocialSecurity, InsuranceProviderId, InsuranceNumber, BloodType, Allergies, Smoke, Work) VALUES ('$firstname', '$lastname', '$dob', '$gender', '$phonenumber', '$streetaddress', '$city', '$state', '$zipcode', '$socialsecurity', '$insuranceproviderid', '$insurancenumber', '$bloodtype', '$allergies', '$smoke', '$work');";
		$con->query($insertquery2);
		$errmessage .= $firstname . ' '. $lastname . ' is registered\n';
		$err = true;
	}
	
	$con->query("COMMIT;");
	//echo '<br />COMMIT;';
} catch (mysqli_sql_exception $e) {
	//echo '<br />ROLLBACK;';
	echo $errmessage = 'Database error: ' . adjust($con->error) . ' & \n';
	throw $e;
	$err = true;
	$con->query("ROLLBACK;");
}

if($con-> error) {
	echo $errmessage = 'Database error: ' . adjust($con->error) . ' & \n'.$e;
	$err = true;
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
    <title>Patient Registration</title>
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
    	<h1 id="title">Patient Registration</h1>        
    
   	</div>
   	<?php
	include 'sidenav.php';
	?>
    <div id="content">
    
    <form action="registerpatient.php" method="POST">
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
        <div id="patientinsurancedisplay">
        Insurance Provider: <br />
         <select name="insuranceproviderid">
        <?php
			while($row1=$result1->fetch_assoc()) {
				$counter = 0;
				echo '<option value="'.$row1['HPId'].'">';
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
        Company Name: *<br />
        <input type="text" name="companyname" size="30" maxlength="30"/> <br /><br />
        Phone Number: *(10 digits only)<br />
        <input type="text" name="phonenumber2" size="30" maxlength="10"/> <br /><br />
        Street Address: <br />
        <input type="text" name="streetaddress2" size="30" maxlength="40"/> <br /><br />
        City: <br />
        <input type="text" name="city2" size="30" maxlength="15"/> <br /><br />
        State: <br />
        <input type="text" name="state2" size="10" maxlength="2"/> <br /><br />
        Zip Code: <br />
        <input type="text" name="zipcode2" size="10" maxlength="5"/> <br /><br />
        
        
        </div>
        Insurance Number: <br />
        <input type="text" name="insurancenumber" size="30" maxlength="12"/> <br /><br />
        Blood Type:* <br />
        <select name="bloodtype">
        <?php
			while($row2=$result2->fetch_assoc()) {
				$counter = 0;
				echo '<option value="'.$row2['BloodTypeId'].'">';
				while($counter<$n2) {
					echo '- '.$row2[$fieldname2[$counter]].' -';
					$counter++;
				}
				echo '</option>';
			}
		?>
        </select> <br /><br />
        Allergies: <br />
        <input type="text" name="allergies" size="30" maxlength="30"/> <br /><br />
        Smoke: *<br />
        <input type="radio" name="smoke" value="Y" /> Yes<br />
        <input type="radio" name="smoke" value="N" /> No<br /><br />
        Work: <br />
        <input type="text" name="work" size="30" maxlength="20"/> <br /><br />
    
    	<input type ="submit" value="Add Patient"/>
        
    </form>
    </div>
    <?php include 'footer.php';?>
</div>
</body>
</html>