(function () {
    'use strict';

    angular
        .module('app')
        .factory('GlobalServices', GlobalServices);

    function GlobalServices() {

        var service = {};

        service.hierarchical_decode = hierarchical_decode;
        service.phone_parser = phone_parser;


        return service;
        
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

    }

})();