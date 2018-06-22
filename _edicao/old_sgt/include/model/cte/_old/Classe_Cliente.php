<?php
 
class Cliente {
    
    //Vars
    public $Codigo_CLiente;
    public $Razao_Social;
    public $Nome_Fantasia;
    public $Endereco;
    public $Codigo_Municipio_IBGE;
    public $CNPJ_CPF;
    public $Pais;
    public $CEP;
    public $Inscricao_Estadual;
    public $Telefone;
    public $Tipo //0 = Remetente, 1 = Destinatario, 2 = Expedidor, 3 = Recebedor, 4 = Tomador

    //Gets
    public function getCodigo_CLiente()         { return $this->Codigo_CLiente;         }
    public function getRazao_Social()           { return $this->Razao_Social;           }
    public function getNome_Fantasia()          { return $this->Nome_Fantasia;          }
    public function getEndereco()               { return $this->Endereco;               }
    public function getCodigo_Municipio_IBGE()  { return $this->Codigo_Municipio_IBGE;  }
    public function getCNPJ_CPF()               { return $this->CNPJ_CPF;               }  
    public function getPais()                   { return $this->Pais;                   }  
    public function getCEP()                    { return $this->CEP;                    }
    public function getInscricao_Estadual()     { return $this->Inscricao_Estadual;     } 
    public function getTelefone()               { return $this->Telefone;               } 
    public function getTipo()                   { return $this->Tipo;                   } 

    //Sets
    public function setCodigo_CLiente()         { $this->Codigo_Veiculo = $valor;               }
    public function setRazao_Social()           { $this->Razao_Social = "'".$valor."'";         }
    public function setNome_Fantasia()          { $this->Nome_Fantasia = "'".$valor."'";        }
    public function setEndereco()               { $this->Endereco = "'".$valor."'";             }
    public function setCodigo_Municipio_IBGE()  { $this->Codigo_Municipio_IBGE = $valor;        }
    public function setCNPJ_CPF()               { $this->CNPJ_CPF = "'".$valor."'";             }
    public function setPais()                   { $this->Pais = "'".$valor."'";                 }
    public function setCEP()                    { $this->CEP = "'".$valor."'";                  }
    public function setInscricao_Estadual()     { $this->Inscricao_Estadual = "'".$valor."'";   }
    public function setTelefone()               { $this->Telefone = "'".$valor."'";             }
    public function setTipo()                   { $this->Tipo = $valor;                         }

    public function QueryInsert() {
        $Query = 'INSERT INTO SGT_Clientes_Omie
            ([Codigo_CLiente] ,[Razao_Social] ,[Nome_Fantasia] ,[Endereco] ,[Codigo_Municipio_IBGE]
            ,[CNPJ_CPF] ,[Pais] ,[CEP] ,[Inscricao_Estadual] ,[Telefone], [Tipo]) VALUES
            (?,?,?,?,?,?,?,?,?,?,?);';
    
        $Parametros = array(    
            $this->getCodigo_CLiente(),
            $this->getRazao_Social(),
            $this->getNome_Fantasia(),
            $this->getEndereco(),
            $this->getCodigo_Municipio_IBGE(),
            $this->getCNPJ_CPF(),
            $this->getPais(),
            $this->getCEP(),
            $this->getInscricao_Estadual(),
            $this->getTelefone(),
            $this->getTipo()
        );
    
        return array(
          'Query' => $Query,
          'Parametros' => $Parametros
        );
    }
}   

?>