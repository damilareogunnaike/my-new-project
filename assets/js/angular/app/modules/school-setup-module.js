var SchoolSetupModule = angular.module("SchoolSetupModule",[
    'ngLahray'
]);

SchoolSetupModule.factory("SETUP_ENDPOINTS", function(BASE_URL){
	BASE_URL = BASE_URL.endsWith("/") ? BASE_URL  : BASE_URL + "/";

	HOST = BASE_URL + "index.php/api/school_setup/";
	return {
		CLASSES    : HOST + "classes/",
		SUBJECTS    : HOST + "subjects/",
		SCHOOL_SESSION    : HOST + "sessions/",
		CLASS_SETTING: HOST + "class_setting/",
		CLASS_SUBJECTS : HOST + "class_subjects/",
		CLASS_SUBJECTS_AGGR : HOST + "class_subjects_aggregate/",
		STUDENT_SUBJECTS : HOST + "student_subjects/",
		SCHOOL_TERMS : HOST + "school_terms/"
	}
});


SchoolSetupModule.factory("SETUP_PARTIALS", function(BASE_URL){
	BASE_URL = BASE_URL.endsWith("/") ? BASE_URL  : BASE_URL + "/";

	PATH = BASE_URL + "assets/partials/school-setup/";
	return {
		SUBJECT_MODES_INFO    : PATH + "subject-modes-info.html",
		CLASS_SUBJECTS  	  : PATH + "class-subjects.html",
		STUDENT_SUBJECTS  	  : PATH + "student-subjects.html"
	}
});


SchoolSetupModule.value("SUBJECT_SELECTION_MODES",[
	{
		'name' : 'Mode 1 (Default)',
		'value': 1,
		'description': "All students inherit subjects when a teacher is assigned to teach the subject for that class."
	},

	{
		'name': 'Mode 2 (Selective Subjects)',
		'value': 2,
		'description': "Compulsory subjects are mapped to a class, which are automatically inherited by the students. Students in this class can also select more subjects optionally."
	}
]);


SchoolSetupModule.run(['$rootScope','ClassesService',
	function($rootScope, ClassesService){
		if($rootScope['classes'] == undefined){
			ClassesService.getAll(function(response){
				$rootScope.classes = response.data;
			},function(error){
				console.log(error);
			})
		}
	}]);


SchoolSetupModule.service("ClassesService", ["WebService",'SETUP_ENDPOINTS',
	function(WebService,SETUP_ENDPOINTS){

		this.getAll = function(successFunc, errorFunc){
			return WebService.get(SETUP_ENDPOINTS.CLASSES).then(function(response){
				successFunc(response);
			},function(error){
				errorFunc(error);
			});
		};

		this.update  = function(classId, classObj){
			return WebService.update(SETUP_ENDPOINTS.CLASSES, {"classId": classId, "classObj": classObj});
		};

		this.add = function(classObj){
			return WebService.post(SETUP_ENDPOINTS.CLASSES, {"classObj": classObj});
		}
}]);


SchoolSetupModule.service("SubjectsService", ["WebService",'SETUP_ENDPOINTS',
	function(WebService,SETUP_ENDPOINTS){

		this.getAll = function(successFunc, errorFunc){
			return WebService.get(SETUP_ENDPOINTS.SUBJECTS).then(function(response){
				successFunc(response);
			},function(error){
				errorFunc(error);
			});
		};

		this.update  = function(subjectId, subjectObj){
			return WebService.update(SETUP_ENDPOINTS.SUBJECTS, {"subjectId": subjectId, "subjectObj": subjectObj});
		};

		this.add = function(subjectObj){
			return WebService.post(SETUP_ENDPOINTS.SUBJECTS, {"subjectObj": subjectObj});
		}
}]);



SchoolSetupModule.service("SessionsService", ["WebService",'SETUP_ENDPOINTS',
	function(WebService,SETUP_ENDPOINTS){

		this.getAll = function(successFunc, errorFunc){
			return WebService.get(SETUP_ENDPOINTS.SCHOOL_SESSION).then(function(response){
				if(successFunc) {
					successFunc(response);
                }
			},function(error){
				if(errorFunc){
					errorFunc(error)
                }
			});
		};

		this.update  = function(sessionId, sessionObj){
			return WebService.update(SETUP_ENDPOINTS.SCHOOL_SESSION, {"sessionId": sessionId, "sessionObj": sessionObj});
		};

		this.add = function(sessionObj){
			return WebService.post(SETUP_ENDPOINTS.SCHOOL_SESSION, {"sessionObj": sessionObj});
		}
}]);


SchoolSetupModule.service("TermsService", ["WebService",'SETUP_ENDPOINTS',
	function(WebService,SETUP_ENDPOINTS){

		this.getAll = function(successFunc, errorFunc){
			return WebService.get(SETUP_ENDPOINTS.SCHOOL_TERMS).then(function(response){
				successFunc(response);
			},function(error){
				errorFunc(error)
			});
		};

		this.update  = function(termId, termObj){
			return WebService.update(SETUP_ENDPOINTS.SCHOOL_TERMS, {"termId": termId, "termObj": termObj});
		};

		this.add = function(termObj){
			return WebService.post(SETUP_ENDPOINTS.SCHOOL_TERMS, {"termObj": termObj});
		}

}]);


SchoolSetupModule.service("SubjectConfigService",["WebService", "SETUP_ENDPOINTS", "SETUP_PARTIALS", 'AlertService', '$uibModal',
	function(WebService, SETUP_ENDPOINTS, SETUP_PARTIALS, AlertService, $uibModal){
		
		this.updateClassSetting = function(requestObj, successFunc, errorFunc){
			return WebService.put(SETUP_ENDPOINTS.CLASS_SETTING, requestObj).then(function(response){
				if(successFunc != undefined){
					successFunc(response);
				}
			}, function(error){
				if(errorFunc != undefined){
					errorFunc(error);
				}
			});
		}


		this.saveClassSubjects = function(requestObj, successFunc, errorFunc){
			return WebService.post(SETUP_ENDPOINTS.CLASS_SUBJECTS, requestObj).then(function(response){
				if(successFunc != undefined){
					successFunc(response);
				}
			}, function(error){
				if(errorFunc != undefined){
					errorFunc(error);
				}
			});
		}


		this.getClassSubjects = function(classId, successFunc, errorFunc){
			return WebService.get(SETUP_ENDPOINTS.CLASS_SUBJECTS + classId).then(function(response){
				if(successFunc != undefined){
					successFunc(response);
				}
			}, function(error){
				if(errorFunc != undefined){
					errorFunc(error);
				}
			});
		}


		this.viewClassSubjects = function(classObj){
			this.getClassSubjects(classObj.class_id, function(response){
				$modalInstance = $uibModal.open({
					templateUrl : SETUP_PARTIALS.CLASS_SUBJECTS,
					size : 'md',
					resolve : {
						'classObj' : function() {
							return classObj;
							},
						'classSubjects' : function() {
							return response.data
							}
					},
					controller : 'ClassSubjectsCtrl'
				})
			})
		}


		this.removeClassSubject = function(recordId, successFunc, errorFunc){
			return WebService.delete(SETUP_ENDPOINTS.CLASS_SUBJECTS + recordId).then(function(response){
				if(successFunc != undefined){
					successFunc(response);
				}
			}, function(error){
				if(errorFunc != undefined){
					errorFunc(error);
				}
			});
		}

		this.saveStudentSubjects = function(requestObj, successFunc, errorFunc){
			return WebService.post(SETUP_ENDPOINTS.STUDENT_SUBJECTS, requestObj).then(function(response){
				if(successFunc != undefined){
					successFunc(response);
				}
			}, function(error){
				if(errorFunc != undefined){
					errorFunc(error);
				}
			});
		}

		this.getStudentSubjects = function(studentId, successFunc, errorFunc){
			return WebService.get(SETUP_ENDPOINTS.STUDENT_SUBJECTS + studentId).then(function(response){
				if(successFunc != undefined){
					successFunc(response);
				}
			}, function(error){
				if(errorFunc != undefined){
					errorFunc(error);
				}
			});
		}


		this.viewStudentSubjects = function(studentObj){
			this.getStudentSubjects(studentObj.student_id, function(response){
				$modalInstance = $uibModal.open({
					templateUrl : SETUP_PARTIALS.STUDENT_SUBJECTS,
					size : 'md',
					resolve : {
						'studentObj' : function() {
							return studentObj;
							},
						'studentSubjects' : function() {
							return response.subjects
							}
					},
					controller : 'StudentSubjectsCtrl'
				})
			})
		}

		this.removeStudentSubject = function(recordId, successFunc, errorFunc){
			return WebService.delete(SETUP_ENDPOINTS.STUDENT_SUBJECTS + recordId).then(function(response){
				if(successFunc != undefined){
					successFunc(response);
				}
			}, function(error){
				if(errorFunc != undefined){
					errorFunc(error);
				}
			});
		}


		/**
		This is applicable for classes where the students have optional
		subjects. This gets a list of compulsory subjects for the class as well
		as all optional subjects offered by the class students..
		*/
		this.getClassSubjectsAggr = function(classId, successFunc, errorFunc){
			return WebService.get(SETUP_ENDPOINTS.CLASS_SUBJECTS_AGGR + classId).then(function(response){
				if(successFunc != undefined){
					successFunc(response);
				}
			}, function(error){
				if(errorFunc != undefined){
					errorFunc(error);
				}
			});
		}

	}]);


SchoolSetupModule.controller("ClassSubjectsCtrl", ['$scope', 'classObj', 'classSubjects', '$uibModalInstance', 'SubjectConfigService', 'Utils', 'AlertService',
	function($scope, classObj, classSubjects, $uibModalInstance, SubjectConfigService, Utils, AlertService){
		$scope.classObj = classObj;
		$scope.classSubjects = classSubjects;
		$scope.close = function(){
			$uibModalInstance.close();
		};

		$scope.removeClassSubject = function(subject){
			SubjectConfigService.removeClassSubject(subject.record_id, function(response){
				if(response.data.status == true){
					Utils.removeEntry($scope.classSubjects, subject);
				}
				else {
					AlertService.error("Operation not successful!");
				}
			})
		}
	}]);


SchoolSetupModule.controller("StudentSubjectsCtrl", ['$scope', 'studentObj', 'studentSubjects', '$uibModalInstance', 'SubjectConfigService', 'Utils', 'AlertService',
	function($scope, studentObj, studentSubjects, $uibModalInstance, SubjectConfigService, Utils, AlertService){
		$scope.studentObj = studentObj;
		$scope.studentSubjects = {"optional": studentSubjects.optional, "compulsory": studentSubjects.compulsory};

		$scope.close = function(){
			$uibModalInstance.close();
		};

		$scope.removeStudentSubject = function(subject){
			SubjectConfigService.removeStudentSubject(subject.record_id, function(response){
				if(response.data.status == true){
					Utils.removeEntry($scope.studentSubjects.optional, subject);
				}
				else {
					AlertService.error("Operation not successful!");
				}
			})
		}
	}]);


SchoolSetupModule.directive("terms",['$rootScope','TermsService',function($rootScope,TermsService){
    return {
        restrict : 'AE',
        scope : {},
        replace  : true,
        template : "<select class='form-control' ng-options='term[valueKey] as term[displayKey] for term in terms'></select>",
        link     : function(scope,elem,attrs){
        	scope.valueKey = "school_term_id";
        	scope.displayKey = "school_term_name";
        	scope.terms = [];
        	if($rootScope.terms == undefined){
        		TermsService.getAll(function(response){
        			scope.terms = response.data;
        			$rootScope.terms = response.data;
        		}, function(error){
        			console.log("Error fetching terms");
        		})
        	}
        	else {
        		scope.terms = $rootScope.terms;
        	}
        }
    }
}]);


SchoolSetupModule.directive("classes",['$rootScope','ClassesService',function($rootScope,ClassesService){
    return {
        restrict : 'AE',
        replace  : true,
        scope : {},
        template : "<select class='form-control' ng-options='getValue(class)  as class[displayKey] for class in classes'></select>",
        link     : function(scope,elem,attrs){
        	scope.valueKey = (attrs.valueKey) ? attrs.valueKey : "class_id";
        	scope.displayKey = "class_name";
        	scope.classes = [];
        	if($rootScope.classes == undefined){
        		ClassesService.getAll(function(response){
        			scope.classes = response.data;
        			$rootScope.classes = response.data;
        		})
        	}
        	else {
        		scope.classes = $rootScope.classes;
        	}

        	scope.getValue = function(obj){
        		if(scope.valueKey == "object"){
        			return obj;
        		}
        		else return obj[scope.valueKey];
        	}
        }
    }
}]);
	

SchoolSetupModule.directive("sessions",['$rootScope','SessionsService',function($rootScope,SessionsService){
    return {
        restrict : 'AE',
        replace  : true,
        scope    : {},
        template : "<select class='form-control' ng-options='session[valueKey] as session[displayKey] for session in sessions'></select>",
        link     : function(scope,elem,attrs){
        	scope.valueKey = "school_session_id";
        	scope.displayKey = "school_session_name";
        	scope.sessions = [];
        	SessionsService.getAll(function(response){
        		scope.sessions = response.data;
        	})
        }
    }
}]);


SchoolSetupModule.directive("subjectSelectionModesInfo", ['SUBJECT_SELECTION_MODES','SETUP_PARTIALS',
 function(SUBJECT_SELECTION_MODES,SETUP_PARTIALS){
 	return {
 		restrict : 'AE',
 		scope : {},
 		templateUrl : SETUP_PARTIALS.SUBJECT_MODES_INFO,
 		controller : function($scope, SUBJECT_SELECTION_MODES){
 			$scope.subjectSelectionModes = SUBJECT_SELECTION_MODES;
 		}
 	}
 }]);


SchoolSetupModule.directive("subjectSelectionModes",['SUBJECT_SELECTION_MODES',function(SUBJECT_SELECTION_MODES){
	return {
		restrict : 'AE',
		replace  : true,
		scope    : {
			selectedMode : '='
		},
		template : "<select><option ng-selected='selectedMode == selection.value' ng-repeat='selection in selectionModes' ng-value='selection.value' ng-bind='selection.name'></option></select>",
		link     : function(scope, elem, attrs){
			scope.selectionModes = SUBJECT_SELECTION_MODES;

			scope.$watch("selectedMode", function(newValue, oldValue){
				console.log(newValue);
			})
		}
	}
}]);


SchoolSetupModule.filter("subjectSelectionMode",function(SUBJECT_SELECTION_MODES,filterFilter){
	return function(input){
		var selectionMode = filterFilter(SUBJECT_SELECTION_MODES, {value:input});
		return selectionMode[0].name;
	}
});