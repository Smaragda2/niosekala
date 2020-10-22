<script>

	function CheckRequired(){
		var allRequired = true;
		
		var fullName = new RegExp("^.{3,}$");
		var all = new RegExp("^.{1,}$");
		
		if( fullName.test($("#name").val()) 
			&& all.test($("#duration").val())
			&& all.test($("#price").val())
			&& all.test($("#description").val()) ){
	        allRequired = true;
		}else{
	        allRequired = false;
		}

		if(!allRequired){
			document.getElementById("errors").innerText = "Παρακαλώ συμπληρώστε όλα τα πεδία με αστερίσκο (*).";
			$("#UpdateSubmit").attr('disabled', true);
			$("#UpdateSubmit").attr('style','background-color:gray');
		}else{
			document.getElementById("errors").innerText = "";
			$("#UpdateSubmit").attr('disabled', false);
			$("#UpdateSubmit").attr('style','background-color:#CC0000');
		}
	}

</script>
<div class="container">
<h2><b>Διαγραφή Υπηρεσιών</b></h2>

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
			<form action="?p=UpdateServices" method="POST" enctype="multipart/form-data" class="needs-validation" style="width:100%" onload="CheckRequired();" novalidate>
				<div class="row">
					<label for="firstRow">ID: $id</label>
					<input type="hidden" id="id" name="id" value="$id"/>
				</div>
				<div class="row" id="firstRow">
					<div class="col-md-5 mb-3">
						<label for="name">*Name</label>
						<input type="text" id="name" name="name" value="$nameS" onkeyup="CheckRequired();" readonly/>
					</div>
		
					<div class="col-md-3 mb-3">
						<label for="duration">*Duration</label>
						<input type="text" id="duration" name="duration" value="$duration" onkeyup="CheckRequired();" readonly/>
					</div>
					<div class="col-md-3 mb-3">
						<label for="price">*Price</label>
						<input type="text" id="price" name="price" value="$price" onkeyup="CheckRequired();" readonly/>
					</div>
				</div>
				<div class="row" style="width:95%">
					<div class="col">
						<label for="description">*Description</label>
						<textarea type="description" id="description" name="description" rows="7" onkeyup="CheckRequired();" readonly> $description </textarea>
					</div>
				</div>
				<br>
				<div class="row">
					<label for="deleteSubmit">**ΣΗΜΕΙΩΣΗ: Η διαγραφή της Υπηρεσίας γίνεται οριστικά και δεν υπάρχει τρόπος επαναφοράς της.</label>
					<div class="col align-self-end" align="left">
						<input type="submit" id="deleteSubmit" name="deleteSubmit"  value="Διαγραφή Υπηρεσίας" style="background-color:#CC0000" />
					</div>
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
