/**
Base Template for Creating Modules.. 
To use, rename ResultPinsModule to desiredModule name and add necessary code..
*/

var ResultPinsModule = angular.module("ResultPinsModule",[
    'ngLahray'
]);

ResultPinsModule.factory("PINS_ENDPOINTS", function(BASE_URL){
	BASE_URL = BASE_URL.endsWith("/") ? BASE_URL  : BASE_URL + "/";
	HOST = BASE_URL + "index.php/api/result_pins/";
	return {
		DELETE_GENERATED : HOST + "generated",
		GET_GENERATED : HOST + "generated",
		GENERATE : HOST + "generate",
		DOWNLOAD_CLASS_PINS : HOST + "download_class_pins",
		DOWNLOAD_ALL_PINS : HOST + "download_all_pins",
		STUDENT_PIN_PRINTOUT : HOST + "student_pin_printout",
		CLASS_PIN_PRINTOUT : HOST + "class_pin_printout"
	}
});


ResultPinsModule.factory("PINS_PARTIALS", function(BASE_URL){
	BASE_URL = BASE_URL.endsWith("/") ? BASE_URL  : BASE_URL + "/";
	PATH = BASE_URL + "assets/partials/result-pins/";
	return {
		CLASS_PINS    : PATH + "class-pins.html"
	}
});



ResultPinsModule.controller("PinsController",['$scope','$rootScope', '$window','$sce','PinsService','AlertService','$uibModal','PINS_PARTIALS',
	function($scope, $rootScope, $window, $sce, PinsService, AlertService, $uibModal, PINS_PARTIALS){

		$scope.pinGenerationParams = {};
		$scope.generatePins = false;

		$scope.generatedClasses = [];

		$scope.getGeneratedClasses = function(){
			$scope.loading = true;
			PinsService.getGeneratedClasses(function(response){
				$scope.loading = false;
				if(response.success){
					$scope.generatedClasses = response.data;
				}
				else {
					AlertService.error(response.msg);
				}

			}, function(error){
				$scope.loading = false;
				console.log(error);
			})
		};

		$scope.generatePins = function(){
			$scope.loading = true;
			PinsService.generatePins($scope.pinGenerationParams, 
				function(response){
					$scope.loading = false;
					if(response && response.success) {
						AlertService.success(response.data);
						$scope.getGeneratedClasses();
					}
					else {
						AlertService.error(response.msg);
					}
				},
				function(error){
					$scope.loading = false;
					AlertService.error("Unable to generate pins.. Try again!");
				});
		};


		$scope.viewPins = function(record){
			$scope.loading = true;
			PinsService.getPinsForClass(record.class_id, function(response){
				$scope.loading = false;
				if(response.success) {
					$modalInstance = $uibModal.open({
						templateUrl : PINS_PARTIALS.CLASS_PINS,
						size : 'lg',
						resolve : {
							'pins' : function() {
								return response.data;
							},
							'selected' : function(){
								return record;
							}
						},
						controller : function($scope, pins, selected, $uibModalInstance, PinsService, $window){
							$scope.selected = selected;
							$scope.pins = pins;

							$scope.close = function(){
								$uibModalInstance.close();
							};

                            $scope.printStudentPin = function(pin){
                                $scope.loading = true;
                                PinsService.getStudentPinFile(pin).then($scope.handlePrintSuccess, $scope.handleError);
                            };

                            $scope.handlePrintSuccess = function (response) {
                            	$scope.loading = false;
                                if(response.success){
                                    var printOutUrl = response.data;
                                    $window.open(printOutUrl, "_blank");
                                }
                                else {
                                    AlertService.error(response.msg);
                                }
                            };

                            $scope.handleError = function(error){
                            	$scope.loading = false;
                            	AlertService.error(error.msg);
							};
						}
					})
				}
			}, function(error){
				$scope.loading = false;
				AlertService.error("Unable to complete request. Please try again.");
			})
		};

		$scope.undoGenerate = function(record){
			var msg = "You are about to delete generated pins for '" + record.class_name + "'. Are you sure about this?";
			if(AlertService.confirm(msg)) {
				PinsService.undoGenerate(record.class_id, function(response){
					console.log(response);
					if(response.data.success){
						AlertService.success(response.data.data);
						$scope.getGeneratedClasses();
					}
					else {
						AlertService.error(response.msg);
					}
				}, function(error) {
					AlertService.error(error.msg);
				})
			}
		};

		$scope.fileGenerated = false;

		$scope.file = {
			data : '',
			name : ''
		};

		$scope.downloadClassPins = function(record){
			$scope.loading = true;
			PinsService.printClassPins(record.class_id, function(response){
				$scope.loading = false;
				$scope.parseDataResponse(response);
			}, function(error){
				$scope.handleError(error);
			})
		};

		$scope.downloadAll = function(){
			$scope.loading = true;
			$scope.fileGenerated = false;
			PinsService.downloadAllPins(function(response){
				$scope.loading = false;
				$scope.parseDataResponse(response);
			
			}, function(error){
				$scope.handleError(error);
			})
		};

		$scope.parseDataResponse = function(response){
			if(response.success) {
				$scope.fileGenerated = true;
				$scope.file.name = response.data.name;
				$scope.file.data = $sce.trustAsResourceUrl(response.data.data);
			}
			else {
				AlertService.error("Unable to generate report.");
			}
		};

		$scope.handleError = function(error){
			$scope.loading = false;
			AlertService.error(error.msg);
		};

		$scope.getGeneratedClasses();

        $scope.printClassPins = function(request){
            console.log(request);
            $scope.loading = true;
            PinsService.getClassPinFile(request).then($scope.handlePrintSuccess, $scope.handleError)
        };

        $scope.handlePrintSuccess = function (response) {
            $scope.loading = false;
            if(response.success){
                var printOutUrl = response.data;
                $window.open(printOutUrl, "_blank");
            }
            else {
                AlertService.error(response.msg);
            }
        };

	}]);



ResultPinsModule.service("PinsService", ["WebService",'PINS_ENDPOINTS',
	function(WebService,PINS_ENDPOINTS){


		this.getGeneratedClasses = function(successFunc, errorFunc){
			return WebService.get(PINS_ENDPOINTS.GET_GENERATED).then(function(response){
				successFunc(response);
			}, function(error){
				errorFunc(error);
			})
		};

		this.generatePins = function(requestObj, successFunc, errorFunc) {
			return WebService.get(PINS_ENDPOINTS.GENERATE, requestObj).then(function(response){
				successFunc(response);
			}, function(error){
				console.log("Error generating pins!");
				errorFunc(error);
			})
		};

		this.getPinsForClass = function(class_id, successFunc, errorFunc) {
			return WebService.get(PINS_ENDPOINTS.GET_GENERATED + "/" + class_id).then(function(response){
				successFunc(response);
			}, function(error){
				errorFunc(error);
			});
		};

		this.undoGenerate = function(class_id, successFunc, errorFunc){
			return WebService.delete(PINS_ENDPOINTS.DELETE_GENERATED + "/" + class_id).then(function(response){
				successFunc(response);
			}, function(error){
				errorFunc(error);
			})
		};

		this.printClassPins = function(class_id, successFunc, errorFunc){
			return WebService.get(PINS_ENDPOINTS.DOWNLOAD_CLASS_PINS + "/" + class_id).then(function(response){
				successFunc(response);
			}, function(error){
				console.log(error);
				if(errorFunc){
					errorFunc(error);
				}
			})
		};

		this.downloadAllPins = function(successFunc, errorFunc){
			return WebService.get(PINS_ENDPOINTS.DOWNLOAD_ALL_PINS).then(function(response){
				successFunc(response);
			}, function(error){
				errorFunc(error);
			})
		};

		this.getStudentPinFile = function(reqObj){
			return WebService.get(PINS_ENDPOINTS.STUDENT_PIN_PRINTOUT, reqObj);
		};

		this.getClassPinFile = function(reqObj){
			return WebService.get(PINS_ENDPOINTS.CLASS_PIN_PRINTOUT, reqObj);
		};


	}]);

