<?php
 
class CTE_Documento_Transporte_Anterior_Eletronico {
    
    //Vars
    public $Codigo_CTE;
    public $Codigo_Documento_Transporte_Anterior;
    public $Codigo_Documento_Transporte_Anterior_Eletronico;
    public $Chave_Acesso;

    //Gets
    public function getCodigo_CTE()                                             { return $this->Codigo_CTE;                                         }
    public function getCodigo_Documento_Transporte_Anterior()                   { return $this->Codigo_Documento_Transporte_Anterior;               }
    public function getCodigo_Documento_Transporte_Anterior_Eletronico()        { return $this->Codigo_Documento_Transporte_Anterior_Eletronico;    }
    public function getChave_Acesso()                                           { return $this->Chave_Acesso;                                       }

    //Sets
    public function setCodigo_CTE($valor)                                       { $this->Codigo_CTE = $valor;                                       }
    public function setCodigo_Documento_Transporte_Anterior($valor)             { $this->Codigo_Documento_Transporte_Anterior = $valor;             }
    public function setCodigo_Documento_Transporte_Anterior_Eletronico($valor)  { $this->Codigo_Documento_Transporte_Anterior_Eletronico = $valor;  }
    public function setChave_Acesso($valor)                                     { $this->Chave_Acesso = $valor;                                     }

    public function QueryInsert() {
        $Query = 'INSERT INTO SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior_Eletronico
        ([Codigo_CTE] ,[Codigo_Documento_Transporte_Anterior] ,[Codigo_Documento_Transporte_Anterior_Eletronico] 
        ,[Chave_Acesso]) VALUES (?,?,?,?);';

        $Parametros = array(    
            $this->getCodigo_CTE(),
            $this->getCodigo_Documento_Transporte_Anterior(),
            $this->getCodigo_Documento_Transporte_Anterior_Eletronico(),
            $this->getChave_Acesso()
        );
            
        return array(
        'Query' => $Query,
        'Parametros' => $Parametros
        );
    }
}   

?>