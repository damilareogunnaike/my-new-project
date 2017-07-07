/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var EventsModule = angular.module("EventsModule",
    [
        'ngLahray',
        'ui.bootstrap'
    ]);


/*
 * CONSTANTS
 */
EventsModule.constant("LANG",{
    EVENT_ADDED   : "Event added successfully!",
    DELETE_EVENT  : "Proceed with deleting this event?!",
    EVENT_DELETED : "Event deleted successfully!",
    EVENT_UPDATED : "Event updated successfully!"
});


/**
 * CONTROLLERS 
 */
EventsModule.controller("EventsController",
    ['$scope','$uibModal','LANG','PARTIALS','AlertService','EventsService','Utils','$rootScope',
    function($scope,$uibModal,LANG,PARTIALS,AlertService,EventsService,Utils,$rootScope){
      
      $scope.events = [];
      $scope.eventInView = {};
      $scope.loading = true;
      
      EventsService.getEvents().then(function(response){
         $scope.events = response.events; 
      },function(error){
          console.log(error);
      }).finally(function(){
          $scope.loading = false;
      });
      
      $scope.addEvent = function(){
          newEventModal = $uibModal.open({
              templateUrl : PARTIALS.NEW_EVENT,
              controller  : 'EventController',
              resolve     : {
                  'editEvent' : null
              }
          });
      };
      
    
      $scope.viewEvent = function(event){
          $scope.loading = true;
          $scope.eventInView = event;
          $scope.viewingEvent = true;
          EventsService.getEvent(event.event_id).then(function(response){
              $scope.eventInView = response.event;
          },function(error){
              console.log(error);
          }).finally(function(){
            $scope.loading = false;
          });
      };
      
      $scope.closeEventView = function(){
          $scope.viewingEvent = false;
          $scope.eventInView = null;
           
      };
      
      $scope.editEvent = function(event){
           $scope.editEventIndex = $scope.events.indexOf(event);
           eventObj = angular.copy(event);
           EventsService.getEvent(eventObj.event_id).then(function(response){
                eventObj = response.event; 
                start_time = eventObj.start_time.split(":");
                end_time = eventObj.end_time.split(":");
                eventObj.start_date = new Date(eventObj.start_date);
                eventObj.end_date = new Date(eventObj.end_date);
                eventObj.start_time = Utils.makeDateFromTime(eventObj.start_time);
                eventObj.end_time = Utils.makeDateFromTime(eventObj.end_time);
                eventObj.recurrence_date = parseInt(eventObj.recurrence_date);
                newEventModal = $uibModal.open({
                    templateUrl : PARTIALS.NEW_EVENT,
                    controller  : 'EventController',
                    resolve     : {
                        "editEvent" : eventObj
                    }
                });
         },function(error){
             console.log(error);
         });
                      
          
      };
      
      $scope.deleteEvent = function(event){
          if(AlertService.confirm(LANG.DELETE_EVENT)){
              EventsService.deleteEvent(event.event_id).then(function(){
                  Utils.removeEntry($scope.events,event);
                  AlertService.success(LANG.EVENT_DELETED);
              });
          }
      };
      
      
      
      $scope.$on("NEW_EVENT_ADDED",function(source,event){
         $scope.events.push(event); 
      });
      
      $scope.$on("EVENT_UPDATED",function(source,event){
         $scope.events[$scope.editEventIndex] = event;
      });
      
}]);


EventsModule.controller("EventController",['$scope','$uibModalInstance','editEvent','$rootScope','uibDateParser','EventsService','LANG',
    'AlertService','dateFilter',
    function($scope,$uibModalInstance,editEvent,$rootScope,uibDateParser,EventsService,LANG,AlertService,dateFilter){
    
    $scope.event = editEvent;
    //$scope.event = {"recurrence_period"(editEvent != null) ? eventObj.recurrence_period : 1;
    $scope.saveEvent = function(){
        eventObj = angular.copy($scope.event);
        eventObj.start_date = dateFilter($scope.event.start_date,$scope.dateFormat);
        eventObj.end_date = dateFilter($scope.event.end_date,$scope.dateFormat);
        eventObj.start_time = dateFilter($scope.event.start_time,$scope.timeFormat);
        eventObj.end_time = dateFilter($scope.event.end_time,$scope.timeFormat);
        EventsService.saveEvent(eventObj).then(function(response){
            if(editEvent != null){
                $rootScope.$broadcast("EVENT_UPDATED",response.event);
                AlertService.success(LANG.EVENT_UPDATED);
            }
            else {
                $rootScope.$broadcast("NEW_EVENT_ADDED",response.event);
                AlertService.success(LANG.EVENT_ADDED);
            }
            $scope.$close();
        },function(error){
            console.log(error);
        });
    };
    
    
    $scope.dateFormat = "yyyy-MM-dd";
    $scope.timeFormat = "HH:mm:ss";
     
    $scope.dateOptions = {
        minDate: new Date(),
        startingDay: 1
      };
      
    
    $scope.$on("EDIT_EVENT",function(source,event){
        $scope.event = event;
        console.log(event);
    });
    
}]);



/**
 * SERVICES
 */
EventsModule.service("EventsService",['WebService','APP_ENDPOINTS',function(WebService,APP_ENDPOINTS){
    
    this.saveEvent = function(event){
      return WebService.post(APP_ENDPOINTS.EVENT,event);  
    };
    
    
    this.getEvent = function(reqObj){
        return WebService.get(APP_ENDPOINTS.EVENT + reqObj);
    };
    
    
    this.getEvents = function(reqObj){
        return WebService.get(APP_ENDPOINTS.EVENT,reqObj);
    };
    
    
    this.deleteEvent = function(queryString){
        return WebService.delete(APP_ENDPOINTS.EVENT + queryString);
    };
    
}]);



EventsModule.directive("eventsView",['PARTIALS','Utils','WEEKDAYS','EventsService','$filter','$uibModal',
    function(PARTIALS,Utils,WEEKDAYS,EventsService,$filter,$uibModal){
   return {
       restrict     : 'AE',
       scope        : {
           events   : '='
       },
       templateUrl  : PARTIALS.EVENTS_VIEW,
       link     : function(scope,elem,attrs){
           scope.weekdays = WEEKDAYS;
           var numOfDays = 7;
           var now = new Date();
           scope.month = now.getMonth();
           scope.year = now.getFullYear();
           scope.monthEvents = [];
           scope.dateFormat = "yyyy-MM-dd";
           scope.today = new Date(new Date().getFullYear(),new Date().getMonth(),new Date().getDate(),0,0,0);
           
           scope.getTodaysEvents = function(today){
                var tDate = $filter('date')(new Date(scope.year,scope.month,today + 1),scope.dateFormat);
                var events = new Array();
                for (var i = 0; i < scope.monthEvents.length; i++){
                    event = scope.monthEvents[i];
                    if(event.start_date <= tDate && event.end_date >= tDate){
                        events.push(event);
                    }
                }
                return events;
            };
            
           scope.dayIsToday = function(day,month){
             var date = new Date(scope.year,month,day,0,0,0);
             return (date.getFullYear() == scope.today.getFullYear() 
                     && date.getMonth() == scope.today.getMonth()
                     && date.getDate() == scope.today.getDate());
           };
           
           
           scope.loadMonthEvents = function(month){
              scope.monthName = Utils.getMonthName(month);
              var daysInMonth = Utils.getDaysInMonth(month);
              var firstDayOfWeek = Utils.getWeekDayIndex(0,month);
              var week1Max = numOfDays - firstDayOfWeek;
              var remDays = daysInMonth - week1Max;
              scope.numOfWeeks = Math.ceil(remDays / numOfDays) + 1;
              scope.weekDayValues = new Array(scope.numOfWeeks);

              for(var i = 0; i < scope.numOfWeeks; i++){
                  scope.weekDayValues[i] = new Array(numOfDays);
                  for(var x = 0; x < numOfDays; x++){
                      scope.weekDayValues[i][x] = {"date":"","event":{}};
                  }
              }
             /*
              * Let's get all the events in this month.
              * Tried getting for individual days but it affectes the database.
              */
             var startDate = $filter('date')(new Date(scope.year,scope.month,1),scope.dateFormat);
             var endDate = $filter('date')(new Date(scope.year,scope.month,daysInMonth),scope.dateFormat);
             scope.loading = true;
             EventsService.getEvents({"start_date":startDate,"end_date":endDate}).then(function(response){
                 scope.monthEvents = response.events;
                 for(var i = 0; i < daysInMonth; i++){
                    dayOfWeek = Utils.getWeekDayIndex(i,month);    
                    weekNo    = Utils.getWeekNo(firstDayOfWeek,i);
                    todaysEvents = scope.getTodaysEvents(i);
                    todaysInfo = {"date":i+1,"events":todaysEvents};
                    todaysInfo["is_today"] = scope.dayIsToday(i+1,month);
                    scope.weekDayValues[weekNo][dayOfWeek] = todaysInfo;
                  }
             },function(error){
                 console.log(error);
             }).finally(function(){
                 scope.loading = false;
             });
              
          };
            
            scope.loadMonthEvents(scope.month);
            
            scope.moveToNext = function(){
                scope.month += 1;
                scope.loadMonthEvents(scope.month);
            };
            
             scope.moveToPrev = function(){
                scope.month -= 1;
                scope.loadMonthEvents(scope.month);
            };
            
            
            scope.openEvents = function(day){
                if(day.events.length < 1) return ;
                eventDay = angular.copy(day);
                eventDay.date = new Date(scope.year,scope.month,eventDay.date);
                $uibModal.open({
                   templateUrl : PARTIALS.EVENTS_IN_DAY,
                   size        : 'lg',
                   controller  : function($scope,day){
                        $scope.day = day;
                   },
                   resolve     : {
                       day     : eventDay
                   }
                });
            };
            
       }
   };
}]); 



EventsModule.filter("weekDay",['WEEKDAYS',function(WEEKDAYS){
        return function(input){
            return WEEKDAYS[input];
        };
}]);