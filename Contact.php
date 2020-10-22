<div class="row" style="width:100%;" >
	<div class="jumbotron col col-6-narrower col-12-mobilep " align="left" style="background-color:#FFCCCC;width:100%;">
		<h3 style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:center">Στοιχεία επικοινωνίας:</h3>
		<br>
		<div style="margin-right:1%;margin-left:1%;text-align:left;font-size:large;font-family:Arial, Helvetica, sans-serif">
			<b>Τηλέφωνο Επικοινωνίας:</b> <a href="tel:6948266209">6948266209</a>
			<br><b>Email:</b> niosekala@gmail.com
			<br>
			<ul class="icons" style="margin-top:1%">
				<br>
				<li><a href="https://www.facebook.com/niosekala/" target="_blank" class="icon brands fa-facebook-f" style="font-size:medium"><span class="label">Facebook</span> NIOSE KALA - νιώσε καλά</a></li>
			</ul>

		</div>
	</div>
	<br>
	<div class="jumbotron col col-12-narrower " align="center" style="background-color:#FFCCCC;text-align:center;margin-right:7%;width:100%">
		<h3 style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:center">Στείλτε Μήνυμα</h3>
		<br>
		<form action="?p=Request" method="POST" enctype="multipart/form-data" id="contact" class="needs-validation was-validated" novalidate>	
			<div class="row" style="width:95%">
				<div class="col-md-6 mb-6">
					<input class="form-control" type="text" name="name" id="contactName" placeholder="Όνομα"  onkeyup="CheckRequiredContact();">
					<div class="invalid-feedback">Παρακαλώ συμπληρώστε το Όνομα σας.</div>
				</div>
				<div class="col-md-6 mb-6">
					<input class="form-control" type="email" name="email" id="contactEmail" placeholder="Email" onkeyup="CheckRequiredContact();">
				</div>
			</div>
			<br>
			<div class="row" style="width:95%">
				<div class="col">
					<textarea class="form-control" type="notes" id="contactMessage" name="message" rows="5" onkeyup="CheckRequiredContact();" ></textarea>
				</div>
			</div>
			<br>
			<input type="submit" id="contactSubmit" name="contact"  value="Αποστολή Μηνύματος" style="background-color:gray" disabled>
		</form>
	</div>
</div>


