<?php 
	$responseDT = getDatesAndTimes();
	$_SESSION['dateTimes']= $responseDT;
?>
<script> 	
	function displayTimesOfDate(){
		var e= document.getElementById("newDate");
		var x= e.options[e.selectedIndex].value; 
		var dateTimes = JSON.parse('<?php echo json_encode($_SESSION["dateTimes"]); ?>');

		var timesSelect = '';
		
		$('#newTime').children().remove();
		var select =document.getElementById('newTime');
		
		for(dateTime in dateTimes){
		    if(dateTime == x){
		    	for(time in dateTimes[dateTime]){
		    		var timeOpt= dateTimes[dateTime][time];
		    		
		    		var opt = document.createElement('option');
		    		opt.value = timeOpt;
    				opt.innerHTML = timeOpt;
    				select.appendChild(opt);
   		    	}
		    }
		}		
	}
</script>

<?php

 	$mysqli = $_SESSION['dbconnect'];
 	$getAppointments = "SELECT * FROM Request WHERE isConfirmed = true and isCompleted = false and isCancelled=false ORDER BY onDate,atTime";
	$stmt = $mysqli->prepare($getAppointments);
	$stmt->execute();
	$results = $stmt->get_result();

	print "<div><h2><b>Επιβεβαιωμένα Ραντεβού</b></h2><br><hr><br>";
	
 	while ($row = $results->fetch_assoc()){
 	 	$productId = $row['selectedProductID'];
		$name = $row['name'];
		$onDate = $row['onDate'];
		$atTime = $row['atTime'];
		$whereTo = $row['whereTo'];		
		$notes= $row['notes'];
		$createdAt= $row['createdAt'];
		$email= $row['email'];
		$tel = $row['tel'];
		$skypeName = $row['skypeName'];
		$isPaid = $row['isPaid'];
		$token = $row['paymentToken'];
		
		$hours = $onDate.' '.$atTime;
		$productName = "δοκιμη";
		$price = 0;

	 	$getProducts = "SELECT * FROM `Product` where id = $productId";
		$stmt2 = $mysqli->prepare($getProducts);
		$stmt2->execute();
		$results2 = $stmt2->get_result();
	 	while ($rowP = $results2->fetch_assoc()){
			$productName = $rowP['name'];
			$price = $rowP['price'];
		}
				
		print<<<END
			<br>
			<form action="?p=Confirmation" method="POST" enctype="multipart/form-data" id="requestForm" class="needs-validation was-validated" novalidate>	
				<input type="hidden" id="id" name="id" value="$productId">
				<input type="hidden" id="productName" name="productName" value="$productName">
				<input type="hidden" id="price" name="price" value="$price">
				<input type="hidden" id="onDate" name="onDate" value="$onDate">
				<input type="hidden" id="atTime" name="atTime" value="$atTime">
				<input type="hidden" id="email" name="email" value="$email">
				<input type="hidden" id="createdAt" name="createdAt" value="$createdAt">
				<input type="hidden" id="token" name="token" value="$token">

				<div class="row border border-info" >
					<p>
						Ραντεβού με τον/την <b>$name</b> στις <b>$hours</b>, για την Υπηρεσία <b>$productName</b>.<br>
END;
		if($whereTo == "tel"){
			print<<<END
							Ο/Η $name ζήτησε το ραντεβού να πραγματοποιηθεί μέσω: <b>Τηλέφωνο</b>.<br>
							Τα στοιχεία επικοινωνίας είναι:<br>
							 &nbsp;&nbsp;<b>Τηλέφωνο</b>: $tel <br>
END;
		}else{
			print<<<END
							Ο/Η $name ζήτησε το ραντεβού να πραγματοποιηθεί μέσω: <b>$whereTo</b>.<br>
							Τα στοιχεία επικοινωνίας είναι:<br>
							&nbsp;&nbsp;<b>Τηλέφωνο</b>: $tel <br>
END;
		}
		if($whereTo == "Skype"){
			print "&nbsp;&nbsp;<b>SkypeName</b>: ".$skypeName."<br>";
		}
		print<<<END
							Σημειώσεις για το Ραντεβού:<br>
							&nbsp;&nbsp;<b>$notes</b>
							
					</p>
				</div>
				<br>
				<div class="row">
END;
		if($isPaid){
		print<<<END
					<div class="col align-self-end" align="right">
						<input type="submit" id="completeAppointment" name="completeAppointment"  value="Το Ραντεβού ολοκληρώθηκε" style="background-color:#37c0fb" />
					</div>
END;
		}else{
		print<<<END
					<div class="col align-self-end" align="right">
						Το Ραντεβού δεν έχει πληρωθεί ακόμα. Παρακαλώ επικοινωνήστε με τον Πελάτη για την ολοκλήρωση τις πληρωμής, ώστε να μπορείτε να ολοκληρώσετε το Ραντεβού.
					</div>
END;
		}
		print<<<END

				</div>
			</form>
			<br><hr><br>
			<form action="?p=Confirmation" method="POST" enctype="multipart/form-data" id="requestForm" class="needs-validation was-validated" novalidate>
				<input type="hidden" id="id" name="id" value="$productId">
				<input type="hidden" id="productName" name="productName" value="$productName">
				<input type="hidden" id="price" name="price" value="$price">
				<input type="hidden" id="onDate" name="onDate" value="$onDate">
				<input type="hidden" id="atTime" name="atTime" value="$atTime">
				<input type="hidden" id="email" name="email" value="$email">
				<input type="hidden" id="createdAt" name="createdAt" value="$createdAt">
				<input type="hidden" id="token" name="token" value="$token">

				<div class="col-md-5 mb-3">
				    <label>Εισάγετε την νέα Ημ/νια και Ώρα του Ραντεβού:</label><br>
				    <label for="newDate">*Επιθυμητή Ημερομηνία Ραντεβού:</label>
				    <select class="custom-select d-block" id="newDate" name="newDate" onchange="displayTimesOfDate();" style="width:50%">
END;
				print displayDatesTimes().'</select>';
				print '<label for="newTime">*Επιθυμητή Ώρα Ραντεβού:</label><select class="custom-select d-block" id="newTime" name="newTime" style="width:50%"><script>displayTimesOfDate();</script></select>';
		print<<<END
				</div>
				<br>
				<input type="submit" id="changeHour" name="changeHour"  value="Αλλαγής της Ημερομηνίας" style="background-color:red"/>
			</form>
			<br><hr><br>
			<div>
END;

	}
	

	function displayDatesTimes(){
		$dateTimes = $_SESSION['dateTimes'];
		$datesSelect = '';
		$i = 0;
		$firstDate = '';
		foreach($dateTimes as $date=>$values){
			if($i == 0){
				$firstDate = $date;
				$i++;
			}
			$datesSelect .= '<option value="'.$date.'">'.$date.'</option>';
		}
		return $datesSelect;
	}
	
	
	function getDatesAndTimes(){
		$curl = curl_init();        
		curl_setopt ($curl, CURLOPT_URL, 'https://niosekala.gr/_aDemo/services/getDatesAndTimeslots.php');   
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_HTTPGET, 1);  
		$result = curl_exec($curl); 
		if($result == FALSE){
			die("cUrl Error: ". curl_error($curl));
		} 
		$resObj = json_decode($result, true);

		curl_close($curl);
		return $resObj ; 
	}

?>
