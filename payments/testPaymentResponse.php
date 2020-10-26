<?php
	$paymentStatus = $_REQUEST['vivawallet'];
	$paymentOrderUniqueID = $_REQUEST['s'];
	
	if(isset($_SESSION['dbconnect'])){
		$mysqli = $_SESSION['dbconnect'];
	}else{
		include_once "../database/dbconnect.php";
		$database = new Database();
		$mysqli = $database->getConnection();
		$_SESSION['dbconnect'] = $mysqli;
	}

	
		$slittedURI = explode('/',$_SERVER['REQUEST_URI']);
		if($slittedURI[1]=="_aDemo"){
			define('GUSER', 'smaragdapink7@gmail.com'); // GMail username
			define('GPWD', 'ltkfycfxpcudyhvu'); // GMail password
		}else{
			define('GUSER','niosekala@gmail.com'); // niose kala gmail email
			define('GPWD','xuvzpmmvboekurgj'); //niose kala gmail pass
		}
	
	if($paymentStatus == 'success'){
		$transactionID = $_REQUEST['t'];
		successfullPayment($transactionID,$paymentOrderUniqueID);
	}else{
		PaymentFailed($paymentOrderUniqueID);
	}
	
	function successfullPayment($transactionID,$paymentOrderUniqueID){
		$mysqli = $_SESSION['dbconnect'];
		
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

		$headers = array(
		    'Content-Type:application/json',
		    'Authorization: Basic '.$encAuth  // <---
		);
	
		$ch = curl_init($host);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$return= curl_exec($ch);
		curl_close($ch);
			
		$result =json_decode($return);
		$paymentToken = $result->Transactions[0]->Order->Tags[0];
		
		$updatePayment = 'UPDATE Request SET isPaid=true WHERE paymentToken="'.$paymentToken.'"';

		$stmt = $mysqli->prepare($updatePayment);
		$status = $stmt->execute();
		
		$paymentStatus = false;
		
		$getPaymentInfoByPaymentToken = 'SELECT COUNT(*) as exist FROM `paymentInfo` WHERE paymentToken = "'.$paymentToken.'"';	
		$results = $mysqli->query($getPaymentInfoByPaymentToken);
		$row = $results->fetch_assoc();

		if($row['exist'] == '0'){
			$insertPaymentInfo = 'INSERT INTO paymentInfo(orderUniqueID, paymentToken, transactionID, paymentStatus) VALUES ("'.$paymentOrderUniqueID.'","'.$paymentToken.'","'.$transactionID.'","Success")';
			$stmt3 = $mysqli->prepare($insertPaymentInfo);
			$paymentStatus = $stmt3->execute();
		}else{
			$updatePaymentInfo = 'UPDATE paymentInfo set orderUniqueID ="'.$paymentOrderUniqueID.'", transactionID = "'.$transactionID.'", paymentStatus = "Success" where paymentToken = "'.$paymentToken.'"';
			$stmt3 = $mysqli->prepare($updatePaymentInfo);
			$paymentStatus = $stmt3->execute();
		}
				
		$getRequestInfo = 'Select name,onDate,atTime,email,notes from Request where paymentToken = "'.$paymentToken.'"';
		$results = $mysqli->query($getRequestInfo);
		$requestRow = $results->fetch_assoc();
				
		$formattedOnDate = date('d-m-Y',strtotime($requestRow['onDate']));
		$notes = '';
		if(isset($requestRow['notes']))
			$notes = $requestRow['notes'];
		
		$message = '<body><divstyle="text-align:left"><br><h2>Η πληρωμή του Ραντεβού για τις '.$formattedOnDate.' '.$requestRow['atTime'].' έχει ολοκληρωθεί:</h2><br><h3>Στοιχεία Πελάτη: </h3><br><hr><br>';
		$message .= '<div class="row" style="text-align:left">Όνομα: '.$requestRow['name'].'<br>';
		$message .= 'Email: '.$requestRow['email'].'<br></div>';
		$message .= '<hr><br><h3>Σημειώσεις Ραντεβού: </h3><br><hr><br>';
		$message .= '<div class="row" style="text-align:left;width:90%">';
		$message .= '<br>&nbsp;&nbsp;<textarea rows="5" readonly>'.$notes.'</textarea><br></div></div><hr><br></body>';

		sendNewAppointmentMessage($paymentToken);

		if($status === true && $paymentStatus === true){
			print<<<END
				<div class="jumbotron col col-6-narrower col-12-mobilep" style="color:green">
					Η πληρωμή σαν ολοκληρώθηκε με επιτυχία!
					<br>Σας ευχαριστούμε για την εμπιστοσύνη.
				</div>	
END;
			smtpmailer($message);
		}else{
			print<<<END
				<div class="jumbotron col col-6-narrower col-12-mobilep" style="color:red">
					Η πληρωμή σαν ολοκληρώθηκε με επιτυχία!
					<br><hr><br>
					<h3>ΣΗΜΑΝΤΙΚΟ:</h3> Κάτι πήγε λάθος κατά την ενημέρωση του ραντεβού σας, παρακαλώ επικοινωνήστε με τον δημιουργό της Ιστοσελίδας στο email: <b>Email: smaragda.prasianaki@gmail.com</b>.
					<br>Το Email θα πρέπει να έχει ως τίτλο (subject): Niose kala - (Πληρωμή) Το ραντεβού μου δεν ενημερώθηκε σωστά.
					<br>Θα πρέπει στο κείμενο μέσα, να αναφέρετε το "Transaction ID", καθώς και τον κωδικό του ραντεβού σας.
					<br>ο Αριθμός του Transaction ID σας είναι: <b>$transactionID</b>., και ο κωδικός του ραντεβού σας είναι "$paymentToken".
					<br>Π.χ. To Transaction ID είναι "94d4fd50-c093-4745-8316-1aa54b665b97".
				</div>	
END;
				smtpmailer($message);
		}
			
	}
	
	function PaymentFailed($paymentOrderUniqueID){
		$mysqli = $_SESSION['dbconnect'];
		
		$insertPaymentInfo = 'INSERT INTO paymentInfo(orderUniqueID, paymentStatus) VALUES ('.$paymentOrderUniqueID.', "Failed")';
		$stmt = $mysqli->prepare($insertPaymentInfo);
		$status = $stmt->execute();
		
		if($status === true){
			print<<<END
				<div class="jumbotron col col-6-narrower col-12-mobilep" style="color:red">
					Η πληρωμή σας μέσω της Ιστοσελίδας Viva Wallet δεν μπόρεσε να ολοκληρωθεί, παρακαλώ δοκιμάστε ξανά αργότερα!
					<br>
					<hr>
					<br>
					<b>ΣΗΜΕΙΩΣΗ:</b> Εάν συνεχίζεται να έχετε πρόβλημα με την ολοκλήρωση της πληρωμής σας, παρακαλώ προωθήστε το email που σας στείλαμε με τίτλο "Niose Kala - Επιβεβαίωση Ημ/νιας & Ώρας Ραντεβού", προσθέτοντας στο email το εξής κείμενο: <b>"ID πληρωμής της Παραγγελίας: $paymentOrderUniqueID"</b>, στον δημιουργό της Ιστοσελίδας στο email: <b>Email: smaragda.prasianaki@gmail.com</b>.
				</div>	
END;
		}else{
			print<<<END
				<div class="jumbotron col col-6-narrower col-12-mobilep" style="color:red">
					Η πληρωμή σας μέσω της Ιστοσελίδας Viva Wallet δεν μπόρεσε να ολοκληρωθεί, παρακαλώ δοκιμάστε ξανά αργότερα!
				</div>	
END;
		}
	}
	
	function smtpmailer($message) { 
		require_once('mailer/class.phpmailer.php');
		
		$subject = "Niose Kala - Μια Πληρωμή ολοκληρώθηκε.";
		
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
		$mail->AddAddress(GUSER);
		if(!$mail->Send()) {
			$error = 'Mail error: '.$mail->ErrorInfo; 
			echo $error;
			return false;
		} else {
			return true;
		}
	}

	function smtpmailerNew($body,$subject) { 
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

	function sendNewAppointmentMessage($token){
		$mysqli = $_SESSION['dbconnect'];

		$getRequestInfo = 'SELECT r.name, onDate,atTime,email,tel,notes, p.name as Pname, price FROM `Request` r JOIN Product p ON r.selectedProductID = p.id WHERE paymentToken = "'.$token.'"';
		$results = $mysqli->query($getRequestInfo);
		$requestRow = $results->fetch_assoc();

		$formattedOnDate = date('d-m-Y',strtotime($requestRow['onDate']));

		$message = 	'<head> <meta charset="utf-8" /> </head>';
		
		$message .= '<body><divstyle="text-align:left"><br><h3>Στοιχεία Πελάτη: </h3><br><hr><br>';
		$message .= '<div class="row" style="text-align:left">Ονοματεπώνυμο: '.$requestRow['name'].'<br>';
		$message .= 'Email: '.$requestRow['email'].'<br>';
		$message .= 'Τηλέφωνο: '.$requestRow['tel'].'<br></div>';
		$message .= '<hr><br><h3>Στοιχεία Ραντεβού: </h3><br><hr><br>';
		$message .= '<div class="row" style="text-align:left">Όνομα Συνεδρίας: '.$requestRow['Pname'].'<br>';
		$message .= 'Κόστος Συνεδρίας: '.$requestRow['price'].'&euro;<br>';
		$message .= '&nbsp;&nbsp; Επιθυμητή Ημερομηνία και Ώρα: '.$formattedOnDate.' '.$requestRow['atTime'].'<br>';
		$message .= '&nbsp;&nbsp; Σημειώσεις Συνεδρίας: <br>&nbsp;&nbsp;<textarea rows="5" readonly>'.$requestRow['notes'].'</textarea><br></div></div><hr><br>';
		$message .= '<div class="row">Μεταβείτε στην σελίδα του <a href="https://niosekala.gr/developement/admin">Admin</a> για την επιβεβαίωση του Ραντεβού.</div></body>';

		if(!$mail = smtpmailerNew($message,"Niose Kala - Νεά αίτηση: Ραντεβού.")) {
			print "<br>Fail to send email.<br> ";
			print('<br>Παρακαλώ καλέστε μας στο τηλέφωνο: <a href="tel:6948266209">6948266209</a>, ώστε να ελέγξουμε το ραντεβού σας!<hr>');
			//echo "<script>setTimeout();</script>";
	
		}else{
			print "<br>".'<div class="jumbotron col col-6-narrower col-12-mobilep" style="color:green">'."Η αίτηση σας στάλθηκε με επιτυχία!<br> Σύντομα θα επικοινωνήσουμε μαζί σας για την επιβεβαίωση της Ημερομηνίας. </div><br>";
			//echo "<script>setTimeout();</script>";
		}
	}
?>


