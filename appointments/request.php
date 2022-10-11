
<?php
	$slittedURI = explode('/',$_SERVER['REQUEST_URI']);
	if($slittedURI[1]=="_aDemo"){
		define('GUSER', 'smaragdapink7@gmail.com'); // GMail username
		define('GPWD', 'nfnhlfytlcgaybvr'); // GMail password
	}else{
		define('GUSER','niosekala@gmail.com'); // niose kala gmail email
		define('GPWD','xuvzpmmvboekurgj'); //niose kala gmail pass
	}

	$message = 	'<head> <meta charset="utf-8" /> </head>';

	$mysqli = $_SESSION['dbconnect'];

	date_default_timezone_set('Europe/Athens');
	$datum = new DateTime();
	$startTime = $datum->format('Y-m-d H:i:s');

	if(isset($_POST['contact'])){
		if(isset($_POST['g-recaptcha-response']))
		{
			$secretKey ="6Le84G4iAAAAALeho4XJWVtttPWuXVW-WNrxFKc4";
	    $createGoogleUrl = 'https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response'];
			$verifyRecaptcha = curlRequest($createGoogleUrl);
			$decodeGoogleResponse = json_decode($verifyRecaptcha,true);

	    if($decodeGoogleResponse['success'] != 1)
			{
				Logger::warn('Recaptcha validation failed. Reason: '.implode(",", $decodeGoogleResponse['error-codes']));
				print("<br>Recaptcha validation failed. Please try again!<br>");
				print<<<END
					<script>
						setTimeout(function(){
							window.location.href = "?p=Contact"
						}, 10 * 1000);
					</script>
END;
				return;
      } else {
      	Logger::info('Recaptcha validation succeed.');
      }
    }

		$message .= '<body><divstyle="text-align:left"><br><h3>Στοιχεία Πελάτη: </h3><br><hr><br>';
		$message .= '<div class="row" style="text-align:left">Όνομα: '.$_POST['name'].'<br>';
		$message .= 'Email: '.$_POST['email'].'<br></div>';
		$message .= '<hr><br><h3>Mήνυμα: </h3><br><hr><br>';
		$message .= '<div class="row" style="text-align:left;width:90%">';
		$message .= '<br>&nbsp;&nbsp;<textarea rows="5" readonly>'.$_POST['message'].'</textarea><br></div></div><hr><br></body>';

		Logger::info('Customer Info {Name: '.$_POST['name'].', Email: '.$_POST['email'].', Message: '.$_POST['message'].'}');
		if(!$mail = smtpmailer($message,"Niose Kala - Έχετε Νέο Μήνυμα.")) {
			print "<br>Fail to send email.<br> ";
			print("<br>Please try again!<hr>");
			Logger::warn('FAILED to send email - Customer Info {Name: '.$_POST['name'].', Email: '.$_POST['email'].', Message: '.$_POST['message'].'}');
		}else{
			print "<br>".'<span style="color:green">'."Το μήνυμα στάλθηκε με επιτυχία. Σύντομα θα επικοινωνίσουμε μαζί σας!</p><br>";
			Logger::info('Email send SUCCESSFULLY - Customer Info {Name: '.$_POST['name'].', Email: '.$_POST['email'].', Message: '.$_POST['message'].'}');
		}
	}

	function smtpmailer($body,$subject) {
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
		$mail->Subject = $subject;
		$mail->MsgHTML($body);
		$mail->AddAddress(GUSER);
		if(!$mail->Send()) {
			$error = 'Mail error: '.$mail->ErrorInfo;
			echo $error;
			return false;
		} else {
			return true;
		}
	}

	function curlRequest($url)
	{
	    $ch = curl_init();
	    $getUrl = $url;
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_URL, $getUrl);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 80);

	    $response = curl_exec($ch);

			curl_close($ch);
	    return $response;
	}

?>
