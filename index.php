<?php>

	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	date_default_timezone_set('EST');

 	 $apiKey = $_GET["key"];
  	 $POIID = $_GET["id"];
	
	if(empty($apiKey)){
		$apiKey = "AQEyhmfxJoLNaBFLw0m/n3Q5qf3VaY9UCJ1+XWZe9W27jmlZioLvYYVLKhuJyPlKTvOFrHEQwV1bDb7kfNy1WIxIIkxgBw==-9pXKdVWbfuOuW3dlgatuXUUrTUg1meQ7br3Qag4y4aA=-t6JtL9kqWkP4I6UC";
	}

	if(empty($POIID)){
		$POIID= "P400Plus-275040713";  //P400Plus-275040713  V400m-324687828
	}
	
	$cloudURL = "https://terminal-api-test.adyen.com/sync";
	//$localIP = 'http://192.168.14.145:8080/nexo/';	
	
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
    $ip = $_SERVER['REMOTE_ADDR'];
	}

	$TransactionID = "TID-" . date("Y-m-d-H:i:s").'--'.$ip;  
	
	$length = 5;
	$randomString = substr(str_shuffle(md5(time())),0,$length);

	$ServiceID = date("dm").$randomString;  
	$SaleID = "TerminalAPI-Ray";
	$TimeStamp = date("Y-m-d\TH:i:s");


	$paymentAmount = 10;   
	$currencyCode = "USD";
	
	$TransactionConditions = "";
	$SaleToAcquirerData = "";

	
?>

<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script>
		function configExpand() {    
		   	$('div.wrapper').find('div').toggleClass('minimized expanded');   
		}
	</script>
</head>	

<form action="./handler.php" method="post" >
	
	<input type="submit" name="submit" value="Pay" class="nicebutton" />
	
	<input type="hidden" name="cloudURL" value="<?= $cloudURL ?>"/>
	<input type="hidden" name="TimeStamp" value="<?= $TimeStamp ?>"/>	

	<br/><br/>
	<label>payment Amount</label>
	<input type="text" name="paymentAmount" value="<?= $paymentAmount ?>"/><br/><br/>

	<label>currency Code</label>
	<input type="text" name="currencyCode" value="<?= $currencyCode ?>"/><br/>
	<br/>


<lable class="niceconfig" onClick="configExpand()"> configs</lable>
<div class="wrapper">
<div class="minimized">	

	<br/>
	<label>API Key</label>
	<input type="text" name="apiKey" value = "<?= $apiKey ?>"/><br/>
	<label>POIID</label>
	<input type="text" name="POIID" value="<?= $POIID ?>"/><br/>

	<br/>
	<label>TransactionID</label>
	<input type="text" name="TransactionID" value="<?= $TransactionID ?>"/><br/>
	<label>ServiceID</label>
	<input type="text" name="ServiceID" value="<?= $ServiceID ?>"/><br/>
	<label>SaleID</label>
	<input type="text" name="SaleID" value="<?= $SaleID ?>"/><br/>



	<label>TransactionConditions</label>
	<input type="text" name="TransactionConditions" value="<?= $TransactionConditions ?>"/><br/>
	<br/>
	<div style=" font-size:  10;padding-left:  16px;">
	options:<br/>
	"ForceEntryMode": ["Keyed"]
	</div>
	<br/>

	<label>SaleToAcquirerData</label>
	<input type="text" name="SaleToAcquirerData" value="<?= $SaleToAcquirerData ?>"/>
	<br/>
	<div style="font-size:  10;padding-left:  16px;">
	options:(separate by &)<br/>
	tenderOption=BypassPin<br/>
    tenderOption=AllowPartialAuthorisation<br/>
	tenderOption=AskGratuity<br/>
	tenderOption=ForcedDecline<br/>
	tenderOption=ReceiptHandler<br/>
	shopperEmail=hola@gmail.com<br/>
	shopperReference=fakeRef<br/>
	recurringContract=RECURRING
	</div>
	<br/>
	
	</div>

	<br/>
	<!--
	<label>shopperReference</label>
	<input type="text" name="shopperReference" value="<?= $shopperReference ?>"/><br/>
	<label>recurringContract</label>
	<input type="text" name="recurringContract" value="<?= $recurringContract ?>"/><br/>
	-->
</div>



</form>

</html>
