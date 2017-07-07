/**
 * Created by Lahray on 6/27/2017.
 */

/**
 Base Template for Creating Modules..
 To use, rename ParentsModule to desiredModule name and add necessary code..
 */
var ParentsModule = angular.module("ParentsModule",[
    'ngLahray',
    'ui.router',
    'ngRoute'
]);

var BASE_URL = APP_BASE_URL;

ParentsModule.factory("ENDPOINTS", function(BASE_URL){
    BASE_URL = BASE_URL.endsWith("/") ? BASE_URL  : BASE_URL + "/";

    HOST = BASE_URL + "index.php/api/parents/";
    return {
        LOGIN : HOST + "access_token"
    }
});

ParentsModule.factory("PARTIALS", function(){
    BASE_URL = BASE_URL.endsWith("/") ? BASE_URL  : BASE_URL + "/";

    PATH = BASE_URL + "assets/partials/parents/";
    return {
        DASHBOARD : PATH + 'dashboard.html',
        LOGIN     : PATH + 'login.html'
    }
});


ParentsModule.config(function($stateProvider, $urlRouterProvider, PARTIALSProvider) {
    $stateProvider
        .state('dashboard',{
            url : '/dashboard',
            templateUrl : PARTIALSProvider.$get().DASHBOARD
        })
        .state('login',{
            url: '/login',
            templateUrl : PARTIALSProvider.$get().LOGIN
        });

    $urlRouterProvider.otherwise('login');

});


ParentsModule.controller("ParentsLoginController",["$scope","$rootScope", 'ParentsLoginService','$mdToast','$cookieStore','$state',
    function($scope, $rootScope, ParentsLoginService, $mdToast, $cookieStore, $state){

        $scope.loading = false;

        $scope.loginData = {
            serial : '',
            pin : ''
        };

        $scope.getAccess = function(){
            $scope.loading = true;
            ParentsLoginService.getAccess($scope.loginData).then(function(response){
                $scope.loading = false;
                if(response.success) {
                    $cookieStore.put('student_info', response.data);
                    $state.go('dashboard');
                }
                else {
                    $scope.showToastMessage(response.msg);
                }
            }, function(error){
                $scope.loading = false;
                $scope.showToastMessage(error.msg);
            })
        };


        $scope.showToastMessage = function(msg){
            var toast = $mdToast.simple()
                .textContent(msg);
            $mdToast.show(toast);
        }

    }]);


ParentsModule.controller("DashboardController", ["$scope","$cookieStore","SessionsService","ClassesService","TermsService",
    function($scope, $cookieStore, SessionsService, ClassesService, TermsService){

    var cookieData =  $cookieStore.get("student_info");
    $scope.selected = cookieData.primary;
    $scope.students = cookieData.students;

    $scope.resultForm = {
        session_id:'',
        term_id : '',
        class_id : ''
    };

    $scope.schoolSessions = [];
    $scope.schoolTerms = [];
    $scope.schoolClasses = [];

    SessionsService.getAll(function(response){
        $scope.schoolSessions = response.data;
    });

    ClassesService.getAll(function(response){
        $scope.schoolClasses = response.data;
    });

    TermsService.getAll(function(response){
        $scope.schoolTerms = response.data;
    });


    $scope.getResults = function () {
        console.log($scope.resultForm);
    }

}]);


ParentsModule.service("ParentsLoginService", ["WebService",'ENDPOINTS',
    function(WebService,ENDPOINTS){

        this.getAccess = function(data, successFunc, errorFunc) {
            return WebService.get(ENDPOINTS.LOGIN, data);
        }

    }]);
