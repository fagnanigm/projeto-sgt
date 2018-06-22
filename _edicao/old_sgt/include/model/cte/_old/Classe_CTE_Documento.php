<?php
 
class CTE_Documento {
    
    //Vars
    public $Codigo_CTE;
	public $Codigo_Documento;
	public $Tipo_Documento;
	public $Nota_Fiscal_Serie;
	public $Nota_Fiscal_Numero;
	public $Nota_Fiscal_Data_Emissao;
	public $Nota_Fiscal_CFOP;
	public $Nota_Fiscal_B_C_ICMS;
	public $Nota_Fiscal_B_C_ICMS_ST;
	public $Nota_Fiscal_Valor_ICMS;
	public $Nota_Fiscal_Valor_ICMS_ST;
	public $Nota_Fiscal_Valor_Produtos;
	public $Nota_Fiscal_Valor_Nota;
	public $Nota_Fiscal_Eletronica_Chave_Acesso;
	public $Outros_Documentos_Data_Emissao;
	public $Outros_Documentos_Documento_Origem;
	public $Outros_Documentos_Descricao;
	public $Outros_Documentos_Valor;

    //Gets
    public function getCodigo_CTE()                             { return $this->Codigo_CTE;                         }
    public function getCodigo_Documento()                       { return $this->Codigo_Documento;                   }
    public function getTipo_Documento()                         { return $this->Tipo_Documento;                     }
    public function getNota_Fiscal_Serie()                      { return $this->Nota_Fiscal_Serie;                  }
    public function getNota_Fiscal_Numero()                     { return $this->Nota_Fiscal_Numero;                 }
    public function getNota_Fiscal_Data_Emissao()               { return $this->Nota_Fiscal_Data_Emissao;           }
    public function getNota_Fiscal_CFOP()                       { return $this->Fiscal_CFOP;                        }
    public function getNota_Fiscal_B_C_ICMS()                   { return $this->Fiscal_B_C_ICMS;                    }
    public function getNota_Fiscal_B_C_ICMS_ST()                { return $this->Fiscal_B_C_ICMS_ST;                 }
    public function getNota_Fiscal_Valor_ICMS()                 { return $this->Fiscal_Valor_ICMS;                  }
    public function getNota_Fiscal_Valor_ICMS_ST()              { return $this->Fiscal_Valor_ICMS_ST;               }
    public function getNota_Fiscal_Valor_Produtos()             { return $this->Fiscal_Valor_Produtos;              }
    public function getNota_Fiscal_Valor_Nota()                 { return $this->Fiscal_Valor_Nota;                  }
    public function getNota_Fiscal_Eletronica_Chave_Acesso()    { return $this->Fiscal_Eletronica_Chave_Acesso;     }
    public function getOutros_Documentos_Data_Emissao()         { return $this->Outros_Documentos_Data_Emissao;     }
    public function getOutros_Documentos_Documento_Origem()     { return $this->Outros_Documentos_Documento_Origem; }
    public function getOutros_Documentos_Descricao()            { return $this->Outros_Documentos_Descricao;        }
    public function getOutros_Documentos_Valor()                { return $this->Outros_Documentos_Valor;            }

    //Sets
    public function setCodigo_CTE($valor)                             { $this->Codigo_CTE = $valor;                             }
    public function setCodigo_Documento($valor)                       { $this->Codigo_Documento = $valor;                       }
    public function setTipo_Documento($valor)                         { $this->Tipo_Documento = $this->Tipo_Documento($valor);         }
    public function setNota_Fiscal_Serie($valor)                      { $this->Nota_Fiscal_Serie = $valor;                      }
    public function setNota_Fiscal_Numero($valor)                     { $this->Nota_Fiscal_Numero = $valor;                     }
    public function setNota_Fiscal_Data_Emissao($valor)               { $this->Nota_Fiscal_Data_Emissao = $valor;               }
    public function setNota_Fiscal_CFOP($valor)                       { $this->Fiscal_CFOP = $valor;                            }
    public function setNota_Fiscal_B_C_ICMS($valor)                   { $this->Fiscal_B_C_ICMS = $valor;                        }
    public function setNota_Fiscal_B_C_ICMS_ST($valor)                { $this->Fiscal_B_C_ICMS_ST = $valor;                     }
    public function setNota_Fiscal_Valor_ICMS($valor)                 { $this->Fiscal_Valor_ICMS = $valor;                      }
    public function setNota_Fiscal_Valor_ICMS_ST($valor)              { $this->Fiscal_Valor_ICMS_ST = $valor;                   }
    public function setNota_Fiscal_Valor_Produtos($valor)             { $this->Fiscal_Valor_Produtos = $valor;                  }
    public function setNota_Fiscal_Valor_Nota($valor)                 { $this->Fiscal_Valor_Nota = $valor;                      }
    public function setNota_Fiscal_Eletronica_Chave_Acesso($valor)    { $this->Fiscal_Eletronica_Chave_Acesso = $valor;         }
    public function setOutros_Documentos_Data_Emissao($valor)         { $this->Outros_Documentos_Data_Emissao = $valor;         }
    public function setOutros_Documentos_Documento_Origem($valor)     { $this->Outros_Documentos_Documento_Origem = $valor;     }
    public function setOutros_Documentos_Descricao($valor)            { $this->Outros_Documentos_Descricao = $valor;            }
    public function setOutros_Documentos_Valor($valor)                { $this->Outros_Documentos_Valor = $valor;                }

    //Ajustes
    public function Tipo_Documento($tipo_Doc) { 

        if ($tipo_Doc == 'NF')  {$NovoTipo_Documento = 1;}
        if ($tipo_Doc == 'NFE') {$NovoTipo_Documento = 2;}
        if ($tipo_Doc == 'OD')  {$NovoTipo_Documento = 3;}
        
        return $NovoTipo_Documento;
    }

    public function QueryInsert() {
        $Query = 'INSERT INTO SGT_Conhecimento_Transporte_Eletronico_Documentos
        ([Codigo_CTE] ,[Codigo_Documento] ,[Tipo_Documento] ,[Nota_Fiscal_Serie] ,[Nota_Fiscal_Numero]
        ,[Nota_Fiscal_Data_Emissao] ,[Nota_Fiscal_CFOP] ,[Nota_Fiscal_B_C_ICMS] ,[Nota_Fiscal_B_C_ICMS_ST]
        ,[Nota_Fiscal_Valor_ICMS] ,[Nota_Fiscal_Valor_ICMS_ST] ,[Nota_Fiscal_Valor_Produtos]
        ,[Nota_Fiscal_Valor_Nota] ,[Nota_Fiscal_Eletronica_Chave_Acesso] ,[Outros_Documentos_Data_Emissao]
        ,[Outros_Documentos_Documento_Origem] ,[Outros_Documentos_Descricao] ,[Outros_Documentos_Valor]) VALUES
        (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);';
    
        $Parametros = array(    
            $this->getCodigo_CTE(),
            $this->getCodigo_Documento(),
            $this->getTipo_Documento(),
            $this->getNota_Fiscal_Serie(),
            $this->getNota_Fiscal_Numero(),
            $this->getNota_Fiscal_Data_Emissao(),
            $this->getNota_Fiscal_CFOP(),
            $this->getNota_Fiscal_B_C_ICMS(),
            $this->getNota_Fiscal_B_C_ICMS_ST(),
            $this->getNota_Fiscal_Valor_ICMS(),
            $this->getNota_Fiscal_Valor_ICMS_ST(),
            $this->getNota_Fiscal_Valor_Produtos(),
            $this->getNota_Fiscal_Valor_Nota(),
            $this->getNota_Fiscal_Eletronica_Chave_Acesso(),
            $this->getOutros_Documentos_Data_Emissao(),
            $this->getOutros_Documentos_Documento_Origem(),
            $this->getOutros_Documentos_Descricao(),
            $this->getOutros_Documentos_Valor()
        );

        return array(
          'Query' => $Query,
          'Parametros' => $Parametros
        );
      }
}   

?>