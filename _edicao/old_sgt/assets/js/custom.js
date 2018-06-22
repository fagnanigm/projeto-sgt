var campos_CTE_Cargas = 1;
function NovoCampoCTE_Cargas() {
    
    var nova = document.getElementById("NovosCampos_CTE_Carga");
    var novadiv = document.createElement("div");
    var nomediv = "div";
    novadiv.innerHTML = '<div class="form-row">'+
                            '<div class="form-group col-md-3">'+
                                '<input type="text" class="form-control" id="inputCodigo_Unidade_Medida'+campos_CTE_Cargas+'" name="Codigo_Unidade_Medida'+campos_CTE_Cargas+'">'+
                            '</div>'+
                            '<div class="form-group col-md-6">'+
                                '<input type="text" class="form-control" id="inputTipo_Medida'+campos_CTE_Cargas+'" name="Tipo_Medida'+campos_CTE_Cargas+'">'+
                            '</div>'+
                            '<div class="form-group col-md-2">'+
                                '<input type="text" class="form-control" id="inputQuantidade_Carga'+campos_CTE_Cargas+'" name="Quantidade_Carga'+campos_CTE_Cargas+'">'+
                            '</div>'+
                            '<div class="form-group col-md-1">'+
                                '<button class="btn btn-outline-info form-control" type="button" onClick="NovoCampoCTE_Cargas()" name ="btnAddCarga'+campos_CTE_Cargas+'">+</button>'+
                            '</div>'+
                        '</div>';
    nova.appendChild(novadiv);

    campos_CTE_Cargas++;

    document.getElementById("inputCarga_Quantidade_Cargas").value = campos_CTE_Cargas;     
}

var campos_CTE_Veiculos = 2;
function NovoCampoCTE_Veiculos() {
    
    var nova = document.getElementById("NovosCampos_CTE_Veiculos");
    var novadiv = document.createElement("div");
    var nomediv = "div";
    novadiv.innerHTML = '<div class="form-row">'+
                            '<div class="form-group col-md-2">'+
                            '<input type="text" class="form-control" id="inputRodoviario_Codigo_Veiculo'+campos_CTE_Veiculos+'" name="Rodoviario_Codigo_Veiculo'+campos_CTE_Veiculos+'" required>'+
                            '</div>'+
                            '<div class="form-group col-md-2">'+
                            '<input type="text" class="form-control" id="inputRodoviario_Renavam'+campos_CTE_Veiculos+'" name="Rodoviario_Renavam'+campos_CTE_Veiculos+'">'+
                            '</div>'+
                            '<div class="form-group col-md-2">'+
                            '<input type="text" class="form-control" id="inputRodoviario_Palca'+campos_CTE_Veiculos+'" name="Rodoviario_Palca'+campos_CTE_Veiculos+'">'+
                            '</div>'+                        
                            '<div class="form-group col-md-2">'+
                            '<input type="text" class="form-control" id="inputRodoviario_Capacidade_KG'+campos_CTE_Veiculos+'" name="Rodoviario_Capacidade_KG'+campos_CTE_Veiculos+'">'+
                            '</div>'+
                            '<div class="form-group col-md-2">'+
                            '<input type="text" class="form-control" id="inputRodoviario_Capacidade_M3'+campos_CTE_Veiculos+'" name="Rodoviario_Capacidade_M3'+campos_CTE_Veiculos+'">'+
                            '</div>'+
                            '<div class="form-group col-md-1">'+
                            '<input type="text" class="form-control" id="inputRodoviario_UF_Veiculo'+campos_CTE_Veiculos+'" name="Rodoviario_UF_Veiculo'+campos_CTE_Veiculos+'">'+
                            '</div>'+              
                            '<div class="form-group col-md-1">'+
                            '<button class="btn btn-outline-info form-control" type="button" onClick="NovoCampoCTE_Veiculos()" name ="btnAddVeiculo'+campos_CTE_Veiculos+'">+</button>'+
                            '</div>'+
                        '</div>';
    nova.appendChild(novadiv);

    document.getElementById("inputRodoviario_Quantidade_Veiculo").value = campos_CTE_Veiculos;
    
    campos_CTE_Veiculos++;
}

var campos_CTE_Motoristas = 2;
function NovoCampoCTE_Motoristas() {
    
    var nova = document.getElementById("NovosCampos_CTE_Motoristas");
    var novadiv = document.createElement("div");
    var nomediv = "div";
    novadiv.innerHTML = '<div class="form-row">'+
                            '<div class="form-group col-md-1">'+
                            '<input type="text" class="form-control" id="inputRodoviario_Codigo_Motorista'+campos_CTE_Motoristas+'" name="Rodoviario_Codigo_Motorista'+campos_CTE_Motoristas+'" required>'+
                            '</div>'+
                            '<div class="form-group col-md-7">'+
                            '<input type="text" class="form-control" id="inputRodoviario_Nome_Motorista'+campos_CTE_Motoristas+'" name="Rodoviario_Nome_Motorista'+campos_CTE_Motoristas+'">'+
                            '</div>'+
                            '<div class="form-group col-md-3">'+
                            '<input type="text" class="form-control" id="inputRodoviario_CPF_Motorista'+campos_CTE_Motoristas+'" name="Rodoviario_CPF_Motorista'+campos_CTE_Motoristas+'">'+
                            '</div>'+                     
                            '<div class="form-group col-md-1">'+
                            '<button class="btn btn-outline-info form-control" type="button" onClick="NovoCampoCTE_Motoristas()" name ="btnAddMotorista'+campos_CTE_Motoristas+'">+</button>'+
                            '</div>'+
                        '</div>';
    nova.appendChild(novadiv);

    document.getElementById("inputRodoviario_Quantidade_Motoristas").value = campos_CTE_Motoristas;
    
    campos_CTE_Motoristas++;
}

var campos_Documentos = 0;
function NovoCampoCTE_Documentos(tipoDocumento) {
    
    var nova = document.getElementById("NovosCampos_Documentos");
    var novadiv = document.createElement("div");

    document.getElementById("inputTipo_Documento_NF").disabled = true;
    document.getElementById("inputTipo_Documento_NFE").disabled = true;
    document.getElementById("inputTipo_Documento_OD").disabled = true;
    document.getElementById("inputDocumento_Tipo_Documento").value = tipoDocumento;

    if (tipoDocumento == "NF") {
        if (campos_Documentos == 0)
        {
            novadiv.innerHTML = '<div class="form-row">'+  
                                    '<div class="form-group col-md-1"><label>Serie</label></div>'+
                                    '<div class="form-group col-md-1"><label>Numero</label></div>'+
                                    '<div class="form-group col-md-1"><label>Data</label></div>'+
                                    '<div class="form-group col-md-1"><label>CFOP</label></div>'+
                                    '<div class="form-group col-md-1"><label>B. C. ICMS</label></div>'+
                                    '<div class="form-group col-md-1"><label>B. C. I. ST</label></div>'+
                                    '<div class="form-group col-md-1"><label>Valor ICMS</label></div>'+
                                    '<div class="form-group col-md-1"><label>V. ICMS ST</label></div>'+
                                    '<div class="form-group col-md-1"><label>Valor Prod.</label></div>'+
                                    '<div class="form-group col-md-2"><label>Valor Nota</label></div>'+
                                '</div>';          
        }
        novadiv.innerHTML = novadiv.innerHTML + '<div class="form-row">'+  
                                                    '<div class="form-group col-md-1">'+                           
                                                        '<input type="text" class="form-control" id="inputNota_Fiscal_Serie'+campos_Documentos+'" name="Nota_Fiscal_Serie'+campos_Documentos+'">'+
                                                    '</div>'+
                                                    '<div class="form-group col-md-1">'+                           
                                                        '<input type="text" class="form-control" id="inputNota_Fiscal_Numero'+campos_Documentos+'" name="Nota_Fiscal_Numero'+campos_Documentos+'">'+
                                                    '</div>'+
                                                    '<div class="form-group col-md-1">'+                         
                                                        '<input type="text" class="form-control" id="inputNota_Fiscal_DataEmissao'+campos_Documentos+'" name="Nota_Fiscal_DataEmissao'+campos_Documentos+'">'+
                                                    '</div>'+
                                                    '<div class="form-group col-md-1">'+                         
                                                        '<input type="text" class="form-control" id="inputNota_Fiscal_CFOP'+campos_Documentos+'" name="CFOP'+campos_Documentos+'">'+
                                                    '</div>'+
                                                    '<div class="form-group col-md-1">'+                        
                                                        '<input type="text" class="form-control" id="inputNota_Fiscal_B_C_ICMS'+campos_Documentos+'" name="Nota_Fiscal_B_C_ICMS'+campos_Documentos+'">'+
                                                    '</div>'+
                                                    '<div class="form-group col-md-1">'+                           
                                                        '<input type="text" class="form-control" id="inputNota_Fiscal_B_C_ICMS_ST'+campos_Documentos+'" name="Nota_Fiscal_B_C_ICMS_ST'+campos_Documentos+'">'+
                                                    '</div>'+
                                                    '<div class="form-group col-md-1">'+                           
                                                        '<input type="text" class="form-control" id="inputNota_Fiscal_Valor_ICMS'+campos_Documentos+'" name="Nota_Fiscal_Valor_ICMS'+campos_Documentos+'">'+
                                                    '</div>'+
                                                    '<div class="form-group col-md-1">'+                           
                                                        '<input type="text" class="form-control" id="inputNota_Fiscal_Valor_ICMS_ST'+campos_Documentos+'" name="Nota_Fiscal_Valor_ICMS_ST'+campos_Documentos+'">'+
                                                    '</div>'+
                                                    '<div class="form-group col-md-1">'+                          
                                                        '<input type="text" class="form-control" id="inputNota_Fiscal_Valor_Produtos'+campos_Documentos+'" name="Valor_Produtos'+campos_Documentos+'">'+
                                                    '</div>'+
                                                    '<div class="form-group col-md-2">'+                         
                                                        '<input type="text" class="form-control" id="inputNota_Fiscal_Valor_Nota'+campos_Documentos+'" name="Valor_Nota'+campos_Documentos+'">'+
                                                    '</div>'+
                                                    '<div class="form-group col-md-1">'+
                                                        '<button class="btn btn-outline-info form-control" type="button" onClick="NovoCampoCTE_Documentos(' + "'NF'" + ')" name ="btnAddNotaFiscal'+campos_Documentos+'">+</button>'+
                                                    '</div>'+
                                                '</div>';
    }

    if (tipoDocumento == "NFE") {
        if (campos_Documentos == 0)
        {
            novadiv.innerHTML = '<div class="form-row"><div class="form-group col-md-11"><label>Chave de Acesso</label></div></div>';          
        }
        novadiv.innerHTML = novadiv.innerHTML + '<div class="form-row">'+
                                                    '<div class="form-group col-md-11">'+                            
                                                    '<input type="text" class="form-control" id="inputNota_Fiscal_Eletronica_Chave_Acesso'+campos_Documentos+'" name="Nota_Fiscal_Eletronica_Chave_Acesso'+campos_Documentos+'">'+
                                                '</div>'+
                                                '<div class="form-group col-md-1">'+
                                                    '<button class="btn btn-outline-info form-control" type="button" onClick="NovoCampoCTE_Documentos(' + "'NFE'" + ')" name ="btnAddNotaFiscalEletronica'+campos_Documentos+'">+</button>'+
                                                    '</div>'+ 
                                                '</div>';
    }

    if (tipoDocumento == "OD") {
        if (campos_Documentos == 0)
        {
            novadiv.innerHTML = '<div class="form-row">'+ 
                                    '<div class="form-group col-md-2"><label>Data de Emissao</label></div>'+
                                    '<div class="form-group col-md-2"><label>Documento de Origem</label></div>'+
                                    '<div class="form-group col-md-5"><label>Descrição</label></div>'+
                                    '<div class="form-group col-md-2"><label>Valor</label></div>'+
                                '</div>';          
        }
        novadiv.innerHTML = novadiv.innerHTML + '<div class="form-row">'+ 
                                                    '<div class="form-group col-md-2">'+                          
                                                        '<input type="text" class="form-control" id="inputOutros_Documentos_Data_Emissao'+campos_Documentos+'" name="Outros_Documentos_Data_Emissao'+campos_Documentos+'">'+
                                                    '</div>'+
                                                    '<div class="form-group col-md-2">'+                          
                                                        '<select id="inputOutros_Documentos_Documento_Origem'+campos_Documentos+'" class="form-control" name="Outros_Documentos_Documento_Origem'+campos_Documentos+'">'+
                                                            '<option value = "00" selected >Declaração</option>'+
                                                            '<option value = "99">Outros</option>'+
                                                        '</select>'+
                                                    '</div>'+
                                                    '<div class="form-group col-md-5">'+                       
                                                        '<input type="text" class="form-control" id="inputOutros_Documentos_Descricao'+campos_Documentos+'" name="Outros_Documentos_Descricao'+campos_Documentos+'">'+
                                                    '</div>'+
                                                    '<div class="form-group col-md-2">'+                        
                                                        '<input type="text" class="form-control" id="inputOutros_Documentos_Valor'+campos_Documentos+'" name="Outros_Documentos_Valor'+campos_Documentos+'">'+
                                                    '</div>'+
                                                    '<div class="form-group col-md-1">'+
                                                        '<button class="btn btn-outline-info form-control" type="button" onClick="NovoCampoCTE_Documentos(' + "'OD'" + ')" name="btnAddOutrosDocumentos'+campos_Documentos+'">+</button>'+
                                                    '</div>'+ 
                                                '</div>'

    }                

    nova.appendChild(novadiv);

    campos_Documentos++;
    
    document.getElementById("inputDocumento_Quantidade_Documento").value = campos_Documentos;    
}

var campos_CTE_Seguros = 2;
function NovoCampoCTE_Seguros() {
    
    var nova = document.getElementById("NovosCampos_CTE_Seguros");
    var novadiv = document.createElement("div");
    var nomediv = "div";
    novadiv.innerHTML = '<div class="form-row">'+
                        '<div class="form-group col-md-2">'+
                          '<select id="inputSeguro_Responsavel_Seguro'+campos_CTE_Seguros+'" class="form-control" name="Seguro_Responsavel_Seguro'+campos_CTE_Seguros+'">'+
                            '<option value = "0" selected >Remetente</option>'+
                            '<option value = "1">Destinatário</option>'+
                            '<option value = "2">Tomador</option>'+
                          '</select>'+
                        '</div>'+          
                        '<div class="form-group col-md-3">'+
                          '<input type="text" class="form-control" id="inputSeguro_Nome_Seguradora'+campos_CTE_Seguros+'" name="Seguro_Nome_Seguradora'+campos_CTE_Seguros+'">'+
                        '</div>'+
                        '<div class="form-group col-md-2">'+
                          '<input type="text" class="form-control" id="inputSeguro_Numero_Apolice'+campos_CTE_Seguros+'" name="Seguro_Numero_Apolice'+campos_CTE_Seguros+'">'+
                        '</div>'+
                        '<div class="form-group col-md-2">'+
                          '<input type="text" class="form-control" id="inputSeguro_Numero_Averbacao'+campos_CTE_Seguros+'" name="Seguro_Numero_Averba'+campos_CTE_Seguros+'">'+
                        '</div>'+
                        '<div class="form-group col-md-2">'+
                          '<input type="text" class="form-control" id="inputSeguro_Valor_Carga_Efeito_Averbacao'+campos_CTE_Seguros+'" name="Seguro_Valor_Carga_Efeito_Averba'+campos_CTE_Seguros+'">'+
                        '</div>'+
                        '<div class="form-group col-md-1">'+
                          '<button class="btn btn-outline-info form-control" type="button" onClick="NovoCampoCTE_Seguros()" name ="btnAddSegurocampos_CTE_Seguros'+campos_CTE_Seguros+'">+</button>'+
                        '</div>'+
                      '</div>';

    nova.appendChild(novadiv);

    document.getElementById("inputSeguro_Quantidade_Seguros").value = campos_CTE_Seguros;
    
    campos_CTE_Seguros++;
}

var campos_CTE_Documentos_Transporte_Anterior_Papel = 0;
function NovoCampoCTE_Documentos_Transporte_Anterior_Papel(Numero_Documento) {
    
    var nova = document.getElementById("NovosCampos_Documentos_Anteriores_Papel" + Numero_Documento);
    var novadiv = document.createElement("div");
    var nomediv = "div";
    novadiv.innerHTML = '<div class="form-row">'+
                            '<div class="form-group col-md-2">'+
                            '<input type="text" class="form-control" value="Impresso" readonly>'+
                            '</div>'+
                            '<div class="form-group col-md-3">'+
                            '<input type="text" class="form-control" placeholder="Tipo de Documento" id="inputDocumento_Transporte_Anterior_Papel_Tipo'+Numero_Documento+'_'+campos_CTE_Documentos_Transporte_Anterior_Papel+'" name="Documento_Transporte_Anterior_Papel_Tipo'+Numero_Documento+'_'+campos_CTE_Documentos_Transporte_Anterior_Papel+'">'+
                            '</div>'+
                            '<div class="form-group col-md-1">'+
                            '<input type="text" class="form-control" placeholder="Serie" id="inputDocumento_Transporte_Anterior_Papel_Serie'+Numero_Documento+'_'+campos_CTE_Documentos_Transporte_Anterior_Papel+'" name="Documento_Transporte_Anterior_Papel_Serie'+Numero_Documento+'_'+campos_CTE_Documentos_Transporte_Anterior_Papel+'">'+
                            '</div>'+
                            '<div class="form-group col-md-1">'+
                            '<input type="text" class="form-control" placeholder="SubSerie" id="inputDocumento_Transporte_Anterior_Papel_SubSerie'+Numero_Documento+'_'+campos_CTE_Documentos_Transporte_Anterior_Papel+'" name="Documento_Transporte_Anterior_Papel_SubSerie'+Numero_Documento+'_'+campos_CTE_Documentos_Transporte_Anterior_Papel+'">'+
                            '</div>'+
                            '<div class="form-group col-md-2">'+
                            '<input type="text" class="form-control" placeholder="Numero" id="inputDocumento_Transporte_Anterior_Papel_Numero'+Numero_Documento+'_'+campos_CTE_Documentos_Transporte_Anterior_Papel+'" name="Documento_Transporte_Anterior_Papel_Numero'+Numero_Documento+'_'+campos_CTE_Documentos_Transporte_Anterior_Papel+'">'+
                            '</div>'+
                            '<div class="form-group col-md-2">'+
                            '<input type="text" class="form-control" placeholder="Data" id="inputDocumento_Transporte_Anterior_Papel_Data_Emissao'+Numero_Documento+'_'+campos_CTE_Documentos_Transporte_Anterior_Papel+'" name="Documento_Transporte_Anterior_Papel_Data_Emissao'+Numero_Documento+'_'+campos_CTE_Documentos_Transporte_Anterior_Papel+'">'+
                            '</div>'+
                            '<div class="form-group col-md-1">'+
                            '<button class="btn btn-outline-info form-control" type="button" name ="btnAddTransporte_Documento_Anterior_Papel'+Numero_Documento+'_'+campos_CTE_Documentos_Transporte_Anterior_Papel+'" onclick="NovoCampoCTE_Documentos_Transporte_Anterior_Papel('+Numero_Documento+')" >+</button>'+
                            '</div>'+
                        '</div>';

    nova.appendChild(novadiv);

    campos_CTE_Documentos_Transporte_Anterior_Papel++;

    document.getElementById("inputDocumentoTransposrteAnterior_Quantidade_Documento_Papel").value = campos_CTE_Documentos_Transporte_Anterior_Papel;
}

var campos_CTE_Documentos_Transporte_Anterior_Eletronico = 0;
function NovoCampoCTE_Documentos_Transporte_Anterior_Eletronico(Numero_Documento) {
    
    var nova = document.getElementById("NovosCampos_Documentos_Anteriores_Eletronico" + Numero_Documento);
    var novadiv = document.createElement("div");
    var nomediv = "div";
    novadiv.innerHTML = '<div class="form-row">'+
                            '<div class="form-group col-md-2">'+ 
                            '<input type="text" class="form-control" value="Eletronico" readonly>'+   
                            '</div>'+
                            '<div class="form-group col-md-9">'+
                            '<input type="text" class="form-control" placeholder="Chave" id="inputDocumento_Transporte_Anterior_Eletronico_Chave'+Numero_Documento+'_'+campos_CTE_Documentos_Transporte_Anterior_Eletronico+'" name="Documento_Transporte_Anterior_Eletronico_Chave'+Numero_Documento+'_'+campos_CTE_Documentos_Transporte_Anterior_Eletronico+'">'+
                            '</div>'+
                            '<div class="form-group col-md-1">'+
                            '<button class="btn btn-outline-info form-control" type="button" name ="btnAddTransporte_Documento_Anterior_Eletronico'+Numero_Documento+'_'+campos_CTE_Documentos_Transporte_Anterior_Eletronico+'" onclick="NovoCampoCTE_Documentos_Transporte_Anterior_Eletronico('+Numero_Documento+')">+</button>'+
                            '</div>'+
                        '</div>';

    nova.appendChild(novadiv);

    campos_CTE_Documentos_Transporte_Anterior_Eletronico++;
    
    document.getElementById("inputDocumentoTransposrteAnterior_Quantidade_Documento_Eletronico").value = campos_CTE_Documentos_Transporte_Anterior_Eletronico;
}

var campos_CTE_Documentos_Transporte_Anterior = 0;
function NovoCampoCTE_Documentos_Transporte_Anterior() {
 
    var nova = document.getElementById("NovosCampos_Documentos_Anteriores" + campos_CTE_Documentos_Transporte_Anterior);
    var novadiv = document.createElement("div");
    var nomediv = "div";
    novadiv.innerHTML = '<div id="NovosCampos_Documentos_Anteriores'+ (campos_CTE_Documentos_Transporte_Anterior + 1) +'">'+
                        '</div>'+
                        '<div class="card">'+
                            '<div class="card-body">'+  
                                
                                '<div class="form-row">'+
                                    '<div class="form-group col-md-6">'+
                                    '<label for="inputDocumento_Transporte_Anterior_Razao_Social">Razão Social / Nome</label>'+
                                    '<input type="text" class="form-control" id="inputDocumento_Transporte_Anterior_Razao_Social'+campos_CTE_Documentos_Transporte_Anterior+'" name="Documento_Transporte_Anterior_Razao_Social'+campos_CTE_Documentos_Transporte_Anterior+'">'+
                                    '</div>'+
                                    '<div class="form-group col-md-3">'+
                                    '<label for="inputDocumento_Transporte_Anterior_CPF_CNPJ">CNPJ / CPF</label>'+
                                    '<input type="text" class="form-control" id="inputDocumento_Transporte_Anterior_CPF_CNPJ'+campos_CTE_Documentos_Transporte_Anterior+'" name="Documento_Transporte_Anterior_CPF_CNPJ'+campos_CTE_Documentos_Transporte_Anterior+'">'+
                                    '</div>'+
                                    '<div class="form-group col-md-2">'+
                                    '<label for="inputDocumento_Transporte_Anterior_Inscricao_Estadual">Inscrição Estadual</label>'+
                                    '<input type="text" class="form-control" id="inputDocumento_Transporte_Anterior_Inscricao_Estadual'+campos_CTE_Documentos_Transporte_Anterior+'" name="Documento_Transporte_Anterior_Inscricao_Estadual'+campos_CTE_Documentos_Transporte_Anterior+'">'+
                                    '</div>'+
                                    '<div class="form-group col-md-1">'+
                                    '<label for="inputDocumento_Transporte_Anterior_UF">UF</label>'+
                                    '<select id="inputDocumento_Transporte_Anterior_UF" class="form-control" name="Documento_Transporte_Anterior_UF'+campos_CTE_Documentos_Transporte_Anterior+'">'+
                                        '<option value = "1" selected >SP</option>'+
                                        '<option value = "2">AC</option>'+
                                    '</select>'+
                                    '</div>'+      
                                '</div>'+   

                                '<div id="NovosCampos_Documentos_Anteriores_Papel'+campos_CTE_Documentos_Transporte_Anterior+'">'+
                                '</div>'+
                                '<div id="NovosCampos_Documentos_Anteriores_Eletronico'+campos_CTE_Documentos_Transporte_Anterior+'">'+
                                '</div>'+

                            '</div>'+
                        '</div><br />';

    nova.appendChild(novadiv);

    NovoCampoCTE_Documentos_Transporte_Anterior_Papel(campos_CTE_Documentos_Transporte_Anterior);
    NovoCampoCTE_Documentos_Transporte_Anterior_Eletronico(campos_CTE_Documentos_Transporte_Anterior);

    campos_CTE_Documentos_Transporte_Anterior++;
    
    document.getElementById("inputDocumentoTransposrteAnterior_Quantidade_Documento").value = campos_CTE_Documentos_Transporte_Anterior;    
}

var campos_CTE_Prestacoes = 2;
function NovoCampoCTE_Prestacoes() {
    
    var nova = document.getElementById("NovosCampos_Prestacao");
    var novadiv = document.createElement("div");
    var nomediv = "div";
    novadiv.innerHTML = '<div class="form-row">'+
                            '<div class="form-group col-md-7">'+
                                '<input type="text" class="form-control" id="inputPrestacao_Nome'+campos_CTE_Prestacoes+'" name="Prestacao_Nome'+campos_CTE_Prestacoes+'">'+
                            '</div>'+
                            '<div class="form-group col-md-4">'+
                                '<input type="text" class="form-control" id="inputPrestacao_Valor'+campos_CTE_Prestacoes+'" name="Prestacao_Valor'+campos_CTE_Prestacoes+'">'+
                            '</div>'+
                            '<div class="form-group col-md-1">'+
                                '<button class="btn btn-outline-info form-control" type="button" name ="btnAddPrestacao'+campos_CTE_Prestacoes+'" onclick="NovoCampoCTE_Prestacoes()">+</button>'+
                            '</div>'+
                        '</div>';

    nova.appendChild(novadiv);

    document.getElementById("inputFatura_Quantidade_Prestacoes").value = campos_CTE_Prestacoes;
    
    campos_CTE_Prestacoes++;
}


$(function(){

    $('.pre-emitir-cte-btn').on('click',function(e){

        $('.cte-error').html('').addClass('ng-hide');

        var id = $(this).data('id');
        $('input[name="global_numero_cte"]').val(id);

        $('.emitir-cte-btn').data('id',id);
        $('.emitir-cte-btn').data('cte',$(this).data('cte'));

        $('#num-cte-modal').modal('show')
    });

    
    $('.emitir-cte-btn').on('click',function(e){

        var data = $(this).data('cte');
        var id = $(this).data('id');
        var num_cte = $('input[name="global_numero_cte"]').val();

        $('.loading-global').removeClass("ng-hide");

        $.ajax({
            url : '/include/model/cte/gerar-cte.php',
            type : 'POST',
            data : { cte : data, id : id, code : num_cte },
            success : function(response){

                console.log(response);

                response_parse = JSON.parse(response);

                if(response_parse.retorno.CStat == '100'){
                    alert('CTE Emitido com sucesso.');
                    location.reload();
                }else{

                    var cte_error_text = 'Erro ao emitir CTE:<br />';

                    if(typeof(response_parse.retorno.XMotivo) == 'object'){

                        $.each(response_parse.retorno.XMotivo,function(key,val){

                            cte_error_text += '<strong>Motivo '+( key + 1 )+':</strong> '+val+'<br />';

                        });

                    }else{
                        cte_error_text += '<strong>Motivo:</strong> '+response_parse.retorno.XMotivo+'<br />';
                    }

                    $('.cte-error').html(cte_error_text).removeClass('ng-hide');
                }

                $('.loading-global').addClass("ng-hide");
            }

        });

        e.preventDefault();

    });

    /// CTE CFOP MODAL
    $('.open-cfop-modal').on('focus',function(){
        $('#cfop-modal').modal('show')
    });

    $('.open-tomador-modal').on('focus',function(){
        $('#tomador-modal').modal('show')
    });

    $('.open-remetente-modal').on('focus',function(){
        $('#remetente-modal').modal('show')
    });

    $('.open-destinatario-modal').on('focus',function(){
        $('#destinatario-modal').modal('show')
    });


    
    
    

});