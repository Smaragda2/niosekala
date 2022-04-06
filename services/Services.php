<div class="container row" style="width:100%;">
<!---
	<div class="jumbotron alert" style="font-family:'Calibri Light';font-size:xx-large;background:red;width:100%;color:black">
		Η σελίδα είναι υπό κατασκευή , θα είναι σύντομα διαθέσιμη με όλες τις δικλείδες ασφαλείας ενεργοποιημές ! Ευχαριστώ.
		<br><a href="https://fb.com/book/niosekala/" target="_blank" style="color:white">Εάν επιθυμείτε, μπορείτε να κλείσετε Ραντεβού μέσω της Σελίδας μας στο Facebook.</a>
	</div>
-->
	<div class="jumbotron alert" style="font-family:'Calibri Light';font-size:x-large;background:yellow;width:100%;color:black">
		<a href="?p=Oroi">Παρακαλώ πριν από οποιαδήποτε ενέργεια διαβάστε πρώτα τους Όρους Χρήσης.</a>
	</div>
	<div class="jumbotron alert" style="font-family:'Calibri Light';font-size:xx-large;background:orange;width:100%;color:black">
		<a href="https://fb.com/book/niosekala/" target="_blank" class="icon brands fa-facebook-f"><b> Για Δωρεάν Συνέδρια 30 λεπτά, πατήστε εδώ.</b></a>
	</div>
			<!-- GET DATA FROM THE DATABASE-->
			<!-- GET DATA FROM THE DATABASE-->
			<!-- GET DATA FROM THE DATABASE-->
			<!-- GET DATA FROM THE DATABASE-->
			<!-- GET DATA FROM THE DATABASE-->
			<!-- GET DATA FROM THE DATABASE-->
			<!-- GET DATA FROM THE DATABASE-->
			<!-- GET DATA FROM THE DATABASE-->
<?php
 	$mysqli = $_SESSION['dbconnect'];
 	$getServices = "SELECT * FROM Product where isDeleted=false";
	$stmt = $mysqli->prepare($getServices);
	$stmt->execute();
	$results = $stmt->get_result();

 	while ($row = $results->fetch_assoc()){
 	 	$id = $row['id'];
		$nameS = $row['name'];
		$price = $row['price'];
		$description = $row['description'];
		$duration = $row['duration'];
		$canBeAnonymous = $row['canBeAnonymous'];

		print<<<END
			<div  class="jumbotron" style="width:100%;background-color:#C1E0FF;">
				<form action="?p=Appointment" method="POST" enctype="multipart/form-data" class="needs-validation" style="width:100%" novalidate>
					<div class="row">
						<img loading="lazy" src="images/servicesNew.jpg" width="200px" height="200px">
						<input type="hidden" id="id" name="id" value="$id"/>
					</div>
					<div class="row" id="firstRow">
						<div class="col-11">
							<label for="name">Όνομα Υπηρεσίας</label>
							<input type="text" id="name" name="name" value="$nameS" readonly/>
						</div>
					</div>
					<div class="row" style="width:95%">
						<div class="col">
							<label for="description">Περιγραφή</label>
							<textarea type="description" id="description" name="description" rows="7" readonly> $description </textarea>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3 mb-3">
							<label for="duration">Διάρκεια</label>
							<input type="text" id="duration" name="duration" value="$duration" readonly/>
						</div>
						<div class="col-md-3 mb-3">
							<label for="price">Τιμή</label>
							<input type="text" id="price" name="price" value="$price" readonly />
						</div>
					</div>
					<div class="row" style="width:95%">
END;
			if($canBeAnonymous){
				print<<<END
						<div class="col align-self-start" align="left">
							<input type="submit" name="AnonymousSubmit" value="Ζητήστε Ανώνυμο Ραντεβού">
						</div>
END;
			}
			print<<<END
						<div class="col align-self-end" align="right">
							<input type="submit" name="Submit" value="Ζητήστε Ραντεβού">
						</div>
					</div>
				</form>
			</div>
			<br><br>
END;

}
?>
</div>
