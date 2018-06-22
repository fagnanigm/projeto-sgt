<?php  
  include_once("../SoapClient/CFOPSoapClient.php");  
  include_once("../SoapClient/OmieAppAuth.php"); // gere a sua chave em: http://developer.omie.com.br/generate-key/ 

  include_once("../../../../app.php");


  function Listar_Cadastros_CFOP_Omie(){

    try { 
        $sc = new CFOPSoapClient();

        $registros_por_pagina = 50;
        $total_paginas = 1;
        $Lista_CFOP = array();    
        $return = array();

        // Adiciona o resultado da requisição no array
        for ($pagina = 1; $pagina <= $total_paginas; $pagina++) {

            //Define Parametros da Pesquisa
            $req = new cfopListarRequest();  
            $req->pagina = $pagina;
            $req->registros_por_pagina = $registros_por_pagina;                 
            
            //Define Filtros da pesquisa
            /**//**//**//**//**//**//**//**//**/
            
            //Faz a pesquisa
            $listacfop = $sc->ListarCFOP($req); 
                  
            // Define a Quantidade de paginas
            if ($pagina == 1) {     
                $total_registros = $listacfop->total_de_registros;                     
                if ($total_registros > $registros_por_pagina){
                    $total_paginas = (int)($total_registros/$registros_por_pagina);                   
                    if ($total_paginas > ($total_registros/$registros_por_pagina)) {$total_paginas--;} else
                    if ($total_paginas < ($total_registros/$registros_por_pagina)) {$total_paginas++;}
                }
            }     
            
            for ($registro = 0; $registro < $registros_por_pagina; $registro++) { 
                                
                if (count($Lista_CFOP) < $total_registros){
                    $Lista_CFOP[] = array(
                        'Codigo' => $listacfop->cadastros[$registro]->nCodigo * 1000,
                        'Descricao' => $listacfop->cadastros[$registro]->cDescricao                
                    ); 

                    $return[] = $listacfop->cadastros[$registro];
                }                        
            }                          
        }

      return $Lista_CFOP;

    // Tratamento de erros 
    } catch (SoapFault $e) { 
      print "Ocorreu um erro no processamento: " . $e->faultstring . "\n"; 
      @print_r($e->detail); 
    }
}

$list = Listar_Cadastros_CFOP_Omie();

foreach ($list as $key => $value) {

	$descricao = $value['Descricao'];

	$query = "INSERT INTO SGT_CFOP_Omie (Codigo_CFOP, Descricao) VALUES (?,?); ";

	var_dump($query);

	//$ans = sqlsrv_query($connection, $query, array($value['Codigo'], $descricao)) or die( print_r( sqlsrv_errors(), true));   

}

?>