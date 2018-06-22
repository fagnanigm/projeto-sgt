<?php
 
class CTE_Seguro {
    
    //Vars
    public $Codigo_CTE;
    public $Codigo_Seguro;
    public $Responsavel_Seguro;
    public $Nome_Seguradora;
    public $Numero_Apolice;
    public $Numero_Averbacao;
    public $Valor_Carga_Efeito_Averbacao;

    //Gets
    public function getCodigo_CTE()                         { return $this->Codigo_CTE;                     }
    public function getCodigo_Seguro()                      { return $this->Codigo_Seguro;                  }
    public function getResponsavel_Seguro()                 { return $this->Responsavel_Seguro;             }
    public function getNome_Seguradora()                    { return $this->Nome_Seguradora;                }
    public function getNumero_Apolice()                     { return $this->Numero_Apolice;                 }
    public function getNumero_Averbacao()                   { return $this->Numero_Averbacao;               }
    public function getValor_Carga_Efeito_Averbacao()       { return $this->Valor_Carga_Efeito_Averbacao;   }    

    //Sets
    public function setCodigo_CTE($valor)                   { $this->Codigo_CTE = $valor;                   }
    public function setCodigo_Seguro($valor)                { $this->Codigo_Seguro = $valor;                }
    public function setResponsavel_Seguro($valor)           { $this->Responsavel_Seguro = $valor;           }   
    public function setNome_Seguradora($valor)              { $this->Nome_Seguradora = $valor;              }
    public function setNumero_Apolice($valor)               { $this->Numero_Apolice = $valor;               }   
    public function setNumero_Averbacao($valor)             { $this->Numero_Averbacao = $valor;             }
    public function setValor_Carga_Efeito_Averbacao($valor) { $this->Valor_Carga_Efeito_Averbacao = $valor; }
    
    public function QueryInsert() {
        $Query = 'INSERT INTO SGT_Conhecimento_Transporte_Eletronico_Seguros
        ([Codigo_CTE] ,[Codigo_Seguro] ,[Responsavel_Seguro] ,[Nome_Seguradora],
        [Numero_Apolice] ,[Numero_Averbacao] ,[Valor_Carga_Efeito_Averbacao])
        VALUES (?,?,?,?,?,?,?);';

        $Parametros = array(    
            $this->getCodigo_CTE(),
            $this->getCodigo_Seguro(),
            $this->getResponsavel_Seguro(),
            $this->getNome_Seguradora(),
            $this->getNumero_Apolice(),
            $this->getNumero_Averbacao(),
            $this->getValor_Carga_Efeito_Averbacao()
        );
            
        return array(
        'Query' => $Query,
        'Parametros' => $Parametros
        );
    }
}   

?>