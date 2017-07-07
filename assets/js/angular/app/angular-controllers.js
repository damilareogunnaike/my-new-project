/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


myApp.controller("LoginController",
['$scope','AlertService','$uibModal','BASE_URL','LANG','PARTIALS',
  function($scope,AlertService,$uibModal,BASE_URL,LANG,PARTIALS){
    
    $scope.login = function(){
        console.log($scope.loginData);
    };
    
}]);


myApp.controller("ClassChangeCtrl", ['$scope','$rootScope','AlertService','StudentsService',"Utils",
	function($scope, $rootScope, AlertService, StudentsService, Utils){

		$scope.destination = {studentIds:[], sessionId:'',classId:''};		
		$scope.migratingStudents = [];
		var migratingStudentIds = [];

		$scope.addRecord = function(studentRecord){
			if((migratingStudentIds.indexOf(studentRecord.student_id)) < 0){
				$scope.migratingStudents.push(studentRecord);
				migratingStudentIds.push(studentRecord.student_id);
			}
		};

		$scope.addAllStudents = function(students){
			angular.forEach(students, function(student){
				$scope.addRecord(student);
			})
		};


		$scope.removeRecord = function(studentRecord){
			Utils.removeEntry($scope.migratingStudents, studentRecord);
			Utils.removeEntry(migratingStudentIds, studentRecord.student_id);
		};

		$scope.changeClass = function(){
			if(AlertService.confirm("Changing students' class. Proceed ?")) {
				$scope.loading = true;
				$scope.destination.studentIds = $scope.migratingStudents.map(function(x){return x.student_id});
				StudentsService.updateClass($scope.destination, function(response){
					msg = "";
					if(response.data.updated > 0){
						msg += response.data.updated + " record(s) updated!";
					}
					if(response.data.inserted > 0){
						msg += response.data.inserted + " record(s) created!";
					}
					$scope.loading = false;
					AlertService.success(msg);
				}, function(error){
					console.log("Error!");
				})
			}
		};

		$scope.clearRecords = function(which){

			if(which == 'migratingStudents') {
				migratingStudentIds = [];
			}
			$scope[which] = [];
		}
	}]);


myApp.controller("ClassSubjectConfigCtrl",['$scope', '$rootScope', 'SubjectConfigService', 'ClassesService', 'SubjectsService', 'AlertService', 'SUBJECT_SELECTION_MODES', 'filterFilter','Utils',
	function($scope, $rootScope, SubjectConfigService, ClassesService, SubjectsService, AlertService, SUBJECT_SELECTION_MODES, filterFilter, Utils){

		$scope.classes = [];

		$scope.editSelectionMode = null;

		ClassesService.getAll(function(response){
			$scope.classes = response.data;
		})

		SubjectsService.getAll(function(response){
			$scope.subjects = response.data;
		})

		var editObjIndex = -1;

		$scope.editClassSetting = function(classObj){
			console.log(classObj);
			var editObj  = angular.copy(classObj);
			editObjIndex = $scope.classes.indexOf(classObj);
			$scope.editClassObj = editObj;
		}

		$scope.updateClassSetting = function(){
			$rootScope.loading = true;
			var reqObj = $scope.editClassObj;
			SubjectConfigService.updateClassSetting(reqObj, function(response){
				if(response.data.updated){
					$scope.classes[editObjIndex] = response.data.class;
					AlertService.success("Updated Successful!");
				}
				$rootScope.loading = false;

			},function(response){
				$rootScope.loading = false;
			})
		}


		$scope.selectedClasses = [];

		$scope.addToClassSelection = function(classObj){
			if($scope.selectedClasses.indexOf(classObj) < 0){
				$scope.selectedClasses.push(classObj);
			}
		}


		$scope.removeFromSelectedClasses = function(classObj){
			Utils.removeEntry($scope.selectedClasses, classObj);
		}


		$scope.selectedSubjects = [];
		$scope.addToSubjectSelection = function(subjectObj){
			if($scope.selectedSubjects.indexOf(subjectObj) < 0){
				$scope.selectedSubjects.push(subjectObj);
			}
		}


		$scope.removeFromSelectedSubjects = function(subjectObj){
			Utils.removeEntry($scope.selectedSubjects, subjectObj);
		}


		$scope.selectedStudents = [];
		$scope.addToStudentSelection = function(studentObj){
			if($scope.selectedStudents.indexOf(studentObj) < 0){
				$scope.selectedStudents.push(studentObj);
			}
		}


		$scope.removeFromSelectedStudents = function(studentObj){
			Utils.removeEntry($scope.selectedStudents, studentObj);
		}


		$scope.saveClassSubjects = function(){
			if(AlertService.confirm("Setting class subjects. Proceed ?")){
				var classIds = $scope.selectedClasses.map(function(x){return x.class_id;});
				var subjectIds = $scope.selectedSubjects.map(function(x){return x.subject_id;});
				var reqObj = {"class_ids":classIds, "subject_ids": subjectIds};
				$rootScope.loading = true;
				console.log(reqObj);
				SubjectConfigService.saveClassSubjects(reqObj, function(response){
					if(response.status == true){
						AlertService.success("Operation Successful!");
					}
					$rootScope.loading = false;
				}, function(error){
					console.log(error);
					$rootScope.loading = false;
				})
			}
		}


		$scope.saveStudentsSubjects = function(){
			if(AlertService.confirm("Setting Student Subjects. Proceed ?")) {
				$rootScope.loading = true;
				var studentIds = $scope.selectedStudents.map(function(x){return x.student_id});
				var subjectIds = $scope.selectedSubjects.map(function(x){return x.subject_id});
				var reqObj = {"student_ids" : studentIds, "subject_ids": subjectIds};
				SubjectConfigService.saveStudentSubjects(reqObj, function(response){
					if(response.status == true) {
						AlertService.success("Operation Successful!");
					}
					$rootScope.loading = false;
				}, function(error){
					console.log(error);
					$rootScope.loading = false;
				})
			}
		}


		$scope.viewClassSubjects = function(classObj){
			SubjectConfigService.viewClassSubjects(classObj);
		}


		$scope.viewStudentSubjects = function(studentObj){
			SubjectConfigService.viewStudentSubjects(studentObj);
		}
}])