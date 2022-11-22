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
$store = array("amount" => 250, 
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

  <style>
  
  .kr-popin-modal-header-background-image{
    background-color: #80D2CE!important;
  }

  .kr-popin-modal-footer{
    //display: none;
    background-color: #80D2CE!important;
  }

  .kr-logo-mcw{
    display: none;
  }

  .kr-header-logo{
    background-color: #80D2CE!important;
  }

  .kr-payment-button{
    background-color: #80D2CE!important;
  }

  .kr-popin-button{
    background-color: #80D2CE!important;
  }

  .kr-embedded{
    background-color: #E64442!important;
  }

  </style>  

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
   href="<?php echo $client->getClientEndpoint();?>/static/js/krypton-client/V4.0/ext/classic.css">
  <script 
   src="<?php echo $client->getClientEndpoint();?>/static/js/krypton-client/V4.0/ext/classic.js">
  </script>
</head>
<script>
    
    var elem = document.createElement('div');
    elem.setAttribute('class', 'kr-embeded');
    //elem.setAttribute('kr-popin', '');
    elem.setAttribute('kr-form-token', "<?php echo $formToken;?>");

    div1 = document.createElement('div');
    div1.setAttribute('class', "kr-pan");
    elem.appendChild(div1);
    div2 = document.createElement('div');
    div2.setAttribute('class', "kr-expiry");
    elem.appendChild(div2);
    div3 = document.createElement('div');
    div3.setAttribute('class', "kr-security-code");
    elem.appendChild(div3);
    div4 = document.createElement('button');
    div4.setAttribute('class', "kr-payment-button");
    elem.appendChild(div4);
    div5 = document.createElement('div');
    div5.setAttribute('class', "kr-form-error");
    elem.appendChild(div5);

    document.body.appendChild(elem);

    KR.attachForm('.kr-embeded')
    // KR.setFormConfig({
    //     'kr-post-url-success': 'https://my.site'
    // })

</script>
<body>
<button class="kr-payment-button"></button>
    </body>