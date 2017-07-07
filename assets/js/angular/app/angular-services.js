
myApp.service("LoginService",
['WebService','APP_ENDPOINTS', 
    function(WebService,APP_ENDPOINTS){
    
    this.login = function(formData){
        return WebService.post(APP_ENDPOINTS.LOGIN);
    };
}]);




myApp.service("SchoolSetupService",['$rootScope','WebService','APP_ENDPOINTS',function($rootScope,WebService,APP_ENDPOINTS){

	this.getClasses = function(){
		return WebService.get(APP_ENDPOINTS.SCHOOL_SETUP + "classes");
	}

	this.getSubjects = function(callback){
		return WebService.get(APP_ENDPOINTS.SCHOOL_SETUP + "subjects");
	}


	this.getClassSubjects = function(classIds){
		return WebService.get(APP_ENDPOINTS.SCHOOL_SETUP + "class_subjects",{"class_ids":classIds});
	}
}])
