<?php 
$userClass = new userClass();
$errorMsgReg='';
$errorMsgLogin='';

/* Login Form */
if (!empty($_POST['loginSubmit'])) 
{
	$usernameEmail=$_POST['usernameEmail'];
	$password=$_POST['password'];
	if(strlen(trim($usernameEmail))>1 && strlen(trim($password))>1 )
		{
		$uid=$userClass->userLogin($usernameEmail,$password);
		if($uid)
		{
			$url=BASE_URL.'views/dashboard.php';
			header("Location: $url"); // Page redirecting to home.php 
		}
		else
		{
			$errorMsgLogin="Usuario o Contraseña Inválidos.";
		}
	}
}


?>