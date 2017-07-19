var studentsModule = angular.module("StudentsModule",[
    'ngLahray'
]);

studentsModule.factory("STUDENT_ENDPOINTS", function(BASE_URL){
	BASE_URL = BASE_URL.endsWith("/") ? BASE_URL  : BASE_URL + "/";

	HOST = BASE_URL + "index.php/api/students/";
	return {
        GET           : HOST + "",
		GET_ALL       : HOST + "find_all/",
		SEARCH        : HOST + "search",
		UPDATE        : HOST + "update/",
		ADD           : HOST + "add",
		UPDATE_CLASS  : HOST + "update_class"
	}
});


studentsModule.factory("STUDENT_PARTIALS", function(BASE_URL){
	BASE_URL = BASE_URL.endsWith("/") ? BASE_URL  : BASE_URL + "/";

	PATH = BASE_URL + "assets/partials/student/";
	return {
		SEARCH    : PATH + "search-student.html"
	}
});



studentsModule.controller("StudentSearchCtrl",["$scope","$rootScope", 'StudentsService',
	function($scope, $rootScope, StudentsService){

		$scope.search = {keyword: ""};
		$scope.students = [];

		$scope.getStudents = function(){
			$rootScope.loading = true;
			StudentsService.search({"classId":$scope.search.classId, "sessionId":$scope.search.sessionId},function(response){
				$scope.students = extractStudentsData(response.data);
				$rootScope.loading = false;
			})
		};


		$scope.$watch("search.keyword",function(newValue, oldValue){
			if(newValue.length > 5){
				$rootScope.loading = true;
				StudentsService.search({"keyword" : newValue}, function(response){
					$scope.students = extractStudentsData(response.data);
					$rootScope.loading = false;
				});
			}
		});


		/*
		This sessionId has a two-way binding to an external scope that wants to set
		the value of search session id. This is used in scenarios where sessionId select control
		for searching for students is hidden, to avoid redundant selections.
		So this updates the internal sessionId with value passed from parent scope...
		*/
		$scope.$watch("sessionId", function(newValue, oldValue){
			$scope.search.sessionId = newValue;
		});

		var extractStudentsData = function(records){
			if(records == null || records.length < 1){
				return [];
			}
			return records.map(function(x) {
				return {"student_id":x.student_id, "student_name" : x.student_name, "current_class":x.current_class};
			})
		}

	}]);



studentsModule.service("StudentsService", ["WebService",'STUDENT_ENDPOINTS',
	function(WebService,STUDENT_ENDPOINTS){

		this.getAll = function(page, pageSize){
			return WebService.get(STUDENT_ENDPOINTS.GET_ALL);
		};


		/**
		searchObject : Object containing search parameters such as keyword, page, pageSize
		*/
		this.search = function(searchObject, successHandler, errorHandler){
			return WebService.get(STUDENT_ENDPOINTS.SEARCH, searchObject)
			.then(function(response){
				if (successHandler != undefined){
					successHandler(response);
				}

			}, function(error){
				console.log(error);
			})
		};


		this.get = function(studentId){
			return WebService.get(STUDENT_ENDPOINTS.GET, {"id": studentId});
		};


		this.update  = function(studentId, studentObj){
			return WebService.update(STUDENT_ENDPOINTS.UPDATE, {"studentId": studentId, "studentObj": studentObj});
		};


		this.add = function(studentObj){
			return WebService.post(STUDENT_ENDPOINTS.ADD, {"studentObj": studentObj});
		};


		this.updateClass = function(requestObj, successHandler){
			return WebService.put(STUDENT_ENDPOINTS.UPDATE_CLASS,requestObj).then(function(response){
				successHandler(response);
			})
		}

	}]);


studentsModule.directive("searchForStudent",['StudentsService','STUDENT_PARTIALS', 
	function(StudentsService, STUDENT_PARTIALS){
	return {
		restrict : 'AEC',
		scope    : {
			selectAction : '&',
			sessionId : '='
		},
		controller : 'StudentSearchCtrl',
		templateUrl : STUDENT_PARTIALS.SEARCH,
		link : function(scope, elem, attrs){
			scope.editSession = (attrs.editSession != undefined) ? eval(attrs.editSession) : true;
			if(!scope.editSession){
			}
		}
	}
}]);