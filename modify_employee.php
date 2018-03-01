<?php
	session_start();
	require('connection.php');

	try {
		$id = trim($_POST['employee_id']);
		$name = trim($_POST['name']);
		$dept = trim($_POST['dept']);
		$email = trim($_POST['email']);
		$office = trim($_POST['office']);

		if(empty($id) || empty($name) || empty($dept) || empty($email) || empty($office)) {
			echo '
				<script>
					alert("Empty Fields!");
					<meta http-equiv="refresh" content="0; url=employees.php" />
				</script>
			';
			die();
		} else if ($dept != "Techie" && $dept != "IT" && $dept != "NOC" && $dept != "Marketing") {
			echo '
				<script>
					alert("Invalid deparment!");
					<meta http-equiv="refresh" content="0; url=employees.php" />
				</script>
			';
			die();
		} else if($office != "PH" && $office != "FJ") {
			echo '
				<script>
					alert("Invalid Office");
					<meta http-equiv="refresh" content="0; url=employees.php" />
				</script>
			';
			die();
		} else {
			$update_memo = $conn->prepare("UPDATE users SET name = ?, dept = ?, email = ?, office = ? WHERE id = ?");
			$update_memo->execute([$name, $dept, $email, $office, $id]);
		}

		header('location: employees.php');
	} catch (Exception $e) {
		echo $e->getMessage();
	}

?>
