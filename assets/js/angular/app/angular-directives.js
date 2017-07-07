/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


myApp.directive("actor",['$cookies',function($cookies){
    return {
        restrict : 'A',
        link : function(scope,elem,attrs){
            var user = $cookies.getObject("user");
            var roles = attrs.actor.split("|");
            if(roles.indexOf(user.role) < 0){
                elem.remove();
            }
        }
    }    
}]);        


myApp.directive("schoolInfo",['$rootScope','APP_ENDPOINTS',function($rootScope,APP_ENDPOINTS){
    return {
        restrict : 'A',
        link     : function(scope,elem,attrs){
            $rootScope.$on("SCHOOL_INFO_RETREIVED",function(sender,args){
                $rootScope.schoolInfo = args;
                elem.val($rootScope.schoolInfo[attrs.schoolInfo]);
            });
        }
    }
}])



myApp.directive("subjectsDropdown",['$rootScope','APP_ENDPOINTS','PARTIALS','WebService',
    function($rootScope,APP_ENDPOINTS,PARTIALS,WebService){
    return {
        restrict      : 'AE',
        templateUrl   : PARTIALS.SUBJECTS_DROPDOWN,
        link     : function(scope,elem,attrs){
            WebService.get(APP_ENDPOINTS.SCHOOL_SETUP + "subjects").then(function(response){
                scope.subjects = response.data;
            },function(error){
                console.log(error);
            }).finally(function(){
                $rootScope.loading = false;
            })
        }
    }
}]);



myApp.directive("schoolSetup",['$rootScope','APP_ENDPOINTS','PARTIALS','WebService',
    function($rootScope,APP_ENDPOINTS,PARTIALS,WebService){
        return {
            restrict       : 'AE',
            scope          : {},
            replace        : true,
            templateUrl    : PARTIALS.SCHOOL_SETUP,
            link           : function(scope,elem,attrs){

                scope.valueKey = attrs.valueKey;
                scope.displayKey = attrs.displayKey;
                scope.selection = String(attrs.which).toUpperCase();
                if($rootScope[attrs.which] == undefined) {
                    WebService.get(APP_ENDPOINTS.SCHOOL_SETUP + attrs.which).then(function(response){
                        scope.records = response.data;
                        $rootScope[attrs.which] = response.data;
                    },function(error){
                        console.log(error);
                    }).finally(function(){
                        $rootScope.loading = false;
                    })
                }
                else {
                    scope.records = $rootScope[attrs.which];
                }
            }
        }
}]);


/*
myApp.directive("scoreInput",['$timeout',function($timeout){
    return {
        restrict : 'AEC',
        scope    : {},
        link     : function(scope,elem,attrs){

            $(elem).keyup(function(e){
                minScore = parseInt(attrs.min);
                maxScore = parseInt(attrs.max);
                oldValue = parseInt(attrs.value);
                val = parseInt($(elem).val());
                if(val < minScore || val > maxScore){
                    $(elem).attr("data-original-title","<span class='text-sm'> Valid range: " + minScore + " - " + maxScore + "</span>");
                    $(elem).tooltip('show');
                    $(elem).val(oldValue);
                    $timeout(function(){
                       $(elem).tooltip('destroy');
                    },2000);
                }
            })
        }
    }
}])
*/
