<script>

	function CheckRequired(){
		var allFilled = false;
    var fieldExceedsMaxValue = false;

   var title = $("#title").val();
   var description = $("#smallDescription").val();
   var body = $("#body").val();

   var titleMaxChars = 150;
   var descriptionMaxChars = 255;
   var bodyMaxChars = 65535;

    var errorsMsg = "";

		if(title?.length == 0 || description?.length == 0 || body?.length == 0){
	        allFilled = false;
		}else{
	        allFilled = true;
		}


    if (title?.length > titleMaxChars) {
      errorsMsg = "Ο Τίτλος δεν μπορεί να ξεπερνάει τους " + titleMaxChars + " χαρακτήρες!\n";
      fieldExceedsMaxValue = true;
    }
    if (description?.length > descriptionMaxChars) {
      errorsMsg += "Η Περιγραφή δεν μπορεί να ξεπερνάει τους " + descriptionMaxChars + " χαρακτήρες!\n";
      fieldExceedsMaxValue = true;
    }
    if (body?.length > bodyMaxChars) {
      errorsMsg += "Ο Κείμενο του Άρθρου δεν μπορεί να ξεπερνάει τους " + bodyMaxChars + " χαρακτήρες!\n";
      fieldExceedsMaxValue = true;
    }

		if(!allFilled){
			document.getElementById("errors").innerText = "Παρακαλώ συμπληρώστε όλα τα πεδία με αστερίσκο (*).";
			$("#addSubmit").attr('disabled', true);
			$("#addSubmit").attr('style','background-color:gray');
		} else if (fieldExceedsMaxValue) {
			document.getElementById("errors").innerText = errorsMsg;
			$("#addSubmit").attr('disabled', true);
			$("#addSubmit").attr('style','background-color:gray');
		} else {
      document.getElementById("errors").innerText = "";
			$("#addSubmit").attr('disabled', false);
			$("#addSubmit").attr('style','background-color:#37c0fb');
    }
	}

</script>

<div class="container" style="width:100%;">
  <h2><b>Προσθήκη Άρθρου</b></h2>
  <h4 style="color: red;"><b>*Τα Emojis δεν υποστηρίζονται!</b></h4>
  <?php
  		print<<<END
  			<form action="?p=handleArticles" method="POST" enctype="multipart/form-data" class="needs-validation" style="width:100%" onload="CheckRequired();" novalidate>
  				<div class="row" style="width:95%">
  					<div class="col">
              <label for="title">*Τίτλος</label>
              <input type="text" id="title" name="title" onkeyup="CheckRequired();"/>
  					</div>
  				</div>
          <div class="row" style="width:95%">
            <div class="col">
              <label for="smallDescription">*Περιγραφή Άρθρου</label>
              <textarea type="smallDescription" id="smallDescription" name="smallDescription" value="Τι θα δει ο χρήστης πριν ανοίξει το Άρθρο" rows="3" onkeyup="CheckRequired();"></textarea>
            </div>
          </div>
          <div class="row" style="width:95%">
            <div class="col">
              <label for="body">*Κείμενο Άρθρου</label>
              <textarea type="body" id="body" name="body" value="Το κείμενο του άρθρου" rows="7" onkeyup="CheckRequired();"></textarea>
            </div>
          </div>

  				<br>
  				<div class="row">
  					<input type="submit" id="addSubmit" name="addSubmit"  value="Προσθήκη Άρθρου" style="background-color:gray" disabled/>
  				</div>
  			</form>
  			<br>
  			<hr>
  			<br>
  		<div id="errors"></div>
  END;
  ?>
  </div>
