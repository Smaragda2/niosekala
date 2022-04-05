<?php

	if(isset($_POST['RequestAppointment'])){

		date_default_timezone_set('Europe/Athens');
		$datum = new DateTime();
		$startTime = $datum->format('Y-m-d H:i:s');

		$email = $_POST['email'];
		$selectedDate = $_POST['onDate'];
		$selectedTime = $_POST['atTime'];
		$createdAt = $startTime;

		$tokenStr = $email.','.$selectedDate.','.$selectedTime.','.$createdAt;
		$token = sha1($tokenStr);

		$url = getUrl($_POST['price'],$_POST['name'],$token);

		$insertFullRequest = "INSERT INTO `Request`(`name`, `email`, createdAt, `tel`, `whereTo`, `skypeName`, `notes`, isCompleted, isCancelled, isConfirmed, isPaid, `selectedProductID`,paymentToken, onDate, atTime) VALUES (?,?,?,?,?,?,?,false,false,false,false,?,?,?,?)";
		$insertTelRequest = "INSERT INTO `Request`(`name`,  `email`, createdAt, `tel`, `whereTo`, `notes`, isCompleted, isCancelled, isConfirmed, isPaid, `selectedProductID`,paymentToken, onDate, atTime) VALUES (?,?,?,?,?,?,false,false,false,false,?,?,?,?)";

		$formattedOnDate = date('Y-m-d',strtotime($selectedDate));
		$insertBooked = "INSERT INTO `Booked`(`createdAt`,`onDate`, `atTime`) VALUES ('".$createdAt."','".$formattedOnDate."','".$selectedTime."')";

	 	$mysqli = $_SESSION['dbconnect'];

		$getPaymentInfoByPaymentToken = 'SELECT COUNT(*) as exist FROM `paymentInfo` WHERE paymentToken = "'.$token.'"';
		$results = $mysqli->query($getPaymentInfoByPaymentToken);
		$row = $results->fetch_assoc();

		if($row['exist'] == '0'){
			$insertPaymentInfo = 'INSERT INTO paymentInfo(paymentToken, paymentStatus) VALUES ("'.$token.'","Pending")';
			$stmt3 = $mysqli->prepare($insertPaymentInfo);
			$paymentStatus = $stmt3->execute();
		}else{
			$paymentStatus = true;
		}

		$send = false;

		$bookedStmt = $mysqli->prepare($insertBooked);
		if(!$bookedStmt->execute())
			print $bookedStmt->error;

		if($_POST['whereTo'] == "Skype"){

			$stmt = $mysqli->prepare($insertFullRequest);
			$stmt->bind_param("sssssssisss", $_POST['fullName'], $_POST['email'], $startTime, $_POST['tel'], $_POST['whereTo'], $_POST['SkypeName'], $_POST['notes'], $_POST['id'],$token, $formattedOnDate, $selectedTime);

			if($stmt->execute()){
				$send = true;

			}else
				$send = false;

		}else{
			$stmt = $mysqli->prepare($insertTelRequest);
			$stmt->bind_param("ssssssisss", $_POST['fullName'], $_POST['email'], $startTime, $_POST['tel'], $_POST['whereTo'], $_POST['notes'], $_POST['id'],$token, $formattedOnDate, $selectediTime);

			if($stmt->execute())
				$send = true;
			else
				$send = false;
		}

		Logger::info('Appointment Info {Date: '.$selectedDate.', At Time: '.$selectedTime.', Customer Name: '.$_POST['fullName'].', Customer Email: '.$_POST['email'].', Customer Phone: '.$_POST['tel'].', Selected Product: '.$_POST['name'].',Price: '.$_POST['price'].', Appointment Notes: '.$_POST['notes'].', Appoint through: '.$_POST['whereTo'].'}');
		Logger::info('Appointment Token: '.$token.', Created At: '.$createdAt); 

		$message = '<div style="text-align:left"><br><h3>Τα στοιχεία σας: </h3><br><hr><br>';
		$message .= '<div class="row" style="text-align:left">Ονοματεπώνυμο: '.$_POST['fullName'].'<br>';
		$message .= 'Email: '.$_POST['email'].'<br>';
		$message .= 'Τηλέφωνο: '.$_POST['tel'].'<br></div>';
		$message .= '<hr><br><h3>Στοιχεία Ραντεβού: </h3><br><hr><br>';
		$message .= '<div class="row" style="text-align:left">Όνομα Συνεδρίας: '.$_POST['name'].'<br>';
		$message .= 'Κόστος Συνεδρίας: '.$_POST['price'].'&euro;<br>';
		$message .= '&nbsp;&nbsp; Επιθυμητή Ημερομηνία και Ώρα: '.$selectedDate.' '.$selectedTime.'<br>';
		$message .= '&nbsp;&nbsp; Σημειώσεις Συνεδρίας: <br>&nbsp;&nbsp;<textarea rows="5" readonly>'.$_POST['notes'].'</textarea><br></div></div><hr><br>';
		$message .= '<a href="'.$url.'">Μετάβαση στην Πληρωμή</a>';

		print<<<END
		<div style="text-align:left">
			<h3>Επιβεβαίωση στοιχείων ραντεβού:</h3><br>
			$message
		</div>
END;

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
