<?php
  Error_reporting(E_ALL);
  InserirCTE();

  function InserirCTE()
  {
    include_once("/Conecta_DB.php");
    Include_once("../Classes/Classe_CTE.php"); 
    Include_once("../Classes/Classe_CTE_Carga.php"); 
    Include_once("../Classes/Classe_CTE_Documento_Transporte_Anterior.php"); 
    Include_once("../Classes/Classe_CTE_Documento_Transporte_Anterior_Eletronico.php"); 
    Include_once("../Classes/Classe_CTE_Documento_Transporte_Anterior_Papel.php"); 
    Include_once("../Classes/Classe_CTE_Documento.php"); 
    Include_once("../Classes/Classe_CTE_Motorista.php"); 
    Include_once("../Classes/Classe_CTE_Prestacao.php"); 
    Include_once("../Classes/Classe_CTE_Seguro.php"); 
    Include_once("../Classes/Classe_CTE_Veiculo.php"); 

    /* Fazendo Conexão com o banco */
    $connection = ConnectDB();    

    /* Pegando Codigo da CTE */
    $QueryCodigoCTE = 'select coalesce(max(Codigo_CTE) + 1, 1) as Codigo_CTE from SGT_Conhecimento_Transporte_Eletronico;';
    $ansCodigo = sqlsrv_query($connection, $QueryCodigoCTE) or die( print_r( sqlsrv_errors(), true));

    while($row = sqlsrv_fetch_array($ansCodigo, SQLSRV_FETCH_ASSOC)){
      $Codigo_CTE = $row['Codigo_CTE'];
    }    
    
    /* ---------------------------------------------------------- */
    /*                                                            */
    /*              Adicionando Valores as Classes                */
    /*                                                            */
    /* ---------------------------------------------------------- */
   
    /* Classe CTE */
    $Codigo_Empresa = 99999;
    for ($i = 0; $i == 0; $i++){

      $CTE = new Conhecimento_Transporte();

      $CTE->setCodigo_Empresa                                           ($Codigo_Empresa);
      $CTE->setCodigo_Estabelecimento                                   (1);
      $CTE->setCodigo_CTE                                               ($Codigo_CTE);
      $CTE->setGeral_Tipo_CTE                                           ($_POST["Geral_Tipo_CTE"]);
      $CTE->setGeral_Data_Emissao                                       ($_POST["Geral_Data_Emissao_Data"] . " " . $_POST["Geral_Data_Emissao_Hora"]);
      $CTE->setGeral_Tipo_Servico                                       ($_POST["Geral_Tipo_Servico"]);
      $CTE->setGeral_CFOP                                               ($_POST["Geral_CFOP"]);
      $CTE->setGeral_Codigo_Natureza                                    ($_POST["Geral_Natureza"]);
      $CTE->setGeral_Cidade_Origem_Codigo_IBGE                          ($_POST["Geral_Cidade_Origem"]);
      $CTE->setGeral_Cidade_Destino_Codigo_IBGE                         ($_POST["Geral_Cidade_Destino"]);
      $CTE->setGeral_Codigo_Remetente                                   ($_POST["Geral_Codigo_Remetente"]);
      $CTE->setGeral_Codigo_Destinatario                                ($_POST["Geral_Codigo_Destinatario"]);
      $CTE->setGeral_Codigo_Tomador                                     ($_POST["Geral_Codigo_Tomador"]);
      $CTE->setCarga_Valor                                              ($_POST["Carga_Valor"]);
      $CTE->setCarga_Produto_Predominante                               ($_POST["Carga_Produto_Predominante"]);
      $CTE->setCarga_Outras_Caracteristicas                             ($_POST["Carga_Outra_Caracteristicas"]);
      $CTE->setRodoviario_RNTRC                                         ($_POST["Rodoviario_RNTRC"]);
      $CTE->setCobranca_Servico_Valor_Total_Servico                     ($_POST["Cobranca_Servico_Valor_Total_Servico"]);
      $CTE->setCobranca_Servico_Valor_Receber                           ($_POST["Cobranca_Servico_Valor_Receber"]);
      $CTE->setCobranca_Servico_Forma_Pagamento                         ($_POST["Cobranca_Forma_Pagamento"]);
      $CTE->setCobranca_Servico_Valor_Aproximado_Tributos               ($_POST["Cobranca_Servico_Valor_Aproximado_Tributos"]);
      $CTE->setCobranca_ICMS_CST                                        ($_POST["Cobranca_ICMS_CST"]);
      $CTE->setCobranca_ICMS_Base                                       ($_POST["Cobranca_ICMS_Base"]);
      $CTE->setCobranca_ICMS_Aliquota                                   ($_POST["Cobranca_ICMS_Aliquota"]);
      $CTE->setCobranca_ICMS_Valor                                      ($_POST["Cobranca_ICMS_Valor"]);
      $CTE->setCobranca_ICMS_Percentual_Reducao_Base_Calculo            ($_POST["Cobranca_ICMS_Percentual_Reducao_Base_Calculo_ICMS"]);
      $CTE->setCobranca_ICMS_Credito                                    ($_POST["Cobranca_ICMS_Credito_ICMS"]);
      $CTE->setCobranca_Partilha_ICMS_Valor_Base_Calculo                ($_POST["Cobranca_Partilha_ICMS_Valor_Base_Calculo"]);
      $CTE->setCobranca_Partilha_ICMS_Aliquota_Interna_UF_Termino       ($_POST["Cobranca_Partilha_ICMS_Aliquota_Interna_UF_Termino"]);
      $CTE->setCobranca_Partilha_ICMS_Aliquota_Interestadual            ($_POST["Cobranca_Partilha_ICMS_Aliquota_Interestadual"]);
      $CTE->setCobranca_Partilha_ICMS_Porcentagem_Partilha_UF_Termino   ($_POST["Cobranca_Partilha_ICMS_Porcentagem_Partilha_UF_Termino"]);
      $CTE->setCobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Inicio     ($_POST["Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Inicio"]);
      $CTE->setCobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Termino    ($_POST["Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Termino"]);
      $CTE->setCobranca_Partilha_ICMS_Porcentagem_ICMS_FCP_UF_Termino   ($_POST["Cobranca_Partilha_ICMS_Porcentagem_ICMS_FCP_UF_Termino"]);
      $CTE->setCobranca_Partilha_ICMS_Valor_ICMS_FCP_UF_Termino         ($_POST["Cobranca_Partilha_ICMS_Valor_ICMS_FCP_UF_Termino"]);
      $CTE->setCobranca_Observacoes_Gerais                              ($_POST["Cobranca_Observacoes_Gerais"]);
      $CTE->setCobranca_Entrega_Prevista                                ($_POST["Cobranca_Entrega_Prevista"]);
      $CTE->setFatura_Numero                                            ($_POST["Fatura_Numero"]);
      $CTE->setFatura_Valor_Origem                                      ($_POST["Fatura_Valor_Origem"]);
      $CTE->setFatura_Valor_Desconto                                    ($_POST["Fatura_Valor_Desconto"]);
      $CTE->setFatura_Valor                                             ($_POST["Fatura_Valor"]);

    }

    /* Classe CTE_Carga */
    $Quantidade_Cargas = ($_POST["Carga_Quantidade_Cargas"]);
    $Contador_Cargas = 0;
    for ($i = 0; $i < $Quantidade_Cargas; $i++){
      if ($_POST["Codigo_Unidade_Medida".$i] != ''){
        $CTE_Carga[$Contador_Cargas] = new CTE_Carga(); 
      
        $CTE_Carga[$Contador_Cargas]->setCodigo_CTE               ($Codigo_CTE);
        $CTE_Carga[$Contador_Cargas]->setCodigo_Carga             ($Contador_Cargas);
        $CTE_Carga[$Contador_Cargas]->setCodigo_Unidade_Medida    ($_POST["Codigo_Unidade_Medida".$i]);
        $CTE_Carga[$Contador_Cargas]->setTipo_Medida              ($_POST["Tipo_Medida".$i]);
        $CTE_Carga[$Contador_Cargas]->setQuantidade_Carga         ($_POST["Quantidade_Carga".$i]);

        $Contador_Cargas++;
      }     
    }

     /* Classe CTE_Documentos */
    $Quantidade_Documentos = ($_POST["Documento_Quantidade_Documento"]);
    $Contador_Documentos = 0;
    for ($i = 0; $i < $Quantidade_Documentos; $i++){
      if (($_POST["Nota_Fiscal_Serie".$i] != '')
      || ($_POST["Nota_Fiscal_Eletronica_Chave_Acesso".$i] != '')
      || ($_POST["Outros_Documentos_Documento_Origem".$i] != '')){

        $CTE_Documento[$Contador_Documentos] = new CTE_Documento(); 

        $CTE_Documento[$Contador_Documentos]->setCodigo_CTE                             ($Codigo_CTE);
        $CTE_Documento[$Contador_Documentos]->setCodigo_Documento                       ($Contador_Documentos);
        $CTE_Documento[$Contador_Documentos]->setTipo_Documento                         ($_POST["Documento_Tipo_Documento"]);

        $CTE_Documento[$Contador_Documentos]->setNota_Fiscal_Serie                      ($_POST["Nota_Fiscal_Serie".$i]);
        $CTE_Documento[$Contador_Documentos]->setNota_Fiscal_Numero                     ($_POST["Nota_Fiscal_Numero".$i]);
        $CTE_Documento[$Contador_Documentos]->setNota_Fiscal_Data_Emissao               ($_POST["Nota_Fiscal_DataEmissao".$i]);
        $CTE_Documento[$Contador_Documentos]->setNota_Fiscal_CFOP                       ($_POST["Nota_Fiscal_CFOP".$i]);
        $CTE_Documento[$Contador_Documentos]->setNota_Fiscal_B_C_ICMS                   ($_POST["Nota_Fiscal_B_C_ICMS".$i]);
        $CTE_Documento[$Contador_Documentos]->setNota_Fiscal_B_C_ICMS_ST                ($_POST["Nota_Fiscal_B_C_ICMS_ST".$i]);
        $CTE_Documento[$Contador_Documentos]->setNota_Fiscal_Valor_ICMS                 ($_POST["Nota_Fiscal_Valor_ICMS".$i]);
        $CTE_Documento[$Contador_Documentos]->setNota_Fiscal_Valor_ICMS_ST              ($_POST["Nota_Fiscal_Valor_ICMS_ST".$i]);
        $CTE_Documento[$Contador_Documentos]->setNota_Fiscal_Valor_Produtos             ($_POST["Valor_Produtos".$i]);
        $CTE_Documento[$Contador_Documentos]->setNota_Fiscal_Valor_Nota                 ($_POST["Valor_Nota".$i]);

        $CTE_Documento[$Contador_Documentos]->setNota_Fiscal_Eletronica_Chave_Acesso    ($_POST["Nota_Fiscal_Eletronica_Chave_Acesso".$i]);

        $CTE_Documento[$Contador_Documentos]->setOutros_Documentos_Data_Emissao         ($_POST["Outros_Documentos_Data_Emissao".$i]);
        $CTE_Documento[$Contador_Documentos]->setOutros_Documentos_Documento_Origem     ($_POST["Outros_Documentos_Documento_Origem".$i]);
        $CTE_Documento[$Contador_Documentos]->setOutros_Documentos_Descricao            ($_POST["Outros_Documentos_Descricao".$i]);
        $CTE_Documento[$Contador_Documentos]->setOutros_Documentos_Valor                ($_POST["Outros_Documentos_Valor".$i]);
      
        $Contador_Documentos++;
      }
    }

    /* Classe CTE_Documentos_Transporte_Anterior */
    $Quantidade_Documentos_Transporte_Anterior = ($_POST["DocumentoTransposrteAnterior_Quantidade_Documento"]);
    $Contador_Documentos_Transporte_Anterior = 0;
    for ($i = 0; $i <= $Quantidade_Documentos_Transporte_Anterior; $i++){
      if ($_POST["Documento_Transporte_Anterior_CPF_CNPJ".$i] != ''){

      $CTE_Documento_Transporte_Anterior[$Contador_Documentos_Transporte_Anterior] = new CTE_Documento_Transporte_Anterior(); 
      
      $CTE_Documento_Transporte_Anterior[$Contador_Documentos_Transporte_Anterior]->setCodigo_CTE                             ($Codigo_CTE);
      $CTE_Documento_Transporte_Anterior[$Contador_Documentos_Transporte_Anterior]->setCodigo_Documento_Transporte_Anterior   ($Contador_Documentos_Transporte_Anterior);
      $CTE_Documento_Transporte_Anterior[$Contador_Documentos_Transporte_Anterior]->setRazao_Social                           ($_POST["Documento_Transporte_Anterior_Razao_Social".$i]);
      $CTE_Documento_Transporte_Anterior[$Contador_Documentos_Transporte_Anterior]->setCPF_CNPJ                               ($_POST["Documento_Transporte_Anterior_CPF_CNPJ".$i]);
      $CTE_Documento_Transporte_Anterior[$Contador_Documentos_Transporte_Anterior]->setInscricao_Estadual                     ($_POST["Documento_Transporte_Anterior_Inscricao_Estadual".$i]);
      $CTE_Documento_Transporte_Anterior[$Contador_Documentos_Transporte_Anterior]->setUF                                     ($_POST["Documento_Transporte_Anterior_UF".$i]);
      
      $Contador_Documentos_Transporte_Anterior++;
      } 
    } 

    /* Classe CTE_Documentos_Transporte_Anterior _Papel e _Eletronico */
    $Quantidade_Documentos_Transporte_Anterior = ($_POST["DocumentoTransposrteAnterior_Quantidade_Documento"]);
    $Quantidade_Documentos_Transporte_Anterior_Eletronico = ($_POST["DocumentoTransposrteAnterior_Quantidade_Documento_Eletronico"]);
    $Quantidade_Documentos_Transporte_Anterior_Papel = ($_POST["DocumentoTransposrteAnterior_Quantidade_Documento_Papel"]);
    $Contador_Documentos_Transporte_Anterior = 0;
    $Contador_Doc_Eletronico_Total = 0;
    $Contador_Doc_Papel_Total = 0;
    for ($i = 0; $i <= $Quantidade_Documentos_Transporte_Anterior; $i++){
      if ($_POST["Documento_Transporte_Anterior_CPF_CNPJ".$i] != ''){
        $Contador_Doc_Eletronico = 0;
        $Contador_Doc_Papel = 0;

        /* CTE_Documentos_Transporte_Anterior_Eletronico */
        for ($j = 0; $j < $Quantidade_Documentos_Transporte_Anterior_Eletronico; $j++){
          if ($_POST["Documento_Transporte_Anterior_Eletronico_Chave".$i."_".$j] != ''){
                  
            $CTE_Documento_Transporte_Anterior_Eletronico[$Contador_Doc_Eletronico_Total] = new CTE_Documento_Transporte_Anterior_Eletronico(); 

            $CTE_Documento_Transporte_Anterior_Eletronico[$Contador_Doc_Eletronico_Total]->setCodigo_CTE                                       ($Codigo_CTE);
            $CTE_Documento_Transporte_Anterior_Eletronico[$Contador_Doc_Eletronico_Total]->setCodigo_Documento_Transporte_Anterior             ($Contador_Documentos_Transporte_Anterior);
            $CTE_Documento_Transporte_Anterior_Eletronico[$Contador_Doc_Eletronico_Total]->setCodigo_Documento_Transporte_Anterior_Eletronico  ($Contador_Doc_Eletronico);
            $CTE_Documento_Transporte_Anterior_Eletronico[$Contador_Doc_Eletronico_Total]->setChave_Acesso                                     ($_POST["Documento_Transporte_Anterior_Eletronico_Chave".$i."_".$j]);
      
            $Contador_Doc_Eletronico_Total++;
            $Contador_Doc_Eletronico++;      
          }
        }      
        
        /* Classe Documentos de Transporte Anterior Papel */
        for ($j = 0; $j < $Quantidade_Documentos_Transporte_Anterior_Papel; $j++){
          if ($_POST["Documento_Transporte_Anterior_Papel_Tipo".$i."_".$j] != ''){
                      
            $CTE_Documento_Transporte_Anterior_Papel[$Contador_Doc_Papel_Total] = new CTE_Documento_Transporte_Anterior_Papel();

            $CTE_Documento_Transporte_Anterior_Papel[$Contador_Doc_Papel_Total]->setCodigo_CTE                                   ($Codigo_CTE);
            $CTE_Documento_Transporte_Anterior_Papel[$Contador_Doc_Papel_Total]->setCodigo_Documento_Transporte_Anterior         ($Contador_Documentos_Transporte_Anterior);
            $CTE_Documento_Transporte_Anterior_Papel[$Contador_Doc_Papel_Total]->setCodigo_Documento_Transporte_Anterior_Papel   ($Contador_Doc_Papel);
            $CTE_Documento_Transporte_Anterior_Papel[$Contador_Doc_Papel_Total]->setTipo                                         ($_POST["Documento_Transporte_Anterior_Papel_Tipo".$i."_".$j]);
            $CTE_Documento_Transporte_Anterior_Papel[$Contador_Doc_Papel_Total]->setSerie                                        ($_POST["Documento_Transporte_Anterior_Papel_Serie".$i."_".$j]);
            $CTE_Documento_Transporte_Anterior_Papel[$Contador_Doc_Papel_Total]->setSub_Serie                                    ($_POST["Documento_Transporte_Anterior_Papel_SubSerie".$i."_".$j]);
            $CTE_Documento_Transporte_Anterior_Papel[$Contador_Doc_Papel_Total]->setNumero                                       ($_POST["Documento_Transporte_Anterior_Papel_Numero".$i."_".$j]);
            $CTE_Documento_Transporte_Anterior_Papel[$Contador_Doc_Papel_Total]->setData_Emissao                                 ($_POST["Documento_Transporte_Anterior_Papel_Data_Emissao".$i."_".$j]);

            $Contador_Doc_Papel_Total++;
            $Contador_Doc_Papel++;      
          }
        }

        $Contador_Documentos_Transporte_Anterior++;
      } 
    } 

    /* Classe CTE_Veiculos */
    $Quantidade_Veiculos = ($_POST["Rodoviario_Quantidade_Veiculo"]);
    $Contador_Veiculos = 0;
    for ($i = 1; $i <= $Quantidade_Veiculos; $i++){
      if ($_POST["Rodoviario_Codigo_Veiculo".$i] != ''){
        $CTE_Veiculo[$Contador_Veiculos] = new CTE_Veiculo();
      
        $CTE_Veiculo[$Contador_Veiculos]->setCodigo_CTE               ($Codigo_CTE);
        $CTE_Veiculo[$Contador_Veiculos]->setCodigo_Veiculo           ($_POST["Rodoviario_Codigo_Veiculo".$i]);

        $Contador_Veiculos++;
      }      
    }

    /* Classe CTE_Motoristas */
    $Quantidade_Motoristas = ($_POST["Rodoviario_Quantidade_Motoristas"]);
    $Contador_Motoristas = 0;
    for ($i = 1; $i <= $Quantidade_Motoristas; $i++){
      if ($_POST["Rodoviario_Codigo_Motorista".$i] != ''){

        $CTE_Motorista[$Contador_Motoristas] = new CTE_Motorista();
        
        $CTE_Motorista[$Contador_Motoristas]->setCodigo_CTE                 ($Codigo_CTE);
        $CTE_Motorista[$Contador_Motoristas]->setCodigo_Motorista           ($_POST["Rodoviario_Codigo_Motorista".$i]);
      
        $Contador_Motoristas++;
      }
    }

    /* Classe CTE_Prestacoes */
    $Quantidade_Prestacoes = ($_POST["Fatura_Quantidade_Prestacoes"]);
    $Contador_Prestacoes = 0;
    for ($i = 1; $i <= $Quantidade_Prestacoes; $i++){
      if ($_POST["Prestacao_Valor".$i] != ''){
        $CTE_Prestacao[$Contador_Prestacoes] = new CTE_Prestacao();  
        
        $CTE_Prestacao[$Contador_Prestacoes]->setCodigo_CTE                   ($Codigo_CTE);
        $CTE_Prestacao[$Contador_Prestacoes]->setCodigo_Prestacao             ($Contador_Prestacoes);
        $CTE_Prestacao[$Contador_Prestacoes]->setNome                         ($_POST["Prestacao_Nome".$i]);
        $CTE_Prestacao[$Contador_Prestacoes]->setValor                        ($_POST["Prestacao_Valor".$i]);

        $Contador_Prestacoes++;
      }
    }  

    /* Classe CTE_Seguros */
    $Quantidade_Seguros = ($_POST["Seguro_Quantidade_Seguros"]);
    $Contador_Seguros = 0;
    for ($i = 1; $i <= $Quantidade_Seguros; $i++){
      if ($_POST["Seguro_Nome_Seguradora".$i] != ''){

        $CTE_Seguro[$Contador_Seguros] = new CTE_Seguro(); 
        
        $CTE_Seguro[$Contador_Seguros]->setCodigo_CTE                      ($Codigo_CTE);
        $CTE_Seguro[$Contador_Seguros]->setCodigo_Seguro                   ($Contador_Seguros);
        $CTE_Seguro[$Contador_Seguros]->setResponsavel_Seguro              ($_POST["Seguro_Responsavel_Seguro".$i]);
        $CTE_Seguro[$Contador_Seguros]->setNome_Seguradora                 ($_POST["Seguro_Nome_Seguradora".$i]);
        $CTE_Seguro[$Contador_Seguros]->setNumero_Apolice                  ($_POST["Seguro_Numero_Apolice".$i]);
        $CTE_Seguro[$Contador_Seguros]->setNumero_Averbacao                ($_POST["Seguro_Numero_Averbacao".$i]);
        $CTE_Seguro[$Contador_Seguros]->setValor_Carga_Efeito_Averbacao    ($_POST["Seguro_Valor_Carga_Efeito_Averbacao".$i]);
      
        $Contador_Seguros++;
      }
    }  

    /* ---------------------------------------------------------- */
    /*                                                            */
    /*            Inserindo Valores no banco de dados             */
    /*                                                            */
    /* ---------------------------------------------------------- */

    /* SGT_Conhecimento_Transporte */
    $CTE_Query = $CTE->QueryInsert(); 
    $ans = sqlsrv_query($connection, $CTE_Query['Query'], $CTE_Query['Parametros']) or die( print_r( sqlsrv_errors(), true));    

    /* SGT_Conhecimento_Transporte_Eletronico_Cargas */
    foreach ($CTE_Carga as &$Carga) {
      $CTE_Cargas_Query = $Carga->QueryInsert(); 
      $ans = sqlsrv_query($connection, $CTE_Cargas_Query['Query'], $CTE_Cargas_Query['Parametros']) or die( print_r( sqlsrv_errors(), true));      
    }

    /* SGT_Conhecimento_Transporte_Eletronico_Documentos */
    foreach ($CTE_Documento as &$Documento) {
      $CTE_Doc_Query = $Documento->QueryInsert(); 
      $ans = sqlsrv_query($connection, $CTE_Doc_Query['Query'], $CTE_Doc_Query['Parametros']) or die( print_r( sqlsrv_errors(), true));      
    }
 
    /* SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior */
    foreach ($CTE_Documento_Transporte_Anterior as &$Documento_TA) {
      $CTE_DocTA_Query = $Documento_TA->QueryInsert(); 
      $ans = sqlsrv_query($connection, $CTE_DocTA_Query['Query'], $CTE_DocTA_Query['Parametros']) or die( print_r( sqlsrv_errors(), true));      
    }

    /* SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior_Eletronico */
    foreach ($CTE_Documento_Transporte_Anterior_Eletronico as &$Documento_TAE) {
      $CTE_DocTAE_Query = $Documento_TAE->QueryInsert(); 
      $ans = sqlsrv_query($connection, $CTE_DocTAE_Query['Query'], $CTE_DocTAE_Query['Parametros']) or die( print_r( sqlsrv_errors(), true));      
    }

    /* SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior_Papel */
    foreach ($CTE_Documento_Transporte_Anterior_Papel as &$Documento_TAP) {
      $CTE_DocTAP_Query = $Documento_TAP->QueryInsert(); 
      $ans = sqlsrv_query($connection, $CTE_DocTAP_Query['Query'], $CTE_DocTAP_Query['Parametros']) or die( print_r( sqlsrv_errors(), true));      
    }

    /* SGT_Conhecimento_Transporte_Eletronico_Veiculos */
    foreach ($CTE_Veiculo as &$Veiculo) {
      $CTE_Veiculo_Query = $Veiculo->QueryInsert(); 
      $ans = sqlsrv_query($connection, $CTE_Veiculo_Query['Query'], $CTE_Veiculo_Query['Parametros']) or die( print_r( sqlsrv_errors(), true));      
    }

    /* SGT_Conhecimento_Transporte_Eletronico_Motoristas */
    foreach ($CTE_Motorista as &$Motorista) {
      $CTE_Motorista_Query = $Motorista->QueryInsert(); 
      $ans = sqlsrv_query($connection, $CTE_Motorista_Query['Query'], $CTE_Motorista_Query['Parametros']) or die( print_r( sqlsrv_errors(), true));      
    }

    /* SGT_Conhecimento_Transporte_Eletronico_Prestacoes */
    foreach ($CTE_Prestacao as &$Prestacao) {
      $CTE_Prestacao_Query = $Prestacao->QueryInsert(); 
      $ans = sqlsrv_query($connection, $CTE_Prestacao_Query['Query'], $CTE_Prestacao_Query['Parametros']) or die( print_r( sqlsrv_errors(), true));      
    }

    /* SGT_Conhecimento_Transporte_Eletronico_Seguros */
    foreach ($CTE_Seguro as &$Seguro) {
      $CTE_Seguro_Query = $Seguro->QueryInsert(); 
      $ans = sqlsrv_query($connection, $CTE_Seguro_Query['Query'], $CTE_Seguro_Query['Parametros']) or die( print_r( sqlsrv_errors(), true));      
    }

    header("Location: ../Cadastros/Formulario_CTE.php");
  }
?>