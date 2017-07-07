<div class="row bg-grey fill-parent overflow-auto" ng-controller="SmsController" ng-cloak>
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" ng-click="viewCompose()"  href="#compose">Compose</a></li>
            <li ><a href="#contacts" data-toggle="tab" ng-click="viewContacts()">Contacts</a></li>
            <li><a href="#sent-items" data-toggle="tab" ng-click="viewSentItems()">Sent Items</a></li>
            <li><a href="#settings" data-toggle="tab" ng-click="viewSettings()">Settings</a></li>
        </ul>
        
         <loader-view></loader-view>
         <div class="bg-white margin-bottom-sm margin-top-sm">   
            <div class="tab-content padding-sm">
                <div class="tab-pane  active fade in" id="compose">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group ">
                                    <span class="input-group-addon">
                                         <label>
                                            Enter Numbers
                                             <input type="radio" ng-model="sendTo" value="input_numbers">
                                         </label>
                                    </span>
                                    <span class="input-group-addon">
                                         <label>
                                            Select from contacts
                                             <input type="radio" ng-model="sendTo" value="input_contacts">
                                         </label>
                                    </span>
                                     <span class="input-group-addon">
                                         <label>
                                            Select from groups
                                             <input type="radio" ng-model="sendTo" value="input_groups">
                                         </label>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group" ng-show="sendTo == 'input_numbers'">
                                <label class="control-label">Enter numbers</label>
                                <textarea class="form-control" ng-model="sendToNumbers"></textarea>
                                <p class="text-info text-sm">
                                    Multiple numbers seperated by comma or new line
                                </p>
                            </div>
                             <div class="form-group" ng-show="sendTo == 'input_contacts'">
                                <label class="control-label">Select Contacts</label>
                                <table class="table table-bordered table-condensed">
                                    <thead>
                                        <th>Name</th>
                                        <th>Number</th>
                                        <th></th>
                                    </thead>
                                    <tr ng-repeat="contact in contacts | orderBy: 'name'">
                                        <td>{{contact.name}}</td>
                                        <td>{{contact.number}}</td>
                                        <td><input ng-click="addNumbersToRecipients($event,contact.number)" type="checkbox"></td>
                                    </tr>
                                </table>
                                
                            </div>
                             <div class="form-group" ng-show="sendTo == 'input_groups'">
                                <label class="control-label">Select Groups</label>
                                <table class="table table-bordered table-condensed">
                                    <thead>
                                        <th>Name</th>
                                        <th>Numbers</th>
                                        <th></th>
                                    </thead>
                                    <tr ng-repeat="group in groups | orderBy: 'name'">
                                        <td>{{group.name}}</td>
                                        <td>{{group.numbers.split(",").length}}</td>
                                        <td><input ng-click="addNumbersToRecipients($event,group.numbers)" type="checkbox"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form>
                                <div class="form-group">
                                    <label class="control-label">Recipients</label>
                                    <textarea ng-disabled="true" class="form-control" ng-bind="sms.recipients"></textarea>
                                    <p class="text-info">
                                        <span class="text-sm">
                                            {{sms.recipients.length > 0 ? sms.recipients.substr(0,sms.recipients.length - 1).split(",").length : 0}} 
                                            numbers {{ numbersValidated ? "after validation!" : "prior to number validation!"}}</span>
                                        <button ng-disabled="sms.recipients.length < 1" class="btn btn-xs btn-warning pull-right" ng-click="resetNumbers()">
                                            <i class="fa fa-refresh"></i> Reset
                                        </button>
                                        <button ng-disabled="sms.recipients.length < 1" class="btn btn-primary  btn-xs pull-right" ng-click="validateNumbersForSending()">
                                            <i class="fa fa-check" ng-hide="validating"></i>
                                            <i class="fa fa-circle-notch fa-spin" ng-show="validating"></i>
                                            Validate
                                        </button>
                                    </p>
                                </div>
                                 <div class="form-group">
                                    <label class="control-label">Message</label>
                                    <textarea text-message class="form-control" ng-model="sms.message"></textarea>
                                    <span  class="text-info ">{{getMsgCount(sms.message)}} Message(s)</span>
                                </div>
                              <div class="form-group">
                                    <label class="control-label">Sender</label>
                                    <input school-info="school_name" type="text" text-message class="form-control" ng-model="sms.sender">                                    
                                </div>

                                <div class="form-group">
                                    <button ng-disabled="sms.recipients.length < 11 || sms.message.length < 10" ng-click="sendSMS()" class="btn btn-primary">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane  fade" id="contacts">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading text-uppercase text-center">Single Contacts</div>
                                
                                <div class="panel-body">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                        <input ng-model="keywordContact" type="text" placeholder="Search Contacts" class="form-control">
                                    </div>
                                    <table class="table table-condensed table-bordered">
                                        <thead>
                                            <th>S/N</th>
                                            <th>Name</th>
                                            <th>Number</th>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="contact in contacts | filter:keywordContact | orderBy : 'name'">
                                                <td>{{$index+1}}</td>
                                                <td>{{contact.name}}</td>
                                                <td>{{contact.number}}</td>
                                                 <td >
                                                    <button ng-click="deleteContact(contact)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i>
                                                    </button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <form>
                                        <legend>Add Contact</legend>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    Name
                                                </span>
                                                <input ng-model="newContact.name" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    Number
                                                </span>
                                                <input type="text" ng-model="newContact.number" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" ng-click="addNewContact()" class="btn btn-primary" value="Add">
                                            <input type="reset" class="btn btn-warning" value="Reset">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading text-uppercase text-center">Groups</div>
                                
                                <div class="panel-body">
                                     <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                        <input ng-model="keywordGroup" type="text" placeholder="Search Groups" class="form-control">
                                    </div>
                                     <table class="table table-condensed table-bordered">
                                        <thead>
                                           <th>S/N</th>
                                           <th>Name</th>
                                           <th>Numbers</th>
                                           <th></th>
                                       </thead>
                                       <tbody>
                                           <tr ng-repeat="group in groups | filter:keywordGroup | orderBy : 'name'">
                                               <td>{{$index+1}}</td>
                                               <td>{{group.name}}</td>
                                               <td>{{countNumbers(group.numbers)}}</td>
                                               <td>
                                                   <button class="btn btn-primary btn-xs" ng-click="editGroup(group)"><i class="fa fa-edit"></i></button>
                                                   <button data-trigger="focus" data-toggle="tooltip" data-placement="top" data-original-title="Delete Group" class="btn btn-danger btn-xs" ng-click="deleteGroup(group)"><i class="fa fa-trash"></i></button>
                                               </td>
                                           </tr>
                                       </tbody>
                                   </table>
                                     <div class="form-group">
                                        <button class="btn btn-primary btn-sm" ng-click="addGroup()"><i class="fa fa-plus-circle"></i> Add Group</button>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="sent-items">
                    Sent Items
                </div>
                
                <div class="tab-pane fade" id="settings">
                    Settings
                </div>
                
            </div>
         </div>
    </div>
    
</div>