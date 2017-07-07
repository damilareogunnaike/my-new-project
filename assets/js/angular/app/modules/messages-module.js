/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var MessagesModule = angular.module("MessagesModule",
    [
        'ngLahray',
        'ui.bootstrap'
    ]);


/*
 * CONSTANTS
 */
MessagesModule.constant("LANG",{
    MESSAGE_SENT : "Message sent successfully!" ,       
    DRAFT_SAVED : "Message saved!"
});

/**
 * CONTROLLERS 
 */
MessagesModule.controller("MessagesController",['$scope','$uibModal','PARTIALS','MessageService','Utils',
    function($scope,$uibModal,PARTIALS,MessageService,Utils){
      
      $scope.messages = {"inbox":[],"outbox":[],"draft":[],"trash":[]};
      
      $scope.compose = function(){
         $scope.msgModal = $uibModal.open({
             templateUrl : PARTIALS.NEW_MSG
         });
      };
      
      $scope.getMessages = function(category){
          $scope.loadView(category);
          $scope.loading = true;
          MessageService.getMessages({"category":category}).then(function(response){
              $scope.messages[category] = response.messages;
              $scope.loading = false;
          },function(error){
              console.log(error);
          });
      };
      
      
      $scope.moveToTrash = function(msg){
          $scope.loading = true;
          MessageService.deleteMessage(msg.message_id).then(function(response){
              console.log(response);
              if(response.data.status === true){
                  //$scope.messages.trash.push(msg);
                  $scope.messages[$scope.activeTab] = Utils.removeEntry($scope.messages[$scope.activeTab],msg);
              }
              $scope.loading = false;
          },function(error){
              console.log(error);
          });
          
      };
      
      
      $scope.openMessage = function(msg){
          $scope.msgInView = msg;
          $scope.viewMessage = true;
      };
      
      $scope.$on("MESSAGE_SENT",function(sender,message){
         $scope.messages.outbox.push(message);
      });
      
      $scope.$on("DRAFT_SAVED",function(sender,message){
         $scope.messages.draft.push(message);
      });
      
      
     $scope.loadView = function(view){
       $scope.activeTab = view;
       $scope.viewMessage = false;
       $scope.msgInView = null;
     };
      
      $scope.getMessages('inbox');
}]);


MessagesModule.controller("MessageController",['$scope','$rootScope','MessageService','LANG','AlertService',
    function($scope,$rootScope,MessageService,LANG,AlertService){
    
    $scope.message = {"recipients":[],"subject":"","content":""};
            
    $scope.sendMessage   = function(){
        $scope.loading = true;
        MessageService.saveMessage($scope.message).then(function(response){
            if(response.status === true){
                $scope.$close();
                AlertService.notify(LANG.MESSAGE_SENT);
                $rootScope.$broadcast("MESSAGE_SENT",response.message);
                $scope.loading = false;
            }
        },function(error){
            $scope.loading = false;
        });
    };
    
    
    $scope.saveDraft = function(){
        $scope.loading = true;
        $scope.message['is_draft'] = 1;
        MessageService.saveMessage($scope.message).then(function(response){
            if(response.status === true){
                $scope.loading = false;
                AlertService.notify(LANG.DRAFT_SAVED);
                $rootScope.$broadcast("DRAFT_SAVED",response.message);
                $scope.message = response.message;
            }
        },function(error){
            console.log(error);
        });
    };
}]);



/**
 * SERVICES
 */
MessagesModule.service("MessageService",['WebService','APP_ENDPOINTS',function(WebService,APP_ENDPOINTS){
    
    this.saveMessage = function(message){
      return WebService.post(APP_ENDPOINTS.MESSAGE,message);  
    };
    
    
    this.getMessages = function(reqObj){
        return WebService.get(APP_ENDPOINTS.MESSAGE,reqObj);
    };
    
    
    this.deleteMessage = function(queryString){
        return WebService.delete(APP_ENDPOINTS.MESSAGE + queryString);
    };
    
}]);

