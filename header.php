<div id="header">
	<?php
    
    //Detect if page is currently in index.php
    $basename = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF']))-4);
	
	if(isset($_POST['username'])&&isset($_POST['password'])){
		$username=$_POST['username'];
		$password=$_POST['password'];
		if(!empty($username)&&!empty($password)){
			echo 'Error Logging in... Please check username and password.';
			
			if($username=='nurse'&&$password='joy'){
				$allow=1;
				setcookie('access','nurse',time()+4800);
				header("Location: index.php");
			} elseif ($username=='doctor'&&$password='who'){
				$allow=1;
				setcookie('access','doctor',time()+4800);
				header("Location: index.php");
			} elseif ($username=='admin'&&$password='guy'){
				$allow=1;
				setcookie('access','admin',time()+4800);
				header("Location: index.php");
			}
		}else{
			$errmessage .= 'Error Logging in: Please enter your username and password!';	
			$err = true;
		}
	}
    
if(isset($_COOKIE['access'])){
    ?>

<input type="button" value="Logout" style="float: right; margin-left: 5px; margin-right:35px; margin-top: 45px; width:100px;border:1px solid #ddd;background:blue;padding:3px;color:white;" onClick="location.href='logout.php'" />

<?php
} else {
?>
<div id="indexlogin">
<form action="<?php echo curPageURL(); ?>" id="login_form" method="POST">
	User:<br />
    <input type="text" name="username" onfocus="if(this.value == 'Username'){this.value = '';}" style="width: 150px;"/><br />
    Password:<br />
    <input type="password" name="password" onfocus="if(this.value == 'Password'){this.value = '';}" style="width: 150px;"/><br />
    
    <input type="submit" value="Sign In" style="background:blue; color: white; position: relative; margin: 0 auto;" />
</form>
</div>
<?php
}
?>

    <div id="logo">
    	<a href="index.php"><img src="files/medical-logo.gif"/></a>
    </div>
</div>