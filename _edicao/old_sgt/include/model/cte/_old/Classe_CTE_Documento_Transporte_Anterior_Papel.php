<?php
 
class CTE_Documento_Transporte_Anterior_Papel {
    
    //Vars
    public $Codigo_CTE;
    public $Codigo_Documento_Transporte_Anterior;
    public $Codigo_Documento_Transporte_Anterior_Papel;
    public $Tipo;
    public $Serie;
    public $Sub_Serie;
    public $Numero;
    public $Data_Emissao;

    //Gets
    public function getCodigo_CTE()                                 { return $this->Codigo_CTE;                                 }
    public function getCodigo_Documento_Transporte_Anterior()       { return $this->Codigo_Documento_Transporte_Anterior;       }
    public function getCodigo_Documento_Transporte_Anterior_Papel() { return $this->Codigo_Documento_Transporte_Anterior_Papel; }
    public function getTipo()                                       { return $this->Tipo;                                       }
    public function getSerie()                                      { return $this->Serie;                                      }
    public function getSub_Serie()                                  { return $this->Sub_Serie;                                  }
    public function getNumero()                                     { return $this->Numero;                                     }
    public function getData_Emissao()                               { return $this->Data_Emissao;                               }

    //Sets
    public function setCodigo_CTE($valor)                                   { $this->Codigo_CTE = $valor;                                   }
    public function setCodigo_Documento_Transporte_Anterior($valor)         { $this->Codigo_Documento_Transporte_Anterior = $valor;         }
    public function setCodigo_Documento_Transporte_Anterior_Papel($valor)   { $this->Codigo_Documento_Transporte_Anterior_Papel = $valor;   }
    public function setTipo($valor)                                         { $this->Tipo = $valor;                                         }
    public function setSerie($valor)                                        { $this->Serie = $valor;                                        }
    public function setSub_Serie($valor)                                    { $this->Sub_Serie = $valor;                                    }
    public function setNumero($valor)                                       { $this->Numero = $valor;                                       }    
    public function setData_Emissao($valor)                                 { $this->Data_Emissao = $valor;                                 } 

    public function QueryInsert() {
        $Query = 'INSERT INTO SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior_Papel
        ([Codigo_CTE] ,[Codigo_Documento_Transporte_Anterior] ,[Codigo_Documento_Transporte_Anterior_Papel]
        ,[Tipo] ,[Serie] ,[Sub_Serie] ,[Numero] ,[Data_Emissao]) VALUES (?,?,?,?,?,?,?,?);';

        $Parametros = array(    
            $this->getCodigo_CTE(),
            $this->getCodigo_Documento_Transporte_Anterior(),
            $this->getCodigo_Documento_Transporte_Anterior_Papel(),
            $this->getTipo(),
            $this->getSerie(),
            $this->getSub_Serie(),
            $this->getNumero(),
            $this->getData_Emissao()
        );
            
        return array(
        'Query' => $Query,
        'Parametros' => $Parametros
        );
    }
}   

?>