<?php
 
class Natureza {
    
    //Vars
    public $Codigo_Natureza;
    public $Descricao_Natureza;
    public $CFOP_Dentro_Estado;
    public $CFOP_Fora_Estado;
    public $Finalidade;
    public $Calculo_Automatico_Tributos;
    public $Finalidade_List = array(
        0 => 'Normal',
        1 => 'Complementar',
        2 => 'Ajuste',
        3 => 'Devolucao'
    );

    //Gets
    public function getCodigo_Natureza()                { return $this->Codigo_Natureza;                }
    public function getDescricao_Natureza()             { return $this->Descricao_Natureza;             }
    public function getCFOP_Dentro_Estado()             { return $this->CFOP_Dentro_Estado;             }
    public function getCFOP_Fora_Estado()               { return $this->CFOP_Fora_Estado;               }
    public function getFinalidade()                     { return $this->Finalidade;                     }
    public function getCalculo_Automatico_Tributos()    { return $this->Calculo_Automatico_Tributos;    } 


    //Sets
    public function setCodigo_Natureza($valor)              { $this->Codigo_Natureza = $valor;                          }
    public function setDescricao_Natureza($valor)           { $this->Descricao_Natureza = "'".$valor."'";               }
    public function setCFOP_Dentro_Estado($valor)           { $this->CFOP_Dentro_Estado = $valor;                       }
    public function setCFOP_Fora_Estado($valor)             { $this->CFOP_Fora_Estado = $valor;                         }
    public function setFinalidade($valor)                   { $this->Finalidade = $valor;                               }
    public function setCalculo_Automatico_Tributos($valor)  { $this->Calculo_Automatico_Tributos = $this->true_false($valor);  }

    public function listarNatureza(){

        global $connection;
        $lista = array();

        $queryCodigo = "SELECT * FROM SGT_Natureza";
        $ansCodigo = sqlsrv_query($connection, $queryCodigo, array()) or die (mssql_get_last_message());

        while($row = sqlsrv_fetch_array($ansCodigo, SQLSRV_FETCH_ASSOC)){
            $row['finalidade_text'] = $this->Finalidade_List[$row['Finalidade']];
            $lista[] = $row;
        }

        return $lista;
    }

}   

?>