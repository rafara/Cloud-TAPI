<html>
<link rel="stylesheet" href="style.css" type="text/css" />
<form action="./index.php" method="get">
  <input type='submit' value='Back' class="nicebutton"/>
</form>
<?php
 
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");

  $apiKey = $_POST["apiKey"];
  $cloudURL = $_POST["cloudURL"];

  $TransactionID = $_POST["TransactionID"]; 
  $ServiceID = $_POST["ServiceID"];
  $SaleID = $_POST["SaleID"];

  $POIID = $_POST["POIID"];
  $TimeStamp = $_POST["TimeStamp"];


  $paymentAmount = $_POST["paymentAmount"];
  $currencyCode = $_POST["currencyCode"];
  
  $TransactionConditions = $_POST["TransactionConditions"];
  $SaleToAcquirerData = $_POST["SaleToAcquirerData"];

  
  $ch = curl_init();
  $url = $cloudURL;


  curl_setopt($ch, CURLOPT_URL, $url);
  
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_VERBOSE, 1);
  curl_setopt($ch, CURLOPT_HEADER, 1);

  $key = 'x-api-key:'.$apiKey;
  curl_setopt($ch, CURLOPT_HTTPHEADER, array($key,"Content-Type:application/json")); 

  
$Data = '{
  "SaleToPOIRequest": {
      "MessageHeader": {
        "ProtocolVersion":"3.0",
        "MessageClass":"Service",
        "MessageCategory":"Payment",
        "MessageType":"Request",
        "ServiceID":"'.$ServiceID.'",
        "SaleID":"'.$SaleID.'",
        "POIID":"'.$POIID.'"
      },
      "PaymentRequest": {
        "SaleData": {
          "SaleTransactionID": {
            "TransactionID": "'.$TransactionID.'",
            "TimeStamp": "'.$TimeStamp.'" 
          },
          "SaleReferenceID": "'.$TransactionID.'",
            "SaleToAcquirerData": "'.$SaleToAcquirerData.'"
        },
        "PaymentTransaction": {
          "AmountsReq": {
            "Currency": "'.$currencyCode.'",
            "RequestedAmount": '.$paymentAmount.'
          },
          "TransactionConditions": {'.$TransactionConditions.' }
        }
      }
    }
  }';



    curl_setopt($ch, CURLOPT_POSTFIELDS ,$Data);
    $response = curl_exec($ch);

    //status
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE); 

    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);


    ////////////////////////////////////////////////////////////////
    //PRINTING ON PAGE:

    $Response = 'Payment Failed';
    if(strpos($body, 'Success'))
      $Response = 'Payment Approved';


    echo "<br/>";

    echo ' <div class="Response"><div class="ResponseIcon">&#10140;</div>';
    echo $Response;
    echo "</div>";

    echo '<div class="codeSnippetHeader"> <pre><code>';
    echo $header;
    echo "</code></pre></div>";
  
    echo '<div class="codeSnippet"> <pre><code>';
    echo _format_json($body,true);
    echo "</code></pre></div>";
    ////////////////////////////////////////////////////////////////


   // close cURL resource, and free up system resources
    curl_multi_close($ch);


    function _format_json($json, $html = false) {
      $tabcount = 0; 
      $result = ''; 
      $inquote = false; 
      $ignorenext = false; 

    if ($html) { 
        $tab = "&nbsp;&nbsp;&nbsp;"; 
        $newline = "<br/>"; 
    } else { 
        $tab = "\t"; 
        $newline = "\n"; 
    } 

    for($i = 0; $i < strlen($json); $i++) { 
        $char = $json[$i]; 

        if ($ignorenext) { 
            $result .= $char; 
            $ignorenext = false; 
        } else { 
            switch($char) { 
                case '{': 
                    $tabcount++; 
                    $result .= $char . $newline . str_repeat($tab, $tabcount); 
                    break; 
                case '}': 
                    $tabcount--; 
                    $result = trim($result) . $newline . str_repeat($tab, $tabcount) . $char; 
                    break; 
                case ',': 
                    $result .= $char . $newline . str_repeat($tab, $tabcount); 
                    break; 
                case '"': 
                    $inquote = !$inquote; 
                    $result .= $char; 
                    break; 
                case '\\': 
                    if ($inquote) $ignorenext = true; 
                    $result .= $char; 
                    break; 
                default: 
                    $result .= $char; 
            } 
        } 
    } 

    return $result; 
  }

?>
</html>