
<?php
	if( !isset($_POST['id']) || !isset($_POST['name']) || !isset($_POST['price']) ){
		print<<<END
			<div style="color:red;width:100%;" class="jumbotron row">Δεν υπάρχει επιλεγμένη Υπηρεσία. Θα μεταφερθείτε ξανά στην σελίδα με τις Υπηρεσίες.</div>
			<script>
				setTimeout(function(){
					window.location.href = "?p=Services"
				}, 4 * 1000);
			</script>
END;
		
	}else{
?>

<script>
	function check(){
		var allRequired = true;
		
		var fullName = new RegExp("^.{3,} .{3,}$");
		var date = new RegExp("^[0-9]{2}-[0-9]{2}-[0-9]{4} [0-9]{2}:[0-9]{2}$");
		var email = new RegExp("^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$");
		var tel = new RegExp("^[0-9]{10}$");
		
		if( fullName.test($("#fullName").val()) && date.test($("#date").val()) && email.test($("#email").val()) && tel.test($("#tel").val()) ){
	        allRequired = true;
		}else{
	        allRequired = false;
		}
		
// --------------------------------------------!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!----------------		
		if($("#whereTo").val() == "Skype" && $("#Skype").val().length < 4){
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
			$("#Skype").attr('required', true);
		}else{
			$('label[for=Skype], input#Skype').attr('hidden',true);
			$("#Skype").attr('required', false);
		}
		check();
	}
</script>
	
<?php	
	print<<<END
		<div class="jumbotron row" style="background-color:#C1E0FF;text-align:center;width:100%;" >
		<label style="font-size:x-large;font-family:Calibri bold" for="requestForm">Φόρμα εκδήλωσης ενδιαφέρωντος:</label>
		<br>
		<form action="?p=Confirm" method="POST" enctype="multipart/form-data" id="requestForm" class="needs-validation was-validated" style="font-family:'Calibri Light';font-size:medium" novalidate>	
END;
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
			    <input type="text" class="form-control" id="fullName" name="fullName" pattern=".{3,} .{3,}" onkeyup="check();" required>
				<div class="invalid-feedback">Παρακαλώ συμπληρώστε το Ονοματεπώνυμο.</div>
			</div>
			<div class="col-md-5 mb-3">
			    <label for="date">*Επιθυμητή Ημ/νια και Ώρα Ραντεβού:</label>
			    <input type="text" class="form-control" id="date" name="date" placeholder="DD-MM-YYYY HH:MM" pattern="[0-9]{2}-[0-9]{2}-[0-9]{4} [0-9]{2}:[0-9]{2}" title="Enter a date in this formart DD-MM-YYYY HH:MM" onkeyup="check();" required>
				<div class="invalid-feedback">Παρακαλώ συμπληρώστε την Ημ/νια και Ώρα όπως το παράδειγμα: 12-05-2020 17:30.</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-5 mb-3">
			    <label for="emailSub">*Email:</label>
			    <label for="email" id="emailSub" style="font-size:small" >Σημείωση: Κατά την επιβεβαίωση/αλλαγή της Ημ/νιας θα σας σταλθεί Email.</label>
			    <input type="email" class="form-control" placeholder="text@text.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" id="email" name="email" onkeyup="check();" required>
				<div class="invalid-feedback">Παρακαλώ συμπληρώστε το Email.</div>
			</div>
			<div class="col-md-5 mb-3">
			    <label for="tel" >*Τηλέφωνο Επικοινωνίας:</label>
			    <label for="tel" style="font-size:small" > Σημείωση: Θα σας καλέσουμε σε περίπτωση αλλαγής της Ημ/νιας του Ραντεβού.</label>
			    <input type="text" class="form-control" id="tel" name="tel" placeholder="6900000000" pattern="[0-9]{10}" title="Enter a valid formart (10 digits): ##########" onkeyup="check();" required>
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
				<input type="text" class="form-control" id="Skype" name="SkypeName" placeholder="Skype Name" pattern="[a-zA-Z0-9.-]{4,}" title="Παρακαλώ συμπληρώστε το Όνομα που έχετε στο Skype." onkeyup="check();" required>
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
		<input type="submit" id="requestSubmit" name="RequestAppointment"  value="Ζητήστε Ραντεβού" style="background-color:gray" disabled/>
	</form>
	</div>
END;
	}
?>
<div id="errors"></div>


