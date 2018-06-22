<?php

    include("app.php");
    
    protectPage();
    
    include(required('header'));

    include(required('menu'));

    $id = (isset($_GET['id']) ? $_GET['id'] : '0' );

?>  
 
<div class="container marketing" ng-controller="CTE.Controller as clcte" ng-init="load(<?php echo $id; ?>)">

    <div class="loading-global ng-hide" ng-show="loading"><p>Carregando...</p></div>

    <div class="success-global ng-hide" ng-show="success"><p>CTE salvo com sucesso, redirecionando...</p></div>

    <div class="row mt-3">
        <div class="col-md-6">
            <h1 class="Titulos">Cadastro de CTE</h1>
        </div>
        <div class="col-md-6 text-right">
            <?php  // echo ($id != '0' ? '<button class="btn btn-primary" type="button" ng-click="emitir_cte()">Emitir CTE</button>' : 'Para gerar o CTE, salve as informações.'); ?>
            
        </div>
    </div>

    <div class="alert alert-danger ng-hide" ng-show="cte_error">
      {{ cte_error_data.retorno.XMotivo }}
    </div>

    <div class="card">

        <div class="card-body"> 

            <form name="CadastroCTE" action="../Functions/Cadastrar_CTE.php" method="POST">

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#informacoes-gerais" role="tab" aria-controls="informacoes-gerais" aria-selected="true">
                            Informações gerais
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"  data-toggle="tab" href="#informacoes-carga" role="tab" aria-controls="informacoes-carga" aria-selected="true">
                            Informações de carga
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#modal-rodoviario" role="tab" aria-controls="modal-rodoviario" aria-selected="true">
                            Modal Rodoviário
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#documentos" role="tab" aria-controls="documentos" aria-selected="true">
                            Documentos
                        </a>
                    </li>
                    <!-- 
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#seguros" role="tab" aria-controls="seguros" aria-selected="true">
                            Seguros
                        </a>
                    </li>
                    -->
                    <!-- 
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#informacoes-cobranca" role="tab" aria-controls="informacoes-cobranca" aria-selected="true">
                            Informações de Cobrança
                        </a>
                    </li>
                    -->
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#fatura" role="tab" aria-controls="fatura" aria-selected="true">
                            Fatura
                        </a>
                    </li>
                </ul>

                <div class="tab-content margin-top-30" id="myTabContent">
                    <div class="tab-pane fade show active" id="informacoes-gerais" role="tabpanel" aria-labelledby="informacoes-gerais-tab">

                        <div class="form row">

                            <div class="form-group col-md-12 ng-hide">
                                <label for="inputGeral_Numero_CTE">Número do CTE</label>
                                <input type="text" class="form-control" id="inputGeral_Numero_CTE" name="Numero_CTE" ng-model="cte.informacoes_gerais.numero_cte" ng-readonly="cte.return_acbr != 0 || cte.is_update == '1'">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="inputGeral_Tipo_CTE">Tipo de CTE</label>
                                <select id="inputGeral_Tipo_CTE" class="form-control" name="Geral_Tipo_CTE" ng-model="cte.informacoes_gerais.Geral_Tipo_CTE" ng-readonly="cte.return_acbr != 0">
                                    <option selected value = "1">CT-e Normal</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="inputGeral_Tipo_Servico">Tipo de Serviço</label>
                                <select id="inputGeral_Tipo_Servico" class="form-control" name="Geral_Tipo_Servico" ng-model="cte.informacoes_gerais.Geral_Tipo_Servico" ng-readonly="cte.return_acbr != 0">
                                    <option value = "1" selected >Normal</option>
                                </select>
                            </div>

                        </div>
                        <div class="row">

                            <div class="form-group col-md-6">
                                <label for="inputGeral_Data_Emissao_Data">Data de Emissão</label>
                                <input type="text" class="form-control" id="inputGeral_Data_Emissao_Data" name="Geral_Data_Emissao_Data" ng-model="cte.informacoes_gerais.Geral_Data_Emissao_Data" ng-readonly="cte.return_acbr != 0" mask="99/99/9999">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="inputGeral_Data_Emissao_Hora">Hora da Emissão</label>
                                <input type="text" class="form-control" id="inputGeral_Data_Emissao_Hora" name="Geral_Data_Emissao_Hora" ng-model="cte.informacoes_gerais.Geral_Data_Emissao_Hora" placeholder="HH:mm" ng-readonly="cte.return_acbr != 0" mask="99:99">
                            </div>

                        </div>

                        <div class="form-row">
                          
                          <div class="form-group col-md-3">
                            <label for="inputGeral_CFOP">CFOP</label>
                            
                            <div class="input-group">
                                <input type="text" class="form-control open-cfop-modal" id="inputGeral_CFOP" name="Geral_CFOP" ng-model="cte.informacoes_gerais.Geral_CFOP" ng-readonly="cte.return_acbr != 0" mask="9.999" clean="true">
                                <div class="input-group-append">
                                  <button class="btn btn-primary" data-toggle="modal" data-target="#cfop-modal" type="button"><i class="fas fa-search"></i></button>
                                </div>
                              </div>
                          </div>
                          <div class="form-group col-md-9">
                            <label for="inputGeral_CFOP">Descrição do CFOP</label>
                            <input type="text" class="form-control open-cfop-modal" id="inputGeral_CFOP" name="Geral_CFOP" ng-model="cte.informacoes_gerais.Geral_CFOP_desc" ng-readonly="cte.return_acbr != 0">
                          </div>



                          <div class="form-group col-md-6 d-none"> 
                          <label for="inputGeral_Natureza">Natureza</label>
                            <input type="text" class="form-control" id="inputGeral_Natureza" name="Geral_Natureza" ng-model="cte.informacoes_gerais.Geral_Natureza" ng-readonly="cte.return_acbr != 0">
                            <small>Utilizar 1 -  Prestação de Serviços</small>
                            <div class="input-group">
                              <!--                    
                              <select id="inputGeral_Natureza" class="form-control" name="Geral_Natureza">
                                <?php 
                                  /*
                                  Include_once("../Functions/Listar_Natureza.php"); 
                                  $ListaNaturezas = ListarNatureza();

                                  foreach ($ListaNaturezas as &$Natureza) {
                                    echo '<option value = "'.$Natureza[0].'">'.$Natureza[1].'</option>';
                                  }
                                  */
                                ?>
                              </select>
                              -->                      
                            </div>
                          </div>
                        </div>

                        <hr />

                        <div class="row">
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="inputGeral_Cidade_Origem">Cidade do início da Prestação</label>

                              <div class="input-group">
                                <input type="text" class="form-control" id="inputGeral_Cidade_Origem" name="Geral_Cidade_Origem" placeholder="Município" ng-model="cte.informacoes_gerais.Geral_Cidade_Origem" readonly>
                                <div class="input-group-append">
                                  <button class="btn btn-primary" type="button" ng-click="choose_municipio('cidade_inicio_prestacao')"><i class="fas fa-search"></i></button>
                                </div>
                              </div>

                            </div>

                          </div>
                          <div class="col-md-4 d-none">

                            <div class="form-group">
                              <label for="inputGeral_Cidade_Origem_Code">Cód. do município</label>
                              <input type="text" class="form-control" id="inputGeral_Cidade_Origem_Code" name="Geral_Cidade_Origem_Code" placeholder="Inserir código do IBGE" ng-model="cte.informacoes_gerais.Geral_Cidade_Origem_Code" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-2">

                            <div class="form-group">
                              <label for="Geral_Cidade_Origem_UF">UF</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Origem_UF" name="Geral_Cidade_Origem_UF" placeholder="UF" ng-model="cte.informacoes_gerais.Geral_Cidade_Origem_UF" readonly>
                            </div>

                          </div>

                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino">Cidade do final da Prestação</label>
                              <div class="input-group">
                                <input type="text" class="form-control" id="Geral_Cidade_Destino" name="Geral_Cidade_Destino" placeholder="Município" ng-model="cte.informacoes_gerais.Geral_Cidade_Destino" readonly>
                                <div class="input-group-append">
                                  <button class="btn btn-primary" type="button" ng-click="choose_municipio('cidade_fim_prestacao')"><i class="fas fa-search"></i></button>
                                </div>
                              </div>
                              
                            </div>

                          </div>
                          <div class="col-md-4 d-none">

                            <div class="form-group">
                              <label for="inputGeral_Cidade_Origem_Code">Cód. do município</label>
                              <input type="text" class="form-control" id="inputGeral_Cidade_Destino_Code" name="Geral_Cidade_Destino_Code" placeholder="Inserir código do IBGE" ng-model="cte.informacoes_gerais.Geral_Cidade_Destino_Code" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-2">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">UF</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" placeholder="UF" ng-model="cte.informacoes_gerais.Geral_Cidade_Destino_UF" readonly>
                            </div>

                          </div>
                        </div>

                        <hr />

                        <h5>Tomador</h5>

                        <button class="btn btn-primary btn-sm open-tomador-modal">Selecionar tomador</button>

                        <div class="row margin-top-30">
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Nome</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.tomador.nome" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">CNPJ</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.tomador.cnpj" ng-readonly="cte.return_acbr != 0" mask="99.999.999/9999-99" clean="true">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">IE</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.tomador.ie" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Logradouro</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.tomador.logradouro" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Número</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.tomador.numero" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_Complemento">Complemento</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_Complemento" name="Geral_Cidade_Destino_Complemento" ng-model="cte.informacoes_gerais.tomador.complemento" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Bairro</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.tomador.bairro" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4 d-none">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Código cidade</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.tomador.codigo_cidade" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Cidade</label>
                              <div class="input-group">
                                <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.tomador.cidade" readonly required>
                                <div class="input-group-append">
                                  <button class="btn btn-primary" type="button" ng-click="choose_municipio('cidade_tomador')"><i class="fas fa-search"></i></button>
                                </div>
                              </div>
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">CEP</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.tomador.cep" ng-readonly="cte.return_acbr != 0" mask="99999-999" clean="true">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Estado</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.tomador.estado" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Pais</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.tomador.pais" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                           <div class="col-md-4 d-none">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Código Pais</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.tomador.codigo_pais" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                        </div>

                        <hr />

                        <h5>Remetente</h5>

                        <button class="btn btn-primary btn-sm open-remetente-modal">Selecionar remetente</button>
                        
                        <div class="row margin-top-30">
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Nome</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.remetente.nome" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">CNPJ</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.remetente.cnpj" ng-readonly="cte.return_acbr != 0" mask="99.999.999/9999-99" clean="true">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">IE</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.remetente.ie" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Logradouro</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.remetente.logradouro" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Número</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.remetente.numero" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_Complemento">Complemento</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_Complemento" name="Geral_Cidade_Destino_Complemento" ng-model="cte.informacoes_gerais.remetente.complemento" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Bairro</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.remetente.bairro" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4 d-none">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Código cidade</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.remetente.codigo_cidade" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Cidade</label>
                              
                              <div class="input-group">
                                <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.remetente.cidade" readonly required>
                                <div class="input-group-append">
                                  <button class="btn btn-primary" type="button" ng-click="choose_municipio('cidade_remetente')"><i class="fas fa-search"></i></button>
                                </div>
                              </div>
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">CEP</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.remetente.cep" ng-readonly="cte.return_acbr != 0" mask="99999-999" clean="true">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Estado</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.remetente.estado" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Pais</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.remetente.pais" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                           <div class="col-md-4 d-none">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Código Pais</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.remetente.codigo_pais" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                        </div>

                        <hr />

                        <h5>Destinatário</h5>

                        <button class="btn btn-primary btn-sm open-destinatario-modal">Selecionar destinatário</button>
                        
                        <div class="row margin-top-30">
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Nome</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.destinatario.nome" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">CNPJ</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.destinatario.cnpj" ng-readonly="cte.return_acbr != 0" mask="99.999.999/9999-99" clean="true">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">IE</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.destinatario.ie" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Logradouro</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.destinatario.logradouro" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Número</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.destinatario.numero" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_Complemento">Complemento</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_Complemento" name="Geral_Cidade_Destino_Complemento" ng-model="cte.informacoes_gerais.destinatario.complemento" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Bairro</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.destinatario.bairro" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4 d-none">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Código cidade</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.destinatario.codigo_cidade" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Cidade</label>
                              
                              <div class="input-group">
                                <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.destinatario.cidade" readonly required>
                                <div class="input-group-append">
                                  <button class="btn btn-primary" type="button" ng-click="choose_municipio('cidade_destinatario')"><i class="fas fa-search"></i></button>
                                </div>
                              </div>
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">CEP</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.destinatario.cep" ng-readonly="cte.return_acbr != 0" mask="99999-999" clean="true">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Estado</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.destinatario.estado" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                          <div class="col-md-4">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Pais</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.destinatario.pais" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                           <div class="col-md-4 d-none">

                            <div class="form-group">
                              <label for="Geral_Cidade_Destino_UF">Código Pais</label>
                              <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.informacoes_gerais.destinatario.codigo_pais" ng-readonly="cte.return_acbr != 0">
                            </div>

                          </div>
                        </div>

                    
                    </div>
                    <div class="tab-pane fade" id="informacoes-carga" role="tabpanel" aria-labelledby="informacoes-carga-tab">
                        <div class="form-row">
                          <div class="form-group col-md-4">
                            <label for="inputCarga_Produto_Predominante">Produto Predominante</label>
                            
                            <div class="input-group">
                              <input type="text" class="form-control" id="inputCarga_Produto_Predominante" name="Carga_Produto_Predominante" ng-model="cte.informacoes_carga.Carga_Produto_Predominante" ng-readonly="cte.return_acbr != 0">
                              <div class="input-group-append">
                                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#produto-modal"><i class="fas fa-search"></i></button>
                              </div>
                            </div>

                          </div>
                          <div class="form-group col-md-4">
                            <label for="inputCarga_Valor">Valor da Carga</label>
                            <input type="text" class="form-control" id="inputCarga_Valor" name="Carga_Valor" ng-model="cte.informacoes_carga.Carga_Valor" ng-readonly="cte.return_acbr != 0">
                          </div>
                          <div class="form-group col-md-4">
                            <label for="inpuCarga_Outra_Caracteristicas">Outras Características do Produto</label>
                            <input type="text" class="form-control" id="inpuCarga_Outra_Caracteristicas" name="Carga_Outra_Caracteristicas" ng-model="cte.informacoes_carga.Carga_Outra_Caracteristicas" ng-readonly="cte.return_acbr != 0">
                          </div>
                        </div>

                        <div class="card">
                          <div class="card-header">
                            Quantidades da Carga                 
                          </div>
                          <div class="card-body">               
                            
                            <div class="form-row">
                              <div class="form-group col-md-3"><label>Codigo Unidade de Medida</label></div>
                              <div class="form-group col-md-6"><label>Tipo de Medida</label></div>
                              <div class="form-group col-md-2"><label>Quantidade</label></div>                            
                            </div>

                            <div class="form-row" ng-repeat="(key, val) in cte.informacoes_carga.quantidades track by key">
                              <div class="form-group col-md-3">
                                <input type="text" class="form-control" id="inputCodigo_Unidade_Medida0" name="Codigo_Unidade_Medida" ng-model="cte.informacoes_carga.quantidades[key].Codigo_Unidade_Medida" ng-readonly="cte.return_acbr != 0">
                              </div>
                              <div class="form-group col-md-6">
                                <input type="text" class="form-control" id="inputTipo_Medida0" name="Tipo_Medida" ng-model="cte.informacoes_carga.quantidades[key].Tipo_Medida" ng-readonly="cte.return_acbr != 0">
                              </div>
                              <div class="form-group col-md-2">
                                <input type="text" class="form-control" id="inputQuantidade_Carga0" name="Quantidade_Carga" ng-model="cte.informacoes_carga.quantidades[key].Quantidade_Carga" ng-readonly="cte.return_acbr != 0">
                              </div>
                              <div class="form-group col-md-1">
                                <button class="btn btn-outline-danger form-control" type="button" ng-click="less_field(key, cte.informacoes_carga.quantidades, 'struct_informacoes_carga_quantidade', false)">-</button>
                              </div>
                            </div>
                            
                            <button class="btn btn-outline-success form-control" type="button" ng-click="plus_field(cte.informacoes_carga.quantidades, 'struct_informacoes_carga_quantidade', false)">+</button>

                          </div>
                        </div></div>
                    <div class="tab-pane fade" id="modal-rodoviario" role="tabpanel" aria-labelledby="modal-rodoviario-tab">
                        <div class="form-row">
                          <div class="form-group col-md-4">
                            <label for="inputRodoviario_RNTRC">RNTRC</label>
                            <input type="text" class="form-control" id="inputRodoviario_RNTRC" name="Rodoviario_RNTRC" ng-model="cte.modal_rodoviario.Rodoviario_RNTRC" ng-readonly="cte.return_acbr != 0">
                          </div>                       
                        </div>

                        <hr />

                        <div class="form-row">
                          <div class="form-group col-md-4">
                            <h5>Veículos</h5>           
                          </div>                       
                        </div>

                        <p>Para a versão de teste, os campos abaixo não são obrigatórios.</p>

                        <div class="form-row">
                          <div class="form-group col-md-2"><label>Codigo</label></div>
                          <div class="form-group col-md-2"><label>Renavam</label></div>
                          <div class="form-group col-md-2"><label>Placa</label></div>
                          <div class="form-group col-md-2"><label>Capacidade (Kg)</label></div>
                          <div class="form-group col-md-2"><label>Capacidade (m3)</label></div>
                          <div class="form-group col-md-1"><label>UF Veiculo</label></div>                         
                        </div>

                        <div class="form-row" ng-repeat="(key, val) in cte.modal_rodoviario.veiculos track by key">
                          <div class="form-group col-md-2">
                            <input type="text" class="form-control" id="inputRodoviario_Codigo_Veiculo1" name="Rodoviario_Codigo_Veiculo" ng-model="cte.modal_rodoviario.veiculos[key].Rodoviario_Codigo_Veiculo" ng-readonly="cte.return_acbr != 0">
                          </div>
                          <div class="form-group col-md-2">
                            <input type="text" class="form-control" id="inputRodoviario_Renavam1" name="Rodoviario_Renavam" ng-model="cte.modal_rodoviario.veiculos[key].Rodoviario_Renavam" ng-readonly="cte.return_acbr != 0">
                          </div>
                          <div class="form-group col-md-2">
                            <input type="text" class="form-control" id="inputRodoviario_Palca1" name="Rodoviario_Palca" ng-model="cte.modal_rodoviario.veiculos[key].Rodoviario_Palca" ng-readonly="cte.return_acbr != 0">
                          </div>                        
                          <div class="form-group col-md-2">
                            <input type="text" class="form-control" id="inputRodoviario_Capacidade_KG1" name="Rodoviario_Capacidade_KG" ng-model="cte.modal_rodoviario.veiculos[key].Rodoviario_Capacidade_KG" ng-readonly="cte.return_acbr != 0">
                          </div>
                          <div class="form-group col-md-2">
                            <input type="text" class="form-control" id="inputRodoviario_Capacidade_M31" name="Rodoviario_Capacidade_M3" ng-model="cte.modal_rodoviario.veiculos[key].Rodoviario_Capacidade_M3" ng-readonly="cte.return_acbr != 0">
                          </div>
                          <div class="form-group col-md-1">
                            <input type="text" class="form-control" id="inputRodoviario_UF_Veiculo1" name="Rodoviario_UF_Veiculo" ng-model="cte.modal_rodoviario.veiculos[key].Rodoviario_UF_Veiculo" ng-readonly="cte.return_acbr != 0">
                          </div>                       
                          <div class="form-group col-md-1">
                            <button class="btn btn-outline-danger form-control" type="button" ng-click="less_field(key, cte.modal_rodoviario.veiculos, 'struct_modal_rodoviario_veiculos', false)">-</button>
                          </div>
                        </div>

                        <button class="btn btn-outline-success form-control" type="button" ng-click="plus_field(cte.modal_rodoviario.veiculos, 'struct_modal_rodoviario_veiculos', false)">+</button>

                        <hr />

                        <div class="form-row">
                          <div class="form-group col-md-4">
                            <h5>Motoristas</h5>   
                            <input type="hidden" class="form-control" id="inputRodoviario_Quantidade_Motoristas" name="Rodoviario_Quantidade_Motoristas" value = 1>          
                          </div>                       
                        </div>

                        <p>Para a versão de teste, os campos abaixo não são obrigatórios.</p>


                        <div class="form-row">
                          <div class="form-group col-md-1"><label>Codigo</label></div>
                          <div class="form-group col-md-7"><label>Nome</label></div>
                          <div class="form-group col-md-4"><label>CPF</label></div>                      
                        </div>

                        <div class="form-row" ng-repeat="(key, val) in cte.modal_rodoviario.motoristas track by key">
                          <div class="form-group col-md-1">
                            <input type="text" class="form-control" id="inputRodoviario_Codigo_Motorista1" name="Rodoviario_Codigo_Motorista" ng-model="cte.modal_rodoviario.motoristas[key].Rodoviario_Codigo_Motorista" ng-readonly="cte.return_acbr != 0">
                          </div>
                          <div class="form-group col-md-7">
                            <input type="text" class="form-control" id="inputRodoviario_Nome_Motorista1" name="Rodoviario_Nome_Motorista" ng-model="cte.modal_rodoviario.motoristas[key].Rodoviario_Nome_Motorista" ng-readonly="cte.return_acbr != 0">
                          </div>
                          <div class="form-group col-md-3">
                            <input type="text" class="form-control" id="inputRodoviario_CPF_Motorista1" name="Rodoviario_CPF_Motorista" ng-model="cte.modal_rodoviario.motoristas[key].Rodoviario_CPF_Motorista" ng-readonly="cte.return_acbr != 0">
                          </div>                     
                          <div class="form-group col-md-1">
                            <button class="btn btn-outline-danger form-control" type="button" ng-click="less_field(key, cte.modal_rodoviario.motoristas, 'struct_modal_rodoviario_motoristas', false)">-</button>
                          </div>
                        </div>

                        <button class="btn btn-outline-success form-control" type="button" ng-click="plus_field(cte.modal_rodoviario.motoristas, 'struct_modal_rodoviario_motoristas', false)">+</button>    

                    </div>
                    <div class="tab-pane fade" id="documentos" role="tabpanel" aria-labelledby="documentos-tab">

                      <h5>Nota fiscal eletrônica</h5>


                      <div class="row" ng-repeat="(key, val) in cte.documentos track by key">
                        <div class="col-md-9">

                          <div class="form-group">
                            <label for="Geral_Cidade_Destino_UF">Chave nota fiscal eletronica</label>
                            <input type="text" class="form-control" id="Geral_Cidade_Destino_UF" name="Geral_Cidade_Destino_UF" ng-model="cte.documentos[key].chave" ng-readonly="cte.return_acbr != 0">
                          </div>

                        </div>
                        <div class="col-md-3">
                          <button class="btn btn-outline-danger form-control mt-4" type="button" ng-click="less_field(key, cte.documentos, 'struct_modal_documentos', false)">-</button>
                        </div>
                      </div>

                      <button class="btn btn-outline-success form-control" type="button" ng-click="plus_field(cte.documentos, 'struct_modal_documentos', false)">+</button>

                    </div>
                    <div class="tab-pane fade" id="seguros" role="tabpanel" aria-labelledby="seguros-tab">

                        <input type="hidden" class="form-control" id="inputSeguro_Quantidade_Seguros" name="Seguro_Quantidade_Seguros" value = 1>

                        <div class="form-row">
                          <div class="form-group col-md-2"><label>Responsável</label></div>
                          <div class="form-group col-md-3"><label>Nome da Seguradora</label></div>
                          <div class="form-group col-md-2"><label>Número da Apolice</label></div>
                          <div class="form-group col-md-2"><label>Número da Averbação</label></div>
                          <div class="form-group col-md-2"><label>Valor da Carga</label></div>
                        </div>

                        <div class="form-row" ng-repeat="(key, val) in cte.seguros track by key">
                          <div class="form-group col-md-2">
                            <select id="inputSeguro_Responsavel_Seguro1" class="form-control" name="Seguro_Responsavel_Seguro" ng-model="cte.seguros[key].Seguro_Responsavel_Seguro">
                              <option value = "0" selected >Remetente</option>
                              <option value = "1">Destinatário</option>
                              <option value = "2">Tomador</option>
                            </select>
                          </div>          
                          <div class="form-group col-md-3">
                            <input type="text" class="form-control" id="inputSeguro_Nome_Seguradora1" name="Seguro_Nome_Seguradora" ng-model="cte.seguros[key].Seguro_Nome_Seguradora">
                          </div>
                          <div class="form-group col-md-2">
                            <input type="text" class="form-control" id="inputSeguro_Numero_Apolice1" name="Seguro_Numero_Apolice" ng-model="cte.seguros[key].Seguro_Numero_Apolice">
                          </div>
                          <div class="form-group col-md-2">
                            <input type="text" class="form-control" id="inputSeguro_Numero_Averbacao1" name="Seguro_Numero_Averbacao" ng-model="cte.seguros[key].Seguro_Numero_Averbacao">
                          </div>
                          <div class="form-group col-md-2">
                            <input type="text" class="form-control" id="inputSeguro_Valor_Carga_Efeito_Averbacao1" name="Seguro_Valor_Carga_Efeito_Averbacao" ng-model="cte.seguros[key].Seguro_Valor_Carga_Efeito_Averbacao">
                          </div>
                          <div class="form-group col-md-1">
                            <button class="btn btn-outline-danger form-control" type="button" ng-click="less_field(key, cte.seguros, 'struct_seguros', false)">-</button>
                          </div>
                        </div>
                        
                        <button class="btn btn-outline-success form-control" type="button" ng-click="plus_field(cte.seguros, 'struct_seguros', false)">+</button>   

                    </div>
                    <div class="tab-pane fade" id="informacoes-cobranca" role="tabpanel" aria-labelledby="informacoes-cobranca-tab">

                        <div class="form-row">                        
                          <div class="form-group col-md-3">
                            <label for="inputCobranca_Servico_Valor_Total_Servico">Valor total do Serviço</label>
                            <input type="text" class="form-control" id="inputCobranca_Servico_Valor_Total_Servico" name="Cobranca_Servico_Valor_Total_Servico" ng-model="cte.informacoes_cobranca.Cobranca_Servico_Valor_Total_Servico"  ng-readonly="cte.return_acbr != 0">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="inputCobranca_Servico_Valor_Receber">Valor a Receber</label>
                            <input type="text" class="form-control" id="inputCobranca_Servico_Valor_Receber" name="Cobranca_Servico_Valor_Receber" ng-model="cte.informacoes_cobranca.Cobranca_Servico_Valor_Receber">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="inputCobranca_Servico_Valor_Aproximado_Tributos">Valor aproximado dos tributos</label>
                            <input type="text" class="form-control" id="inputCobranca_Servico_Valor_Aproximado_Tributos" name="Cobranca_Servico_Valor_Aproximado_Tributos" ng-model="cte.informacoes_cobranca.Cobranca_Servico_Valor_Aproximado_Tributos">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="inputCobranca_Forma_Pagamento">Forma de Pagamento</label>
                            <select id="inputCobranca_Forma_Pagamento" class="form-control" name="Cobranca_Forma_Pagamento" ng-model="cte.informacoes_cobranca.Cobranca_Forma_Pagamento">
                              <option value = "1" selected >Normal</option>
                              <option value = "2">Subcontratação</option>
                            </select>
                          </div>
                        </div>

                        <hr />

                        <div class="form-row">                        
                          <div class="form-group col-md-3">
                            <label for="inputCobranca_ICMS_CST">CST ICMS</label>
                            <input type="text" class="form-control" id="inputCobranca_ICMS_CST" name="Cobranca_ICMS_CST" ng-model="cte.informacoes_cobranca.Cobranca_ICMS_CST">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="inputCobranca_ICMS_Base">Base de Cálculo ICMS</label>
                            <input type="text" class="form-control" id="inputCobranca_ICMS_Base" name="Cobranca_ICMS_Base" ng-model="cte.informacoes_cobranca.Cobranca_ICMS_Base">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="inputCobranca_ICMS_Aliquota">Alíquota ICMS</label>
                            <input type="text" class="form-control" id="inputCobranca_ICMS_Aliquota" name="Cobranca_ICMS_Aliquota" ng-model="cte.informacoes_cobranca.Cobranca_ICMS_Aliquota">
                          </div>
                          <div class="form-group col-md-3">
                            <label for="inputCobranca_ICMS_Valor">Valor ICMS</label>
                            <input type="text" class="form-control" id="inputCobranca_ICMS_Valor" name="Cobranca_ICMS_Valor" ng-model="cte.informacoes_cobranca.Cobranca_ICMS_Valor">
                          </div>
                        </div>
                        <div class="form-row">                        
                          <div class="form-group col-md-6">
                            <label for="inputCobranca_Percentual_Reducao_Base_Calculo_ICMS">Percentual de Redução da Base de Cálculo ICMS</label>
                            <input type="text" class="form-control" id="inputCobranca_Percentual_Reducao_Base_Calculo_ICMS" name="Cobranca_ICMS_Percentual_Reducao_Base_Calculo_ICMS" ng-model="cte.informacoes_cobranca.Cobranca_ICMS_Percentual_Reducao_Base_Calculo_ICMS">
                          </div>
                          <div class="form-group col-md-6">
                            <label for="inputCobranca_ICMS_Credito_ICMS">Crédito ICMS</label>
                            <input type="text" class="form-control" id="inputCobranca_ICMS_Credito_ICMS" name="Cobranca_ICMS_Credito_ICMS" ng-model="cte.informacoes_cobranca.Cobranca_ICMS_Credito_ICMS">
                          </div>
                        </div>

                        <hr />

                        <div class="form-row">                           
                          <div class="form-group col-md-6">
                            <label for="inputCobranca_Partilha_ICMS_Aliquota_Interna_UF_Termino">Alíquota interna da UF de término</label>
                            <input type="text" class="form-control" id="inputCobranca_Partilha_ICMS_Aliquota_Interna_UF_Termino" name="Cobranca_Partilha_ICMS_Aliquota_Interna_UF_Termino" ng-model="cte.informacoes_cobranca.Cobranca_Partilha_ICMS_Aliquota_Interna_UF_Termino">
                          </div>
                          <div class="form-group col-md-6">
                            <label for="inputCobranca_Partilha_ICMS_Aliquota_Interestadual">Alíquota interestadual</label>
                            <input type="text" class="form-control" id="inputCobranca_Partilha_ICMS_Aliquota_Interestadual" name="Cobranca_Partilha_ICMS_Aliquota_Interestadual" ng-model="cte.informacoes_cobranca.Cobranca_Partilha_ICMS_Aliquota_Interestadual">
                          </div>
                          <div class="form-group col-md-4">
                            <label for="inputCobranca_Partilha_ICMS_Porcentagem_Partilha_UF_Termino">% de partilha para a UF de término</label>
                            <input type="text" class="form-control" id="inputCobranca_Partilha_ICMS_Porcentagem_Partilha_UF_Termino" name="Cobranca_Partilha_ICMS_Porcentagem_Partilha_UF_Termino" ng-model="cte.informacoes_cobranca.Cobranca_Partilha_ICMS_Porcentagem_Partilha_UF_Termino">
                          </div>                                         
                          <div class="form-group col-md-4">
                            <label for="inputCobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Termino">Valor do ICMS de partilha para a UF de término</label>
                            <input type="text" class="form-control" id="inputCobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Termino" name="Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Termino" ng-model="cte.informacoes_cobranca.Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Termino">
                          </div>
                          <div class="form-group col-md-4">
                            <label for="inputCobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Inicio">Valor do ICMS de partilha para a UF de início</label>
                            <input type="text" class="form-control" id="inputCobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Inicio" name="Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Inicio" ng-model="cte.informacoes_cobranca.Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Inicio">
                          </div>
                          <div class="form-group col-md-4">
                            <label for="inputCobranca_Partilha_ICMS_Porcentagem_ICMS_FCP_UF_Termino">% de ICMS ao F.C.P na UF de término</label>
                            <input type="text" class="form-control" id="inputCobranca_Partilha_ICMS_Porcentagem_ICMS_FCP_UF_Termino" name="Cobranca_Partilha_ICMS_Porcentagem_ICMS_FCP_UF_Termino" ng-model="cte.informacoes_cobranca.Cobranca_Partilha_ICMS_Porcentagem_ICMS_FCP_UF_Termino">
                          </div>
                          <div class="form-group col-md-4">
                            <label for="inputCobranca_Partilha_ICMS_Valor_ICMS_FCP_UF_Termino">Valor de ICMS ao F.C.P na UF de término</label>
                            <input type="text" class="form-control" id="inputCobranca_Partilha_ICMS_Valor_ICMS_FCP_UF_Termino" name="Cobranca_Partilha_ICMS_Valor_ICMS_FCP_UF_Termino" ng-model="cte.informacoes_cobranca.Cobranca_Partilha_ICMS_Valor_ICMS_FCP_UF_Termino">
                          </div>
                          <div class="form-group col-md-4">
                            <label for="inputCobranca_Partilha_ICMS_Valor_Base_Calculo">Valor da base de cálculo do ICMS</label>
                            <input type="text" class="form-control" id="inputCobranca_Partilha_ICMS_Valor_Base_Calculo" name="Cobranca_Partilha_ICMS_Valor_Base_Calculo" ng-model="cte.informacoes_cobranca.Cobranca_Partilha_ICMS_Valor_Base_Calculo">
                          </div>                
                        </div> 

                        <hr />

                        <div class="form-row">                           
                          <div class="form-group col-md-12">
                            <label for="inputCobranca_Observacoes_Gerais">Observações Gerais</label>
                            <textarea class="form-control" id="inputCobranca_Observacoes_Gerais" rows="3" name="Cobranca_Observacoes_Gerais" ng-model="cte.informacoes_cobranca.Cobranca_Observacoes_Gerais"></textarea>
                          </div>
                        </div>         

                        <div class="form-row">                           
                          <div class="form-group col-md-2">
                            <label for="inputCobranca_Entrega_Prevista">Entrega Prevista</label>
                              <input type="text" class="form-control" id="inputCobranca_Entrega_Prevista" name="Cobranca_Entrega_Prevista" ng-model="cte.informacoes_cobranca.Cobranca_Entrega_Prevista">
                          </div>
                        </div> </div>
                    <div class="tab-pane fade" id="fatura" role="tabpanel" aria-labelledby="fatura-tab">

                        <p>Para a versão de teste, os campos abaixo não são obrigatórios.</p>

                        <div class="form-row">
                        <div class="form-group col-md-3">
                          <label for="inputFatura_Numero">Numero</label>
                          <input type="text" class="form-control" id="inputFatura_Numero" name="Fatura_Numero" ng-model="cte.fatura.Fatura_Numero" ng-readonly="cte.return_acbr != 0">
                        </div>     
                        <div class="form-group col-md-3">
                          <label for="inputFatura_Valor_Origem">Valor Origem</label>
                          <input type="text" class="form-control" id="inputFatura_Valor_Origem" name="Fatura_Valor_Origem" ng-model="cte.fatura.Fatura_Valor_Origem" ng-readonly="cte.return_acbr != 0">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputFatura_Valor_Desconto">Valor Desconto</label>
                          <input type="text" class="form-control" id="inputFatura_Valor_Desconto" name="Fatura_Valor_Desconto" ng-model="cte.fatura.Fatura_Valor_Desconto" ng-readonly="cte.return_acbr != 0">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="inputFatura_Valor">Valor</label>
                          <input type="text" class="form-control" id="inputFatura_Valor" name="Fatura_Valor" ng-model="cte.fatura.Fatura_Valor" ng-readonly="cte.return_acbr != 0">
                        </div>                  
                      </div>

                      <div class="card">
                        <div class="card-header">
                          Prestações                                      
                        </div>
                        <div class="card-body">           

                          <div class="form-row">
                            <div class="form-group col-md-7"><label>Descrição</label></div>
                            <div class="form-group col-md-4"><label>Valor</label></div>
                          </div>

                          <div class="form-row" ng-repeat="(key, val) in cte.fatura.prestacoes track by key">
                            <div class="form-group col-md-7">
                              <input type="text" class="form-control" id="inputPrestacao_Nome1" name="Prestacao_Nome" ng-model="cte.fatura.prestacoes[key].Prestacao_Nome" ng-readonly="cte.return_acbr != 0">
                            </div>
                            <div class="form-group col-md-4">
                              <input type="text" class="form-control" id="inputPrestacao_Valor1" name="Prestacao_Valor" ng-model="cte.fatura.prestacoes[key].Prestacao_Valor" ng-readonly="cte.return_acbr != 0">
                            </div>
                            <div class="form-group col-md-1">
                              <button class="btn btn-outline-danger form-control" type="button" ng-click="less_field(key, cte.fatura.prestacoes, 'struct_fatura_prestacoes', false)">-</button>
                            </div>
                          </div>

                          <button class="btn btn-outline-success form-control" type="button" ng-click="plus_field(cte.fatura.prestacoes, 'struct_fatura_prestacoes', false)">+</button>   

                        </div>
                      </div>  </div>
                </div>

                <hr />

                <div class="row">
                  <div class="col-md-6 text-left">
                    <a href="/registros-cte.php" class="btn btn-outline-danger">Cancelar</a>
                  </div>
                  <div class="col-md-6 text-right">
                    
                    <button class="btn btn-success" type="button" ng-click="save()" ng-show="cte.return_acbr == 0">Salvar</button>
                  </div>
                </div>

            </form>

        </div>

    </div>


  <!-- Modal -->
  <div class="modal fade" id="cfop-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Selecione o CFOP</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
          <div class="input-group mb-3">
            <input type="text" class="form-control" ng-model="search_cfop_term" placeholder="Descrição ou código">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button" ng-click="search_cfop()">Pesquisar</button>
            </div>
          </div>

          <div class="modal-data-box">

            <div class="loading text-center padding-top-30 padding-bottom-30" ng-show="cfop_loading">
              <i class="fas fa-spinner fa-spin"></i>
              Carregando...
            </div>

            <div class="modal-data-content" ng-if="cfop_data.length > 0">

              <table class="table table-striped">
                <tr>
                  <th>Descrição</th>
                  <th width="90">Opção</th>
                </tr> 
                <tr ng-repeat="(key,val) in cfop_data">
                  <td>{{ val.Descricao }}</td>
                  <td><button class="btn btn-success btn-sm" ng-click="setCFOP(key)">Selecionar</button></td>
                </tr>               
              </table>

            </div>

            <div class="alert alert-warning" ng-if="cfop_data.length == 0">
              Nenhum CFOP encontrado
            </div>

          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>


    <!-- Modal -->
  <div class="modal fade" id="tomador-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Selecione o Tomador</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
          <div class="input-group mb-3">
            <input type="text" class="form-control" ng-model="search_cliente_term" placeholder="Nome ou razão social">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button" ng-click="search_cliente()">Pesquisar</button>
            </div>
          </div>

          <div class="modal-data-box">

            <div class="loading text-center padding-top-30 padding-bottom-30" ng-show="cliente_loading">
              <i class="fas fa-spinner fa-spin"></i>
              Carregando...
            </div>

            <div class="modal-data-content" ng-if="cliente_data.length > 0">

              <table class="table table-striped">
                <tr>
                  <th>Nome</th>
                  <th>Razão Social</th>
                  <th width="90">Opção</th>
                </tr> 
                <tr ng-repeat="(key,val) in cliente_data">
                  <td>{{ val.Nome_Fantasia }}</td>
                  <td>{{ val.Razao_Social }}</td>
                  <td><button class="btn btn-success btn-sm" ng-click="setTomador(key)">Selecionar</button></td>
                </tr>               
              </table>

            </div>

            <div class="alert alert-warning" ng-if="cliente_data.length == 0">
              Nenhum cliente encontrado
            </div>

          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="remetente-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Selecione o Remetente</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
          <div class="input-group mb-3">
            <input type="text" class="form-control" ng-model="search_cliente_term" placeholder="Nome ou razão social">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button" ng-click="search_cliente()">Pesquisar</button>
            </div>
          </div>

          <div class="modal-data-box">

            <div class="loading text-center padding-top-30 padding-bottom-30" ng-show="cliente_loading">
              <i class="fas fa-spinner fa-spin"></i>
              Carregando...
            </div>

            <div class="modal-data-content" ng-if="cliente_data.length > 0">

              <table class="table table-striped">
                <tr>
                  <th>Nome</th>
                  <th>Razão Social</th>
                  <th width="90">Opção</th>
                </tr> 
                <tr ng-repeat="(key,val) in cliente_data">
                  <td>{{ val.Nome_Fantasia }}</td>
                  <td>{{ val.Razao_Social }}</td>
                  <td><button class="btn btn-success btn-sm" ng-click="setRemetente(key)">Selecionar</button></td>
                </tr>               
              </table>

            </div>

            <div class="alert alert-warning" ng-if="cliente_data.length == 0">
              Nenhum cliente encontrado
            </div>

          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal -->
  <div class="modal fade" id="destinatario-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Selecione o destinatario</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
          <div class="input-group mb-3">
            <input type="text" class="form-control" ng-model="search_cliente_term" placeholder="Nome ou razão social">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button" ng-click="search_cliente()">Pesquisar</button>
            </div>
          </div>

          <div class="modal-data-box">

            <div class="loading text-center padding-top-30 padding-bottom-30" ng-show="cliente_loading">
              <i class="fas fa-spinner fa-spin"></i>
              Carregando...
            </div>

            <div class="modal-data-content" ng-if="cliente_data.length > 0">

              <table class="table table-striped">
                <tr>
                  <th>Nome</th>
                  <th>Razão Social</th>
                  <th width="90">Opção</th>
                </tr> 
                <tr ng-repeat="(key,val) in cliente_data">
                  <td>{{ val.Nome_Fantasia }}</td>
                  <td>{{ val.Razao_Social }}</td>
                  <td><button class="btn btn-success btn-sm" ng-click="setDestinatario(key)">Selecionar</button></td>
                </tr>               
              </table>

            </div>

            <div class="alert alert-warning" ng-if="cliente_data.length == 0">
              Nenhum cliente encontrado
            </div>

          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>



  <!-- Modal -->
  <div class="modal fade" id="municipio-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Selecione o município</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
          <div class="input-group mb-3">
            <input type="text" class="form-control" ng-model="search_municipio_term" placeholder="Nome do município">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button" ng-click="search_municipio()">Pesquisar</button>
            </div>
          </div>

          <div class="modal-data-box">

            <div class="loading text-center padding-top-30 padding-bottom-30" ng-show="municipio_loading">
              <i class="fas fa-spinner fa-spin"></i>
              Carregando...
            </div>

            <div class="modal-data-content" ng-if="municipio_data.length > 0">

              <table class="table table-striped">
                <tr>
                  <th>Município</th>
                  <th>Estado</th>
                  <th width="90">Opção</th>
                </tr> 
                <tr ng-repeat="(key,val) in municipio_data">
                  <td>{{ val.nome }}</td>
                  <td>{{ val.estado }}</td>
                  <td><button class="btn btn-success btn-sm" ng-click="selectMunicipio(key)">Selecionar</button></td>
                </tr>               
              </table>

            </div>

            <div class="alert alert-warning" ng-if="municipio_data.length == 0">
              Nenhum município encontrado
            </div>

          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>


    <!-- Modal -->
  <div class="modal fade" id="produto-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Selecione o produto</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
          <div class="input-group mb-3">
            <input type="text" class="form-control" ng-model="search_produto_term" placeholder="Descrição do produto">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button" ng-click="search_produto()">Pesquisar</button>
            </div>
          </div>

          <div class="modal-data-box">

            <div class="loading text-center padding-top-30 padding-bottom-30" ng-show="produto_loading">
              <i class="fas fa-spinner fa-spin"></i>
              Carregando...
            </div>

            <div class="modal-data-content" ng-if="produto_data.length > 0">

              <table class="table table-striped">
                <tr>
                  <th>Cód. do produto</th>
                  <th>Descrição</th>
                  <th width="90">Opção</th>
                </tr> 
                <tr ng-repeat="(key,val) in produto_data">
                  <td>{{ val.codigo_produto }}</td>
                  <td>{{ val.descricao }}</td>
                  <td><button class="btn btn-success btn-sm" ng-click="selectProduto(key)">Selecionar</button></td>
                </tr>               
              </table>

            </div>

            <div class="alert alert-warning" ng-if="produto_data.length == 0">
              Nenhum produto encontrado
            </div>

          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>
                                
</div><!-- /.container -->

<?php include(required('footer')); ?>
