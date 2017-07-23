'use strict'

/**
 * AngularLahray Module v1.0
 */

var ngLahray = angular.module("ngLahray",[]);


ngLahray.constant("WEEKDAYS",["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]);

ngLahray.constant("MONTHS",["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
]);

/**
 * Used for performing asynchronous requests to web servers
 * Depends on angular $http, $q and jquery $.ajax
 */
ngLahray.service("WebService",function($q,$http){

   /**
    * Makes asynchrnous POST form submission
    * 
    * @param {type} requestUrl URL to which form is to be submitted
    * @param {type} formData    Data to be submitted to URL
    * @returns {$q@call;defer.promise}
    */
   this.post = function(requestUrl,formData){
        var deferred = $q.defer();
        $.ajax({
            method    :'POST',
            url       : requestUrl,
            data      : $.param(formData),
            headers   : {'Content-Type':'application/x-www-form-urlencoded' }
        })
        .success(function(data){
            deferred.resolve(data);
        })
        .error(function(data){
            deferred.reject(data);
        });
        
        return deferred.promise;
    };
    
    
    /**
     * 
     * @param {type} requestUrl URL to which GET request is to be made
     * @param {type} reqData    Data to be passed to this request
     * @returns {deffered.promise}  Promise to be returned
     */
    this.get = function(requestUrl,reqData){
        var deferred = $q.defer();
        reqData = (reqData === null || reqData === undefined) ? {} : reqData;
        $.ajax({
                method  : 'GET',
                url     : requestUrl,
                data	:$.param(reqData),
                headers: {'Content-Type':'application/x-www-form-urlencoded' }
        }).success(function(data){
                deferred.resolve(data);
        }).error(function(data){
                deferred.reject(data);
        });
        return deferred.promise;
    };
    
    
    this.delete = function(requestUrl, requestObj){
        return $http.delete(requestUrl, requestObj);
    };
    
    
    this.put = function(requestUrl,requestData){
        return $http.put(requestUrl,requestData);
    };
     
});


ngLahray.service("AlertService",function(){
    
    this.notify = function(msg){
      swal(msg);
    };
    
    this.error = function(msg){
        if(typeof msg == "string" &&  msg.length > 0) {
            //Then this is an html string
            if(msg[0] === "<") {
                _alert = $(msg);
                _alert.css({"position":"absolute","z-index":100});
               $("body").append(_alert);
            }
            else {
                swal('',msg, 'error');
            }
        } 
    };
    
    this.success = function(msg){
        swal('Success',msg, 'success');
    };
    
    this.confirm = function(msg){
        return window.confirm(msg);
    };
    
    this.msg = function(msg){
        swal(msg);
    };
});


ngLahray.service("Utils",['$filter','MONTHS',function($filter,MONTHS){
    /**
     * 
     * @param {Array} data Array from which entry is to be removed
     * @param {Object} entry Entry to be removed from array
     * @returns {Array} Array of objects remaining after removing entry
     */
    this.removeEntry = function(data,entry){
        var index = data.indexOf(entry);
        data.splice(index,1);
        return data;
    };
     
    /** 
     * Replaces an object in an array with another object.
     * Searches the array objects for a match using a property of each object
     * @param {Array} arrayData Array of Objects
     * @param {Object} oldEntry Object to be replaced
     * @param {Object} newEntry Object to replace
     * @param {String} checkProp Object property to check for searching
     * @returns {Array}
     */
    this.replaceEntry = function(arrayData,oldEntry,newEntry,checkProp){
        oldEntry = $.grep(arrayData,function(e) { return e[checkProp] == oldEntry[checkProp];});
        var index = arrayData.indexOf(oldEntry[0]);
        arrayData[index] = newEntry;
        return arrayData;
    };
    
    
    this.makeDateFromTime = function(input){
        var time = input.split(":");
        var date = new Date();
        date.setHours(time[0]);
        date.setMinutes(time[1]);
        return date;
    };
    
    /**
     * 
     * @param {type} month Index of month of  the year starting from 0
     * @param {type} year  Year whose months days is requierd
     * @returns {Number} Number of days in the month
     */
    this.getDaysInMonth = function(month,year){
        if(year === undefined){
            year = new Date().getYear();
        }
        var date = new Date(year,month + 1,0);
        return date.getDate();
    };
    
    
    this.getWeekDayIndex = function(date,month,year){
         if(year === undefined){
            year = new Date().getFullYear();
        }
        var date = new Date(year,month,date + 1);
        return date.getDay();
    };
    
    
    this.getWeekNo = function(firstDayOfWeek,dayOfMonth){
        var weekNo = 0;
        var initDay = firstDayOfWeek;
        firstDayOfWeek = 6 - firstDayOfWeek;
        while(dayOfMonth > firstDayOfWeek){
            firstDayOfWeek += 7;
            weekNo ++;
        }
        return weekNo;
    };
    
    
    this.getMonthName = function(month){
        return MONTHS[month];
    };
    
}]);

/** DIRECTIVES **/
ngLahray.directive("uploadBtn",function(){
   return {
       restrict : 'A',
       template : "<button ng-click='selectFile()' class='btn btn-sm  btn-default'>Upload File</button>",
       link     : function(scope,elem,attrs){
           var $elem = $(elem);
           $elem.css("display","none");
           var button = $("<button ng-click='selectFile()' class='btn btn-sm btn-default'>Upload File</button>");
           button.click(function(){
               $(elem).click();
           });
           $elem.after(button);
           $elem.change(function(){
               console.log($elem.val());
               button.after($elem.val()); 
           });
       }
   };
});


ngLahray.directive("isActiveTab",function(){
   return {
       restrict : 'A',
       link     : function(scope,elem,attrs){
           $(elem).attr("ng-class","{'active':activeTab === '" + attrs.isActiveTab + "'}");
       }
   };
});



ngLahray.directive("numberSelect",function(){
   return {
       scope    : {
           value:'@ngValue'
       },
       restrict : 'A',
       link     : function(scope,elem,attrs){
           var min = attrs.min;
           var max = attrs.max;
           for(var i = min; i <= max; i++){
               var html = "<option value='" + i + "'";
               html += (scope.value == i) ? " selected>"  : ">";
               html += i + "</option>";
               $(elem).append(html);
           }
       }
   };
});


ngLahray.directive("weekdaysSelect",function(WEEKDAYS){
   return {
       
       restrict : 'A',
       scope    : {
           value:'='
       },
       link     : function(scope,elem,attrs){
           var weekdays = WEEKDAYS;
           for(var i = 0; i < weekdays.length; i++){
               $(elem).append("<option value=" + i + ">" + weekdays[i] + "</option>");
           }
       }
   };
});


ngLahray.directive("loaderView",function(){
    return {
        restrict : 'AEC',
        'replace': true,
        template : "<div ng-show='loading'><i class='fa fa-spin fa-spinner'></i> Loading</div>",
        link     : function(scope,elem,attrs){
            elem.css({
                "background":"rgba(0,0,0,.8)",
                "position"  :"fixed",
                "width"     :"200px",
                "text-align":"center",
                "z-index"   :1000,
                "right"     : "6px",
                "bottom"     : "70px",
                "color"     : "#fff",
                "padding"     : "8px",
                "font-size" : "15px"
            });
            
        }
    };
});


ngLahray.directive("fixedHeight",function(){
  return {
    restrict : 'A',
    replace  : false,
    link     : function(scope, elem, attrs){
      var maxHeight = attrs.fixedHeight;
      $(elem).css({
        "max-height":maxHeight,
        "min-height":maxHeight,
        "overflow-y" : 'scroll'
      });
    }
  }
});

ngLahray.directive("maxHeight",function(){
  return {
    restrict : 'A',
    replace  : false,
    link     : function(scope, elem, attrs){
      var maxHeight = attrs.maxHeight;
      $(elem).css({
        "max-height":maxHeight,
        "overflow-y" : 'scroll'
      });
    }
  }
});


ngLahray.directive("countdownTimer", function(PARTIALS){
    return {
        restrict: 'AE',
        replace : false,
        templateUrl: PARTIALS.COUNTDOWN_TIMER,

        link : function(scope, elem, attrs){
            var date = eval(attrs['countdownTimer']);
            var currentDate = new Date();
            var difference = date.getTime() - currentDate.getTime();

            var timer = {days: 0, hours : 0, minutes: 0, seconds: 0};
            scope.timer = timer;

            var seconds = 1000;
            var minutes = 60 * 1000;
            var hours = 60 * 60 * 1000;
            var days = 24 * 60 * 60 * 1000;

            scope.parseAndFormat = function(value){
                return value < 0 ? '00' : (value < 10) ? '0' + value : value;
            };

            setInterval(function(){
                timer.days = Math.floor(difference / days);
                timer.hours = Math.floor((difference - (timer.days * days)) / hours);
                timer.minutes = Math.floor((difference - ((timer.days * days) + (timer.hours * hours))) / minutes);
                timer.seconds = Math.floor((difference - ((timer.days * days) + (timer.hours * hours) + (timer.minutes * minutes))) / seconds);
                difference -= 1000;
                scope.timer = timer;
                scope.$apply();
            }, 1000);

        }
    }
});

/* FILTERS */
ngLahray.filter("sweetDate",function($filter){
    return function(input,format){
        var format = "EEE, MMM d yyyy";
        var date = new Date(input);
        return $filter('date')(date,format);
    };
});


/* FILTERS */
ngLahray.filter("sweetTime",function($filter){
    return function(input,format){
        if(input === undefined) {
            return "";
        }
        var time = input.split(":");
        var date = new Date();
        date.setHours(time[0]);
        date.setMinutes(time[1]);
        var format = "hh:mm a";
        return $filter('date')(date,format);
    };
});


ngLahray.filter("sweetDateTime",function($filter){
   return function(input, format){
       if(input === undefined){
           return "";
       }
       var date = new Date(input);
       var format = "EEE, MMM d yyyy hh:mm a";
       return $filter('date')(date,format);
   };
});


ngLahray.filter("maxCharacters",['$filter',function($filter){
    return function(input,maxCharacters){
        if(input.length > maxCharacters){
            return input.substr(0,maxCharacters) + "...";
        }
        else 
        {   
            return input;
        }
    };    
}]);

ngLahray.filter("dayOfWeek",function(WEEKDAYS){
    return function(input){
        return WEEKDAYS[input];
    };
});

