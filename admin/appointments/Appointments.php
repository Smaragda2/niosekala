
<?php

 	$mysqli = $_SESSION['dbconnect'];
 	$getAppointments = "SELECT * FROM Request WHERE isConfirmed = true and isCompleted = false and isCancelled=false ORDER BY SUBSTRING(hours,3)";
	$stmt = $mysqli->prepare($getAppointments);
	$stmt->execute();
	$results = $stmt->get_result();

	print "<div><h2><b>Επιβεβαιωμένα Ραντεβού</b></h2><br><hr><br>";
	
 	while ($row = $results->fetch_assoc()){
 	 	$productId = $row['selectedProductID'];
		$name = $row['name'];
		$hours = $row['hours'];
		$whereTo = $row['whereTo'];		
		$notes= $row['notes'];
		$createdAt= $row['createdAt'];
		$email= $row['email'];
		$tel = $row['tel'];
		$skypeName = $row['skypeName'];
		$isPaid = $row['isPaid'];
		$token = $row['paymentToken'];
		
		$productName = "δοκιμη";
		$price = 0;

	 	$getProducts = "SELECT * FROM `Product` where id = $productId";
		$stmt2 = $mysqli->prepare($getProducts);
		$stmt2->execute();
		$results2 = $stmt2->get_result();
	 	while ($rowP = $results2->fetch_assoc()){
			$productName = $rowP['name'];
			$price = $rowP['price'];
		}
				
		print<<<END
			<br>
			<form action="?p=Confirmation" method="POST" enctype="multipart/form-data" id="requestForm" class="needs-validation was-validated" novalidate>	
				<input type="hidden" id="id" name="id" value="$productId">
				<input type="hidden" id="productName" name="productName" value="$productName">
				<input type="hidden" id="price" name="price" value="$price">
				<input type="hidden" id="hours" name="hours" value="$hours">
				<input type="hidden" id="email" name="email" value="$email">
				<input type="hidden" id="createdAt" name="createdAt" value="$createdAt">
				<input type="hidden" id="token" name="token" value="$token">

				<div class="row border border-info" >
					<p>
						Ραντεβού με τον/την <b>$name</b> στις <b>$hours</b>, για την Υπηρεσία <b>$productName</b>.<br>
END;
		if($whereTo == "tel"){
			print<<<END
							Ο/Η $name ζήτησε το ραντεβού να πραγματοποιηθεί μέσω: <b>Τηλέφωνο</b>.<br>
							Τα στοιχεία επικοινωνίας είναι:<br>
							 &nbsp;&nbsp;<b>Τηλέφωνο</b>: $tel <br>
END;
		}else{
			print<<<END
							Ο/Η $name ζήτησε το ραντεβού να πραγματοποιηθεί μέσω: <b>$whereTo</b>.<br>
							Τα στοιχεία επικοινωνίας είναι:<br>
							&nbsp;&nbsp;<b>Τηλέφωνο</b>: $tel <br>
END;
		}
		if($whereTo == "Skype"){
			print "&nbsp;&nbsp;<b>SkypeName</b>: ".$skypeName."<br>";
		}
		print<<<END
							Σημειώσεις για το Ραντεβού:<br>
							&nbsp;&nbsp;<b>$notes</b>
							
					</p>
				</div>
				<br>
				<div class="row">
					<div class="col align-self-end" align="right">
						<input type="submit" id="completeAppointment" name="completeAppointment"  value="Το Ραντεβού ολοκληρώθηκε" style="background-color:#37c0fb" />
					</div>
				</div>
			</form>
			<br><hr><br>
			<form action="?p=Confirmation" method="POST" enctype="multipart/form-data" id="requestForm" class="needs-validation was-validated" novalidate>
				<input type="hidden" id="id" name="id" value="$productId">
				<input type="hidden" id="productName" name="productName" value="$productName">
				<input type="hidden" id="price" name="price" value="$price">
				<input type="hidden" id="hours" name="hours" value="$hours">
				<input type="hidden" id="email" name="email" value="$email">
				<input type="hidden" id="createdAt" name="createdAt" value="$createdAt">
				<input type="hidden" id="token" name="token" value="$token">

				<div class="col-md-5 mb-3">
				    <label for="date">Εισάγετε την νέα Ημ/νια και Ώρα του Ραντεβού:</label>
				    <input type="text" class="form-control" id="date" name="date" value="$hours" placeholder="DD-MM-YYYY HH:MM" pattern="[0-9]{2}-[0-9]{2}-[0-9]{4} [0-9]{2}:[0-9]{2}" title="Enter a date in this formart DD-MM-YYYY HH:MM" onkeyup="enableButton();" required>
					<div class="invalid-feedback">Παρακαλώ συμπληρώστε την Ημ/νια και Ώρα όπως το παράδειγμα: 12-05-2020 17:30.</div>
				</div>
				<br>
				<input type="submit" id="changeHour" name="changeHour"  value="Αλλαγής της Ημερομηνίας" style="background-color:red"/>
			</form>
			<br><hr><br>
			<div>
END;

	}
	

?>
