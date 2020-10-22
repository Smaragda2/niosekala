<div class="container">

	<h3 style="text-align:center">Δημιουργία Νέου Ραντεβού:</h3>
<?php
 	$mysqli = $_SESSION['dbconnect'];
 	$getServices = "SELECT * FROM Product";
	$stmt = $mysqli->prepare($getServices);
	$stmt->execute();
	$results = $stmt->get_result();
	
 	while ($row = $results->fetch_assoc()){
 	 	$id = $row['id'];
		$nameS = $row['name'];
		$price = $row['price'];
		$description = $row['description'];
		$duration = $row['duration'];

		print<<<END
		
			<form action="?p=Appointment" method="POST" enctype="multipart/form-data" class="needs-validation" style="width:100%" novalidate>
				<div class="row">
					<label for="firstRow">ID: $id</label>
					<input type="hidden" id="id" name="id" value="$id"/>
				</div>
				<div class="row" id="firstRow">
					<div class="col-md-5 mb-3">
						<label for="name">Name</label>
						<input type="text" id="name" name="name" value="$nameS" readonly/>
					</div>
		
					<div class="col-md-3 mb-3">
						<label for="duration">Duration</label>
						<input type="text" id="duration" name="duration" value="$duration" readonly/>
					</div>
					<div class="col-md-3 mb-3">
						<label for="price">Price</label>
						<input type="text" id="price" name="price" value="$price" readonly />
					</div>
				</div>
				<div class="row" style="width:95%">
					<div class="col">
						<label for="description">Description</label>
						<textarea type="description" id="description" name="description" rows="7" readonly> $description </textarea>
					</div>
				</div>
				<div class="row" style="width:95%">
					<div class="col align-self-end" align="right">
						<input type="submit" name="Submit" value="Δημιουργία Νέου Ραντεβού">
					</div>
				</div>
			</form>

END;
			
}
?>
</div>
