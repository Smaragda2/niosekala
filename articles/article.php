<?php
 	$mysqli = $_SESSION['dbconnect'];
 	$getArticles = "SELECT id, title, body FROM Article where id=".$_POST['id'];
	$stmt = $mysqli->prepare($getArticles);
	$stmt->execute();
	$results = $stmt->get_result();

 	$row = $results->fetch_row();

	$id = $row[0];
	$articleTitle = $row[1];
	$body = $row[2];

  print<<<END
  <div class="Container" style="width:100%;">
    <p style="width: 100%;"><b>Τίτλος Άρθρου:</b> $articleTitle</p>
    <div class="jumbotron" style="width:100%;">
      <p class="articleBody col">$body</p>
    </div>
  </div>
END;
?>
