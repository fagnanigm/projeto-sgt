<?php
 
class CTE_Motorista {
    
    //Vars
    public $Codigo_CTE;
    public $Codigo_Motorista;

    //Gets
    public function getCodigo_CTE()                 { return $this->Codigo_CTE;         }
    public function getCodigo_Motorista()           { return $this->Codigo_Motorista;   }

    //Sets
    public function setCodigo_CTE($valor)           { $this->Codigo_CTE = $valor;       }
    public function setCodigo_Motorista($valor)     { $this->Codigo_Motorista = $valor; }    

    public function QueryInsert() {
        $Query = 'INSERT INTO SGT_Conhecimento_Transporte_Eletronico_Motoristas
        ([Codigo_CTE] ,[Codigo_Motorista]) VALUES (?,?);';

        $Parametros = array(    
            $this->getCodigo_CTE(),
            $this->getCodigo_Motorista()
        );
            
        return array(
        'Query' => $Query,
        'Parametros' => $Parametros
        );
    }
}   

?>