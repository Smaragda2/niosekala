<?php

	if(isset($_POST['RequestAppointment'])){
		$mysqli = $_SESSION['dbconnect'];
		
		date_default_timezone_set('Europe/Athens');
		$datum = new DateTime();
		$startTime = $datum->format('Y-m-d H:i:s');
		
		$onDate = $_POST['onDate'];
		$atTime = $_POST['atTime'];
		$email = $_POST['email'];

		$hour = $onDate.' '.$atTime;
		$createdAt = $startTime;
		
		$insertFullRequest = "INSERT INTO `Request`(`name`, `email`, createdAt, `tel`, `whereTo`, `skypeName`, `notes`, isCompleted, isCancelled, isConfirmed, isPaid, `selectedProductID`,paymentToken, onDate, atTime) VALUES (?,?,?,?,?,?,?,false,false,false,false,?,?,?,?)";
		$insertTelRequest = "INSERT INTO `Request`(`name`,  `email`, createdAt, `tel`, `whereTo`, `notes`, isCompleted, isCancelled, isConfirmed, isPaid, `selectedProductID`,paymentToken, onDate, atTime) VALUES (?,?,?,?,?,?,false,false,false,false,?,?,?,?)";

		$formattedOnDate = date('Y-m-d',strtotime($onDate));
		$insertBooked = "INSERT INTO `Booked`(`createdAt`,`onDate`, `atTime`) VALUES ('".$createdAt."','".$formattedOnDate."','".$atTime."')";
				
		$send = false;


		$createdAt = $startTime;
		
		$tokenStr = $email.','.$hour.','.$createdAt;
		$token = sha1($tokenStr);

		$bookedStmt = $mysqli->prepare($insertBooked); 
		if(!$bookedStmt->execute())
			print $bookedStmt->error;
			
		if($_POST['whereTo'] == "Skype"){

			$stmt = $mysqli->prepare($insertFullRequest);
			$stmt->bind_param("sssssssisss", $_POST['fullName'], $_POST['email'], $startTime, $_POST['tel'], $_POST['whereTo'], $_POST['SkypeName'], $_POST['notes'], $_POST['id'],$token, $formattedOnDate, $atTime);
			
			if($stmt->execute())
				$send = true;
			else{
				$send = false;
			}

		}else{
			$stmt = $mysqli->prepare($insertTelRequest);
			$stmt->bind_param("ssssssisss", $_POST['fullName'], $_POST['email'], $startTime, $_POST['tel'], $_POST['whereTo'], $_POST['notes'], $_POST['id'],$token, $formattedOnDate, $atTime);

			if($stmt->execute())
				$send = true;
			else
				$send = false;
		}

		$getPaymentInfoByPaymentToken = 'SELECT COUNT(*) as exist FROM `paymentInfo` WHERE paymentToken = "'.$token.'"';	
		$results = $mysqli->query($getPaymentInfoByPaymentToken);
		$row = $results->fetch_assoc();

		if($row['exist'] == '0'){
			$insertPaymentInfo = 'INSERT INTO paymentInfo(paymentToken, paymentStatus) VALUES ("'.$token.'","Pending")';
			$stmt3 = $mysqli->prepare($insertPaymentInfo);
			$paymentStatus = $stmt3->execute();
		}


		if($send){

			$url = getUrl($_POST['price'],$_POST['name'],$token);
			
			$subject = "Niose Kala - Ένα νέο Ραντεβού δημιουργήθηκε!";
			
			$message = 	'<head> <meta charset="utf-8" /> </head>';
			$message .= '<body><divstyle="text-align:left"><h2>Στοιχεία Νέου Ραντεβού:</h2><br><hr><br>';
			$message .= '<div class="row" style="text-align:left">Η ημερομηνία και ώρα του ραντεβού σας είναι '.$hour.'.<br>';
			$message .= '*Η Ημερομηνία και ώρα του ραντεβού είναι επιβεβαιωμένα.<br></div>';
			$message .= '<hr><br><h3>Για να ισχύει το ραντεβού σας θα πρέπει να ολοκληρώσετε την πληρωμή σας.</h3><br><hr><br>';
			$message .= '<div class="row" style="text-align:left">Για την πληρωμή πατήστε το παρακάτω link:<br>';
			$message .=  $url.'<br>';
			$message .= '<hr><br><h3>Το Link θα σας μεταφέρει αμέσως στην σελίδα της Viva Wallet για την ολοκλήρωση της πληρωμής σας.</h3><br><hr><br>';
			if(!$mail = smtpmailer($email,$subject,$message)) {
				print "<br>".'<span style="color:red">'."Fail to send email.<br> Please try again!<hr> </p><br>";
				//echo "<script>setTimeout();</script>";
		
			}else{
				print "<br>".'<span style="color:green">'."Το Ραντεβού Δημιουργήθηκε με επιτυχία. </p><br>";
				print<<<END
					<script>
						setTimeout(function(){
							window.location.href = "?p=createAppointment"
						}, 4 * 1000);
					</script>
END;
			}
		}else{
			print "<br>".'<span style="color:red">'."Something went wrong.<br> Please try again!<hr> </p><br>";
		}
	}	

	function smtpmailer($email,$subject,$message) { 
		$slittedURI = explode('/',$_SERVER['REQUEST_URI']);
		if($slittedURI[1]=="_aDemo"){
			define('GUSER', 'smaragdapink7@gmail.com'); // GMail username
			define('GPWD', 'ltkfycfxpcudyhvu'); // GMail password
		}else{
			define('GUSER','niosekala@gmail.com'); // niose kala gmail email
			define('GPWD','xuvzpmmvboekurgj'); //niose kala gmail pass
		}

		require_once('../appointments/mailer/class.phpmailer.php');
		
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
	
		$slittedURI = explode('/',$_SERVER['REQUEST_URI']);
		if($slittedURI[1]=="_aDemo"){
		//----- DEMO -----
			$host = "https://demo.vivapayments.com/api/orders";
			$encAuth = "NDA2MTY2ZWEtNmZhNy00ZTcwLTg1MmItMzYyNGY5ZWY1YzA2OnckRHs0Lw==";
			$sourceCode = "9539";
			$refURL= "https://demo.vivapayments.com/web/checkout?ref=";
		}else{
		//----- LIVE -----
			$host = "https://www.vivapayments.com/api/orders";
			$encAuth = "N2RiMzQ0YTQtMzlmYy00N2ExLWFiMjUtYzkyZWViYzMxNGRhOjU3V3NBT2gzNjhKbjk5Zk83SHV0WFFMM2YzaDk3VA==";
			$sourceCode = "3441";
			$refURL = "https://www.vivapayments.com/web/checkout?ref=";
		}

		$return = "";
	    
	    $price = $price*100;
	    $productName = $name;
	    
		$data = array(
			"tags"=> array( $token ),
		    "PaymentTimeOut"=> 65535,
		    "RequestLang"=> "el-GR",
		    "MaxInstallments"=> 12,
		    "AllowRecurring"=> false,
		    "IsPreAuth"=> false,
		    "Amount"=> $price,
		    "MerchantTrns"=> "Niose Kala",
		   	"disableCash"=> true,
		   	"disablePayAtHome"=> true,
		   	"sourceCode"=>$sourceCode,
		    "CustomerTrns"=> $productName,
		    "disableIVR"=> true
		);	
		$headers = array(
		    'Content-Type:application/json',
		    'Authorization: Basic '.$encAuth // <---
		);
		
		$ch = curl_init($host);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$return = curl_exec($ch);
		curl_close($ch);
		
		$result =json_decode($return);
		$orderCode = $result->OrderCode;
		
		return $refURL.$orderCode;

	}


?>