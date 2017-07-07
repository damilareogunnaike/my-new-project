<div class="row bg-grey padding-sm fill-parent" ng-controller="MessagesController">
    <div class="col-md-2">
        <ul class="nav">
            <li><button href="#compose" class="btn btn-danger" ng-click="compose()">Compose</button></li>
            <li ng-class="{'active':activeTab == 'inbox'}"><a href="#inbox" ng-click="getMessages('inbox')">Inbox</a></li>
            <li is-active-tab='outbox' ng-class="{'active':activeTab == 'outbox'}"><a href="#outbox" ng-click="getMessages('outbox')">Outbox</a></li>
            <li is-active-tab='draft' ng-class="{'active':activeTab == 'draft'}"><a href="#draft" ng-click="getMessages('draft')">Draft</a></li>
            <li ng-hide="true" is-active-tab='trash' ng-class="{'active':activeTab == 'trash'}"><a href="#trash" ng-click="getMessages('trash')">Trash</a></li>
        </ul>
    </div>
    
    <div class="col-md-10 no-padding bg-white ">
        
      <div ng-show="viewMessage" class="padding-sm message-view fill-parent">
            <h3 ng-bind="msgInView.subject" class="text-strong"></h3>
            <p ng-bind="msgInView.content">
            </p>
            <div class="">
                <p><strong ng-bind="activeTab == 'inbox' ? 'Sender' : 'Recipients'"></strong> 
                    <span ng-bind="activeTab == 'inbox' ? msgInView.sender_id : msgInView.recipients"></span>
                </p>
                <p class="text-muted text-sm"><em><span ng-bind="msgInView.date_added | sweetDateTime"></span></em></p>
            </div>
        </div>
       
        <loader-view></loader-view>
        <h3 class="padding-sm no-margin">{{activeTab |  uppercase}}</h3> 
        <div class="padding-sm" >
            <table class="table message-table table-condensed">
                <tr ng-repeat="msg in messages[activeTab] | orderBy:'-date_added'">
                    <td ng-bind="activeTab == 'inbox' ? msg.sender_id : msg.recipients | maxCharacters:15"></td>
                    <td class="cursor-pointer" ng-click="openMessage(msg)" ><strong><span ng-bind="msg.subject"></span></strong></td>
                    <td ng-bind="msg.date_added | sweetDateTime"></td>
                    <td>
                        <button ng-click="moveToTrash(msg)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            </table>
        </div>
        
    </div>
</div>