/**
Base Template for Creating Modules.. 
To use, rename NewModule to desiredModule name and add necessary code..
*/

var NewModule = angular.module("NewModule",[
    'ngLahray',
]);

NewModule.factory("MODULE_ENDPOINTS", function(BASE_URL){
	BASE_URL = BASE_URL.endsWith("/") ? BASE_URL  : BASE_URL + "/";

	HOST = BASE_URL + "index.php/api/module/"
	return {
	}
})


NewModule.factory("MODULE_PARTIALS", function(BASE_URL){
	BASE_URL = BASE_URL.endsWith("/") ? BASE_URL  : BASE_URL + "/";

	PATH = BASE_URL + "assets/partials/module/"
	return {
	}
})



NewModule.controller("ModuleCtrl1",["$scope","$rootScope", 'ModuleService1',
	function($scope, $rootScope, ModuleService1){

	}]);



NewModule.service("ModuleService1", ["WebService",'MODULE_ENDPOINTS',
	function(WebService,STUDENT_ENDPOINTS){


	}])


NewModule.directive("searchForStudent",['ModuleService1','MODULE_ENDPOINTS', 
	function(ModuleService1, MODULE_PARTIALS){
	return {
		restrict : 'AEC',
		scope    : {
			selectAction : '&'
		},
		templateUrl : MODULE_PARTIALS.SEARCH,
		link : function(scope, elem, attrs){

		}
	}
}]);