<?php

	require('connection.php');

	try {
		if(isset($_POST['confirm_login'])) {
			$username = $_POST['username'];
			$password = $_POST['password'];

			$verify = $conn->prepare("SELECT password AS pw FROM users WHERE username = ?");
			$verify->execute([$username]);
			$verify = $verify->fetch(PDO::FETCH_ASSOC)['pw'];

			$validity = password_verify($password, $verify);

			if(empty($username) || empty($password)) {
				echo '
					<script>
						alert(\'Fields are empty!\');
					</script>
				';
				die();
			} else {
				if($validity == 1) {
					$id = $conn->prepare("SELECT id, name, dept, user_type, email FROM users WHERE username = ?");
					$id->execute([$username]);
					$row = $id->fetch(PDO::FETCH_ASSOC);

					$first_name = explode(' ', $row['name']);

					$_SESSION['employee_id'] = $row['id'];
					$_SESSION['username'] = $username;
					$_SESSION['name'] = $first_name[0];
                    $_SESSION['full_name'] = $row['name'];
					$_SESSION['dept'] = $row['dept'];
					$_SESSION['admin_type'] = $row['user_type'];
					$_SESSION['user_email'] = $row['email'];

					$get_timestamp = $conn->prepare("INSERT INTO timestamps VALUES('', ?, ?, ?, now(), null, null)");
					$get_timestamp->execute([$row['id'], $row['name'], $row['dept']]);

					header('location:index.php');
				} else {
					$message = "Ooops.. Invalid username/password.";
				}
			}
		} else if(isset($_POST['submit_memo'])) {
			$to = trim($_POST['to']);
			$from = trim($_POST['from']);
			$re = trim($_POST['re']);
			$content = trim($_POST['content']);

				if(empty($to) || empty($from) || empty($re) || empty($content)) {
					echo '
						<script>
							alert(\'Fields are empty!\');
						</script>
					';
					die();
				} else {
					$memo = $conn->prepare("INSERT INTO memos VALUES ('', ?, ?, ?, ?, now())");
					$memo->execute([$to, $from, $re, $content]);
				}
		} else if (isset($_POST['submit_newemp'])) {
			$f_name = trim($_POST['f_name']);
			$username = trim($_POST['username']);
			$password = trim($_POST['password']);
			$repassword = trim($_POST['repassword']);
			$email = trim($_POST['email']);
			$dept = trim($_POST['dept']);
			$office = trim($_POST['office']);

			if(empty($f_name) || empty($username) || empty($password) || empty($email) || empty($dept) || empty($office)){
				echo '
					<script>
						alert(\'Fields are empty!\');
					</script>
				';
				die();
			} else if($password != $repassword) {
				echo '
					<script>
						alert(\'Passwords do not match!\');
					</script>
					<meta http-equiv="refresh" content="0; url=new_employee.php" />
				';
				die();
			} else {
				$hashed_pass = password_hash($password, PASSWORD_BCRYPT);
				$newemp = $conn->prepare("INSERT INTO users VALUES ('', ?, ?, ?, ?, ?, ?, '1')");
				$newemp->execute([$f_name, $username, $hashed_pass, $email, $dept, $office]);
			}
		}
	} catch(PDOException $e) {
		echo $e->getMEssage();
	}
?>
