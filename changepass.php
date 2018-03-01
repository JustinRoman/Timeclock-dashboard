<?php
	session_start();
	$conn = new PDO('mysql:host=localhost;dbname=gmloginsystem',"root","");

	$currentPassword = $_POST['prevpass'];
	$newPass = $_POST['nupass'];
	$confirmPass = $_POST['renupass'];

	if(empty($currentPassword)||empty($newPass)||empty($confirmPass)){
		echo '<script>alert ("Empty Fields!") 
		window.location.href = "../gmloginfinal/index.php";</script>';
		die();
	}else if($newPass!=$confirmPass){
		//
		echo $newPass . " " . $renupass;
		echo '<script>alert ("Passwords does not match!") 
		window.location.href = "../gmloginfinal/index.php";</script>';
		die();
	}else{
		$password_hashed = password_hash($newPass, PASSWORD_BCRYPT);

		echo $password_hashed . $_SESSION['username'];

		$stmt = $conn->prepare("UPDATE users set password = ? AND name = ?");
		$stmt->execute([$password_hashed, $_SESSION['full_name']]);

		header('location: ../gmloginfinal/index.php');
	}

?>
