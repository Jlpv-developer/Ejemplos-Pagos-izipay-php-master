<?php

/**
 * I initialize the PHP SDK
 */
require_once __DIR__ . '/rest-php-sdk-master/src/autoload.php';
require_once __DIR__ . '/keys.php';

/** 
 * Initialize the SDK 
 * see keys.php
 */
$client = new Lyra\Client();

/**
 * I create a formToken
 */
$store = array("amount" => 180, 
"currency" => "PEN", 
"orderId" => uniqid("MyOrderId"),
"customer" => array(
  "email" => "wilmer@yarkan.pe"
));
$response = $client->post("V4/Charge/CreatePayment", $store);

/* I check if there are some errors */
if ($response['status'] != 'SUCCESS') {
    /* an error occurs, I throw an exception */
    display_error($response);
    $error = $response['answer'];
    throw new Exception("error " . $error['errorCode'] . ": " . $error['errorMessage'] );
}

/* everything is fine, I extract the formToken */
$formToken = $response["answer"]["formToken"];

?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" 
   content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" /> 

  <!-- Javascript library. Should be loaded in head section -->
  <!--En la etiqueta kr-post-url-success Colocar el archivo de redireccion o URL (RECORDAR)-->
  <script 
   src="<?php echo $client->getClientEndpoint();?>/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js"
   kr-public-key="<?php echo $client->getPublicKey();?>"
   kr-post-url-success="respuesta.php">

  </script>

  <!-- theme and plugins. should be loaded after the javascript library -->
  <!-- not mandatory but helps to have a nice payment form out of the box -->
  <link rel="stylesheet" 
   href="<?php echo $client->getClientEndpoint();?>/static/js/krypton-client/V4.0/ext/classic-reset.css">
  <script 
   src="<?php echo $client->getClientEndpoint();?>/static/js/krypton-client/V4.0/ext/classic.js">
  </script>
</head>
<body style="padding-top:20px">
<button cambiar()>cambiar</button>
  <!-- payment form -->
  <div class="kr-embedded"
   kr-form-token="<?php echo $formToken;?>">

    <!-- payment form fields -->
    <div class="kr-pan"></div>
    <div class="kr-expiry"></div>
    <div class="kr-security-code"></div>  

    <!-- payment form submit button -->
    <button class="kr-payment-button"></button>

    <!-- error zone -->
    <div class="kr-form-error"></div>
  </div>  
</body>
</html>