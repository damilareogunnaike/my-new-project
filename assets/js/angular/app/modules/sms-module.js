/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var SmsModule = angular.module("SmsModule",
    [
        'ngLahray',
        'ui.bootstrap'
    ]);

/*
 * CONSTANTS
 */
SmsModule.constant("LANG",{
    CONTACT_SAVED  : "Contact added!",
    DELETE_CONTACT : "Delete contact?",
    DELETE_GROUP   : "Delete group?",
    GROUP_ADDED    : "Group added successfully!",
    GROUP_UPDATED  : "Group updated!"
});

/**
 * CONTROLLERS 
 */
SmsModule.controller("SmsController",['$scope','$uibModal','PARTIALS','SmsService','AlertService','Utils','LANG',
    function($scope,$uibModal,PARTIALS,SmsService,AlertService,Utils,LANG){
        
        //Single Contacts
        $scope.contacts         = [];
        
        //Group Contacts
        $scope.groups           = [];
        
        //Current Selected Tab
        $scope.activeTab        = "compose";
        
        $scope.viewCompose      = function(){
            $scope.activeTab    = "compose";
        };
        
        $scope.viewContacts     = function(){
            $scope.activeTab    = "contacts";
            
            if($scope.contacts.length < 1){
                if($scope.contacts.length < 1) {
                    $scope.loading = true;
                    SmsService.getContacts().then(function(response){
                       $scope.contacts = response.contacts; 
                       $scope.loading = false;
                    });
                }
            }

            if($scope.groups.length < 1) {
                $scope.loading = true;
                SmsService.getGroups().then(function(response){
                   $scope.groups = response.groups; 
                   $scope.loading = false;
                },function(error){
                    console.log(error);
                });
            }
            
        };
        
        $scope.viewSentItems    = function(){
            $scope.activeTab    = "Sent Items";
        };
        
        $scope.viewSettings = function(){
            $scope.activeTab =   "Settings";
        };
        
        
        $scope.newContact = {"name":"","number":""};
        $scope.addNewContact = function(){
            SmsService.addContact($scope.newContact).then(function(response){
                $scope.contacts.push(response.contact);
                AlertService.notify(LANG.CONTACT_SAVED);
                $scope.newContact.name = "";
                $scope.newContact.number = "";
            },function(error){
                console.log(error);
            });
        };
        

        $scope.deleteContact = function(contact){
            if(AlertService.confirm(LANG.DELETE_CONTACT)){
                SmsService.deleteContact(contact.contact_id).then(function(response){
                    if(response.data.status == true){
                        $scope.contacts = Utils.removeEntry($scope.contacts,contact);
                    }
                },function(error){
                    console.log(error);
                })
            }
        }
        
        $scope.addGroup = function(){
          $uibModal.open({
                templateUrl : PARTIALS.CONTACT_GROUP,
                controller  : 'SmsContactGroupController',
                resolve     : {
                    editGroup : null
                }
          });
        };
        
        
        $scope.editGroup = function(group){
          $uibModal.open({
              templateUrl : PARTIALS.CONTACT_GROUP,
              controller  : 'SmsContactGroupController',
              resolve     : {
                  editGroup : group
              }
          });
        };
        $scope.deleteGroup = function(group){
            if(AlertService.confirm(LANG.DELETE_GROUP)) {
                SmsService.deleteGroup(group.group_id).then(function(response){
                    if(response.data.status == true){
                        $scope.groups = Utils.removeEntry($scope.groups,group);
                    }
                },function(error){
                    console.log(error);
                });
            };
         };
        
        /* EVENT BROADCAST LISTENERS */
        $scope.$on("NEW_GROUP_ADDED",function(sender,data){
            $scope.groups.push(data);
        });
        
         $scope.$on("GROUP_UPDATED",function(sender,oldGroup,newGroup){
             $scope.groups = Utils.replaceEntry($scope.groups,oldGroup,newGroup,"group_id");
        });
        

        /*
            SMS Creator

            Handles the selection of numbers to which message will be sent 
            and also the sending of the message
        */
        $scope.sms = {"recipients":"","message":"","sender":""};
        $scope.sendTo = "input_numbers";
        $scope.numbersValidated = false;
        $scope.$watch("sendTo",function(newValue,oldValue){

            //The contacts tab might not have been opened, therefore contats
            //have not been retreived. So please retrieve it!
            if(newValue == "input_contacts"){
                if($scope.contacts.length < 1) {
                    $scope.loading = true;
                    SmsService.getContacts().then(function(response){
                       $scope.contacts = response.contacts; 
                       $scope.loading = false;
                    });
                }
            }

            if(newValue == "input_groups"){
                if($scope.groups.length < 1) {
                    $scope.loading = true;
                    SmsService.getGroups().then(function(response){
                       $scope.groups = response.groups; 
                       $scope.loading = false;
                    },function(error){
                        console.log(error);
                    });
                 }
            }
        });

        $scope.$watch("sendToNumbers",function(newValue,oldValue){
            if(newValue != undefined && newValue != undefined) {
                if($scope.sms.recipients.indexOf(oldValue) >= 0){
                    $scope.sms.recipients = $scope.sms.recipients.replace(oldValue,newValue);
                }
                else {
                    $scope.sms.recipients += newValue;
                }
                $scope.numbersValidated = false;
            }
        });


        $scope.addNumbersToRecipients = function($event,numbers){
            var checkBox = $event.target;
            if(checkBox.checked){
                $scope.sms.recipients += numbers + ",";
            }
            else {
                var newNumbers = $scope.sms.recipients.replace(numbers + ",","");
                $scope.sms.recipients = (!$scope.sms.recipients.endsWith(",") && $scope.sms.recipients.length > 0) ?  "," + newNumbers : newNumbers;
            }

            $scope.numbersValidated = false;
        }

        $scope.validateNumbersForSending = function(){
            $scope.validating = true;
            var numbersToSend = $scope.sms.recipients.split(",");
            var validNumbers = [];
            for (var i = 0; i < numbersToSend.length ; i++) {
                if(numbersToSend[i].length == 11)
                {
                    validNumbers.push(numbersToSend[i]);
                }
            };
            $scope.sms.recipients = validNumbers.join(",");
            $scope.validating = false;
            $scope.numbersValidated = true;
        }


        $scope.resetNumbers = function(){
            $scope.sms.recipients = "";
            $scope.numbersValidated = false;
            $("input[type='checkbox']").attr("checked",false);
        }


        $scope.sendSMS = function(){
            $scope.loading = true;
            if(!$scope.numbersValidated) {
                $scope.validateNumbersForSending();
            }

            SmsService.sendSMS($scope.sms).then(function(response){
                console.log(response);
                if(response.sent > 0) {
                    AlertService.success("Message sent to " + response.sent + " recipient(s)");
                }
                else {
                    AlertService.notify("Message not sent to anyone!");
                }
                $scope.loading = false;
            },function(error){
                console.log(error);
                $scope.loading = false;
            })
        };
        /* OPERATIONAL METHODS */
        
        //This method calculates number of text messages typed into a textarea
        //A message consists of 160 characters. Some special characters take
        //more than a space!
        $scope.getMsgCount = function(msg){
          length = (msg === undefined) ? 0 : msg.length;
          return Math.ceil(length/160);
        };
        
        
        //For each group, numbers are stored with comma separating each number
        //This method gets the number of numbers by splitting into an array.
        //You get?
        $scope.countNumbers = function(numbers){
            numbersArr = numbers.split(",");
            return angular.isArray(numbersArr) ? numbersArr.length : 0;
        };


}]);


SmsModule.controller("SmsContactGroupController",
    ['$scope','$uibModalInstance','editGroup','$rootScope','SmsService','Utils','AlertService','LANG',
    function($scope,$uibModalInstance,editGroup,$rootScope,SmsService,Utils,AlertService,LANG){
    
    $scope.editMode = editGroup == null ? false : true;
    $scope.group = editGroup;
    $scope.saveGroup = function(group){
        SmsService.addGroup(group).then(function(response){
            $rootScope.$broadcast("NEW_GROUP_ADDED",group);
            AlertService.success(LANG.GROUP_ADDED);
            $uibModalInstance.close();
        },function(error){
            console.log(error);
        });
    };
    
    
    //Removes a number from a group
    $scope.removeNumber = function(number){
       numbers = $scope.group.numbers.split(",");
       numbers = Utils.removeEntry(numbers,number);
       $scope.group.numbers = numbers.join(",");
       SmsService.updateGroup({"group":$scope.group}).then(function(response){
           $rootScope.$broadcast("GROUP_UPDATED",$scope.group,response.data.group);
           AlertService.notify(LANG.GROUP_UPDATED);
       },function(error){
           console.log(error);
       });
    };
    
    //Adds a number to a group
    $scope.addNumber = function(){
        updatedGroup = angular.copy($scope.group); 
        numbers = updatedGroup.numbers.split(","); //What is this is empty, then it'll return an empty array
        numbers.push($scope.newNumber);
        updatedGroup.numbers = numbers.join(",");
        SmsService.updateGroup({"group":updatedGroup}).then(function(response){
           $rootScope.$broadcast("GROUP_UPDATED",$scope.group,response.data.group);
           $scope.group = response.data.group;
           $scope.newNumber = "";
           AlertService.notify(LANG.GROUP_UPDATED);
        },function(error){
            console.log(error);
        });
    };
}]);


/**
 * SERVICES
 */
SmsModule.service("SmsService",['WebService','APP_ENDPOINTS',function(WebService,APP_ENDPOINTS){


    this.sendSMS = function(sms){
        return WebService.post(APP_ENDPOINTS.SMS_SENDER,sms);
    };
    
    this.addContact = function(newContact){
        return WebService.post(APP_ENDPOINTS.SMS_CONTACT,newContact);
    };
    
    
    this.getContacts = function(){
        return WebService.get(APP_ENDPOINTS.SMS_CONTACT);
    };
    

    this.deleteContact = function(contactId){
        return WebService.delete(APP_ENDPOINTS.SMS_CONTACT + contactId);
    };

    
    this.getGroups = function(){
        return WebService.get(APP_ENDPOINTS.SMS_GROUP);
    };
    
    this.addGroup = function(group){
        return WebService.post(APP_ENDPOINTS.SMS_GROUP,group);
    };
    
    this.deleteGroup = function(groupId){
        return WebService.delete(APP_ENDPOINTS.SMS_GROUP + groupId);
    };
    
    
    this.updateGroup  = function(group){
        return WebService.put(APP_ENDPOINTS.SMS_GROUP,group);
    };

}]);


