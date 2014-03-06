<div id="sidenav">
	<?php
	if(isset($_COOKIE['access'])&&$_COOKIE['access']=='admin') {
	?>
	<h4>Registration:</h4>
    <ul>
    <li><a href="registeremployee.php"><u>Employee Registration</u></a></li>
    <li><a href="registerpatient.php"><u>Patient Registration</u></a></li>
    <li><a href="404.php"><i>Visit Registration</i></a></li>
    </ul>
    <h4>Entry:</h4>
    <ul>
    <li><a href="enterjob.php"><u>Job</u></a></li>
    <li><a href="enterprofession.php"><u>Profession</u></a></li>
    </ul>
    <h4>Administrative:</h4>
    <ul>
    <li><a href="404.php"><i>Billing</i></a></li>
    <li><a href="adminpayroll.php"><u>Pay Roll</u></a></li>
    </ul>
    <h4>Lookups:</h4>
    <ul>
    <li><a href="lookupemployee.php"><u>All Employees</u></a></li>
    <li><a href="404.php"><i>Pay Roll</i></a></li>
    <li><a href="lookupdoctor.php"><u>Doctors</u></a></li>
    <li><a href="lookupnurse.php"><u>Nurses</u></a></li>
    <li><a href="lookuppatient.php"><u>Patients</u></a></li>
    </ul>
    <h4>Medication:</h4>
    <ul>
    <li><a href="404.php"><i>Drug Orders</i></a></li>
    <li><a href="404.php"><i>Supplier Information</i></a></li>
    </ul>
    <?php
	} else if(isset($_COOKIE['access'])&&$_COOKIE['access']=='doctor') {
	?>
    <h4>Registration:</h4>
    <ul>
    <li><a href="registerpatient.php"><u>Patient Registration</u></a></li>
    </ul>
    <h4>Record Lookup:</h4>
    <ul>
    <li><a href="lookuppatient.php"><u>Patients</u></a></li>
    <li><a href="404.php"><i>Past Medications</i></a></li>
    <li><a href="404.php"><i>Past Admittance</i></a></li>
    </ul>
    <h4>Medication:</h4>
    <ul>
    <li><a href="404.php"><i>Drug Stock</i></a></li>
    </ul>
    <h4>My PayRoll:</h4>
    <ul>
    <li><a href="404.php"><i>My PayRoll</i></a></li>
    </ul>
    <?php
	} else if(isset($_COOKIE['access'])&&$_COOKIE['access']=='nurse') {
	?>
    <h4>Registration:</h4>
    <ul>
    <li><a href="registerpatient.php"><u>Patient Registration</u></a></li>
    </ul>
    <h4>Record Lookup:</h4>
    <ul>
    <li><a href="lookuppatient.php"><u>Patients</u></a></li>
    <li><a href="404.php"><i>Past Medications</i></a></li>
    <li><a href="404.php"><i>Past Admittance</i></a></li>
    </ul>
    <h4>Medication:</h4>
    <ul>
    <li><a href="404.php"><i>Drug Stock</i></a></li>
    </ul>
    <h4>My PayRoll:</h4>
    <ul>
    <li><a href="404.php"><i>My PayRoll</i></a></li>
    </ul>
    <h4>Rooms:</h4>
    <ul>
    <li><a href="404.php"><i>Room Lookup</i></a></li>
    </ul>
    <?php 
	} else {
	?>
    <h4>My Record Lookup:</h4>
    <ul>
    <li><a href="myrecord.php"><u>Patient Profile Search</u></a></li>
    <li><a href="404.php"><i>Past Medications</i></a></li>
    <li><a href="404.php"><i>Past Admittance</i></a></li>
    </ul>
    <h4>Lookups:</h4>
    <ul>
    <li><a href="lookupdoctor.php"><u>Doctors</u></a></li>
    <li><a href="lookupnurse.php"><u>Nurses</u></a></li>
    </ul>
    <?php
	}
	?>
</div>
<!--
	<h4>Registration:</h4>
    <ul>
    <li><a href="registeremployee.php"><u>Employee Registration</u></a></li>
    <li><a href="registerpatient.php"><u>Patient Registration</u></a></li>
    <li><a href="404.php"><i>Visit Registration</i></a></li>
    </ul>
    <h4>Entry:</h4>
    <ul>
    <li><a href="enterjob.php"><u>Job</u></a></li>
    <li><a href="enterprofession.php"><u>Profession</u></a></li>
    </ul>
    <h4>Administrative:</h4>
    <ul>
    <li><a href="404.php"><i>Billing</i></a></li>
    <li><a href="adminpayroll.php"><u>Pay Roll</u></a></li>
    </ul>
    <h4>Lookups:</h4>
    <ul>
    <li><a href="lookupemployee.php"><u>All Employees</u></a></li>
    <li><a href="">Pay Roll</a></li>
    <li><a href="lookupdoctor.php"><u>Doctors</u></a></li>
    <li><a href="lookupnurse.php"><u>Nurses</u></a></li>
    <li><a href="lookuppatient.php"><u>Patients</u></a></li>
    </ul>
    -->