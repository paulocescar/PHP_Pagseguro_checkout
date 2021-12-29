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

    $sessao = get_post( 'sessao' , '' );
    $valor_receber = get_post( 'valor_receber' , '' );
    $cartao_numero = get_post( 'cartao_numero' , '' );
    $cartao_bandeira = get_post( 'cartao_bandeira' , '' );
    $cartao_codigo = get_post( 'cartao_codigo' , '' );
    $cartao_mes = get_post( 'cartao_mes' , '' );
    $cartao_ano = get_post( 'cartao_ano' , '' );

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://df.uol.com.br/v2/cards',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'sessionId='.$sessao.'&amount='.$valor_receber.'&cardNumber='.$cartao_numero.'&cardBrand='.$cartao_bandeira.'&cardCvv='.$cartao_codigo.'&cardExpirationMonth='.$cartao_mes.'&cardExpirationYear='.$cartao_ano,
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
        $data =  $error;
    } else {
        $data = $array;
    }

    $dados = array(
        "erro"=> count(isset($data['errors']) ? $data['errors'] : array()),
        "mensagem"=> "card_token",
        "registros"=> count($data),
        "dados" => array(
            $data
        )
    );

    $arr = json_encode($dados);
    echo $arr;
?>