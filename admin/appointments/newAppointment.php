<?php 
	$responseDT = getDatesAndTimes();
	$_SESSION['dateTimes']= $responseDT;
?>
<script>

	function displayTimesOfDate(){
		var e= document.getElementById("onDate");
		var x= e.options[e.selectedIndex].value; 
		var dateTimes = JSON.parse('<?php echo json_encode($_SESSION["dateTimes"]); ?>');

		var timesSelect = '';
		
		$('#atTime').children().remove();
		var select =document.getElementById('atTime');
		
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
	
	var telNotRequired = "Τηλέφωνο Επικοινωνίας:";
	var telRequired = "*Τηλέφωνο Επικοινωνίας:";
	
	function CheckRequired(){
		var allRequired = true;
		
		var fullName = new RegExp("^.{3,} .{3,}$");
		var email = new RegExp("^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$");
		
		var selectedDateValue = $('#onDate').val();
		var selectedTimeValue = $('#atTime').val();

		
		if( fullName.test($("#fullName").val()) && email.test($("#email").val()) && selectedDateValue && selectedTimeValue){
	        allRequired = true;
		}else{
	        allRequired = false;
		}
// --------------------------------------------!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!----------------		
		if($("#whereTo").val() == "Skype" && $("#Skype").val().length < 4){
	        allRequired = false;
		}else if($("#whereTo").val() != "Skype" && $("#tel").val().length < 10){
	        allRequired = false;
		}
// --------------------------------------------!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!----------------		

		if(!allRequired){
			document.getElementById("errors").innerText = "Παρακαλώ συμπληρώστε όλα τα πεδία με αστερίσκο (*).";
			$("#requestSubmit").attr('disabled', true);
			$("#requestSubmit").attr('style','background-color:gray');
			//document.getElementByID("requestSubmit").style.background-color = '#37c0fb';
		}else{
			document.getElementById("errors").innerText = "";
			$("#requestSubmit").attr('disabled', false);
			$("#requestSubmit").attr('style','background-color:#37c0fb');
			//document.getElementByID("requestSubmit").style.background-color = '#37c0fb';
		}
	}
	
	function changeRequireds(){
		if($("#whereTo").val() == "Skype"){
			$('label[for=Skype], input#Skype').attr('hidden',false);
			$("#telL").text(telNotRequired);

			
			$("#tel").attr('required', false);
			$("#Skype").attr('required', true);
		}else{
			$('label[for=Skype], input#Skype').attr('hidden',true);
			$("#telL").text(telRequired);
			
			$("#tel").attr('required', true);
			$("#Skype").attr('required', false);
		}
		CheckRequired();
	}
</script>
<label style="text-align:center" for="requestForm">Δημιουργία Νέου Ραντεβού:</label>
<form action="?p=Request" method="POST" id="requestForm" enctype="multipart/form-data" id="requestForm" class="needs-validation was-validated" novalidate>	
	
<?php	
	print '<div class="row">';
	print	'<input type="hidden" id="id" name="id" value="'.$_POST['id'].'">';

	print<<<END

			<div class="col-md-5 mb-3">
			    <label for="item">Choose a Product:</label>
END;
	print   '<input type="text" id="name" name="name" value="'.$_POST['name'].'" readonly>';
			
	print<<<END
			</div>
			<div class="col-md-3 mb-3">
				<label for="price">Price</label>
END;
	print   '<input type="text" id="price" name="price" value="'.$_POST['price'].'" readonly>';
			
	print<<<END
			</div>
		</div>
		<div class="row">
			<div class="col-md-5 mb-3">
			    <label for="fullName">*Ονοματεπώνυμο:</label>
			    <input type="text" class="form-control" id="fullName" name="fullName" pattern=".{3,} .{3,}" onkeyup="CheckRequired();" required>
				<div class="invalid-feedback">Παρακαλώ συμπληρώστε το Ονοματεπώνυμο.</div>
			</div>
			<div class="col-md-5 mb-3">
				<label for="onDate">*Επιθυμητή Ημερομηνία Ραντεβού:</label>
				<select class="custom-select d-block" id="onDate" name="onDate" onchange="CheckRequired();displayTimesOfDate();" style="width:50%">
END;
				print displayDatesTimes().'</select>';
				print '<label for="atTime">*Επιθυμητή Ώρα Ραντεβού:</label><select class="custom-select d-block" id="atTime" name="atTime" onchange="CheckRequired();" style="width:50%"><script>displayTimesOfDate();</script></select>';
				
	print<<<END
			</div>
		</div>
		<div class="row">
			<div class="col-md-5 mb-3">
			    <label for="emailSub">*Email:</label>
			    <label for="email" id="emailSub">Σημείωση: Κατά την Δημιουργία του Ραντεβού θα σταλθεί email στον Πελάτη.</label>
			    <input type="email" class="form-control" placeholder="text@text.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" id="email" name="email" onkeyup="CheckRequired();" required>
				<div class="invalid-feedback">Παρακαλώ συμπληρώστε το Email.</div>
			</div>
			<div class="col-md-5 mb-3">
			    <label for="tel" id="telL">Τηλέφωνο Επικοινωνίας:</label>
			    <input type="text" class="form-control" id="tel" name="tel" placeholder="6900000000" pattern="[0-9]{10}" title="Enter a valid formart (10 digits): ##########" onkeyup="CheckRequired();" >
				<div class="invalid-feedback">Παρακαλώ συμπληρώστε τον Αριθμό σας.</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-5 mb-3">
				<label for="whereTo">Επιλέξτε τον τρόπο της Συνεδρίας:</label>
				<select class="custom-select d-block w-100" id="whereTo" name="whereTo" onchange="changeRequireds();">
					<option value="Skype" selected>Skype</option>
					<option value="tel">Τηλέφωνο</option>
					<option value="viber">Viber</option>
				</select>
			</div>
			<div class="col-md-5 mb-3">
				<label for="Skype">*Skype Name:</label>
				<input type="text" class="form-control" id="Skype" name="SkypeName" placeholder="Skype Name" pattern="[a-z0-9.-]{4,}" title="Παρακαλώ συμπληρώστε το Όνομα που έχετε στο Skype." onkeyup="CheckRequired();" required>
				<div class="invalid-feedback">Παρακαλώ συμπληρώστε το Όνομα που έχετε στο Skype.</div>
			</div>
		</div>
		<div class="row" style="width:85%">
			<div class="col">
				<label for="notes">Σχόλια Ραντεβού:</label>
				<textarea class="form-control" type="notes" id="notes" name="notes" rows="5" >  </textarea>
			</div>
		</div>
		<br>
		<input type="submit" id="requestSubmit" name="RequestAppointment"  value="Δημιουργία Ραντεβού" style="background-color:gray" disabled/>
		
END;
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

</form>
<div id="errors"></div>


