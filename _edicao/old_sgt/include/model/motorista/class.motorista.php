<?php
 
class Motorista {
    
    //Vars
    public $Codigo_Motorista;
    public $CPF;
    public $Nome;

    //Gets
    public function getCodigo_Motorista()   { return $this->Codigo_Motorista;   }
    public function getCPF()                { return $this->CPF;                }
    public function getNome()               { return $this->Nome;               }

    //Sets
    public function setCodigo_Motorista($valor)     { $this->Codigo_Motorista = $valor; }
    public function setCPF($valor)                  { $this->CPF = "'".$valor."'";      }
    public function setNome($valor)                 { $this->Nome = "'".$valor."'";     }

    public function ListarMotorista(){

        global $connection;
        $lista = array();

        $queryCodigo = "SELECT * FROM SGT_Motoristas";
        $ansCodigo = sqlsrv_query($connection, $queryCodigo, array()) or die (mssql_get_last_message());

        while($row = sqlsrv_fetch_array($ansCodigo, SQLSRV_FETCH_ASSOC)){
            $lista[] = $row;
        }

        return $lista;
    }

}   

?>