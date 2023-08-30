<!DOCTYPE HTML>
<!--
	Arcana by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->

<!-- VERSION 1.2.2 -->

<html>
<?php
	include_once "database/dbconnect.php";
	$database = new Database();
	$db = $database->getConnection();
	$_SESSION['dbconnect'] = $db;

	include_once "logger/Logger.php";
?>
	<head>
		<title>Niose Kala</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<meta name="description" content="Σύμβουλος ψυχικής υγείας - Σταυρούλα Καροφυλλάκη. Niose Kala. Online Συνεδρίες ψυχολογίας."/>
		<link rel="stylesheet" href="assets/css/main.css" media="print" onload="this.media='all'" />
		<link rel="stylesheet" href="assets/css/_custom-forms.scss" media="print" onload="this.media='all'"/>
		<script src="https://kit.fontawesome.com/ec3f31a4cb.js" crossorigin="anonymous"></script>

		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

		<meta name="referrer" content="strict-origin-when-cross-origin">
		<meta property="og:type" content="website"/>
		<meta property="og:title" content="Niose Kala"/>
		<meta property="og:url" content="https://niosekala.gr/"/>
		<meta property="og:description" content="Το τι μπορείς να κάνεις, είναι πιο σημαντικό από το ποιος είσαι"/>

		<meta name="theme-color" media="(prefers-color-scheme: light)" content="white">
		<link rel="manifest" href="/manifest.json">

	    <!-- Bootstrap core CSS -->
	    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'">

	    <!-- Custom styles for this template -->
		<link href="https://getbootstrap.com/docs/4.0/examples/checkout/form-validation.css" rel="stylesheet" media="print" onload="this.media='all'">
	</head>
	<body class="is-preload">
		<div id="page-wrapper">

			<!-- Header -->
				<div id="header">
					<a href="index.php" id="logo" hidden="hidden">Niose Kala</a>
						<p style="text-align:left;margin-left:15%;font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;color:blue">Email: niosekala@gmail.com&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Τηλέφωνο Επικοινωνίας: 6948266209&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="https://www.facebook.com/niosekala/" target="_blank" class="icon brands fa-facebook-f"><span class="label">Facebook</span> NIOSE KALA - νιώσε καλά</a></p>
					<!-- Nav -->
						<nav id="nav" style="background-color:#182061;float: left;list-style:none;width:100%;">
							<ul style="height: 100px;">
								<li style="white-space: nowrap;display: inline;text-align:center;line-height: 100px;"><img loading="lazy" alt="Logo Image. Σύμβουλος ψυχικής υγείας Καροφυλλάκη Σταυρούλα" src="images/logo.webp" height="100px" width="300px" style="float: left;"></li>
								<li><a href="?p=start">Αρχική</a></li>
								<li><a href="?p=Services">Υπηρεσίες</a></li>
								<li><a href="?p=Articles">Άρθρα</a></li>
								<li><a href="?p=whyOnline">Γιατί Online?</a></li>
								<li><a href="?p=Biography">Βιογραφικό</a></li>
								<li><a href="?p=Contact">Επικοινωνία</a></li>
								<li>
									<i class="fa fa-bars" style="color:#c0c0c0">
										Περισσότερα
									</i>
									<ul>
										<!--<li><a href="?p=Videos">Βίντεο - Συνέντευξεις</a></li>-->
										<li><a href="?p=howTo">Οδηγός Πληρωμής</a></li>
										<li><a href="?p=Oroi">Όροι Χρήσης</a></li>
									</ul>
								</li>
							</ul>
						</nav>
				</div>
			<!-- Banner
				<section id="banner">
				</section>
			-->

			<!-- Internal Pages -->
			<main role="main" style="margin-top:2%;margin-left:10%;margin-right:10%;width:95%;font-family:Calibri;font-size:large;display:inline-block;" class="col-md-9 col-lg-10 px-4" id="main">
	    	<section class="row text-center placeholders">
	      	<?php
						if( ! isset($_REQUEST['p'])) {
							$_REQUEST['p']='start';
						}
						$p = $_REQUEST['p'];
						Logger::navigateToPage();
						switch ($p){
							case "start" :			require "start.php";
														break;
							case "Appointment" :		if(isset($_POST['AnonymousSubmit'])){
															require "appointments/createAnonymousAppointment.php";
															break;
														}
														require "appointments/createAppointment.php";
														break;
							case "Services" :			require "services/Services.php";
														break;
							case "Payment" :			require "payments/testPaymentResponse.php";
														break;
							case "Request" :			require "appointments/request.php";
														break;
							case "Test" :				require "payments/test.php";
														break;
							case "Biography" :			require "biography.php";
														break;
							case "Contact" :			require "Contact.php";
														break;
							case "whyOnline" :			require "whyOnline.php";
														break;
							case "Oroi" :				require "oroiXrisis.php";
														break;
							case "howTo" :				require "howTo/howTo.php";
														break;
							case "Confirm" :			require "appointments/Confirm.php";
														break;
							case "Videos" :				require "videos.php";
														break;
							case "Articles":			require "articles/articles.php";
														break;
							case "Article":				require "articles/article.php";
														break;
						}
					?>
				</section>
			</main>

			<!-- Footer -->
				<div id="footer">
					<div class="container">
						<div class="row">
							<section class="col-4 col-6-narrower col-12-mobilep">
								<h3>Links σχετικά με το Site:</h3>
								<ul class="links">
									<li><a href="?p=Services">Υπηρεσίες</a></li>
									<li><a href="?p=whyOnline">Γιατί Online?</a></li>
									<li><a href="?p=Biography">Βιογραφικό</a></li>
									<li><a href="?p=Contact">Επικοινωνία</a></li>
									<li><a href="?p=Oroi">Όροι Χρήσης</a></li>
								</ul>
							</section>
							<section class="col-3 col-12-narrower">
								<div class="col" align="right" style="text-align:left;margin-right:7%;width:90%;font-size:small">
									<h4><b>ΧΡΗΣΙΜΟΙ ΣΥΝΔΕΣΜΟΙ:</b></h4>
									<ul class="links">
										<li><a href="https://www.hamogelo.gr/" target="_blank">Το Χαμόγελο του Παιδιού</a></li>
										<li><a href="https://www.saferinternet.gr/" target="_blank">Ελληνικό Κέντρο Ασφαλούς Διαδικτύου</a></li>
										<li><a href="https://www.na-greece.gr/" target="_blank">Ναρκομανείς Ανώνυμοι</a></li>
										<li><a href="http://aa-greece.gr/" target="_blank">Αλκοολικοί Ανώνυμοι</a></li>
										<li><a href="https://www.18ano.gr/" target="_blank">Μονάδα Απεξάρτησης 18 ΑΝΩ</a></li>
										<li><a href="https://www.kethea.gr/" target="_blank">Κέντρο Θεραπείας Εξαρτημένων Ατόμων</a></li>
									</ul>
								</div>
							</section>
							<section class="col-5 col-12-narrower">
								<div class="col col-12-narrower" align="right" style="text-align:center;margin-right:7%;width:90%;font-size:small">
									<ul class="links">
										<li>Γραμμή Άμεσης Κοινωνικής Βοήθειας - <a href="tel:197">197</a></li>
										<li>Γραμμή SOS για Γυναίκες Θύματα Βίας - <a href="tel:15900">15900</a></li>
										<li>Γραμμή Βοήθειας για την χρήση του Διαδικτύου ΥποΣΤΗΡΙΖΩ - <a href="tel:8001180015">800 11 800 15</a></li>
										<li>Γραμμή Βοήθειας για την Κατάθλιψη - <a href="tel:1034">1034</a></li>
										<li>Γραμμή Παρέμβασης για την Αυτοκτονία- Κλίμακα - <a href="tel:1018">1018</a></li>
										<li>Εθνική Τηλεφωνική Γραμμή για τα Παιδιά SOS - <a href="tel:1056">1056</a></li>
										<li>Τηλεφωνική συμβουλευτική «Γραμμή -Σύνδεσμος» - <a href="tel:8018011177">801 801 1177</a></li>
									</ul>
								</div>
							</section>
						</div>
						<div class="row" style="margin-top:1%">
							<section class="col col-12-narrower">
								<div class="col col-12-narrower" align="right" style="text-align:center;margin-right:7%;width:90%;">
									<?php require "Contact.php"; ?>
								</div>
							</section>
						</div>

					</div>

					<!-- Company -->
					<section class="col-6 col-6-narrower col-12-mobilep" style="width:100%">
						<p style="font-size:small;width:100%;margin-left:50%">
							Karofillaki Stavroula  - counseling psychologist
							<br>head office address: 28h oktovriou 34, Agios Nikolaos Crete, tel : 6948266209, VAT:EL045580163
						</p>
					</section>
					<!-- Copyright -->
						<div class="copyright">
							<ul class="menu">
								<li>&copy; 2020. All rights reserved</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
								<li>Created By: <a href="https://sites.google.com/site/prasianakismaragda">Smaragda Prasianaki</a></li>
							</ul>
						</div>

				</div>
		</div>
		<?php
			if (isset($_GET['hello'])) {
			    getAppointmentDetails();
			}

			function getDates(){
				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL, "https://calendly.com/api/v1/users/me/event_types");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

				$headers = array();
				$headers[] = "X-TOKEN: CAIFDIEBAUCRICLEJUDER7TEY64NBMBO";
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$result = curl_exec($ch);
				if (curl_errno($ch)) {
				    echo 'Error:' . curl_error($ch);
				}else{
					print "<div> Results <hr>";
					print_r($result);
					print "</div>";
				}
				curl_close ($ch);

			}

			function getAppointmentDetails(){
				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL, "https://calendly.com/api/v2/users/me/events");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

				$post = array(
					"min_start_time"=>"2020-03-24T12:00:00Z",
				    "order" => "desc",
				    "page_size"=>"1"
				);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

				$headers = array();
				$headers[] = "X-TOKEN: CAIFDIEBAUCRICLEJUDER7TEY64NBMBO";
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$resultID = curl_exec($ch);
				if (curl_errno($ch)) {
				    echo 'Error:' . curl_error($ch);
				}else{
					print "<div> Results <hr>";
					print_r($resultID);
					print "</div>";
				}
				curl_close ($ch);

				//getDetails($resultID);
			}
			function getDetails($id){
				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL, "https://calendly.com/api/v1/hooks/".$id);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

				$headers = array();
				$headers[] = "X-TOKEN: CAIFDIEBAUCRICLEJUDER7TEY64NBMBO";
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$resultID = curl_exec($ch);
				if (curl_errno($ch)) {
				    echo 'Error:' . curl_error($ch);
				}else{
					print "<div> Results <hr>";
					print_r($resultID);
					print "</div>";
				}
				curl_close ($ch);

			}

		?>
		<!-- Scripts -->
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>
			<script>
				function CheckRequiredContact(){
					var allRequired = true;

					var name = new RegExp("^[a-zA-Zα-ωΑ-Ω]{4,}");
					var email = new RegExp("^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$");
					var message = new RegExp("^[a-zA-Zα-ωΑ-Ω].{5,}");

					var recaptcha = grecaptcha.getResponse().length > 0;

					if( name.test($("#contactName").val()) && email.test($("#contactEmail").val())
							&& message.test($("#contactMessage").val()) && recaptcha){
				        allRequired = true;
					}else{
				        allRequired = false;
					}

					if(!allRequired){
						$("#contactSubmit").attr('disabled', true);
						$("#contactSubmit").attr('style','background-color:gray');
						//document.getElementByID("contact").style.background-color = '#37c0fb';
					}else{
						$("#contactSubmit").attr('disabled', false);
						$("#contactSubmit").attr('style','background-color:#37c0fb');
						//document.getElementByID("contact").style.background-color = '#37c0fb';
					}
				}

			</script>

			<!-- Cookie Consent by https://www.TermsFeed.com -->
			<script type="text/javascript" src="//www.termsfeed.com/public/cookie-consent/3.0.0/cookie-consent.js"></script>
			<script type="text/javascript">
			document.addEventListener('DOMContentLoaded', function () {
			cookieconsent.run({"notice_banner_type":"headline","consent_type":"implied","palette":"dark","language":"el","website_name":"Niose Kala"});
			});
			</script>
			<noscript>Cookie Consent by <a href="https://www.TermsFeed.com/">TermsFeed Generator</a></noscript>
			<!-- End Cookie Consent -->

	</body>
</html>
