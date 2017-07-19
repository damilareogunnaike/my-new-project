/**
Base Template for Creating Modules.. 
To use, rename ReportsModule to desiredModule name and add necessary code..
*/

var ReportsModule = angular.module("ReportsModule",[
    'ngLahray',
    'ngSanitize'
]);

ReportsModule.factory("REPORTS_ENDPOINTS", function(BASE_URL){
    BASE_URL = BASE_URL.endsWith("/") ? BASE_URL  : BASE_URL + "/";

    HOST = BASE_URL + "index.php/api/reports/";
    return {
        CLASS_REPORT : HOST + "class_report",
        STUDENT_REPORT : HOST + "student_report"
    }
});


ReportsModule.factory("REPORTS_PARTIALS", function(BASE_URL){
    BASE_URL = BASE_URL.endsWith("/") ? BASE_URL  : BASE_URL + "/";

    PATH = BASE_URL + "assets/partials/reports/";
    return {
    }
});



ReportsModule.controller("ReportsCtrl",["$scope","$rootScope", 'ReportsService','AlertService',
    function($scope, $rootScope, ReportsService, AlertService){

        $scope.getClassReport = function(){
            $scope.loading = true;


            $scope.classReportData = {data: [], msg : ''};

            ReportsService.getClassReport($scope.classReport, function(response){
                console.log(response);
                $scope.classReportData.data = response.data;
                $scope.classReportData.msg = response.msg;
            
            }, function(error){
                AlertService.error("Error loading report. Please retry!");
            }).finally(function(){
                $scope.loading = false;
            })
        }
    }]);



ReportsModule.service("ReportsService", ["WebService",'REPORTS_ENDPOINTS',
    function(WebService,REPORTS_ENDPOINTS){

        this.getClassReport = function(requestObj, successFunc, errorFunc){
            return WebService.get(REPORTS_ENDPOINTS.CLASS_REPORT, requestObj).then(function(response){
                if(successFunc != undefined){
                    successFunc(response);
                }
            }, function(error){
                errorFunc(error);
            })
        };

        this.getStudentsReport = function(reqObj){
            return WebService.get(REPORTS_ENDPOINTS.STUDENT_REPORT, reqObj);
        }

    }]);
