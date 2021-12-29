<?php

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://ws.sandbox.pagseguro.uol.com.br/v2/sessions?email=paulo.cr93@gmail.com&token=34549F9C75F64B64BC550DF390F59AB1',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
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
        "mensagem"=> "session_id",
        "registros"=> count($data),
        "dados" => array(
            $data
        )
    );

    $arr = json_encode($dados);
    echo $arr;

?>