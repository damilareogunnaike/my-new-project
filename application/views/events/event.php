<div class="row bg-grey padding-sm fill-parent" ng-controller="EventsController">
    <div class="loader-view"></div>
    <div class="col-md-12 bg-white padding-sm">
        <button actor="admin" ng-click="addEvent()" class="btn btn-xs btn-primary pull-right"><i class="fa fa-plus-circle"></i> Add Event</button>
        <hr>
         <div class="">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#grid-view" data-toggle="tab" ><i class="fa fa-th-large"></i> Grid View</a></li>
               <li actor="admin"><a href="#table-view" data-toggle="tab" > <i class="fa fa-th-list"></i> Manage Events</a></li>
            </ul>
            <div class="tab-content padding-xs">
                <div class="tab-pane active" id="grid-view">
                    <events-view events="events"></events-view>
                </div>
                  <div actor="admin"  class="tab-pane " id="table-view">
                    <table ng-hide="viewingEvent" class="table table-bordered table-condensed">
                    <thead>
                        <th>S/N</th>
                        <th>Title</th>
                        <th>Recurring Event</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <tr ng-repeat="event in events">
                            <td ng-bind="$index + 1"></td>
                            <td ng-bind="event.title"></td>
                            <td ng-bind="event.is_recurring | isRecurring"></td>
                            <td ng-bind="event.start_date | sweetDate"></td>
                            <td ng-bind="event.end_date | sweetDate"></td>
                            <td ng-bind="event.start_time | sweetTime"></td>
                            <td ng-bind="event.end_time | sweetTime"></td>
                            <td>
                                <button class="btn btn-xs btn-primary" ng-click="viewEvent(event)"><i class="fa fa-eye"></i></button>
                                <button class="btn btn-xs btn-info" ng-click="editEvent(event)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger" ng-click="deleteEvent(event)"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
         </div>
        <div actor="admin" ng-show="viewingEvent" class="b">
            <h3 class="bg-grey padding-xs color2"><span ng-bind="eventInView.title"></span><i class="fa fa-arrow-circle-left pull-right cursor-pointer" ng-click="closeEventView()"></i></h3>
            <div class="padding-xs">
                <p ng-bind="eventInView.description">
                </p>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Start Date: </label>
                        <span class="form-control-static" ng-bind="eventInView.start_date | sweetDate">
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">End Date: </label>
                        <span class="form-control-static" ng-bind="eventInView.end_date | sweetDate">
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Start Time: </label>
                        <span class="form-control-static" ng-bind="eventInView.start_time | sweetTime">
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">End Time: </label>
                        <span class="form-control-static" ng-bind="eventInView.end_time | sweetTime">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label">Recurring Event?</label>
                        <span class="form-control-static" ng-bind="eventInView.is_recurring | isRecurring">
                    </div>
                    <div class="col-md-3" ng-show="eventInView.is_recurring == 1">
                        <label class="control-label">Recurrence Period: </label>
                        <span class="form-control-static" ng-bind="eventInView.recurrence_period">
                    </div>
                    <div class="col-md-3" ng-show="eventInView.recurrence_period == 'Month'">
                        <label class="control-label" >Day of Month: </label>
                        <span class="form-control-static" ng-bind="eventInView.recurrence_date">
                    </div>
                    <div class="col-md-3" ng-show="eventInView.recurrence_period == 'Week'">
                        <label class="control-label">Day of Week: </label>
                        <span class="form-control-static" ng-bind="eventInView.recurrence_date | dayOfWeek">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
</div>