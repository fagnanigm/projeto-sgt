<?php
 
class Veiculo {
    
    //Vars
    public $Codigo_Veiculo;
    public $Renavam;
    public $Placa;
    public $Tipo_Veiculo;
    public $Tipo_Carroceria;
    public $Tipo_Rodado;
    public $Tara_Kg;
    public $Capacidade_Kg;
    public $Capacidade_M3;
    public $UF_Veiculo;
    public $Tipo_Veiculo_List = array(
        0 => 'Tração',
        1 => 'Torque'
    );

    //Gets
    public function getCodigo_Veiculo()         { return $this->Codigo_Veiculo;     }
    public function getRenavam()                { return $this->Renavam;            }
    public function getPlaca()                  { return $this->Placa;              }
    public function getTipo_Veiculo()           { return $this->Tipo_Veiculo;       }
    public function getTipo_Carroceria()        { return $this->Tipo_Carroceria;    }
    public function getTipo_Rodado()            { return $this->Tipo_Rodado;        }  
    public function getTara_Kg()                { return $this->Tara_Kg;            }  
    public function getCapacidade_Kg()          { return $this->Capacidade_Kg;      }
    public function getCapacidade_M3()          { return $this->Capacidade_M3;      } 
    public function getUF_Veiculo()             { return $this->UF_Veiculo;         } 

    //Sets
    public function setCodigo_Veiculo($valor)   { $this->Codigo_Veiculo = $valor;       }
    public function setRenavam($valor)          { $this->Renavam = "'".$valor."'";      }
    public function setPlaca($valor)            { $this->Placa = "'".$valor."'";        }
    public function setTipo_Veiculo($valor)     { $this->Tipo_Veiculo = $valor;         }
    public function setTipo_Carroceria($valor)  { $this->Tipo_Carroceria = $valor;      }
    public function setTipo_Rodado($valor)      { $this->Tipo_Rodado = $valor;          }
    public function setTara_Kg($valor)          { $this->Tara_Kg = $valor;              }
    public function setCapacidade_Kg($valor)    { $this->Capacidade_Kg = $valor;        }
    public function setCapacidade_M3($valor)    { $this->Capacidade_M3 = $valor;        }
    public function setUF_Veiculo($valor)       { $this->UF_Veiculo =  "'".$valor."'";  }

    public function ListarVeiculo(){

        global $connection;

        $lista = array();

        $queryCodigo = "SELECT * FROM SGT_Veiculos";
        $ansCodigo = sqlsrv_query($connection, $queryCodigo, array()) or die (mssql_get_last_message());

        while($row = sqlsrv_fetch_array($ansCodigo, SQLSRV_FETCH_ASSOC)){

            $row['Tipo_Veiculo_text'] = $this->Tipo_Veiculo_List[$row['Tipo_Veiculo']];

            $lista[] = $row;
        }

        return $lista;
    }

}   

?>