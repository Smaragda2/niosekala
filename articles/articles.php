<div class="Container" style="width:100%;">
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

    print<<<END
    <p style="width: 100%;"><b>Τίτλος Άρθρου:</b> $articleTitle</p>
    <div class="jumbotron " style="width:100%;">
      <div class="col">$smallDescription</div>
      <br>
      <form action="?p=Article" method="POST" enctype="multipart/form-data" class="needs-validation" style="width:100%" novalidate>
        <input type="hidden" id="id" name="id" value="$id"/>
        <input type="submit" name="Submit" value="Διάβασε το Άρθρο"/>
      </form>
    </div>
    <hr>
    <br>
END;
  }
?>
</div>
