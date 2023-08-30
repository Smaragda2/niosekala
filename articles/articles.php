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
  <div class="jumbotron row" style="width:100%;">
    <p class="articlesTitle">$articleTitle</p>
    <p class="articleDescription">$smallDescription</p>
    <form action="?p=Article" method="POST" enctype="multipart/form-data" class="needs-validation" style="width:100%" novalidate>
      <input type="hidden" id="id" name="id" value="$id"/>
      <input type="submit" name="Submit" value="Διάβασε το Άρθρο"/>
    </form>
  </div>
END;
  }

  if ($result->num_rows == 0) {
    print<<<END
    <div class="jumbotron row" style="width:100%;">
      <p>Δεν βρέθηκε κάποιο Άρθρο.</p>
    </div>
  END;
  }
?>
</div>
