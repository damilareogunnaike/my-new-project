<div class="row" ng-cloak>
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="<?=(!isset($active_tab)) ? "active" : '';?>">
                <?=anchor('#bulk_change','Class Change',array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        <loader-view></loader-view>
        <!-- Tab Content -->
        <div class="tab-content">
             <div ng-controller="ClassChangeCtrl" class="tab-pane <?=(!isset($active_tab)) ? "active" : '';?>" id="bulk_change">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                         <h3 class="panel-title text-center text-uppercase"></h3>
                    </div>
                    <div class="panel-body" ng-controller="StudentSearchCtrl">
                        <div class="row">
                            <div class="col-md-6">
                                <h2>
                                    <div class="form-inline" ng-init="search.mode = 'select_from_class'">
                                        <label class="text-info">Search Mode</label>
                                         <select ng-model="search.mode" class="form-control input-sm pull-right">
                                            <option value="search_for_student">Search for student</option>
                                            <option value="select_from_class">Select from class</option>
                                        </select>
                                    </div>
                                </h2>
                                 <div id="search-form" ng-show="search.mode == 'search_for_student'">
                                    <h4>Search for student</h4>
                                    <div class="form-group">
                                        <label class="control-label">
                                            Enter name or ID 
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <input type="text" class="form-control input-sm" ng-model="search.keyword">
                                            <div class="input-group-btn">
                                                <button class="btn btn-primary btn-sm" ng-click="searchStudent()">Go</button>
                                            </div>
                                        </div>
                                        <p class="text-info text-sm ">Min: 6 characters</p>
                                    </div>
                                </div>

                                <div  ng-show="search.mode == 'select_from_class'">
                                    <h4>Select From Class</h4>
                                    <div class="row">
                                        <div class="col-md-4">
                                             <div class="form-group">
                                                 <label class="control-label">Select Session</label>
                                                <sessions ng-model="search.sessionId" class="form-control input-sm"></sessions>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                             <div class="form-group">
                                                 <label class="control-label">Select Class</label>
                                                <classes ng-model="search.classId" class="form-control input-sm"></classes>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <button style="margin-top:25px;" class="btn btn-primary btn-sm" ng-click="getStudents()">Get Students</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-warning btn-sm" ng-click="students = []">Clear</button>
                                    <button class="btn btn-sm btn-primary pull-right" ng-click="addAllStudents(students)"> Add All <i class="fa fa-plus-circle"></i></button>
                                </div>
                            </div>
                            <div class="col-md-6">
                            	 <h2 class="text-info"><strong>Migrating Students</strong></h2>
                                  <div id="search-form" >
                                    <h4>Destination</h4>
                                    <div class="row">
                                        <div class="col-md-4">
                                             <div class="form-group">
                                                 <label class="control-label">Select Session</label>
                                                <sessions ng-model="destination.sessionId" class="form-control input-sm"></sessions>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                             <div class="form-group">
                                                 <label class="control-label">Select Class</label>
                                                <classes ng-model="destination.classId" class="form-control input-sm"></classes>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <button 
                                                    ng-disabled="(migratingStudents.length < 1) || (isNaN(destination.classId)) || isNaN(destination.sessionId == '')" 
                                                    style="margin-top:25px;" class="btn btn-primary btn-sm" ng-click="changeClass()">Proceed</button>
                                            </div>
                                        </div>
                                    </div>
                                     <h5> 
                                        <span ng-bind="'(' + migratingStudents.length + ') records...'"></span>
                                        <button class="btn btn-warning btn-sm pull-right" ng-click="clearRecords('migratingStudents')">Clear</button>
                                     </h5>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        	<div class="col-md-6">
                                <div id='search-results'>
                                    <table class="table table-bordered table-condensed table-striped">
                                        <thead>
                                            <th>S/N</th>
                                            <th>Name</th>
                                            <th>Current Class</th>
                                            <th></th>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="record in students">
                                                <td ng-bind="$index + 1"></td>
                                                <td ng-bind="record.student_name"></td>
                                                <td ng-bind="record.current_class"></td>
                                                <td>
                                                    <button ng-click="addRecord(record)" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-arrow-right"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                   <table class="table table-bordered table-condensed table-striped">
                                        <thead>
                                            <th>S/N</th>
                                            <th>Name</th>
                                            <th></th>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="record in migratingStudents">
                                                <td ng-bind="$index + 1"></td>
                                                <td ng-bind="record.student_name"></td>
                                                <td><button class="btn btn-sm btn-danger" ng-click="removeRecord(record)"><i class="fa fa-times"></i></button>
                                            </tr>
                                        </tbody>
                                    </table>
                            </div>

                        </div>

                    </div>
                    <div class="panel-footer">
                         <div class="row">
                            <div class="col-md-8 col-md-offset-2 text-right">
                            </div>
                         </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- End of Tab Content -->
    </div>
</div>