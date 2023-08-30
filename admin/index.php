<?php session_start(); ?>
<!doctype html>
<html lang="en">
<?php

	include_once "../database/dbconnect.php";
	$database = new Database();
	$db = $database->getConnection();
	$_SESSION['dbconnect'] = $db;

	include_once "../logger/Logger.php";

	if(!isset($_SESSION['admin'])){
		$_SESSION['admin'] = '?';
	}
?>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
		<link rel="stylesheet" href="../assets/css/_custom-forms.scss" />
		<script src="https://kit.fontawesome.com/ec3f31a4cb.js" crossorigin="anonymous"></script>

		<link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/checkout/">

	    <!-- Bootstrap core CSS -->
	    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">

	    <!-- Custom styles for this template -->
		<link href="https://getbootstrap.com/docs/4.0/examples/checkout/form-validation.css" rel="stylesheet">
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<style type="text/css">
			tr:nth-child(even) {background: #CCC}
			tr:nth-child(odd) {background: #FFF}
		</style>

	</head>

  	<body class="is-preload">
		<div id="page-wrapper">
			<div id="header" style="background-color:blue">
				<a href="index.php" id="logo" hidden="hidden">Niose Kala</a>
				<!-- Nav -->
				<nav id="nav">
					<ul>
						<li><a href="?p=start">Αρχική</a></li>
						<li>
							<a href="#">Υπηρεσίες</a>
							<ul>
								<li><a href="?p=Services">Αλλαγή Υπηρεσιών</a></li>
								<li><a href="?p=newService">Προσθήκη Νέας Υπηρεσίας</a></li>
								<li><hr></li>
								<li><a href="?p=deleteService">Διαγραφή Υπηρεσίας</a></li>
							</ul>
						</li>
						<li>
							<a href="#">Ραντεβού</a>
							<ul>
								<li><a href="?p=Appointments">Όλα τα Επιβεβαιωμένα Ραντεβού</a></li>
								<li><a href="?p=confirmAppointment">Επιβεβαίωση Ραντεβου</a></li>
								<li><a href="?p=createAppointment">Δημιουργία Ραντεβού</a></li>
							</ul>
						</li>
						<li>
							<a href="#">Άρθρα</a>
							<ul>
								<li><a href="?p=newArticle">Προσθήκη Νέου Άρθρου</a></li>
								<li><hr></li>
								<li><a href="?p=deleteArticle">Διαγραφή Άρθρου</a></li>
							</ul>
						</li>
						<li>
							<a href="#"><i class="fa fa-gear"> Ρυθμίσεις</i></a>
							<ul>
								<li><a href="?p=TimeslotSettings">Ημέρες και Ώρες Ραντεβού</a></li>
							</ul>
						</li>
						<li>
						<?php
							if($_SESSION['admin'] == 'ok'){
								print '<a href="?p=logout">Logout</a>';
							}else{
								print '<a href="?p=login">Login</a>';
							}
						?>
						</li>
					</ul>
				</nav>
			</div>

			<!-- Internal Pages -->
			<main role="main" style="padding-top:5%;margin-right:10%;margin-left:10%" class="col-md-9 col-lg-10 px-4" id="main">
	          	<section class="row text-center placeholders">
	           		<?php
						if( !isset($_REQUEST['p']) && $_SESSION['admin'] == 'ok' ){
							$_REQUEST['p']='start';
						}else if(!isset($_REQUEST['p']) && $_SESSION['admin'] != 'ok' ){
							$_REQUEST['p']='login';
						}
						if(isset($_REQUEST['p']) && $_SESSION['admin'] != 'ok'){
							$p = $_REQUEST['p'];
							switch ($p){
								case "login" :				require "login.php";
															break;
								case "do_login" :			require "do_login.php";
															break;
								default:					require "login.php";
															break;
							}
						}
						if(isset($_REQUEST['p']) && $_SESSION['admin'] == 'ok'){
							$p = $_REQUEST['p'];
							switch ($p){
								case "start" :				require "start.php";
															break;
								case "login" :				require "login.php";
															break;
								case "do_login" :			require "do_login.php";
															break;
								case "logout" :				require "logout.php";
															break;
								case "UpdateServices" :		require "services/updateServices.php";
															break;
								case "Services" :			require "services/Services.php";
															break;
								case "newService" :			require "services/newService.php";
															break;
								case "deleteService" :		require "services/deleteService.php";
															break;
								case "confirmAppointment" :	require "appointments/confirmAppointment.php";
															break;
								case "createAppointment" :	require "services/allServices.php";
															break;
								case "Appointment" :		require "appointments/newAppointment.php";
															break;
								case "cancelAppointment" :	require "appointments/cancelAppointment.php";
															break;
								case "Confirmation" :		require "appointments/Confirmation.php";
															break;
								case "Appointments" :		require "appointments/Appointments.php";
															break;
								case "Payment" :			require "payments/testPaymentResponse.php";
															break;
								case "Request" :			require "services/request.php";
															break;
								case "TimeslotSettings" :	require "settings/timeslotSettings.php";
															break;
								case "UpdateSettings" :		require "settings/UpdateSettings.php";
															break;
								case "newArticle" :	require "articles/newArticle.php";
															break;
								case "deleteArticle" :	require "articles/deleteArticle.php";
															break;
								case "Articles" :	require "articles/allArticles.php";
															break;
								case "handleArticles" :	require "articles/handleArticles.php";
															break;
							}
						}
					?>
				</section>
			</main>
  		</div>

			<!-- Scripts -->
		<!--<script src="../assets/js/jquery.min.js"></script>-->
		<script src="../assets/js/jquery.dropotron.min.js"></script>
		<script src="../assets/js/browser.min.js"></script>
		<script src="../assets/js/breakpoints.min.js"></script>
		<script src="../assets/js/util.js"></script>
		<script src="../assets/js/main.js"></script>
  	</body>
</html>
