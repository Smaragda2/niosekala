<?php	
	$mysqli = $_SESSION['dbconnect'];

	if(isset($_POST['UpdateSubmit'])){
		
		$name = $_POST['name'];
		$price = $_POST['price'];
		$duration = $_POST['duration'];
		$description = $_POST['description'];
		$canBeAnonumoys = $_POST['canBeAnonymous'];
		$id = $_POST['id'];
		
		$updateServices = "";

	 	if($canBeAnonumoys == 'yes')
			$updateServices = "UPDATE Product SET name=?, price=$price ,duration=? ,description=? ,canBeAnonymous=true WHERE id = ?";
		else
			$updateServices = "UPDATE Product SET name=?, price=$price ,duration=? ,description=? ,canBeAnonymous=false WHERE id = ?";

		$stmt = $mysqli->prepare($updateServices);
		$stmt->bind_param('sssi', $name, $duration, $description, $id);
		$status = $stmt->execute();

		if($status === false){
			print "something went wrong.";
		}else{
		
				print<<<END
				Η Υπηρεσία ενημερώθηκε με επιτυχία.
			<script>
				window.location = "index.php?p=Services";
			</script>
END;
		}
	}
	
	if(isset($_POST['addSubmit'])){
		$name = $_POST['name'];
		$price = $_POST['price'];
		$duration = $_POST['duration'];
		$description = $_POST['description'];
		$canBeAnonumoys = $_POST['canBeAnonymous'];
		
		$addServices = "";

	 	if($canBeAnonumoys == 'yes')
			$addServices = "INSERT INTO `Product`( `name`, `price`, `duration`, `description`, `canBeAnonymous`,isDeleted) VALUES (?,$price,?,?,true,false)";
		else
			$addServices = "INSERT INTO `Product`( `name`, `price`, `duration`, `description`, `canBeAnonymous`,isDeleted) VALUES (?,$price,?,?,false,false)";

		$stmt = $mysqli->prepare($addServices);
		$stmt->bind_param('sss', $name, $duration, $description);
		$status = $stmt->execute();
		
		if($status === false){
			print "something went wrong.";
		}else{
		
				print<<<END
				Η Υπηρεσία προστέθηκε με επιτυχία.
			<script>
				window.location = "index.php?p=Services";
			</script>
END;
		}
	}
	
	if(isset($_POST['deleteSubmit'])){
		$id = $_POST['id'];
		$name = $_POST['name'];
		$price = $_POST['price'];
		$duration = $_POST['duration'];
		$description = $_POST['description'];
		
		$deleteServices = "Update Product set isDeleted=true where id = ?";
		$stmt = $mysqli->prepare($deleteServices );
		$stmt->bind_param('i', $id);
		$status = $stmt->execute();
		
		if($status === false){
			print "something went wrong.";
		}else{
		
				print<<<END
				Η Υπηρεσία διαγράφηκε με επιτυχία.
			<script>
				window.location = "index.php?p=Services";
			</script>
END;
		}
	}

?>

