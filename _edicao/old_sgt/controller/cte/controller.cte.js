(function () {
    'use strict';

    angular
    	.module('app')
        .controller('CTE.Controller', Controller);

    function Controller($rootScope,$scope,$http){

        $scope.loading = false;

        $scope.cfop_loading = false;

        $scope.produto_loading = false;

        $scope.cliente_loading = false;

        $scope.municipio_loading = false;

        $scope.cfop_data = [];

        $scope.produto_data = [];

        $scope.cliente_data = [];

        $scope.municipio_data = [];

        $scope.success = false;

        $scope.error = false;

        $scope.cte_error = false;

        // Modelos padrões plus
        $scope.structs = {};


        // Informações de carga
        $scope.structs.struct_informacoes_carga_quantidade = {
            Codigo_Unidade_Medida : '',
            Tipo_Medida : '',
            Quantidade_Carga : ''
        } 

        // Modal rodovário
        $scope.structs.struct_modal_rodoviario_veiculos = {
            Rodoviario_Codigo_Veiculo : '',
            Rodoviario_Renavam : '',
            Rodoviario_Palca : '',
            Rodoviario_Capacidade_KG : '',
            Rodoviario_Capacidade_M3 : '',
            Rodoviario_UF_Veiculo : ''
        }

        $scope.structs.struct_modal_rodoviario_motoristas = {
            Rodoviario_Codigo_Motorista : '',
            Rodoviario_Nome_Motorista : '',
            Rodoviario_CPF_Motorista : ''
        }

        // Seguros
        $scope.structs.struct_seguros = {
            Seguro_Responsavel_Seguro : '0',
            Seguro_Nome_Seguradora : '',
            Seguro_Numero_Apolice : '', 
            Seguro_Numero_Averbacao : '',
            Seguro_Valor_Carga_Efeito_Averbacao : ''
        }

        // Fatura
        $scope.structs.struct_fatura_prestacoes = {
            Prestacao_Nome : '',
            Prestacao_Valor : ''
        }

        $scope.structs.struct_modal_documentos = {
            chave : ''
        }

        $scope.load = function($id){

            $scope.loading = true;

            var date = new Date();

            var date_day = (date.getDate() < 10 ? '0'+date.getDate() : date.getDate())+'/'+
                           ((date.getMonth() + 1) < 10 ? '0'+(date.getMonth() + 1) : (date.getMonth() + 1))+'/'+
                           date.getFullYear();
            var date_time = (date.getHours() < 10 ? '0'+date.getHours() : date.getHours())+':'+ (date.getMinutes() < 10 ? '0'+date.getMinutes() : date.getMinutes());

            $scope.cte_id = $id;

            if($scope.cte_id == '0'){

                // puxa dados do cte cadastrado.
                $http.post('/include/model/cte/get-cte.php',{ id : $scope.cte_id }).success(function(data){

                    $scope.loading = false;

                    $scope.cte = {
                        return_acbr : 0,
                        is_update : 0,
                        informacoes_gerais : {
                            numero_cte : data.code,
                            Geral_Tipo_CTE : '1',
                            Geral_Tipo_Servico : '1',
                            Geral_Data_Emissao_Data : date_day,
                            Geral_Data_Emissao_Hora : date_time,

                            Geral_Cidade_Origem : '',
                            Geral_Cidade_Origem_Code : '',
                            Geral_Cidade_Origem_UF : '',

                            Geral_Cidade_Destino : '',
                            Geral_Cidade_Destino_Code : '',
                            Geral_Cidade_Destino_UF : '',

                            Geral_CFOP : '',
                            Geral_CFOP_desc : '',
                            Geral_Natureza : '1',
                            tomador : {
                                nome : '',
                                cnpj : '',
                                ie : '',
                                logradouro : '',
                                numero : '',
                                bairro : '',
                                codigo_cidade : '',
                                cidade : '',
                                cep : '',
                                estado : '',
                                pais : '',
                                codigo_pais : '1058'
                            },
                            remetente : {
                                nome : '',
                                cnpj : '',
                                ie : '',
                                logradouro : '',
                                numero : '',
                                bairro : '',
                                codigo_cidade : '',
                                cidade : '',
                                cep : '',
                                estado : '',
                                pais : '',
                                codigo_pais : '1058'
                            },
                            destinatario : {
                                nome : '',
                                cnpj : '',
                                ie : '',
                                logradouro : '',
                                numero : '',
                                bairro : '',
                                codigo_cidade : '',
                                cidade : '',
                                cep : '',
                                estado : '',
                                pais : '',
                                codigo_pais : '1058'
                            }
                        },
                        informacoes_carga : {
                            Carga_Valor : '',
                            Carga_Produto_Predominante : '',
                            Carga_Outra_Caracteristicas : '',
                            quantidades : [{
                                Codigo_Unidade_Medida : '',
                                Tipo_Medida : '',
                                Quantidade_Carga : ''
                            },{
                                Codigo_Unidade_Medida : '',
                                Tipo_Medida : '',
                                Quantidade_Carga : ''
                            }]        
                        },
                        modal_rodoviario : {
                            Rodoviario_RNTRC : '',
                            veiculos : [angular.copy($scope.structs.struct_modal_rodoviario_veiculos)],
                            motoristas : [angular.copy($scope.structs.struct_modal_rodoviario_motoristas)]
                        },
                        documentos : [
                            {
                                chave : ''
                            }
                        ],
                        
                        // seguros : [angular.copy($scope.structs.struct_seguros)],
                        
                        /* 
                        informacoes_cobranca : {
                            Cobranca_Servico_Valor_Total_Servico : '',
                            Cobranca_Servico_Valor_Receber : '',
                            Cobranca_Servico_Valor_Aproximado_Tributos : '',
                            Cobranca_Forma_Pagamento  : '1',
                            Cobranca_ICMS_CST : '',
                            Cobranca_ICMS_Base : '',
                            Cobranca_ICMS_Aliquota : '',
                            Cobranca_ICMS_Valor : '',
                            Cobranca_ICMS_Percentual_Reducao_Base_Calculo_ICMS : '',
                            Cobranca_ICMS_Credito_ICMS : '',
                            Cobranca_Partilha_ICMS_Aliquota_Interna_UF_Termino : '',
                            Cobranca_Partilha_ICMS_Aliquota_Interestadual : '',
                            Cobranca_Partilha_ICMS_Porcentagem_Partilha_UF_Termino : '',
                            Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Termino : '',
                            Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Inicio : '',
                            Cobranca_Partilha_ICMS_Porcentagem_ICMS_FCP_UF_Termino : '',
                            Cobranca_Partilha_ICMS_Valor_ICMS_FCP_UF_Termino : '',
                            Cobranca_Partilha_ICMS_Valor_Base_Calculo : '',
                            Cobranca_Observacoes_Gerais : '',
                            Cobranca_Entrega_Prevista : ''
                        },
                        */
                        fatura : {
                            Fatura_Numero : '',
                            Fatura_Valor_Origem : '',                    
                            Fatura_Valor_Desconto : '',
                            Fatura_Valor : '',
                            prestacoes : [angular.copy($scope.structs.struct_fatura_prestacoes)]
                        }
                    }

                });

                



            }else{

                // puxa dados do cte cadastrado.
                $http.post('/include/model/cte/get-cte.php',{ id : $scope.cte_id }).success(function(data){

                    $scope.loading = false;

                    $scope.cte = data.cte.content;

                    $scope.cte.is_update = 1;

                    $scope.cte.return_acbr = data.cte.return_acbr;

                });

            }
            
        }

    	$scope.save = function(){

            $scope.loading = true;

    		
            $http.post('/include/model/cte/cadastrar-cte.php',{ content : $scope.cte, cte_id : $scope.cte_id }).success(function(data){
                
                if(data.result){
                    location.href = "/registros-cte.php";
                }

            });

    		return false;

    	}

        $scope.emitir_cte = function(){

            $scope.loading = true;

            
            $http.post('/include/model/cte/gerar-cte.php',{ id : $scope.cte_id, cte : $scope.cte }).success(function(data){

                console.log(data);
                            
                if(data.retorno.CStat == '100'){
                    $scope.success = true;
                    
                    setTimeout(function(){
                        location.href = "/registros-cte.php";
                    },3000);

                }else{
                    console.log(data);

                    $scope.cte_error = true;
                    $scope.cte_error_data = data;
                }

                $scope.loading = false;
                

            });

            return false;

        }

        $scope.plus_field = function(object, struct, callback){
            struct = angular.copy($scope.structs[struct]);
            object.push(struct);
        }

        $scope.less_field = function(key, object, struct, callback){

            if(object.length == 1){
                struct = angular.copy($scope.structs[struct]);
                object = [struct];
                return;
            }

            object.splice(key, 1);

        }

        $scope.search_cfop = function(){

            $scope.cfop_loading = true;

            var term = $scope.search_cfop_term;

            $http.post('/include/model/cfop/get-cfops.php',{ term : term }).success(function(data){

                console.log(data);

                $scope.cfop_data = data;

                $scope.cfop_loading = false;

            });

            return false;

        }

        $scope.setCFOP = function(key){

            var cfop_selected = $scope.cfop_data[key];

            $scope.cte.informacoes_gerais.Geral_CFOP = cfop_selected.Codigo_CFOP;
            $scope.cte.informacoes_gerais.Geral_CFOP_desc = cfop_selected.Descricao;

            $('#cfop-modal').modal('hide')


        }


        $scope.search_cliente = function(){ 

            var term = $scope.search_cliente_term;

            if(typeof(term) != 'undefined'){

                if(term.length > 0){

                    $scope.cliente_loading = true;

                    $http.post('/include/model/cliente/get-clientes.php',{ term : term }).success(function(data){

                        console.log(data);

                        $scope.cliente_data = data;

                        $scope.cliente_loading = false;

                    });

                }

            }

            return false;


        }

        $scope.setTomador = function(key){

            var cliente = $scope.cliente_data[key];

            $scope.cte.informacoes_gerais.tomador = {
                nome : cliente.Nome_Fantasia,
                cnpj : cliente.CNPJ_CPF,
                ie : cliente.Inscricao_Estadual,
                logradouro : cliente.Endereco,
                numero : '',
                bairro : '',
                cep : cliente.CEP,
                estado : 'SP',
                pais : 'BRASIL',
                codigo_pais : '1058'
            }

            $('#tomador-modal').modal('hide')

        }


        $scope.setRemetente = function(key){

            var cliente = $scope.cliente_data[key];

            $scope.cte.informacoes_gerais.remetente = {
                nome : cliente.Nome_Fantasia,
                cnpj : cliente.CNPJ_CPF,
                ie : cliente.Inscricao_Estadual,
                logradouro : cliente.Endereco,
                numero : '',
                bairro : '',
                cep : cliente.CEP,
                estado : 'SP',
                pais : 'BRASIL',
                codigo_pais : '1058'
            }

            $('#remetente-modal').modal('hide')

        }
        
        $scope.setDestinatario = function(key){

            var cliente = $scope.cliente_data[key];

            $scope.cte.informacoes_gerais.destinatario = {
                nome : cliente.Nome_Fantasia,
                cnpj : cliente.CNPJ_CPF,
                ie : cliente.Inscricao_Estadual,
                logradouro : cliente.Endereco,
                numero : '',
                bairro : '',
                cep : cliente.CEP,
                estado : 'SP',
                pais : 'BRASIL',
                codigo_pais : '1058'
            }

            $('#destinatario-modal').modal('hide')

        }

        $scope.cidade_inicio_prestacao = function(key){

            var current_municipio = $scope.municipio_data[key];

            $scope.cte.informacoes_gerais.Geral_Cidade_Origem = current_municipio.nome;
            $scope.cte.informacoes_gerais.Geral_Cidade_Origem_Code = current_municipio.cd_municipio;
            $scope.cte.informacoes_gerais.Geral_Cidade_Origem_UF = current_municipio.estado;

            $('#municipio-modal').modal('hide');

        }

        $scope.cidade_fim_prestacao = function(key){

            var current_municipio = $scope.municipio_data[key];

            $scope.cte.informacoes_gerais.Geral_Cidade_Destino = current_municipio.nome;
            $scope.cte.informacoes_gerais.Geral_Cidade_Destino_Code = current_municipio.cd_municipio;
            $scope.cte.informacoes_gerais.Geral_Cidade_Destino_UF = current_municipio.estado;

            $('#municipio-modal').modal('hide');         
        }

        $scope.cidade_tomador = function(key){

            var current_municipio = $scope.municipio_data[key];

            $scope.cte.informacoes_gerais.tomador.cidade = current_municipio.nome;
            $scope.cte.informacoes_gerais.tomador.codigo_cidade = current_municipio.cd_municipio;
            $scope.cte.informacoes_gerais.tomador.estado = current_municipio.estado;

            $('#municipio-modal').modal('hide');         
        }

        $scope.cidade_remetente = function(key){

            var current_municipio = $scope.municipio_data[key];

            $scope.cte.informacoes_gerais.remetente.cidade = current_municipio.nome;
            $scope.cte.informacoes_gerais.remetente.codigo_cidade = current_municipio.cd_municipio;
            $scope.cte.informacoes_gerais.remetente.estado = current_municipio.estado;

            $('#municipio-modal').modal('hide');         
        }

        $scope.cidade_destinatario = function(key){

            var current_municipio = $scope.municipio_data[key];

            $scope.cte.informacoes_gerais.destinatario.cidade = current_municipio.nome;
            $scope.cte.informacoes_gerais.destinatario.codigo_cidade = current_municipio.cd_municipio;
            $scope.cte.informacoes_gerais.destinatario.estado = current_municipio.estado;

            $('#municipio-modal').modal('hide');         
        }
  
        $scope.choose_municipio = function($fn){
            $scope.municipio_context = $fn;
            $('#municipio-modal').modal('show')
        }

        $scope.selectMunicipio = function(key){
            this[$scope.municipio_context](key);
        }


        $scope.search_municipio = function(){

            var term = $scope.search_municipio_term;

            if(typeof(term) != 'undefined'){

                if(term.length > 0){

                    $scope.municipio_loading = true;

                    $http.post('/include/model/municipio/get-municipios.php',{ term : term }).success(function(data){

                        $scope.municipio_data = data;

                        $scope.municipio_loading = false;

                    });

                }

            }

            return false;

        }


        $scope.search_produto = function(){

            var term = $scope.search_produto_term;

            if(typeof(term) != 'undefined'){

                if(term.length > 0){

                    $scope.produto_loading = true;

                    $http.post('/include/model/produto/get-produtos.php',{ term : term }).success(function(data){

                        console.log(data);

                        $scope.produto_data = data;

                        $scope.produto_loading = false;

                    });

                }

            }

            return false;

        }

        $scope.selectProduto = function(key){

            var current_product = $scope.produto_data[key];

            $scope.cte.informacoes_carga.Carga_Produto_Predominante = current_product.descricao;
            $scope.cte.informacoes_carga.Carga_Valor = current_product.valor_unitario.trim();

            $('#produto-modal').modal('hide'); 

        }

    }

})();