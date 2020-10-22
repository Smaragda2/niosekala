<script>

	function CheckRequired(){
		var allRequired = true;
		
		var fullName = new RegExp("^.{3,}$");
		var all = new RegExp("^.{1,}");
		
		
		if( fullName.test($("#name").val()) 
			&& all.test($("#duration").val())
			&& all.test($("#price").val())
			&& all.test($("#description").val()) ){
	        allRequired = true;
		}else{
	        allRequired = false;
		}

		if(!allRequired){
			document.getElementById("errors").innerText = "Παρακαλώ συμπληρώστε όλα τα πεδία με αστερίσκο (*).";
			$("#addSubmit").attr('disabled', true);
			$("#addSubmit").attr('style','background-color:gray');
		}else{
			document.getElementById("errors").innerText = "";
			$("#addSubmit").attr('disabled', false);
			$("#addSubmit").attr('style','background-color:#37c0fb');
		}
	}

</script>

<div class="container">

<h2><b>Προσθήκη Υπηρεσιών</b></h2>

<?php
		print<<<END
			<form action="?p=UpdateServices" method="POST" enctype="multipart/form-data" class="needs-validation" style="width:100%" onload="CheckRequired();" novalidate>
				<div class="row" id="firstRow">
					<div class="row" style="width:90%">
						<label for="name">*Name</label>
						<input type="text" id="name" name="name" onkeyup="CheckRequired();"/>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3 mb-3">
						<label for="duration">*Duration</label>
						<input type="text" id="duration" name="duration" value="0 min/hours" onkeyup="CheckRequired();"/>
					</div>
					<div class="col-md-3 mb-3">
						<label for="price">*Price</label>
						<input type="text" id="price" name="price" onkeyup="CheckRequired();"/>
					</div>
				</div>
				<div class="row" style="width:95%">
					<div class="col">
						<label for="description">*Description</label>
						<textarea type="description" id="description" name="description" rows="7" onkeyup="CheckRequired();">  </textarea>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5 mb-3">
						<label for="canBeAnonymous">*Επιλέξτε εάν μπορεί να ζητηθεί ανώνυμη συνεδρία:</label>
						<select class="custom-select d-block w-100" id="canBeAnonymous" name="canBeAnonymous" onkeyup="CheckRequired();">
				print		'<option value="no" selected>Όχι</option>';
				print		'<option value="yes" >Ναι</option>';
						</select>
					</div>
				</div>
				<br>
				<div class="row">
					<input type="submit" id="addSubmit" name="addSubmit"  value="Προσθήκη Υπηρεσίας" style="background-color:gray" disabled/>
				</div>
			</form>
			<br>
			<hr>
			<br>
		<div id="errors"></div>
END;
?>
</div>


