<?php
	if(!isset($_SESSION['dbconnect'])){
		include_once "../../database/dbconnect.php";
		$database = new Database();
		$db = $database->getConnection();
		$_SESSION['dbconnect'] = $db;
	}
	
	$tel = $_GET['tel'];
	$exist = "";
//		$tel = '<script>$("#tel").val();</script>';
			
	$mysqli = $_SESSION['dbconnect'];
 	$getAnonymous = "SELECT count(*) AS exist FROM RequestAnonymous where tel='$tel'";
	$stmt = $mysqli->prepare($getAnonymous);
	$stmt->execute();
	$results = $stmt->get_result();

	$row = $results->fetch_assoc();
	$exist = $row['exist'];

	print $exist;
?>

