<?php

// ----------------------------------------- Confirm Appontment -------------------------------------------
	if(isset($_POST['confirmAppointment'])){
		$email = $_POST['email'];
		$onDate  = $_POST['onDate'];
		$atTime  = $_POST['atTime'];
		$createdAt = $_POST['createdAt'];

		$hour = $onDate.' '.$atTime;

		$send = false;

		$token = $_POST['token'];

	 	$mysqli = $_SESSION['dbconnect'];

		$updateConfirm = 'UPDATE Request SET isConfirmed=true WHERE paymentToken="'.$token.'"';
		$stmt = $mysqli->prepare($updateConfirm);
		$status = $stmt->execute();

		if($status === true){
			$send = true;
		}

		if($send){
			if(!$mail = smtpmailer($email,$hour)) {
				print "<br>".'<span style="color:red">'."Fail to send email.<br> Please try again!<hr> </p><br>";
				//echo "<script>setTimeout();</script>";

			}else{
				print "<br>".'<span style="color:green">'."Το ραντεβού επιβεβαιώθηκε με επιτυχεία. </p><br>";
				print<<<END
					<script>
						setTimeout(function(){
							window.location.href = "?p=confirmAppointment"
						}, 4 * 1000);
					</script>
END;
			}
		}else{
			print "<br>".'<span style="color:red">'."Something went wrong.<br> Please try again!<hr> </p><br>";
		}
	}

// --------------------------------------------- Appointment Completed ------------------------------------------------------
	if(isset($_POST['completeAppointment'])){
	 	$mysqli = $_SESSION['dbconnect'];

		$email = $_POST['email'];
		$onDate  = $_POST['onDate'];
		$atTime  = $_POST['atTime'];
		$createdAt = $_POST['createdAt'];
		$token = $_POST['token'];

		$updateConfirm = 'UPDATE Request SET isCompleted=true WHERE paymentToken = "'.$token.'"';

		$stmt = $mysqli->prepare($updateConfirm);

		if($stmt->execute()){
			print "<br>".'<span style="color:green">'."Το ραντεβού ενημερώθηκε με επιτυχεία. </span><br>";
			print<<<END
				<script>
					setTimeout(function(){
						window.location.href = "?p=Appointments"
					}, 2 * 1000);
				</script>
END;
		}else{
			print "<br>".'<span style="color:red">'."Something went wrong.<br> Please try again!<hr> </p><br>";
		}
	}

	if(isset($_POST['completeAnonymous'])){
	 	$mysqli = $_SESSION['dbconnect'];

		$tel = $_POST['tel'];
		$createdAt = $_POST['createdAt'];

		$updateConfirm = 'UPDATE RequestAnonymous SET isCompleted=true WHERE tel = "'.$tel.'" and createdAt = "'.$createdAt.'"';

		$stmt = $mysqli->prepare($updateConfirm);

		if($stmt->execute()){
			print "<br>".'<span style="color:green">'."Το ραντεβού ενημερώθηκε με επιτυχεία. </span><br>";
			print<<<END
				<script>
					setTimeout(function(){
						window.location.href = "?p=Appointments"
					}, 4 * 1000);
				</script>
END;
		}else{
			print "<br>".'<span style="color:red">'."Something went wrong.<br> Please try again!<hr> </p><br>";
		}
	}

// --------------------------------------------- Change Appointment ------------------------------------------------------

	if(isset($_POST['changeHour'])){
	 	$mysqli = $_SESSION['dbconnect'];

		$email = $_POST['email'];
		$newDate = $_POST['newDate'];
		$newTime = $_POST['newTime'];
		$onDate  = $_POST['onDate'];
		$atTime  = $_POST['atTime'];
		$createdAt = $_POST['createdAt'];
		$token = $_POST['token'];

		$date = $newDate.' '.$newTime;
		$hours = $onDate.' '.$atTime;

		$send = true;

		$formattedNewDate = date('d-m-Y',strtotime($newDate));
		$updateConfirm = 'UPDATE Request SET onDate="'.$formattedNewDate.'",atTime = "'.$newTime.'", isConfirmed=true WHERE paymentToken="'.$token.'"';

		$stmt = $mysqli->prepare($updateConfirm);
		if($send){
			if($stmt->execute()){

				if(!$mail = smtpmailerChange($email,$hours,$date)) {
					print "<br>".'<span style="color:red">'."Fail to send email.<br> Please try again!<br><hr><br>";
					print "<br> <b>*Σημείωση:</b> Η ημερομηνία & ώρα του ραντεβού έχει αλλάξει, όμως το email ενημέρωσης ΔΕΝ μπόρεσε να σταλθεί στον πελάτη.<br> To ραντεβού πλέον βρίσκετε στην σελίδα <a href='?p=confirmAppointment'> Όλα τα επιβεβαιωμένα ραντεβού </a><br>";
				}else{
					print "<br>".'<span style="color:green">'."Η Ημερομηνία και η Ώρα του ραντεβού ενημερώθηκαν με επιτυχεία. <br> *Στάλθηκε ενημερωτικό email στον πελάτη.</span><br>";
					print<<<END
						<script>
							setTimeout(function(){
								window.location.href = "?p=confirmAppointment"
							}, 10 * 1000);
						</script>
END;
				}
			}else{
				print "<br>".'<span style="color:red">'."Something went wrong.<br> Please try again later!<hr> </p><br>";
			}
		}else{
			print "<br>".'<span style="color:red">'."Το ραντεβού δεν μπόρεσε να ενημερωθεί! <br> Παρακαλώ δοκιμάστε να αλλάξετε ξανά την Ημερομηνία & Ώρα του ραντεβού.<hr> </p><br>";
			print<<<END
				<script>
					setTimeout(function(){
						window.location.href = "?p=confirmAppointment"
					}, 6 * 1000);
				</script>
END;
		}
	}


// --------------------------------------------- Functions ------------------------------------------------------
	function smtpmailerChange($email,$hour,$date) {

		$slittedURI = explode('/',$_SERVER['REQUEST_URI']);
		if($slittedURI[1]=="_aDemo"){
			define('GUSER', 'smaragdapink7@gmail.com'); // GMail username
			define('GPWD', 'nfnhlfytlcgaybvr'); // GMail password
		}else{
			define('GUSER','niosekala@gmail.com'); // niose kala gmail email
			define('GPWD','xuvzpmmvboekurgj'); //niose kala gmail pass
		}

		$message = 	'<head> <meta charset="utf-8" /> </head>';
		$message .= '<body><divstyle="text-align:left"><h2>Αλλαγή Ημερομηνίας Ραντεβού:</h2><br><hr><br>';
		$message .= '<div class="row" style="text-align:left">Η ημερομηνία και ώρα του ραντεβού σας, για τις '.$hour.', άλλαξε.<br>';
		$message .= 'Η <b><i>Νέα</i></b> Ημ/νια & Ώρα του Ραντεβού ειναι: <b>'.$date.'</b><br></div>';
		$message .= '<br><h3>*Η ημερομηνία και ώρα του ραντεβού σας έχουν επιβεβαιωθεί.</h3><br></body>';

		require_once('mailer/class.phpmailer.php');

		global $error;
		$mail = new PHPMailer();  // create a new object
		$mail->CharSet="UTF-8";
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true;  // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 465;
		$mail->Username = GUSER;
		$mail->Password = GPWD;
		$mail->SetFrom(GUSER, 'Niose Kala');
		$mail->Subject = "Niose Kala - Η Ημερομηνία του Ραντεβού σας άλλαξε";
		$mail->MsgHTML($message);
		$mail->AddAddress($email);
		$mail->AddBCC(GUSER);
		if(!$mail->Send()) {
			$error = 'Mail error: '.$mail->ErrorInfo;
			print_r($error);
			return false;
		} else {
			return true;
		}
	}


	function smtpmailer($email,$hour) {
		$slittedURI = explode('/',$_SERVER['REQUEST_URI']);
		if($slittedURI[1]=="_aDemo"){
			define('GUSER', 'smaragdapink7@gmail.com'); // GMail username
			define('GPWD', 'nfnhlfytlcgaybvr'); // GMail password
		}else{
			define('GUSER','niosekala@gmail.com'); // niose kala gmail email
			define('GPWD','xuvzpmmvboekurgj'); //niose kala gmail pass
		}

		$message = 	'<head> <meta charset="utf-8" /> </head>';
		$message .= '<body><divstyle="text-align:left"><h2>Επιβεβαίωση Ραντεβού:</h2><br><hr><br>';
		$message .= '<div class="row" style="text-align:left">Η ημερομηνία και ώρα του ραντεβού σας επιβεβαιώθηκε.<br>';
		$message .= 'Ημ/νια & Ώρα Ραντεβού: '.$hour.'<br></div></body>';

		require_once('mailer/class.phpmailer.php');

		global $error;
		$mail = new PHPMailer();  // create a new object
		$mail->CharSet="UTF-8";
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true;  // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 465;
		$mail->Username = GUSER;
		$mail->Password = GPWD;
		$mail->SetFrom(GUSER, 'Niose Kala');
		$mail->Subject = "Niose Kala - Επιβεβαίωση Ημ/νιας & Ώρας Ραντεβού";
		$mail->MsgHTML($message);
		$mail->AddAddress($email);
		$mail->AddBCC(GUSER);
		if(!$mail->Send()) {
			$error = 'Mail error: '.$mail->ErrorInfo;
			print_r($error);
			return false;
		} else {
			return true;
		}
	}

	function smtpmailer2($email,$hour) {
		$slittedURI = explode('/',$_SERVER['REQUEST_URI']);
		if($slittedURI[1]=="_aDemo"){
			define('GUSER', 'smaragdapink7@gmail.com'); // GMail username
			define('GPWD', 'nfnhlfytlcgaybvr'); // GMail password
		}else{
			define('GUSER','niosekala@gmail.com'); // niose kala gmail email
			define('GPWD','xuvzpmmvboekurgj'); //niose kala gmail pass
		}

		$message = 	'<head> <meta charset="utf-8" /> </head>';
		$message .= '<body><divstyle="text-align:left"><h2>Το Ραντεβού σας για τις '.$hour.' Ακυρώθηκε.</h2><br><hr><br>';
		$message .= '<h3>Για νέο ραντεβού επικοινωνήστε μαζί μας στο: 6900000000</h3><br>';
		$message .= 'ή κλείστε ραντβού μέσω τις σελίδας μας: <a href="https://niosekala.gr">niosekala.gr</a><br><hr><br>';

		require_once('mailer/class.phpmailer.php');

		global $error;
		$mail = new PHPMailer();  // create a new object
		$mail->CharSet="UTF-8";
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true;  // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 465;
		$mail->Username = GUSER;
		$mail->Password = GPWD;
		$mail->SetFrom(GUSER, 'Niose Kala');
		$mail->Subject = "Niose Kala - Το Ραντεβού σας Ακυρώθηκε.";
		$mail->MsgHTML($message);
		$mail->AddAddress($email);
		$mail->AddBCC(GUSER);
		if(!$mail->Send()) {
			$error = 'Mail error: '.$mail->ErrorInfo;
			return false;
		} else {
			return true;
		}
	}

	function getUrl($price,$name,$token){
	//----- DEMO -----
		$host = "https://demo.vivapayments.com/api/orders";
		$encAuth = "NDA2MTY2ZWEtNmZhNy00ZTcwLTg1MmItMzYyNGY5ZWY1YzA2OnckRHs0Lw==";
		$sourceCode = "6726";

	//----- LIVE -----
		$hostLive = "https://www.vivapayments.com/api/orders";
		$encAuthLive = "N2RiMzQ0YTQtMzlmYy00N2ExLWFiMjUtYzkyZWViYzMxNGRhOjU3V3NBT2gzNjhKbjk5Zk83SHV0WFFMM2YzaDk3VA==";
		$sourceCodeLive = "3441";

		$return = "";

	    $price = $price*100;
	    $productName = $name;

		$data = array(
			"tags"=>array( $token ),
		    "PaymentTimeOut"=> 65535,
		    "RequestLang"=> "el-GR",
		    "MaxInstallments"=> 12,
		    "AllowRecurring"=> false,
		    "IsPreAuth"=> false,
		    "Amount"=> $price,
		    "MerchantTrns"=> "Niose Kala",
		   	"disableCash"=> true,
		   	"disablePayAtHome"=> true,
		   	"sourceCode"=>$sourceCodeLive,
		    "CustomerTrns"=> $productName,
		    "disableIVR"=> true
		);
		$headers = array(
		    'Content-Type:application/json',
		    'Authorization: Basic '.$encAuthLive // <---
		);

		$ch = curl_init($hostLive);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$return = curl_exec($ch);
		curl_close($ch);

		$result =json_decode($return);
		$orderCode = $result->OrderCode;

		return "https://www.vivapayments.com/web/checkout?ref=".$orderCode;

	}
?>
