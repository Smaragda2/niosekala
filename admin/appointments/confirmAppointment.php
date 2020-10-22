<div id="errors"></div>

<div style="text-align:left">

	<h2><b>Αιτήσεις για Ραντεβού</b></h2>
<?php
	unset($_POST['confirmAppointment']);
	unset($_POST['confirmAnonymous']);

 	$mysqli = $_SESSION['dbconnect'];
 	$getAppointments = "SELECT * FROM Request WHERE isConfirmed = false AND isCancelled = false ORDER BY SUBSTRING(hours,3)";
	$stmt = $mysqli->prepare($getAppointments);
	$stmt->execute();
	$results = $stmt->get_result();

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
			<br>
			<h5>Ο/Η $name αιτήθηκε ραντεβού για τις $hours:</h5><br>
			<form action="?p=Confirmation" method="POST" enctype="multipart/form-data" id="requestForm" class="needs-validation was-validated" novalidate>	
				<input type="hidden" id="id" name="id" value="$productId">
				<input type="hidden" id="productName" name="productName" value="$productName">
				<input type="hidden" id="price" name="price" value="$price">
				<input type="hidden" id="hours" name="hours" value="$hours">
				<input type="hidden" id="email" name="email" value="$email">
				<input type="hidden" id="createdAt" name="createdAt" value="$createdAt">
				<input type="hidden" id="token" name="token" value="$token">


				<div class="row border border-info" style="width:90%">
					<p>
						Ο/Η <b>$name</b>, αιτήθηκε ραντεβού για την Υπηρεσία <b>$productName</b>, για την ημερομηνία <b>$hours</b>.<br>
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
				<input type="submit" id="confirmAppointment" name="confirmAppointment"  value="Επιβεβαίωση Ραντεβού" style="background-color:#37c0fb" />
			</form>
			<br>
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
END;

	}
?>
</div>
	

