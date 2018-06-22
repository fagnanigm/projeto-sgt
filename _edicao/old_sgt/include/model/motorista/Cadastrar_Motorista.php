<?php

  CadastrarMotorista();

  function CadastrarMotorista()
  {
    include_once("/Conecta_DB.php");
    Include_once("../Classes/Classe_Motorista.php"); 

    /* Fazendo Conexão com o banco */
    $connection = ConnectDB();  

    /*Criando Instancia */
    $Motorista = new Motorista();

    /* Atribuindo o Proximo Código para o registro */
    $queryCodigo = "select COALESCE(max(Codigo_Motorista) + 1, 1) as Codigo from SGT_Motoristas";
    $ansCodigo = sqlsrv_query($connection, $queryCodigo, array()) or die (mssql_get_last_message());
    
    while($row = sqlsrv_fetch_array($ansCodigo, SQLSRV_FETCH_ASSOC)){
      $Codigo_Motorista = $row['Codigo'];
    }

    /* Pegando valores do Form(formulario_Motorista) e adicionando a classe Motorista */
    $Motorista->setCodigo_Motorista                   ($Codigo_Motorista);
    $Motorista->setCPF                                ($_POST["CPF"]);
    $Motorista->setNome                               ($_POST["Nome"]);

    /* Campos que serão adicionados os valores */
    /*  01  */$campos = "Codigo_Motorista";
    /*  02  */$campos .= ",CPF";
    /*  03  */$campos .= ",Nome";

    /* Pegando os valores da classe Natureza */
    /*  01  */$valores  =     $Motorista->getCodigo_Motorista();
    /*  02  */$valores .= ",".$Motorista->getCPF();
    /*  03  */$valores .= ",".$Motorista->getNome(); 

    /*Montando a Query */
    $query = "insert into SGT_Motoristas(".$campos.") values (".$valores.");";   
    $ans = sqlsrv_query($connection, $query, array()) or die (mssql_get_last_message());

    header("Location: /Cadastros/Formulario_Motorista.php");

  }
?>