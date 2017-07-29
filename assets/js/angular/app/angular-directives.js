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
                var param = attrs.schoolInfo;
                if(!$rootScope.schoolInfo.hasOwnProperty(param)){
                    //Attempt appending the prefix 'school' as it might not have been set.
                    param = "school_" + param;
                }
                var value = $rootScope.schoolInfo[param];
                if(attrs.property){
                    elem.prop(attrs.property, value);
                }
                else {
                    elem.val(value);
                }
            });
        }
    }
}]);



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

myApp.directive("position", function(){
    return {
        restrict : 'AE',
        replace : true,
        scope : {},
        template : '<span class="position">{{value}}</span>',
        link: function(scope, elem, attrs){

            var position = new String(attrs.value) || 0;
            if(position == 0){
                scope.value = "N-th"
            }
            else if(position.endsWith("1")){
                scope.value = position + "st";
            }
            else if(position.endsWith("2")){
                scope.value = position + "nd";
            }
            else if(position.endsWith("3")) {
                scope.value = position + "rd";
            }
            else {
                scope.value = position + "th";
            }
        }
    }
});

myApp.directive("grade", function(){
    return {
        restrict : 'AE',
        replace : true,
        scope : {},
        template : "<span ng-class=\"{failed:isGrade('F'), passed:isGrade('D'), average : isGrade('C'), good:isGrade('B'), excellent:isGrade('A')}\"><strong>{{grade}}</strong></span>",
        link: function(scope, elem, attrs){

            var totalScore = attrs.totalScore || 0;

            scope.totalScore = totalScore;
            scope.grade = "F";

            scope.isGrade = function(grade){
                return scope.grade == grade;
            };

            if(totalScore >= 70){
                scope.grade = "A";
            }
            else if(totalScore < 70 && totalScore >= 60){
                scope.grade = "B";
            }
            else if(totalScore < 60 && totalScore >= 50){
                scope.grade = "C";
            }
            else if(totalScore < 50 && totalScore >= 40) {
                scope.grade = "D";
            }
            else {
                scope.grade = "F";
            }
        }
    }
});



myApp.directive("comment", function(){
    return {
        restrict : 'AE',
        replace : true,
        scope : {},
        template : "<span ng-class=\"{failed:isGrade('F'), passed:isGrade('D'), average : isGrade('C'), good:isGrade('B'), excellent:isGrade('A')}\"><strong>{{comment}}</strong></span>",
        link: function(scope, elem, attrs){

            var totalScore = attrs.totalScore || 0;

            scope.totalScore = totalScore;
            scope.grade = "F";
            scope.comment = "Fair";

            scope.isGrade = function(grade){
                return scope.grade == grade;
            };

            if(totalScore >= 70){
                scope.grade = "A";
                scope.comment = "Distinction";
            }
            else if(totalScore < 70 && totalScore >= 60){
                scope.grade = "B";
                scope.comment = "Excellent";
            }
            else if(totalScore < 60 && totalScore >= 50){
                scope.grade = "C";
                scope.comment = "Good";
            }
            else if(totalScore < 50 && totalScore >= 40) {
                scope.grade = "D";
                scope.comment = "Pass";
            }
            else {
                scope.grade = "F";
                scope.comment = "Fair";
            }

            function get_comment($score)
            {
                if($score <= 100 && $score >= 70) { return success_text("Distinction"); }
                else if($score < 70 && $score >= 60) { return info_text("Excellent"); }
                else if($score < 60 && $score >= 50) { return default_text ("Good");}
                else if($score < 50 && $score >= 40) { return warning_text ("Pass");}
                else if($score < 40 ) { return orange_text ("Fair"); }
            }
        }
    }
});


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
