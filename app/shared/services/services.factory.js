(function () {
    'use strict';

    angular
        .module('app')
        .factory('GlobalServices', GlobalServices);

    function GlobalServices($http, $location, $rootScope) {

        var service = {};

        service.hierarchical_decode = hierarchical_decode;
        service.phone_parser = phone_parser;
        service.get_as_code_sequencial = get_as_code_sequencial;
        service.get_filter_load = get_filter_load;
        service.get_paginate_list = get_paginate_list;

        

        return service;

        function get_filter_load(){
            var filters = $location.search()
            $rootScope.get_filters = (typeof(filters) == 'undefined' ? false : filters);
        }

        function get_as_code_sequencial(code){
            code = code.split("/");
            var seq = code[0];
            return seq; 
        }
        
        function hierarchical_decode(data,nivel){
            var global_data = [];
            $.each(data,function(key,val){
                val.nivel = nivel;
                global_data.push(val);
                global_data = global_data.concat(hierarchical_decode(val.childs, (nivel + 1 )));
            });
            return global_data;
        }

        function phone_parser(data, method){

            var response = {};

            if(method == 'explode'){
                if(typeof(data.number) != 'undefined'){
                    if(data.number.length > 0){
                        var pieces = data.number.split(" ");
                        response.ddd = pieces[0].replace(/\(/g, "").replace(/\)/g, "");
                        response.number = pieces[1].replace(/-/g, "");
                    }else{
                        response.ddd = '';
                        response.number = '';
                    }
                }else{
                    response.ddd = '';
                    response.number = '';
                }
                return response;
            }

            if(method == 'implode'){

                var phone = '';

                if(data.ddd.length > 0 && data.number.length > 0){

                    phone = '('+ data.ddd +') '+data.number;

                }

                return phone;

            }  

        }

        function get_paginate_list(config){

            var limit_itens = 5;

            var pages = [];
            var page;

            // First
            pages.push(1);

            // Middle
            if(config.total_pages > limit_itens){

                var init = ( (config.current_page - 2) < 2 ? 2 : (config.current_page - 2));
                var end = ( (init + limit_itens) > config.total_pages ? config.total_pages : (init + limit_itens) );

                if(init > 2){
                    pages.push('..');
                }

                for(page = init; page < end; page++){
                    pages.push(page);
                }

                if(end < config.total_pages){
                    pages.push('..');
                }

            }else{
                for(page = 2; page < config.total_pages; page++){
                    pages.push(page);
                }                                
            }

            // Last
            pages.push(config.total_pages);

            return pages;

        }


    }

})();