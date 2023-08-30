<?php
 	$mysqli = $_SESSION['dbconnect'];
 	$getArticles = "SELECT * FROM Article where id=?";
	$stmt = $mysqli->prepare($getArticles);
  $stmt->bind_param("i", $_POST['id']);
	$stmt->execute();
	$results = $stmt->get_result();

 	$row = $results->fetch_row();

	$id = $row['id'];
	$articleTitle = $row['title'];
	$body = $row['body'];
?>
<div class="Container" style="width:100%;">
  <div class="jumbotron row" style="width:100%;">
    <p class="articlesTitle"><?php $articleTitle ?></p>
    <p class="articleBody"><?php $body ?></p>
  </div>
</div>
