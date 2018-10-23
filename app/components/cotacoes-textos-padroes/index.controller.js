(function () {
    'use strict';

    angular
        .module('app')
        .controller('CotacoesTextosPadroes.IndexController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        $scope.textos = {
            cotacao_objeto_operacao : '',
            cotacao_carga_descarga : '',
            cotacao_carencia : '',
            cotacao_prazo_execucao : '',
            cotacao_observacoes_finais : ''
        };

        $scope.get_results = function(){

            var rest_address = '/api/public/textos-padroes/get?keys=cotacao_objeto_operacao,cotacao_carga_descarga,cotacao_carencia,cotacao_prazo_execucao,cotacao_observacoes_finais';

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