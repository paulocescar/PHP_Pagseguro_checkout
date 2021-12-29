<?php

  function get_post( $str , $default ){
      $ret = $default;
      if ( isset($_GET[$str]) ){
          $ret = $_GET[$str];
      } else {
          if ( isset($_POST[$str]) ){
              $ret = $_POST[$str];
          }
      }
      return $ret;
  }

  $currency = get_post( 'currency' , '' );
  $extraAmount = get_post( 'extraAmount' , '' );
  $itemId = get_post( 'itemId' , '' );
  $itemDescription = get_post( 'itemDescription' , '' );
  $itemAmount = get_post( 'itemAmount' , '' );
  $itemQuantity = get_post( 'itemQuantity' , '' );
  $notificationURL = get_post( 'notificationURL' , '' );
  $reference = get_post( 'reference' , '' );
  $shippingAddressRequired = get_post( 'shippingAddressRequired' , '' );
  $creditCardToken = get_post( 'creditCardToken' , '' );
  $installmentQuantity = get_post( 'installmentQuantity' , '' );
  $installmentValue = get_post( 'installmentValue' , '' );
  $noInterestInstallmentQuantity = get_post( 'noInterestInstallmentQuantity' , '' );
  $creditCardHolderName = get_post( 'creditCardHolderName' , '' );
  $creditCardHolderCPF = get_post( 'creditCardHolderCPF' , '' );
  $creditCardHolderBirthDate = get_post( 'creditCardHolderBirthDate' , '' );
  $creditCardHolderAreaCode = get_post( 'creditCardHolderAreaCode' , '' );
  $creditCardHolderPhone = get_post( 'creditCardHolderPhone' , '' );
  $billingAddressStreet = get_post( 'billingAddressStreet' , '' );
  $billingAddressNumber = get_post( 'billingAddressNumber' , '' );
  $billingAddressComplement = get_post( 'billingAddressComplement' , '' );
  $billingAddressDistrict = get_post( 'billingAddressDistrict' , '' );
  $billingAddressPostalCode = get_post( 'billingAddressPostalCode' , '' );
  $billingAddressCity = get_post( 'billingAddressCity' , '' );
  $billingAddressState = get_post( 'billingAddressState' , '' );
  $billingAddressCountry = get_post( 'billingAddressCountry' , '' );
  $senderName = get_post( 'senderName' , '' );
  $senderCPF = get_post( 'senderCPF' , '' );
  $senderAreaCode = get_post( 'senderAreaCode' , '' );
  $senderPhone = get_post( 'senderPhone' , '' );
  $senderEmail = get_post( 'senderEmail' , '' );

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://ws.sandbox.pagseguro.uol.com.br/v2/transactions?email=alefrodrigues538@gmail.com&token=F397326FBA1D47E19F563E7D412E0A98',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'paymentMode=default&paymentMethod=creditCard&currency='.$currency.'&extraAmount='.$extraAmount.'&itemId1='.$itemId.'&itemDescription1='.$itemDescription.'&itemAmount1='.$itemAmount.'&itemQuantity1='.$itemQuantity.'&notificationURL='.$notificationURL.'&reference='.$reference.'&senderName='.$senderName.'&senderCPF='.$senderCPF.'&senderAreaCode='.$senderAreaCode.'&senderPhone='.$senderPhone.'&senderEmail='.$senderEmail.'&shippingAddressRequired='.$shippingAddressRequired.'&creditCardToken='.$creditCardToken.'&installmentQuantity='.$installmentQuantity.'&installmentValue='.$installmentValue.'&noInterestInstallmentQuantity='.$noInterestInstallmentQuantity.'&creditCardHolderName='.$creditCardHolderName.'&creditCardHolderCPF='.$creditCardHolderCPF.'&creditCardHolderBirthDate='.$creditCardHolderBirthDate.'&creditCardHolderAreaCode='.$creditCardHolderAreaCode.'&creditCardHolderPhone='.$creditCardHolderPhone.'&billingAddressStreet='.$billingAddressStreet.'&billingAddressNumber='.$billingAddressNumber.'&billingAddressComplement='.$billingAddressComplement.'&billingAddressDistrict='.$billingAddressDistrict.'&billingAddressPostalCode='.$billingAddressPostalCode.'&billingAddressCity='.$billingAddressCity.'&billingAddressState='.$billingAddressState.'&billingAddressCountry='.$billingAddressCountry,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/x-www-form-urlencoded'
    ),
  ));

  $response = curl_exec($curl);
  $error = curl_error($curl);
  $curl_errno = curl_errno($curl);
  
  curl_close($curl);

  function XML2Array(SimpleXMLElement $parent)
  {
      $array = array();

      foreach ($parent as $name => $element) {
          ($node = & $array[$name])
              && (1 === count($node) ? $node = array($node) : 1)
              && $node = & $node[];

          $node = $element->count() ? XML2Array($element) : trim($element);
      }

      return $array;
  }

  $xml   = simplexml_load_string($response);
  $array = XML2Array($xml);
  $array = array($xml->getName() => $array);

  if ($curl_errno > 0) {
    $data = $error;
  } else {
    $data = $array;
  }

  $dados = array(
    "erro"=> count(isset($data['errors']) ? $data['errors'] : array()),
    "mensagem"=> "cartao_credito",
    "registros"=> count($data),
    "dados" => array(
      $data,
    )
  );

  $arr = json_encode($dados);
  echo $arr;