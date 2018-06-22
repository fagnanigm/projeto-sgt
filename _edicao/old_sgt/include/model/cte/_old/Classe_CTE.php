<?php
 
class Conhecimento_Transporte {
  
  public $Codigo_Empresa;
  public $Codigo_Estabelecimento;
  public $Codigo_CTE;
  public $Geral_Tipo_CTE;
  public $Geral_Tipo_Servico;
  public $Geral_Data_Emissao;
  public $Geral_CFOP;
  public $Geral_Codigo_Natureza;
  public $Geral_Cidade_Origem_Codigo_IBGE;
  public $Geral_Cidade_Destino_Codigo_IBGE;
  public $Geral_Codigo_Remetente;
  public $Geral_Codigo_Destinatario;
  public $Geral_Codigo_Tomador;
  public $Carga_Valor;
  public $Carga_Produto_Predominante;
  public $Carga_Outras_Caracteristicas;
  public $Rodoviario_RNTRC;
  public $Documento_Codigo_Documento;
  public $Cobranca_Servico_Valor_Total_Servico;
  public $Cobranca_Servico_Valor_Receber;
  public $Cobranca_Servico_Forma_Pagamento;
  public $Cobranca_Servico_Valor_Aproximado_Tributos;
  public $Cobranca_ICMS_CST;
  public $Cobranca_ICMS_Base;
  public $Cobranca_ICMS_Aliquota;
  public $Cobranca_ICMS_Valor;
  public $Cobranca_ICMS_Percentual_Reducao_Base_Calculo;
  public $Cobranca_ICMS_Credito;
  public $Cobranca_Partilha_ICMS_Valor_Base_Calculo;
  public $Cobranca_Partilha_ICMS_Aliquota_Interna_UF_Termino;
  public $Cobranca_Partilha_ICMS_Aliquota_Interestadual;
  public $Cobranca_Partilha_ICMS_Porcentagem_Partilha_UF_Termino;
  public $Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Inicio;
  public $Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Termino;
  public $Cobranca_Partilha_ICMS_Porcentagem_ICMS_FCP_UF_Termino;
  public $Cobranca_Partilha_ICMS_Valor_ICMS_FCP_UF_Termino;
  public $Cobranca_Observacoes_Gerais;
  public $Cobranca_Entrega_Prevista;
  public $Fatura_Numero;
  public $Fatura_Valor_Origem;
  public $Fatura_Valor_Desconto;
  public $Fatura_Valor;

  //Gets
  public function getCodigo_Empresa()                                           { return $this->Codigo_Empresa;                                           }
  public function getCodigo_Estabelecimento()                                   { return $this->Codigo_Estabelecimento;                                   }
  public function getCodigo_CTE()                                               { return $this->Codigo_CTE;                                               }
  public function getGeral_Tipo_CTE()                                           { return $this->Geral_Tipo_CTE;                                           }
  public function getGeral_Tipo_Servico()                                       { return $this->Geral_Tipo_Servico;                                       }
  public function getGeral_Data_Emissao()                                       { return $this->Geral_Data_Emissao;                                       }
  public function getGeral_CFOP()                                               { return $this->Geral_CFOP;                                               }
  public function getGeral_Codigo_Natureza()                                    { return $this->Geral_Codigo_Natureza;                                    }
  public function getGeral_Cidade_Origem_Codigo_IBGE()                          { return $this->Geral_Cidade_Origem_Codigo_IBGE;                          }
  public function getGeral_Cidade_Destino_Codigo_IBGE()                         { return $this->Geral_Cidade_Destino_Codigo_IBGE;                         }
  public function getGeral_Codigo_Remetente()                                   { return $this->Geral_Codigo_Remetente;                                   }
  public function getGeral_Codigo_Destinatario()                                { return $this->Geral_Codigo_Destinatario;                                }
  public function getGeral_Codigo_Tomador()                                     { return $this->Geral_Codigo_Tomador;                                     }
  public function getCarga_Valor()                                              { return $this->Carga_Valor;                                              }
  public function getCarga_Produto_Predominante()                               { return $this->Carga_Produto_Predominante;                               }
  public function getCarga_Outras_Caracteristicas()                             { return $this->Carga_Outras_Caracteristicas;                             }
  public function getRodoviario_RNTRC()                                         { return $this->Rodoviario_RNTRC;                                         }
  public function getCobranca_Servico_Valor_Total_Servico()                     { return $this->Cobranca_Servico_Valor_Total_Servico;                     }
  public function getCobranca_Servico_Valor_Receber()                           { return $this->Cobranca_Servico_Valor_Receber;                           }
  public function getCobranca_Servico_Forma_Pagamento()                         { return $this->Cobranca_Servico_Forma_Pagamento;                         }
  public function getCobranca_Servico_Valor_Aproximado_Tributos()               { return $this->Cobranca_Servico_Valor_Aproximado_Tributos;               }
  public function getCobranca_ICMS_CST()                                        { return $this->Cobranca_ICMS_CST;                                        }
  public function getCobranca_ICMS_Base()                                       { return $this->Cobranca_ICMS_Base;                                       }
  public function getCobranca_ICMS_Aliquota()                                   { return $this->Cobranca_ICMS_Aliquota;                                   }
  public function getCobranca_ICMS_Valor()                                      { return $this->Cobranca_ICMS_Valor;                                      }
  public function getCobranca_ICMS_Percentual_Reducao_Base_Calculo()            { return $this->Cobranca_ICMS_Percentual_Reducao_Base_Calculo;            }
  public function getCobranca_ICMS_Credito()                                    { return $this->Cobranca_ICMS_Credito;                                    }
  public function getCobranca_Partilha_ICMS_Valor_Base_Calculo()                { return $this->Cobranca_Partilha_ICMS_Valor_Base_Calculo;                }
  public function getCobranca_Partilha_ICMS_Aliquota_Interna_UF_Termino()       { return $this->Cobranca_Partilha_ICMS_Aliquota_Interna_UF_Termino;       }
  public function getCobranca_Partilha_ICMS_Aliquota_Interestadual()            { return $this->Cobranca_Partilha_ICMS_Aliquota_Interestadual;            }
  public function getCobranca_Partilha_ICMS_Porcentagem_Partilha_UF_Termino()   { return $this->Cobranca_Partilha_ICMS_Porcentagem_Partilha_UF_Termino;   }
  public function getCobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Inicio()     { return $this->Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Inicio;     }
  public function getCobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Termino()    { return $this->Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Termino;    }
  public function getCobranca_Partilha_ICMS_Porcentagem_ICMS_FCP_UF_Termino()   { return $this->Cobranca_Partilha_ICMS_Porcentagem_ICMS_FCP_UF_Termino;   }
  public function getCobranca_Partilha_ICMS_Valor_ICMS_FCP_UF_Termino()         { return $this->Cobranca_Partilha_ICMS_Valor_ICMS_FCP_UF_Termino;         }
  public function getCobranca_Observacoes_Gerais()                              { return $this->Cobranca_Observacoes_Gerais;                              }
  public function getCobranca_Entrega_Prevista()                                { return $this->Cobranca_Entrega_Prevista;                                }
  public function getFatura_Numero()                                            { return $this->Fatura_Numero;                                            }
  public function getFatura_Valor_Origem()                                      { return $this->Fatura_Valor_Origem;                                      }
  public function getFatura_Valor_Desconto()                                    { return $this->Fatura_Valor_Desconto;                                    }
  public function getFatura_Valor()                                             { return $this->Fatura_Valor;                                             }


  //Sets
  public function setCodigo_Empresa($valor)                                           { $this->Codigo_Empresa = $valor;                                           }
  public function setCodigo_Estabelecimento($valor)                                   { $this->Codigo_Estabelecimento = $valor;                                   }
  public function setCodigo_CTE($valor)                                               { $this->Codigo_CTE = $valor;                                               }
  public function setGeral_Tipo_CTE($valor)                                           { $this->Geral_Tipo_CTE = $valor;                                           }
  public function setGeral_Tipo_Servico($valor)                                       { $this->Geral_Tipo_Servico = $valor;                                       }
  public function setGeral_Data_Emissao($valor)                                       { $this->Geral_Data_Emissao = $valor;                                       }
  public function setGeral_CFOP($valor)                                               { $this->Geral_CFOP = $valor;                                               }
  public function setGeral_Codigo_Natureza($valor)                                    { $this->Geral_Codigo_Natureza = $valor;                                    }
  public function setGeral_Cidade_Origem_Codigo_IBGE($valor)                          { $this->Geral_Cidade_Origem_Codigo_IBGE = $valor;                          }
  public function setGeral_Cidade_Destino_Codigo_IBGE($valor)                         { $this->Geral_Cidade_Destino_Codigo_IBGE = $valor;                         }
  public function setGeral_Codigo_Remetente($valor)                                   { $this->Geral_Codigo_Remetente = $valor;                                   }
  public function setGeral_Codigo_Destinatario($valor)                                { $this->Geral_Codigo_Destinatario = $valor;                                }
  public function setGeral_Codigo_Tomador($valor)                                     { $this->Geral_Codigo_Tomador = $valor;                                     }
  public function setCarga_Valor($valor)                                              { $this->Carga_Valor = $valor;                                              }
  public function setCarga_Produto_Predominante($valor)                               { $this->Carga_Produto_Predominante = $valor;                               }
  public function setCarga_Outras_Caracteristicas($valor)                             { $this->Carga_Outras_Caracteristicas = $valor;                             }
  public function setRodoviario_RNTRC($valor)                                         { $this->Rodoviario_RNTRC = $valor;                                         }
  public function setCobranca_Servico_Valor_Total_Servico($valor)                     { $this->Cobranca_Servico_Valor_Total_Servico = $valor;                     }
  public function setCobranca_Servico_Valor_Receber($valor)                           { $this->Cobranca_Servico_Valor_Receber = $valor;                           }
  public function setCobranca_Servico_Forma_Pagamento($valor)                         { $this->Cobranca_Servico_Forma_Pagamento = $valor;                         }
  public function setCobranca_Servico_Valor_Aproximado_Tributos($valor)               { $this->Cobranca_Servico_Valor_Aproximado_Tributos = $valor;               }
  public function setCobranca_ICMS_CST($valor)                                        { $this->Cobranca_ICMS_CST = $valor;                                        }
  public function setCobranca_ICMS_Base($valor)                                       { $this->Cobranca_ICMS_Base = $valor;                                       }
  public function setCobranca_ICMS_Aliquota($valor)                                   { $this->Cobranca_ICMS_Aliquota = $valor;                                   }
  public function setCobranca_ICMS_Valor($valor)                                      { $this->Cobranca_ICMS_Valor = $valor;                                      }
  public function setCobranca_ICMS_Percentual_Reducao_Base_Calculo($valor)            { $this->Cobranca_ICMS_Percentual_Reducao_Base_Calculo = $valor;            }
  public function setCobranca_ICMS_Credito($valor)                                    { $this->Cobranca_ICMS_Credito = $valor;                                    }
  public function setCobranca_Partilha_ICMS_Valor_Base_Calculo($valor)                { $this->Cobranca_Partilha_ICMS_Valor_Base_Calculo = $valor;                }
  public function setCobranca_Partilha_ICMS_Aliquota_Interna_UF_Termino($valor)       { $this->Cobranca_Partilha_ICMS_Aliquota_Interna_UF_Termino = $valor;       }
  public function setCobranca_Partilha_ICMS_Aliquota_Interestadual($valor)            { $this->Cobranca_Partilha_ICMS_Aliquota_Interestadual = $valor;            }
  public function setCobranca_Partilha_ICMS_Porcentagem_Partilha_UF_Termino($valor)   { $this->Cobranca_Partilha_ICMS_Porcentagem_Partilha_UF_Termino = $valor;   }
  public function setCobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Inicio($valor)     { $this->Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Inicio = $valor;     }
  public function setCobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Termino($valor)    { $this->Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Termino = $valor;    }
  public function setCobranca_Partilha_ICMS_Porcentagem_ICMS_FCP_UF_Termino($valor)   { $this->Cobranca_Partilha_ICMS_Porcentagem_ICMS_FCP_UF_Termino = $valor;   }
  public function setCobranca_Partilha_ICMS_Valor_ICMS_FCP_UF_Termino($valor)         { $this->Cobranca_Partilha_ICMS_Valor_ICMS_FCP_UF_Termino = $valor;         }
  public function setCobranca_Observacoes_Gerais($valor)                              { $this->Cobranca_Observacoes_Gerais = $valor;                              }
  public function setCobranca_Entrega_Prevista($valor)                                { $this->Cobranca_Entrega_Prevista = $valor;                                }
  public function setFatura_Numero($valor)                                            { $this->Fatura_Numero = $valor;                                            }
  public function setFatura_Valor_Origem($valor)                                      { $this->Fatura_Valor_Origem = $valor;                                      }
  public function setFatura_Valor_Desconto($valor)                                    { $this->Fatura_Valor_Desconto = $valor;                                    }
  public function setFatura_Valor($valor)                                             { $this->Fatura_Valor = $valor;                                             } 

  public function QueryInsert() {
    $Query = 'INSERT INTO SGT_Conhecimento_Transporte_Eletronico
    ([Codigo_Empresa] ,[Codigo_Estabelecimento] ,[Codigo_CTE] ,[Geral_Tipo_CTE] ,[Geral_Tipo_Servico]
    ,[Geral_Data_Emissao] ,[Geral_CFOP] ,[Geral_Codigo_Natureza] ,[Geral_Cidade_Origem_Codigo_IBGE]
    ,[Geral_Cidade_Destino_Codigo_IBGE] ,[Geral_Codigo_Remetente] ,[Geral_Codigo_Destinatario]
    ,[Geral_Codigo_Tomador] ,[Carga_Valor] ,[Carga_Produto_Predominante] ,[Carga_Outras_Caracteristicas]
    ,[Rodoviario_RNTRC] ,[Cobranca_Servico_Valor_Total_Servico] ,[Cobranca_Servico_Valor_Receber]
    ,[Cobranca_Servico_Forma_Pagamento] ,[Cobranca_Servico_Valor_Aproximado_Tributos] ,[Cobranca_ICMS_CST]
    ,[Cobranca_ICMS_Base] ,[Cobranca_ICMS_Aliquota] ,[Cobranca_ICMS_Valor] ,[Cobranca_ICMS_Percentual_Reducao_Base_Calculo]
    ,[Cobranca_ICMS_Credito] ,[Cobranca_Partilha_ICMS_Valor_Base_Calculo] ,[Cobranca_Partilha_ICMS_Aliquota_Interna_UF_Termino]
    ,[Cobranca_Partilha_ICMS_Aliquota_Interestadual] ,[Cobranca_Partilha_ICMS_Porcentagem_Partilha_UF_Termino]
    ,[Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Inicio] ,[Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Termino]
    ,[Cobranca_Partilha_ICMS_Porcentagem_ICMS_FCP_UF_Termino] ,[Cobranca_Partilha_ICMS_Valor_ICMS_FCP_UF_Termino]
    ,[Cobranca_Observacoes_Gerais] ,[Cobranca_Entrega_Prevista] ,[Fatura_Numero] ,[Fatura_Valor_Origem]
    ,[Fatura_Valor_Desconto] ,[Fatura_Valor]) VALUES
    (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);';

    $Parametros = array(
      $this->getCodigo_Empresa(),
      $this->getCodigo_Estabelecimento(),
      $this->getCodigo_CTE(),
      $this->getGeral_Tipo_CTE(),
      $this->getGeral_Tipo_Servico(),
      $this->getGeral_Data_Emissao(),
      $this->getGeral_CFOP(),
      $this->getGeral_Codigo_Natureza(),      
      $this->getGeral_Cidade_Origem_Codigo_IBGE(),
      $this->getGeral_Cidade_Destino_Codigo_IBGE(),
      $this->getGeral_Codigo_Remetente(),
      $this->getGeral_Codigo_Destinatario(),
      $this->getGeral_Codigo_Tomador(),
      $this->getCarga_Valor(),
      $this->getCarga_Produto_Predominante(),
      $this->getCarga_Outras_Caracteristicas(),
      $this->getRodoviario_RNTRC(),
      $this->getCobranca_Servico_Valor_Total_Servico(),
      $this->getCobranca_Servico_Valor_Receber(),
      $this->getCobranca_Servico_Forma_Pagamento(),
      $this->getCobranca_Servico_Valor_Aproximado_Tributos(),
      $this->getCobranca_ICMS_CST(),
      $this->getCobranca_ICMS_Base(),
      $this->getCobranca_ICMS_Aliquota(),
      $this->getCobranca_ICMS_Valor(),      
      $this->getCobranca_ICMS_Percentual_Reducao_Base_Calculo(),
      $this->getCobranca_ICMS_Credito(),
      $this->getCobranca_Partilha_ICMS_Valor_Base_Calculo(),
      $this->getCobranca_Partilha_ICMS_Aliquota_Interna_UF_Termino(),
      $this->getCobranca_Partilha_ICMS_Aliquota_Interestadual(),
      $this->getCobranca_Partilha_ICMS_Porcentagem_Partilha_UF_Termino(),
      $this->getCobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Inicio(),
      $this->getCobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Termino(),
      $this->getCobranca_Partilha_ICMS_Porcentagem_ICMS_FCP_UF_Termino(),
      $this->getCobranca_Partilha_ICMS_Valor_ICMS_FCP_UF_Termino(),
      $this->getCobranca_Observacoes_Gerais(),
      $this->getCobranca_Entrega_Prevista(),
      $this->getFatura_Numero(),
      $this->getFatura_Valor_Origem(),
      $this->getFatura_Valor_Desconto(),
      $this->getFatura_Valor()
    );

    return array(
      'Query' => $Query,
      'Parametros' => $Parametros
    );
  }

}   

?>