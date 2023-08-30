<div class="container" style="width:100%;">
<h2><b>Όλα τα Άρθρα</b></h2>

<?php
 	$mysqli = $_SESSION['dbconnect'];
 	$getArticles = "SELECT * FROM Article where isDeleted=false";
	$stmt = $mysqli->prepare($getArticles);
	$stmt->execute();
	$results = $stmt->get_result();

 	while ($row = $results->fetch_assoc()){
		$articleTitle = $row['title'];
		$smallDescription = $row['smallDescription'];
    $body = $row['body'];

		print<<<END
        <div class="row" style="width:95%">
          <div class="col">
            <label for="title">Τίτλος</label>
            <input type="text" id="title" name="title" value="$articleTitle" readonly/>
          </div>
        </div>
        <div class="row" style="width:95%">
          <div class="col">
            <label for="smallDescription">Περιγραφή Άρθρου</label>
            <textarea type="smallDescription" id="smallDescription" name="smallDescription" rows="3" readonly">$smallDescription</textarea>
          </div>
        </div>
        <div class="row" style="width:95%">
          <div class="col">
            <label for="body">Κείμενο Άρθρου</label>
            <textarea type="body" id="body" name="body" rows="7" readonly>$body</textarea>
          </div>
        </div>
        <br>
        <hr>
        <br>
END;
}
?>
</div>
