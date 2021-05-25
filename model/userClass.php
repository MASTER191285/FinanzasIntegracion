<?php 
class userClass
{
/* User Login */
	public function userLogin($usernameEmail,$password)
	{
		try{
		$db = getDB();
		$hash_password= hash('sha256', $password); //Password encryption 
		$stmt = $db->prepare("SELECT uid FROM users WHERE (username=:usernameEmail or email=:usernameEmail) AND password=:hash_password"); 
		$stmt->bindParam("usernameEmail", $usernameEmail,PDO::PARAM_STR) ;
		$stmt->bindParam("hash_password", $hash_password,PDO::PARAM_STR) ;
		$stmt->execute();
		$count=$stmt->rowCount();
		$data=$stmt->fetch(PDO::FETCH_OBJ);
		$db = null;
		if($count)
			{
				$_SESSION['uid']=$data->uid; // Storing user session value
				return true;
			}
		else
			{
				return false;
			} 
		}
		catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
		}

	}

	/* User Registration */
	public function userRegistration($username,$password,$email,$name,$image)
	{
		try{
		$db = getDB();
		$st = $db->prepare("SELECT uid FROM users WHERE username=:username OR email=:email"); 
		$st->bindParam("username", $username,PDO::PARAM_STR);
		$st->bindParam("email", $email,PDO::PARAM_STR);
		$st->execute();
		$count=$st->rowCount();
		/*Lógica de subida de imagen*/
		$directorio = "img/";
		$image = basename($_FILES["registerPhoto"]["name"]);
		$destino = $directorio . $image;
		$tipoArchivo = pathinfo($destino,PATHINFO_EXTENSION);
		// Extensiones permitidas
		$extPermitida = array('jpg','png','jpeg','JPG','PNG','JPEG');
		/*Fin Lógica de subida de imagen*/

		if($count<1)
		{
			$stmt = $db->prepare("INSERT INTO users(username,password,email,name) VALUES (:username,:hash_password,:email,:name)");			
			$stmt->bindParam("username", $username,PDO::PARAM_STR) ;
			$hash_password= hash('sha256', $password); //Password encryption
			$stmt->bindParam("hash_password", $hash_password,PDO::PARAM_STR) ;
			$stmt->bindParam("email", $email,PDO::PARAM_STR) ;
			$stmt->bindParam("name", $name,PDO::PARAM_STR) ;
			
			$stmt2 = $db->prepare("INSERT INTO users(username,password,email,name, profile_pic) VALUES (:username,:hash_password,:email,:name,:profile_pic)");
			$stmt2->bindParam("username", $username,PDO::PARAM_STR) ;
			$hash_password2= hash('sha256', $password); //Password encryption
			$stmt2->bindParam("hash_password", $hash_password2,PDO::PARAM_STR) ;
			$stmt2->bindParam("email", $email,PDO::PARAM_STR) ;
			$stmt2->bindParam("name", $name,PDO::PARAM_STR) ;
			$stmt2->bindParam("profile_pic", $image, PDO::PARAM_STR) ;

			if (empty($_FILES["registerPhoto"]["name"])) {
					$stmt->execute();
					$uid=$db->lastInsertId(); // Last inserted row id
					$db = null;
					$_SESSION['uid']=$uid;
			}else{
				if(in_array($tipoArchivo, $extPermitida)){
					//Subir Imagen
					if(move_uploaded_file($_FILES["registerPhoto"]["tmp_name"], $destino)){
						$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$stmt2->execute();
						$uid=$db->lastInsertId(); // Last inserted row id
						$db = null;
						$_SESSION['uid']=$uid;
					}
				}
			}
						
			return true;
		}
		else
		{
			$db = null;
			return false;
		}

		} 
		catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
		}
	}

/* User Details */
	public function userDetails($uid)
	{
		try{
		$db = getDB();
		$stmt = $db->prepare("SELECT uid, email,username,name, profile_pic FROM users WHERE uid=:uid"); 
		$stmt->bindParam("uid", $uid,PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch(PDO::FETCH_OBJ); //User data
		return $data;
		}
		catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

}

?>