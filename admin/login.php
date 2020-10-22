<div style="width:100%">
	<form action="index.php" method="POST" enctype="multipart/form-data" id="loginForm" class="needs-validation was-validated" novalidate>	
		<div class="row justify-content-center">
			<div class="col-md-5 mb-3 align-self-center">
			    <label for="username">*username:</label>
			    <input type="text" class="form-control" id="username" name="username" pattern=".{5,}" onkeyup="CheckRequired();" required>
				<div class="invalid-feedback">Παρακαλώ συμπληρώστε το Username.</div>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-md-5 mb-3 align-self-center">
			    <label for="date">*Password:</label>
			    <input type="password" class="form-control" id="pass" name="pass" pattern=".{5,}" title="Enter a valid password" onkeyup="CheckRequired();" required>
				<div class="invalid-feedback">Παρακαλώ συμπληρώστε τον Κωδικό.</div>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-md-5 mb-3 align-self-center">
				<input type="submit" id="Submit" name="Login" value="Σύνδεση" style="background-color:gray" disabled>
				<input name="p" value="do_login" type="hidden"> 
			</div>
		</div>
    </form>
</div>
	
<script>
	function CheckRequired(){		
		var allRequired = false;
		
		var check = new RegExp("^.{5,}$");
		
		if( check.test($("#username").val()) && check.test($("#pass").val()) ){
	        allRequired = true;
		}else{
	        allRequired = false;
		}

		if(!allRequired){
			$("#Submit").attr('disabled', true);
			$("#Submit").attr('style','background-color:gray');
			//document.getElementByID("requestSubmit").style.background-color = '#37c0fb';
		}else{
			$("#Submit").attr('disabled', false);
			$("#Submit").attr('style','background-color:#37c0fb');
			//document.getElementByID("requestSubmit").style.background-color = '#37c0fb';
		}
	}
	
	function isAdmin(){
		$.ajax({
        	url:"do_login.php", //the page containing php script
            type: "post", //request type,
	        data: {"username": $("#username").val(), "pass": $("#pass").val()},
            success:function(result){
	           window.location.href = "index.php";
           	}
    	});
	}
	
</script>

