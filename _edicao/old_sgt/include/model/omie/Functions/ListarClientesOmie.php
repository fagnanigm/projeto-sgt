<?php

include_once("../SoapClient/ClientesSoapClient.php");  
include_once("../SoapClient/OmieAppAuth.php"); // gere a sua chave em: http://developer.omie.com.br/generate-key/ 

include_once("../../../../app.php");


function ListarClientesOmie($Codigo = null, $CNPJ = null, $RazaoSocial = null) {

    try { 
        $sc = new ClientesCadastroSoapClient();

        $registros_por_pagina = 50;
        $total_paginas = 1;
        $listagem = array();

        // Adiciona o resultado da requisição no array
        for ($pagina = 1; $pagina <= $total_paginas; $pagina++) {

            //Define parametros da pesquisa
            $req = new clientes_list_request();
            $req->pagina = $pagina;
            $req->registros_por_pagina = $registros_por_pagina;

            //Define Filtros da pesquisa
            if (($Codigo != null) || ($RazaoSocial != null) || ($CNPJ != null)) {
                $req->clientesFiltro = new clientesFiltro();
            }
            if ($Codigo != null) {
                $req->clientesFiltro->codigo_cliente_omie = $Codigo;
            }
            if ($RazaoSocial != null) {
                $req->clientesFiltro->razao_social = $RazaoSocial;
            }
            if ($CNPJ != null) {
                $req->clientesFiltro->cnpj_cpf = $CNPJ;
            }

            //Faz a pesquisa
            $resultado = $sc->ListarClientes($req); 

            
            // Define a quantidade de paginas
            if ($pagina == 1) {
                $total_registros = $resultado->total_de_registros;
                if ($total_registros > $registros_por_pagina) {
                    $total_paginas = (int)($total_registros/$registros_por_pagina);
                    if ($total_paginas > ($total_registros/$registros_por_pagina)) {
                        $total_paginas--;
                    } else {
                        if ($total_paginas < ($total_registros/$registros_por_pagina)) {
                            $total_paginas++;
                        }
                    }
                }
            }

            for ($registro = 0; $registro < $registros_por_pagina; $registro++) {
                if (count($listagem) < $total_registros) {
                    $listagem[] = array(
                        'Codigo' => (isset($resultado->clientes_cadastro[$registro]->codigo_cliente_omie) ? $resultado->clientes_cadastro[$registro]->codigo_cliente_omie : ''),

                        'RazaoSocial' => (isset($resultado->clientes_cadastro[$registro]->razao_social) ? $resultado->clientes_cadastro[$registro]->razao_social : ''),

                        'NomeFantasia' => (isset($resultado->clientes_cadastro[$registro]->nome_fantasia) ? $resultado->clientes_cadastro[$registro]->nome_fantasia : ''),

                        'CNPJCPF' => (isset($resultado->clientes_cadastro[$registro]->cnpj_cpf) ? $resultado->clientes_cadastro[$registro]->cnpj_cpf : ''), 

                        'dddTelefone' => '',

                        'Telefone' => (isset($resultado->clientes_cadastro[$registro]->telefone1_numero) && $resultado->clientes_cadastro[$registro]->telefone1_numero != '0'  ? $resultado->clientes_cadastro[$registro]->telefone1_numero : ''),

                        'Endereco' => (isset($resultado->clientes_cadastro[$registro]->endereco) ? $resultado->clientes_cadastro[$registro]->endereco : '') ,

                        'NumeroEndereco' => (isset($resultado->clientes_cadastro[$registro]->endereco_numero) ? $resultado->clientes_cadastro[$registro]->endereco_numero : ''),  
                        'Codigo_Cidade' => (isset($resultado->clientes_cadastro[$registro]->cidade) ? $resultado->clientes_cadastro[$registro]->cidade : ''),
                        'Codigo_Pais' => (isset($resultado->clientes_cadastro[$registro]->codigo_pais) ? $resultado->clientes_cadastro[$registro]->codigo_pais : ''),
                        'CEP' => (isset($resultado->clientes_cadastro[$registro]->cep) ? $resultado->clientes_cadastro[$registro]->cep : ''),
                        'Inscricao_Estadual' => (isset($resultado->clientes_cadastro[$registro]->inscricao_estadual) ? $resultado->clientes_cadastro[$registro]->inscricao_estadual : ''),
                        'pessoa_fisica' => (isset($resultado->clientes_cadastro[$registro]->pessoa_fisica) ? $resultado->clientes_cadastro[$registro]->pessoa_fisica : '')
                    );
                }
            }

            
        }

        return $listagem;

    // Tratamento de erros
    } catch (SoapFault $e) {
        print "Ocorreu um erro no processamento: " . $e->faultstring . "\n";
        @print_r($e->detail);
    }
}


$list = ListarClientesOmie();

var_dump($list);


foreach ($list as $key => $value) {

    $query = "INSERT INTO SGT_Clientes_Omie (Codigo_CLiente, Razao_Social, Nome_Fantasia, Endereco, Codigo_Municipio_IBGE, CNPJ_CPF, Pais, CEP, Inscricao_Estadual, Telefone, Tipo) VALUES (
            '".$value['Codigo']."',
            '".$value['RazaoSocial']."',
            '".$value['NomeFantasia']."',
            '".$value['Endereco']. ','. $value['NumeroEndereco']."',
            '".$value['Codigo_Cidade']."',
            '".$value['CNPJCPF']."',
            '".$value['Codigo_Pais']."',
            '".$value['CEP']."',
            '".$value['Inscricao_Estadual']."',
            '".$value['Telefone']."',
            '".($value['pessoa_fisica'] == 'S' ? 'F' : 'J')."'); ";


    var_dump($query);

    //$ans = sqlsrv_query($connection, $query) or die( print_r( sqlsrv_errors(), true));   

}

?>