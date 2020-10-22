<div class="container">

<h2><b>Ενημέρωση Υπηρεσιών</b></h2>

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
 	$getServices = "SELECT * FROM Product where isDeleted = false";
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
			<form action="?p=UpdateServices" method="POST" enctype="multipart/form-data" class="needs-validation" style="width:100%" novalidate>
				<div class="row">
					<label for="firstRow">ID: $id</label>
					<input type="hidden" id="id" name="id" value="$id"/>
				</div>
				<div class="row" id="firstRow">
					<div class="col-md-5 mb-3">
						<label for="name">*Name</label>
						<input type="text" id="name" name="name" value="$nameS" />
					</div>
		
					<div class="col-md-3 mb-3">
						<label for="duration">*Duration</label>
						<input type="text" id="duration" name="duration" value="$duration" />
					</div>
					<div class="col-md-3 mb-3">
						<label for="price">*Price</label>
						<input type="text" id="price" name="price" value="$price" />
					</div>
				</div>
				<div class="row" style="width:95%">
					<div class="col">
						<label for="description">*Description</label>
						<textarea type="description" id="description" name="description" rows="7" > $description </textarea>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5 mb-3">
						<label for="canBeAnonymous">*Επιλέξτε εάν μπορεί να ζητηθεί ανώνυμη συνεδρία:</label>
						<select class="custom-select d-block w-100" id="canBeAnonymous" name="canBeAnonymous" >
END;
			if($canBeAnonymous){
				print		'<option value="no" >Όχι</option>';
				print		'<option value="yes" selected>Ναι</option>';
			}else{
				print		'<option value="no" selected>Όχι</option>';
				print		'<option value="yes" >Ναι</option>';
			}
		print<<<END
						</select>
					</div>
				</div>
				<br>
				<br>
				<div class="row">
					<input type="submit" id="UpdateSubmit" name="UpdateSubmit"  value="Αποθήκευση Αλλαγών" style="background-color:#37c0fb" />
				</div>
			</form>
			<br>
			<hr>
			<br>
		<div id="errors"></div>

END;
			
}
?>
</div>
