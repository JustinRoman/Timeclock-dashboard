<?php
	session_start();
	require('connection.php');

	try {
		$to = trim($_POST['to']);
		$from = trim($_POST['from']);
		$re = trim($_POST['re']);
		$content = trim($_POST['content']);
		$id = trim($_POST['memo_id']);

		if(empty($to) || empty($from) || empty($re) || empty($content)) {
			echo '
				<script>
					alert("Empty Fields!");
				</script>
			';
			die();
		} else {
			$update_memo = $conn->prepare("UPDATE memos SET to_ = ?, from_ = ?, subject = ?, content = ?, time_added = now() WHERE id = ?");
			$update_memo->execute([$to, $from, $re, $content, $id]);
		}

		header('location: memos.php');
	} catch (Exception $e) {
		echo $e->getMessage();
	}

?>
