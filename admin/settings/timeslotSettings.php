
<?php
	print '<br><div id="PhpResponse" tabindex="-1" align="center"></div>';

	$mysqli = $_SESSION['dbconnect'];
	
	$getTimes = "Select * from Times";
	$TimesExists = "select count(*) as ok from Times";
	
	$stmt = $mysqli->prepare($TimesExists);
	$stmt->execute();
	$results = $stmt->get_result();
	
	$isExists = 1;
	
	while($rowExist = $results->fetch_assoc()){
		$isExists = $rowExist['ok'];
	}
	
	$minDays = 1;
	$maxDays = 5;
	$startTime = '10:00';
	$endTime = '20:00';
	$onMonday = 1;
	$onTuesday = 1;
	$onWednsday = 1;
	$onThursday = 1;
	$onFriday = 0;
	$onSaturday = 1;
	$onSunday = 1;	
	
	if($isExists == 1){
		$stmt1 = $mysqli->prepare($getTimes);
		$stmt1->execute();
		$results1 = $stmt1->get_result();
		while($row = $results1->fetch_assoc()){
			$minDays = $row['minDays'];
			$maxDays = $row['maxDays'];
			$startTime = $row['startTime'];
			$endTime = $row['endTime'];
			$onMonday = $row['onMonday'];
			$onTuesday = $row['onTuesday'];
			$onWednsday = $row['onWednsday'];
			$onThursday = $row['onThursday'];
			$onFriday = $row['onFriday'];
			$onSaturday = $row['onSaturday'];
			$onSunday = $row['onSunday'];
		}
	}
	$allDaysValues = array($onMonday, $onTuesday, $onWednsday, $onThursday, $onFriday, $onSaturday, $onSunday);
	$daysofWeek = array('Monday', 'Tuesday', 'Wednsday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
	$daysofWeekGR = array('Δευτέρα', 'Τρίτη', 'Τετάρτη', 'Πέμπτη', 'Παρασκευή', 'Σάββατο', 'Κυριακή');
		
	$daysDiv = '<div><label for="days">Ποιες ημέρες μπορεί να κλείσει ραντεβού ο Πελάτης;</label><br>';
	$daysDiv .= '<table name="days" id="days" style="width:50%">';

	for($i=0; $i<7; $i++){		
		$daysDiv .= '<tr><td style="text-align:center">'.$daysofWeekGR[$i].'</td><td><input type="checkbox" id="'.$daysofWeek[$i].'" name="'.$daysofWeek[$i].'" value="yes" ';
			if($allDaysValues[$i] == 1){
				$daysDiv .= "checked='checked' ";
			}
		$daysDiv .= '></td></tr>';			
	}
	$daysDiv .= '</table></div>';

		
	print<<<END
		<div align="center">
			<form action="#" method="POST" enctype="multipart/form-data" class="needs-validation" id="timesForm" style="width:90%;margin-left:-10%" novalidate>
				<input type="hidden" id="hasTimes" name="hasTimes" value="$isExists">
				<div class="firstRow row" style="width:100%" >
					<div class="col-md-6 mb-6" >
						<label for="minDays">*Πόσες ημέρες πριν μπορεί να κλείσει ο πελάτης ραντεβού:</label>
						<br>
						<input type="number" id="minDays" name="minDays" value="$minDays" width="50%"/>
					</div>
					<div class="col-md-6 mb-6">
						<label for="maxDays">*Πόσες ημέρες μετά μπορεί να κλείσει ο πελάτης ραντεβού:</label>
						<br>
						<input type="number" id="maxDays" name="maxDays" value="$maxDays" width="50%"/>
					</div>
				</div>
				<br>
				<div class="row" style="width:100%">
					<div class="col-md-6 mb-6">
						<label for="startTime">*Από τι ώρα μπορεί ένας πελάτης να κλείσει ραντεβού:</label>
						<br>
						<input type="time" id="startTime" name="startTime" value="$startTime" width="100%"/>
					</div>
					<div class="col-md-6 mb-6">
						<label for="endTime">*Ποια η τελευταία διαθέσιμη ώρα που μπορεί ένας πελάτης να κλείσει ραντεβού:</label>
						<br>
						<input type="time" id="endTime" name="endTime" value="$endTime" width="100%"/>
					</div>
				</div>
				<br>
END;
				print $daysDiv;
				print<<<END
				<br>
				<div class="row" style="width:100%">
					<div class="col">
						<input type="submit" id="timeslotSubmit" name="timeslotSubmit">
					</div>
				</div>
			</form>
			<br>
		</div>
END;
?>



<script language="javascript" type="text/javascript">
	$(document).ready(function() {
	    $('#timesForm').submit(function(e) {          
	    	e.preventDefault();
			$.ajax({
	          	url: "./settings/UpdateSettings.php",
	          	timeout: 30000,
	          	data : $('#timesForm').serialize(),
	          	cache: false,
	          	type: "POST",
	          	error: function(){
	          		$('#PhpResponse').hide().html('server not responding');
	          		document.getElementById('PhpResponse').style.color = "red";
	          		$("#PhpResponse").attr("tabindex",-1).focus();
	            },
	           	success: function( msg ) {
	           		$('#PhpResponse').text(msg);
	           	 	document.getElementById('PhpResponse').style.color = "green";
	           	 	$("#PhpResponse").attr("tabindex",-1).focus();
	        	}
	    	});
		});
	});
</script>


