<?php
 
class CTE_Documento_Transporte_Anterior {
    
    //Vars
    public $Codigo_CTE;
    public $Codigo_Documento_Transporte_Anterior;
    public $Razao_Social;
    public $CPF_CNPJ;
    public $Inscricao_Estadual;
    public $UF;

    //Gets
    public function getCodigo_CTE()                                 { return $this->Codigo_CTE;                             }
    public function getCodigo_Documento_Transporte_Anterior()       { return $this->Codigo_Documento_Transporte_Anterior;   }
    public function getRazao_Social()                               { return $this->Razao_Social;                           }
    public function getCPF_CNPJ()                                   { return $this->CPF_CNPJ;                               }
    public function getInscricao_Estadual()                         { return $this->Inscricao_Estadual;                     }
    public function getUF()                                         { return $this->UF;                                     }

    //Sets
    public function setCodigo_CTE($valor)                           { $this->Codigo_CTE = $valor;                           }
    public function setCodigo_Documento_Transporte_Anterior($valor) { $this->Codigo_Documento_Transporte_Anterior = $valor; }
    public function setRazao_Social($valor)                         { $this->Razao_Social = $valor;                         }
    public function setCPF_CNPJ($valor)                             { $this->CPF_CNPJ = $valor;                             }
    public function setInscricao_Estadual($valor)                   { $this->Inscricao_Estadual = $valor;                   }
    public function setUF($valor)                                   { $this->UF = $valor;                                   }
   
    public function QueryInsert() {
        $Query = 'INSERT INTO SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior
        ([Codigo_CTE] ,[Codigo_Documento_Transporte_Anterior] ,[Razao_Social]
        ,[CPF_CNPJ] ,[Inscricao_Estadual] ,[UF]) VALUES (?,?,?,?,?,?);';

        $Parametros = array(    
            $this->getCodigo_CTE(),
            $this->getCodigo_Documento_Transporte_Anterior(),
            $this->getRazao_Social(),
            $this->getCPF_CNPJ(),
            $this->getInscricao_Estadual(),
            $this->getUF()
        );
            
        return array(
        'Query' => $Query,
        'Parametros' => $Parametros
        );
    }

}

?>