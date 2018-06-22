<?php
 
class CTE_Carga {
    
    //Vars
    public $Codigo_CTE;
    public $Codigo_Carga;
    public $Codigo_Unidade_Medida;
    public $Tipo_Medida;
    public $Quantidade_Carga;

    //Gets
    public function getCodigo_CTE()                     { return $this->Codigo_CTE; }
    public function getCodigo_Carga()                   { return $this->Codigo_Carga; }
    public function getCodigo_Unidade_Medida()          { return $this->Codigo_Unidade_Medida; }
    public function getTipo_Medida()                    { return $this->Tipo_Medida; }
    public function getQuantidade_Carga()               { return $this->Quantidade_Carga; }

    //Sets
    public function setCodigo_CTE($valor)               { $this->Codigo_CTE = $valor;               }
    public function setCodigo_Carga($valor)             { $this->Codigo_Carga = $valor;             }
    public function setCodigo_Unidade_Medida($valor)    { $this->Codigo_Unidade_Medida = $valor;    }
    public function setTipo_Medida($valor)              { $this->Tipo_Medida = $valor;              }
    public function setQuantidade_Carga($valor)         { $this->Quantidade_Carga = $valor;         }

    public function QueryInsert() {
        $Query = 'INSERT INTO SGT_Conhecimento_Transporte_Eletronico_Cargas
        ([Codigo_CTE], [Codigo_Carga] ,[Codigo_Unidade_Medida] ,[Tipo_Medida] ,[Quantidade_Carga]) VALUES
        (?,?,?,?,?);';
    
        $Parametros = array(    
            $this->getCodigo_CTE(),
            $this->getCodigo_Carga(),
            $this->getCodigo_Unidade_Medida(),
            $this->getTipo_Medida(),
            $this->getQuantidade_Carga()
        );
    
        return array(
          'Query' => $Query,
          'Parametros' => $Parametros
        );
    }
}   

?>