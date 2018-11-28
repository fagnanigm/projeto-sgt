(function () {
    'use strict';

    angular
        .module('app')
        .controller('CotacoesTextosPadroes.IndexController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {

        $('#tabs-cotacoes-textos a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        });
        
        $scope.textos = {
            cotacao_objeto_operacao_modelo_01 : '',
            cotacao_complemento_objeto_modelo_01: '',
            cotacao_complemento_condicoes_modelo_01: '',
            cotacao_responsabilidades_modelo_01: '',
            cotacao_devolucao_modelo_01: '',
            cotacao_observacoes_finais_modelo_01: '',
            cotacao_objeto_operacao : '',
            cotacao_carga_descarga : '',
            cotacao_carencia : '',
            cotacao_prazo_execucao : '',
            cotacao_observacoes_finais : ''
        };

        $scope.get_results = function(){

            var rest_address = '/api/public/textos-padroes/get?keys=';

            $.each($scope.textos, function(key, val){
                rest_address += key + ',';
            });

            rest_address = rest_address.slice(0, -1); 

            $http.get(rest_address).then(function (response) {

                $.each(response.data.results ,function(key, val){
                    $scope.textos[key] = val.texto_descricao;
                });

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });
        }

        $scope.get_results();

        $scope.save_texto_padrao = function($context){

            $rootScope.is_loading = true;

            var param = {
                texto_field : $context,
                texto_descricao : $scope.textos[$context]
            }

            $http.post('/api/public/textos-padroes/set', param).then(function (response) {

                if(response.data.result){

                    ngToast.create({
                        className: 'success',
                        content: "Texto salvo com sucesso!"
                    });

                }else{
                    ngToast.create({
                        className: 'danger',
                        content: response.data.error
                    });
                }
                

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

    }

})();