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
        LOGIN : HOST + "access_token",
        STUDENT_RESULT: HOST + "student_result"
    }
});

ParentsModule.config(function($mdThemingProvider){
   // $mdThemingProvider.theme('default')
   //     .primaryPalette('pink')
   //     .accentPalette('orange')
});

ParentsModule.factory("PARTIALS", function(){
    BASE_URL = BASE_URL.endsWith("/") ? BASE_URL  : BASE_URL + "/";

    PATH = BASE_URL + "assets/partials/parents/";
    return {
        DASHBOARD : PATH + 'dashboard.html',
        STUDENT : PATH + 'student-view.html',
        LOGIN     : PATH + 'login.html'
    }
});


ParentsModule.config(function($stateProvider, $urlRouterProvider, PARTIALSProvider) {
    $stateProvider
        .state('index',{
            url : '/index',
            templateUrl : PARTIALSProvider.$get().DASHBOARD
        })
        .state('student',{
            parent: 'index',
            url : '/student',
            templateUrl : PARTIALSProvider.$get().STUDENT
        })
        .state('login',{
            url: '/login',
            templateUrl : PARTIALSProvider.$get().LOGIN
        });

    $urlRouterProvider.otherwise('login');

});


ParentsModule.controller("ParentsLoginController",["$scope","$rootScope", 'ParentsLoginService','$mdToast','$cookies','$state',
    function($scope, $rootScope, ParentsLoginService, $mdToast, $cookies, $state){

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
                    $cookies.putObject('student_info', response.data);
                    $state.go('index');
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


ParentsModule.controller("DashboardController", ["$rootScope","$scope","$cookies","SessionsService","ClassesService","TermsService","$state",
    function($rootScope,$scope, $cookies, SessionsService, ClassesService, TermsService, $state){

    var cookieData =  $cookies.getObject("student_info");
    var students = cookieData.students;
    if(students && students.length > 1){
    }
    else {
        $rootScope.currentStudent = cookieData.primary;
        $state.go('student');
    }

    $scope.selected = cookieData.primary;
    $scope.students = cookieData.students;

    $scope.logout = function(){
        $cookies.remove();
        $state.go('login');
    };
}]);


ParentsModule.controller("StudentController", ["$rootScope", "$scope", "$state","StudentsService","ReportsService","TermsService", "SessionsService",
    function($rootScope, $scope, $state, StudentsService, ReportsService, TermsService, SessionsService){

        $scope.loading = false;

        $scope.studentData = {
            id : '',
            'name' : ''
        };
        $scope.studentData = $rootScope.currentStudent;

        $scope.getStudentsProfile = function(){
            $scope.loading = true;
            StudentsService.get($scope.studentData.student_id).then(function(response) {
                $scope.loading = false;
                if(response.success){
                    $scope.studentData.student_name = response.data.student_name;
                    $scope.studentData.class_name = response.data.class.class_name;
                    $scope.studentData.image = response.data.image;
                    $scope.studentData.gender = response.data.gender;
                    $scope.loading = true;
                }
                else {
                    $scope.loading = false;
                    console.log("error occurred");
                }
            }, function(error){
                $scope.loading = false;
            });
        };

        $scope.getStudentsProfile();

        $scope.resultRequest = {
            student_id: '',
            session_id : '',
            term_id : ''
        };

        $scope.result = {};

        $scope.isCumulative = false;

        $scope.$watch("resultRequest.term_id", function(newValue, oldValue){
            console.log(newValue);
            if(newValue != oldValue) {
                $scope.getResults();
            }
        });


        $scope.getResults = function(){
            $scope.loading = true;
            $scope.resultRequest.student_id = $scope.studentData.student_id;

            ReportsService.getStudentsReport($scope.resultRequest).then(function(response){
                $scope.loading = false;
                if(response.success){
                    $scope.result = response.data;
                    $scope.isCumulative = response.data.is_cumulative;
                }
            }, function(response){
                $scope.loading = false;
                console.log("error occurred");
            })
        };

        $scope.getResults();

        $scope.getDefaultImage = function () {
            var image = "";
            if($scope.studentData.gender){
                var gender = $scope.studentData.gender.toLowerCase();
                image = "uploads/students/default-" + gender + ".png";
            }
            return image;
        };

        $scope.sessions = [];
        $scope.terms = [];

        SessionsService.getAll(function (response) {
            if(response.success){
                $scope.sessions = response.data;
            }
        });

        TermsService.getAll(function(response){
            if(response.success){
                $scope.terms = response.data;
            }
        });

    }]);


ParentsModule.service("ParentsLoginService", ["WebService",'ENDPOINTS',
    function(WebService,ENDPOINTS){

        this.getAccess = function(data, successFunc, errorFunc) {
            return WebService.get(ENDPOINTS.LOGIN, data);
        }
    }]);
