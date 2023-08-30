<div class="container">
<h2><b>Διαγραφή Άρθρων</b></h2>

<?php
 	$mysqli = $_SESSION['dbconnect'];
 	$getArticles = "SELECT * FROM Article where isDeleted=false";
	$stmt = $mysqli->prepare($getArticles);
	$stmt->execute();
	$results = $stmt->get_result();

 	while ($row = $results->fetch_assoc()){
    $id = $row['id'];
		$articleTitle = $row['title'];
		$smallDescription = $row['smallDescription'];
    $body = $row['body'];

		print<<<END
			<form action="?p=handleArticles" method="POST" enctype="multipart/form-data" class="needs-validation" style="width:100%" novalidate>
				<div class="row">
					<label for="firstRow">ID: $id</label>
					<input type="hidden" id="id" name="id" value="$id"/>
				</div>
        <div class="row" style="width:95%">
          <div class="col">
            <label for="title">*Τίτλος</label>
            <input type="text" id="title" name="title" value="$articleTitle" readonly/>
          </div>
        </div>
        <div class="row" style="width:95%">
          <div class="col">
            <label for="smallDescription">*Περιγραφή Άρθρου</label>
            <textarea type="smallDescription" id="smallDescription" name="smallDescription" value="$smallDescription" rows="3" readonly">  </textarea>
          </div>
        </div>
        <div class="row" style="width:95%">
          <div class="col">
            <label for="body">*Κείμενο Άρθρου</label>
            <textarea type="body" id="body" name="body" value="$body" rows="7" readonly>  </textarea>
          </div>
        </div>

				<br>
				<div class="row">
					<label for="deleteSubmit">**ΣΗΜΕΙΩΣΗ: Η διαγραφή του Άρθρου γίνεται οριστικά και δεν υπάρχει τρόπος επαναφοράς της.</label>
					<div class="col align-self-end" align="left">
						<input type="submit" id="deleteSubmit" name="deleteSubmit"  value="Διαγραφή Άρθρου" style="background-color:#CC0000" />
					</div>
				</div>
			</form>


END;
}
?>
</div>
