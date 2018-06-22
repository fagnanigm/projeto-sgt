<?php
/*
include_once("../../../../app.php");

function get_produtos($page = 1){

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://app.omie.com.br/api/v1/geral/produtos/",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\"call\":\"ListarProdutosResumido\",\"app_key\":\"7722651396\",\"app_secret\":\"dc643a3cf2fcde9c3b7c6e561bfb3b9f\",\"param\":[{\"pagina\":".$page.",\"registros_por_pagina\":50,\"apenas_importado_api\":\"N\",\"filtrar_apenas_omiepdv\":\"N\"}]}",
      CURLOPT_HTTPHEADER => array(
        "Cache-Control: no-cache",
        "Content-Type: application/json",
        "Postman-Token: 98e4b9bd-dc86-46b6-b269-8d566c529f40"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      $response = json_decode($response);
      return $response;
    }

}

$page = 1;

$produtos = array();

$response = get_produtos($page);

$produtos = array_merge($produtos,$response->produto_servico_resumido);

for ($i=2; $i <= $response->total_de_paginas; $i++) { 
    
    $response = get_produtos($i);

    $produtos = array_merge($produtos,$response->produto_servico_resumido);

}

foreach ($produtos as $key => $value) {

    $query = "INSERT INTO sgt_produtos (codigo, codigo_produto, descricao, valor_unitario) VALUES (
            '".$value->codigo."',
            '".$value->codigo_produto."',
            '".$value->descricao."',
            '".$value->valor_unitario."'); ";


    var_dump($query);

    //$ans = sqlsrv_query($connection, $query) or die( print_r( sqlsrv_errors(), true));   

}


*/
?>