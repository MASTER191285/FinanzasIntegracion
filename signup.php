<?php 
$userClass = new userClass();
$errorMsgReg='';
$errorMsgLogin='';

/* Signup Form */
if (!empty($_POST['signupSubmit'])) 
{
    $username=$_POST['registerUsername'];
    $email=$_POST['registerEmail'];
    $password=$_POST['registerPassword'];
    $name=$_POST['nombre'];
    /*Lógica de subida de imagen*/
    $directorio = "img/";
    $image = basename($_FILES["registerPhoto"]["name"]);
    $destino = $directorio . $image;
    $tipoArchivo = pathinfo($destino,PATHINFO_EXTENSION);
    // Extensiones permitidas
    $extPermitida = array('jpg','png','jpeg','JPG','PNG','JPEG');
    /*Fin Lógica de subida de imagen*/
    /* Regular expression check */
    $username_check = preg_match('~^[A-Za-z0-9_]{3,20}$~i', $username);
    $email_check = preg_match('~^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+.([a-zA-Z]{2,4})$~i', $email);
    $password_check = preg_match('~^[A-Za-z0-9!@#$%^&*()_]{6,20}$~i', $password);

    if($username_check && $email_check && $password_check && strlen(trim($name))>0) 
    {
        $uid=$userClass->userRegistration($username,$password,$email,$name,$image);
        if($uid)
        {
            $url=BASE_URL.'views/dashboard.php';
            header("Location: $url"); // Page redirecting to home.php 
        }
        else
        {
            $errorMsgReg="El Nombre de usuario o correo especificado ya existe en el sistema.";
        }
    }
}

?>