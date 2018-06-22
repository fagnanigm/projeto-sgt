(function () {
    'use strict';

    angular
        .module('app')
        .factory('GlobalServices', GlobalServices);

    function GlobalServices() {

        var service = {};

        service.hierarchical_decode = hierarchical_decode;

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

    }

})();