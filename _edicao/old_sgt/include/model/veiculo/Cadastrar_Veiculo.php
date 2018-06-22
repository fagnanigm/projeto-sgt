<?php

  CadastrarVeiculo();

  function CadastrarVeiculo()
  {
    include_once("/Conecta_DB.php");
    Include_once("../Classes/Classe_Veiculo.php"); 

    /* Fazendo Conexão com o banco */
    $connection = ConnectDB();  

    /*Criando Instancia */
    $Veiculo = new Veiculo();

    /* Atribuindo o Proximo Código para o registro */
    $queryCodigo = "select COALESCE(max(Codigo_Veiculo) + 1, 1) as Codigo from SGT_Veiculos";
    $ansCodigo = sqlsrv_query($connection, $queryCodigo, array()) or die (mssql_get_last_message());
    
    while($row = sqlsrv_fetch_array($ansCodigo, SQLSRV_FETCH_ASSOC)){
      $Codigo_Veiculo = $row['Codigo'];
    }

    /* Pegando valores do Form(formulario_Veiculo) e adicionando a classe Veiculo */
    $Veiculo->setCodigo_Veiculo                 ($Codigo_Veiculo);
    $Veiculo->setRenavam                        ($_POST["Renavam"]);
    $Veiculo->setPlaca                          ($_POST["Placa"]);
    $Veiculo->setTipo_Veiculo                   ($_POST["Tipo_Veiculo"]);
    $Veiculo->setTipo_Carroceria                ($_POST["Tipo_Carroceria"]);
    $Veiculo->setTipo_Rodado                    ($_POST["Tipo_Rodado"]);
    $Veiculo->setTara_Kg                        ($_POST["Tara_Kg"]);
    $Veiculo->setCapacidade_Kg                  ($_POST["Capacidade_Kg"]);
    $Veiculo->setCapacidade_M3                  ($_POST["Capacidade_M3"]);
    $Veiculo->setUF_Veiculo                     ($_POST["UF_Veiculo"]);

    /* Campos que serão adicionados os valores */
    /*  01  */$campos  = "Codigo_Veiculo";
    /*  02  */$campos .= ",Renavam";
    /*  03  */$campos .= ",Placa";
    /*  04  */$campos .= ",Tipo_Veiculo";
    /*  05  */$campos .= ",Tipo_Carroceria";
    /*  06  */$campos .= ",Tipo_Rodado";
    /*  07  */$campos .= ",Tara_Kg";
    /*  08  */$campos .= ",Capacidade_Kg";
    /*  09  */$campos .= ",Capacidade_M3";
    /*  10  */$campos .= ",UF_Veiculo";

    /* Pegando os valores da classe Natureza */
    /*  01  */$valores  =     $Veiculo->getCodigo_Veiculo();
    /*  02  */$valores .= ",".$Veiculo->getRenavam();
    /*  03  */$valores .= ",".$Veiculo->getPlaca(); 
    /*  04  */$valores .= ",".$Veiculo->getTipo_Veiculo();
    /*  05  */$valores .= ",".$Veiculo->getTipo_Carroceria(); 
    /*  06  */$valores .= ",".$Veiculo->getTipo_Rodado();
    /*  07  */$valores .= ",".$Veiculo->getTara_Kg(); 
    /*  08  */$valores .= ",".$Veiculo->getCapacidade_Kg(); 
    /*  09  */$valores .= ",".$Veiculo->getCapacidade_M3();
    /*  10  */$valores .= ",".$Veiculo->getUF_Veiculo(); 

    /*Montando a Query */
    $query = "insert into SGT_Veiculos(".$campos.") values (".$valores.");";   
    $ans = sqlsrv_query($connection, $query, array()) or die (mssql_get_last_message());

    header("Location: /Cadastros/Formulario_Veiculos.php");

  }
?>