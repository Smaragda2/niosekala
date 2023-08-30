<?php
	$mysqli = $_SESSION['dbconnect'];

	if(isset($_POST['addSubmit'])){
		$title = $_POST['title'];
		$smallDescription = $_POST['smallDescription'];
		$body = $_POST['body'];

		$addArticle =  "INSERT INTO `Article`( `title`, `smallDescription`, `body`, isDeleted) VALUES (?,?,?,false)";

		$stmt = $mysqli->prepare($addArticle);
		$stmt->bind_param('sss', $title, $smallDescription, $body);
		$status = $stmt->execute();

		if($status === false) {
			print "something went wrong.";
		} else {
			print<<<END
				Το Άρθρο προστέθηκε με επιτυχία.
			<script>
				window.location = "index.php?p=Articles";
			</script>
END;
		}
	}

	if(isset($_POST['deleteSubmit'])){
		$id = $_POST['id'];

		$deleteArticle = "Update Article set isDeleted=true where id = ".$id;
		$stmt = $mysqli->prepare($deleteArticle);
		$status = $stmt->execute();

		if($status === false) {
			print "something went wrong.";
		} else {
			print<<<END
				Το Άρθρο διαγράφηκε με επιτυχία.
			<script>
				window.location = "index.php?p=Articles";
			</script>
END;
		}
	}

?>
