<?php
 
class CTE_Prestacao {
    
    //Vars
    public $Codigo_CTE;
    public $Codigo_Prestacao;
    public $Nome;
    public $Valor;

    //Gets
    public function getCodigo_CTE()                 { return $this->Codigo_CTE;         }
    public function getCodigo_Prestacao()           { return $this->Codigo_Prestacao;   }
    public function getNome()                       { return $this->Nome;               }
    public function getValor()                      { return $this->Valor;              }

    //Sets
    public function setCodigo_CTE($valor)           { $this->Codigo_CTE = $valor;       }
    public function setCodigo_Prestacao($valor)     { $this->Codigo_Prestacao = $valor; }   
    public function setNome($valor)                 { $this->Nome = $valor;             }
    public function setValor($valor)                { $this->Valor = $valor;            }   

    public function QueryInsert() {
        $Query = 'INSERT INTO SGT_Conhecimento_Transporte_Eletronico_Prestacoes
        ([Codigo_CTE] ,[Codigo_Prestacao] ,[Nome] ,[Valor]) VALUES (?,?,?,?);';

        $Parametros = array(    
            $this->getCodigo_CTE(),
            $this->getCodigo_Prestacao(),
            $this->getNome(),
            $this->getValor()
        );
            
        return array(
        'Query' => $Query,
        'Parametros' => $Parametros
        );
    }
}   

?>