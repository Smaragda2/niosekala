<?php
	$u = $_REQUEST['username'];
	$p = $_REQUEST['pass'];
	$mysqli = $_SESSION['dbconnect'];


	$sql = "SELECT COUNT(*) as ok FROM Admin WHERE username ='".$u."' AND pass = '".$p."'";
	$stmt = $mysqli->prepare($sql);
	$stmt->execute();
	$results = $stmt->get_result();

	if($row = $results->fetch_assoc()){		
		if($row["ok"] == '1'){
			$flag = 1;
			$_SESSION['admin'] = "ok";	
		print<<<END
			<script>
				window.location = "index.php";
			</script>
END;
		}else{
			$flag = 0;
			$_SESSION['admin'] = "?";
		print<<<END
			<script>
				window.location = "index.php";
			</script>
END;
		}
	}else{
			$flag = 0;
			$_SESSION['admin'] = "?";
		print<<<END
			<script>
				window.location = "index.php";
			</script>
END;
	}
?>