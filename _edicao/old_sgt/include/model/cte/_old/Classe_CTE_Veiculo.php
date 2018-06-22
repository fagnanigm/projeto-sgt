<?php
 
class CTE_Veiculo {
    
    //Vars
    public $Codigo_CTE;
    public $Codigo_Veiculo;

    //Gets
    public function getCodigo_CTE()             { return $this->Codigo_CTE;         }
    public function getCodigo_Veiculo()         { return $this->Codigo_Veiculo;     }

    //Sets
    public function setCodigo_CTE($valor)       { $this->Codigo_CTE = $valor;       }
    public function setCodigo_Veiculo($valor)   { $this->Codigo_Veiculo = $valor;   }

    public function QueryInsert() {
        $Query = 'INSERT INTO SGT_Conhecimento_Transporte_Eletronico_Veiculos
        ([Codigo_CTE] ,[Codigo_Veiculo]) VALUES (?,?);';

        $Parametros = array(    
            $this->getCodigo_CTE(),
            $this->getCodigo_Veiculo()
        );
            
        return array(
        'Query' => $Query,
        'Parametros' => $Parametros
        );
    }
}   

?>