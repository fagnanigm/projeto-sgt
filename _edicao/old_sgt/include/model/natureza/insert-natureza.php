<?php
  CadastrarNatureza();

  function CadastrarNatureza()
  {
    include_once("/Conecta_DB.php");
    Include_once("../Classes/Classe_Natureza.php"); 
    
    /* Fazendo Conexão com o banco */
    $connection = ConnectDB();  

    /*Criando Instancia */
    $Cadastro_Natureza = new Natureza();

    /* Atribuindo o Proximo Código para o registro */
    $queryCodigo = "select COALESCE(max(Codigo_Natureza) + 1, 1) as Codigo from SGT_Natureza";
    $ansCodigo = sqlsrv_query($connection, $queryCodigo, array()) or die (mssql_get_last_message());
    
    while($row = sqlsrv_fetch_array($ansCodigo, SQLSRV_FETCH_ASSOC)){
      $Codigo_Natureza = $row['Codigo'];
    }
    
    /* Pegando valores do Form(formulario_Natureza) e adicionando a classe CTE */
    $Cadastro_Natureza->setCodigo_Natureza                   ($Codigo_Natureza);
    $Cadastro_Natureza->setDescricao_Natureza                ($_POST["Descricao_Natureza"]);
    $Cadastro_Natureza->setCFOP_Dentro_Estado                ($_POST["CFOP_Dentro_Estado"]);
    $Cadastro_Natureza->setCFOP_Fora_Estado                  ($_POST["CFOP_Fora_Estado"]);
    $Cadastro_Natureza->setFinalidade                        ($_POST["Finalidade"]);
    $Cadastro_Natureza->setCalculo_Automatico_Tributos       ($_POST["Calculo_Automatico_Tributos"]);

    /* Campos que serão adicionados os valores */
    /*  01  */$campos = "Codigo_Natureza";
    /*  02  */$campos .= ",Descricao_Natureza";
    /*  03  */$campos .= ",CFOP_Dentro_Estado";
    /*  04  */$campos .= ",CFOP_Fora_Estado";
    /*  05  */$campos .= ",Finalidade";
    /*  06  */$campos .= ",Calculo_Automatico_Tributos";

    /* Pegando os valores da classe Natureza */
    /*  01  */$valores  =     $Cadastro_Natureza->getCodigo_Natureza();
    /*  02  */$valores .= ",".$Cadastro_Natureza->getDescricao_Natureza();
    /*  03  */$valores .= ",".$Cadastro_Natureza->getCFOP_Dentro_Estado();
    /*  04  */$valores .= ",".$Cadastro_Natureza->getCFOP_Fora_Estado();
    /*  05  */$valores .= ",".$Cadastro_Natureza->getFinalidade();
    /*  06  */$valores .= ",".$Cadastro_Natureza->getCalculo_Automatico_Tributos();    

    /*Montando a Query */
    $query = "insert into SGT_Natureza(".$campos.") values (".$valores.");"; 
    $ans = sqlsrv_query($connection, $query, array()) or die( print_r( sqlsrv_errors(), true));

    header("Location: /Cadastros/Formulario_Natureza.php");
  }
?>