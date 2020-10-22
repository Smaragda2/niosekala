 

<?php
	$host = "https://demo.vivapayments.com/api/orders";
	$encAuth = "NDA2MTY2ZWEtNmZhNy00ZTcwLTg1MmItMzYyNGY5ZWY1YzA2OnckRHs0Lw==";
	$sourceCode = "6726";
	
	$return = "";
    
    $price = $_REQUEST['price']*100;
    $productName = $_REQUEST['productName'];
    
	$data = array(
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
	
	echo "https://demo.vivapayments.com/web/checkout?ref=".$orderCode;
?>

