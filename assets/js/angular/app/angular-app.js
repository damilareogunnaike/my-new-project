var myApp = angular.module("myApp",[
	'ngRoute',
    'ngAnimate',
	'ngMaterial',
	'md.data.table',
	'ui.router',
    'ngCookies',
    'ngLahray',
    'ui.bootstrap',
    'ngResource',
    'MessagesModule',
    'EventsModule',
    'SmsModule',
    'ReportsModule',
    'StudentsModule',
    'SchoolSetupModule',
    'ResultsModule',
    'ResultPinsModule',
    'ParentsModule'
]);

/**
 * Website URL 
 * Should be changed on production
 */
var BASE_URL = APP_BASE_URL;

myApp.value("BASE_URL",BASE_URL);

var urlBase = BASE_URL.endsWith("/") ? BASE_URL : BASE_URL + "/";
myApp.value("HOST", urlBase + "index.php/api_v1/");

myApp.value("SCHOOL_INFO","");

/**
 * Backend URLS
 */
myApp.factory("APP_ENDPOINTS",function(BASE_URL, HOST){
    return {
        BASE_URL        : BASE_URL,
        USER            : HOST + "user",
        SCHOOL_INFO     : HOST + "school_info",
        MESSAGE         : HOST + "messages/",
        EVENT           : HOST + "events/",
        SMS_SENDER      : HOST + "sms_sender/",
        SMS_CONTACT     : HOST + "sms_contact/",
        SMS_GROUP       : HOST + "sms_group/",
        SCHOOL_SETUP    : HOST + "school_setup/",
        REPORTS         : HOST + "reports/"
    };
});


myApp.run(['$http','$cookies','$rootScope','APP_ENDPOINTS',
    function($http,$cookies,$rootScope,APP_ENDPOINTS,SCHOOL_INFO){ 
    $http.get(APP_ENDPOINTS.USER).then(function(response){
        $cookies.putObject("user",response.data.user);
        $rootScope.$emit("USER_INFO_RETREIVED",null);
    });


    $http.get(APP_ENDPOINTS.SCHOOL_INFO).then(function(response){
        $rootScope.$emit("SCHOOL_INFO_RETREIVED",response.data.info);
    });

    $rootScope.showLoader = function(){
        $rootScope.loading = true;
    };

    $rootScope.hideLoader = function(){
        $rootScope.loading = false;
    }

}]);




myApp.config(['$stateProvider','$provide','$httpProvider','APP_ENDPOINTSProvider','$compileProvider',
    function($stateProvider,$provide,$httpProvider,APP_ENDPOINTSProvider, $compileProvider){
          $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|file|tel|data):/);
}]);

/**
 * Holds path to all partials used in our application
 */
myApp.factory("PARTIALS",function(BASE_URL){
    var BASE_PATH = BASE_URL + "assets/partials/";
    return {
      NEW_MSG           : BASE_PATH + "new-message.html",
      NEW_EVENT         : BASE_PATH + "new-event.html",
      EVENTS_VIEW       : BASE_PATH + "events-view.html",
      EVENTS_IN_DAY     : BASE_PATH + "events-in-day.html",
      CONTACT_GROUP     : BASE_PATH + "contact-group.html",
      SUBJECTS_DROPDOWN : BASE_PATH + "subjects-dropdown.html",
      SCHOOL_SETUP      : BASE_PATH + "school-setup.html"
    };
});