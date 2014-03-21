<?php
include_once 'core.php';
require_once '../config.php';

$http_client_ip;
$http_x_forwarded_for;
$remote_addr = $_SERVER;
if(isset($_SERVER['HTTP_CLIENT_IP']))
	$http_client_ip = $_SERVER['HTTP_CLIENT_IP'];
if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	$http_x_forwarded_for = $_SERVER['HTTP_X_FORWARDED_FOR'];
if(isset($_SERVER['REMOTE_ADDR']))
	$remote_addr = $_SERVER['REMOTE_ADDR'];

if(!empty($http_client_ip)){
	$ip_address = $http_client_ip;	
} else if(!empty($http_x_forwarded_for)){
	$ip_address = $http_x_forwarded_for;
} else {
	$ip_address = $remote_addr;
}


$dbServer = DB_HOST;
$dbuserName = DB_USER;
$dbpassword = DB_PASSWORD;
$dbName = DB_DATABASE;

// ********** Connect. Change fields above.
// ********** Do not edit below
$con = new mysqli($dbServer, $dbuserName, $dbpassword, $dbName);

if($con->connect_errno){
	$errmessage = 'Could not connect to MySQL database server: (' . adjust($con->connect_errno). ') '. adjust($con->connect_error);
	$err = true;
}
$con->autocommit(FALSE);
?>