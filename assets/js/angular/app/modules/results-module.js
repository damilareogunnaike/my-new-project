/**
Base Template for Creating Modules.. 
To use, rename ResultsModule to desiredModule name and add necessary code..
*/

var ResultsModule = angular.module("ResultsModule",[
    'ngLahray'
]);

ResultsModule.factory("RESULTS_ENDPOINTS", function(BASE_URL){
	BASE_URL = BASE_URL.endsWith("/") ? BASE_URL  : BASE_URL + "/";

	HOST = BASE_URL + "index.php/api/results/";
	return {
		FOR_STUDENT		     : HOST + "students_result/",
		FOR_CLASS_SUBJECT    : HOST + "class_subject_result/",
		COMPUTE_RESULT_STEPS : HOST + "compute_result_steps/",
		COMPUTE_RESULTS      : HOST + "compute_results/"
	}
});


ResultsModule.factory("RESULTS_PARTIALS", function(BASE_URL){
	BASE_URL = BASE_URL.endsWith("/") ? BASE_URL  : BASE_URL + "/";

	PATH = BASE_URL + "assets/partials/results/";
	return {
	}
});



ResultsModule.controller("ResultsCtrl",["$scope","$rootScope", 'ResultsService','AlertService','SubjectConfigService',
	function($scope, $rootScope, ResultsService, AlertService, SubjectConfigService){

		$scope.resultView = {"session_id":'', "term_id":""};
		$scope.resultForm = {};


		$scope.prepareFormForStudent = function(studentObj){
			console.log(studentObj);
			$scope.loading = true;

			$scope.resultView.student_id = studentObj.student_id;

			$scope.resultForm.student = studentObj;

			$scope.loading = true;
			ResultsService.getForStudent($scope.resultView, function(response){
				$scope.resultForm.scores = response.form;
				$scope.loading = false;
			}, function(error){
				$scope.loading = false;
			});
		};


		$scope.computeTotal = function(){
			var total = 0;
			for(var i = 0; i < arguments.length; i++){
				total += parseInt(arguments[i]);
			}
			return total;
		};


		$scope.updateScores = function(){
			$scope.loading = true;
			var requestObj = $scope.resultView;
			requestObj.scores = $scope.resultForm.scores;
			ResultsService.updateForStudent(requestObj, function(response){
				if(response.data.status === true){
					AlertService.success("Result saved successfully!");
				}
			}, function(error){
				console.log(error);
			}).finally(function(){
				$scope.loading = false;
			});
		}


		$scope.classSubjects = [];
		$scope.$watch("selection.class", function(newValue, oldValue){
			if(newValue !== undefined){
				$scope.loading = true;
				SubjectConfigService.getClassSubjectsAggr(newValue.class_id, function(response){
					$scope.classSubjects = response.data;
					if(angular.isArray($scope.classSubjects) && $scope.classSubjects.length > 0){
						$scope.selection.subject_id = $scope.classSubjects[0];
					}
					$scope.loading = false;
				}, function(error){
					$scope.loading = false;
				})
			}
		});


		$scope.classResultForm = {scores:[]};
		$scope.prepareFormForClassSubject = function(){
			$scope.loading = true;
			$scope.classResultForm.subject = $scope.selection.subject;
			$scope.classResultForm.class = $scope.selection.class;
			var requestObj = {'subject_id':$scope.selection.subject.subject_id, 'class_id':$scope.selection.class.class_id,
							  'session_id':$scope.selection.session_id, 'term_id':$scope.selection.term_id};

			ResultsService.getForClassSubject(requestObj, function(response){
				if(response != undefined && response.form != undefined && angular.isArray(response.form) ){
					$scope.classResultForm.scores = response.form;
					$scope.classResultForm.selection = requestObj;
				} else {
					AlertService.msg("No records found for this selection!");
				}
				$scope.loading = false;
			}, function(error){
				$scope.loading = false;
				console.log(error);
			})
		}


		$scope.updateClassSubjectScores = function(){
			$scope.loading = true;
			ResultsService.updateForClassSubject($scope.classResultForm, function(response){
				if(response.data.status == true){
					AlertService.success("Result saved successfully!");
				}
			}, function(error){
				console.log(error);
			}).finally(function(){
				$scope.loading = false;
			});
		}


	}]);


ResultsModule.controller("ResultsComputationCtrl", ['$scope','ResultsComputationService',
 function($scope, ResultsComputationService){

 	var complete = false;
 	$scope.computationParam = {};

 	$scope.progress = [];

 	$scope.computeReport = function(){

 		$scope.computingResults = true;
		ResultsComputationService.computeReportSteps($scope.computationParam, function(response){

			$scope.steps = response.steps;
			$scope.computationParam.step = eval(response.next_step);

			$scope.doReportComputation();
			
		}, function(error){
			console.log(error);
		})
 	};


 	$scope.doReportComputation = function(){
 		var computeCompleted = false;
 		ResultsComputationService.computeReport($scope.computationParam, function(response){
 			console.log(response);
			var executedStep = response.executed_step;
			if(executedStep.completed && !computeCompleted) {
				$scope.steps[executedStep.index] = executedStep;

				if($scope.steps[executedStep.next_step] != undefined){
					$scope.steps[executedStep.next_step]['in_progress'] = true;
					$scope.computationParam.step = eval(executedStep.next_step);
				}	
			}
			computeCompleted = eval(response.compute_completed);
			
			if(!computeCompleted) {
				$scope.doReportComputation();
			}

		}, function(error){
 			computeCompleted = true;
 			$scope.computeError = true;
 			$scope.computeErrorMsg = "Could not complete computation. Unknown error occured.";
			console.log(error);
		})
 	}
}]);

ResultsModule.service("ResultsService", ["WebService",'RESULTS_ENDPOINTS',
	function(WebService,RESULTS_ENDPOINTS){

		this.getForStudent = function(requestObj, successFunc, errorFunc){
			return WebService.get(RESULTS_ENDPOINTS.FOR_STUDENT, requestObj).then(function(response){
				successFunc(response);
			}, function(error){
				console.log("Error fetching student's result!");
				errorFunc(error);
			})
		};


		this.updateForStudent = function(requestObj, successFunc, errorFunc){
			return WebService.put(RESULTS_ENDPOINTS.FOR_STUDENT, requestObj).then(function(response){
				successFunc(response);
			}, function(error){
				console.log("Error updating scores!");
				errorFunc(error);
			})
		};


		this.getForClassSubject = function(requestObj, successFunc, errorFunc){
			return WebService.get(RESULTS_ENDPOINTS.FOR_CLASS_SUBJECT, requestObj).then(function(response){
				successFunc(response);
			}, function(error){
				console.log("Error fetching class' result!");
				errorFunc(error);
			})
		};


		this.updateForClassSubject = function(requestObj, successFunc, errorFunc){
			return WebService.put(RESULTS_ENDPOINTS.FOR_CLASS_SUBJECT, requestObj).then(function(response){
				successFunc(response);
			}, function(error){
				console.log("Error updating scores!");
				errorFunc(error);
			})
		}
	}]);


ResultsModule.service("ResultsComputationService", ["WebService", "RESULTS_ENDPOINTS", 
	function(WebService, RESULTS_ENDPOINTS){

		this.computeReport = function(requestObj, successFunc, errorFunc){
			return WebService.get(RESULTS_ENDPOINTS.COMPUTE_RESULTS, requestObj).then(function(response){
				successFunc(response);
			}, function(error){
				errorFunc(error);
			})
		};

		this.computeReportSteps = function(requestObj, successFunc, errorFunc){
			return WebService.get(RESULTS_ENDPOINTS.COMPUTE_RESULT_STEPS, requestObj).then(function(response){
				successFunc(response);
			}, function(error){
				errorFunc(error);
			})
		}
	}]);


ResultsModule.directive("scoreInput",['$timeout',function($timeout){
    return {
        restrict : 'AEC',
        scope    : {},
        replace  : true,
        template : "<input type='number' class='form-control input-sm'>",
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
}]);

