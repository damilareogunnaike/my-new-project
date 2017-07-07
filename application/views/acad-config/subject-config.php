<div class="row" ng-cloak>
    <div class="col-md-12" ng-controller="ClassSubjectConfigCtrl">
        <ul class="nav nav-tabs">
            <li class="active">
                <?=anchor('#subj_selection_mode','Subject Selection Mode',array('data-toggle'=>"tab"));?>
            </li>
            <li class="">
                <?=anchor('#default_class_subject',"Compulsory Class Subjects",array('data-toggle'=>"tab"));?>
            </li>
            <li class="">
                <?=anchor('#student_subject_config',"Student Subject Config.",array('data-toggle'=>"tab"));?>
            </li>
        </ul>
        <!-- Status Message -->
        <?=isset($msg) ? $msg : '';?>
        <loader-view></loader-view>
        <!-- Tab Content -->
        <div class="tab-content">
             <div class="tab-pane active" id="subj_selection_mode">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                         <h3 class="panel-title text-center text-uppercase"></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h3>Subject Selection Mode</h3>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">Search:</span>
                                        <input type="text" class="form-control input-sm" ng-model="searchClassText">
                                    </div>
                                </div>
                                <table class="table table-bordered table-condensed">
                                    <thead>
                                        <th>S/N</th>
                                        <th>Class Name</th>
                                        <th>Selection Mode</th>
                                        <th>Report Sheet View</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="class in classes  | filter:searchClassText | orderBy: 'class_name' ">
                                            <td ng-bind="$index + 1"></td>
                                            <td ng-bind="class.class_name"></td>
                                            <td ng-bind="class.subject_selection_mode | subjectSelectionMode"></td>
                                            <td ng-bind="class.report_sheet_view"></td>
                                            <td>
                                                <button class="btn btn-info btn-sm" ng-click="editClassSetting(class)"><i class="fa fa-edit"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <h3>Edit Selection Mode</h3>
                                <div>
                                    <div class="form-group">
                                        <label class="control-label">Class Name</label>
                                        <input type="text" class="form-control input-sm" ng-model="editClassObj.class_name" ng-disabled="true">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Selection Mode</label>
                                        <subject-selection-modes selected-mode="editClassObj.subject_selection_mode" class="form-control input-sm" ng-model="editClassObj.subject_selection_mode"></subject-selection-modes>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Report Sheet View</label>
                                        <select class="form-control" ng-model="editClassObj.report_sheet_view">
                                            <option value="DEFAULT">DEFAULT</option>
                                            <option value="SHEET_2">SHEET 2</option>
                                        </select>
                                    </div>
                                    <div>
                                        <button ng-disabled="editClassObj == null" class="btn btn-primary btn-sm" ng-click="updateClassSetting()">Save</button>
                                    </div>
                                </div>
                                <br/>
                                <div subject-selection-modes-info>
                                </div>
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

            <div class="tab-pane" id="default_class_subject">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                         <h3 class="panel-title text-center text-uppercase"></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Select Classes</h3>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">Search:</span>
                                        <input type="text" class="form-control input-sm" ng-model="searchClassText">
                                        <span class="input-group-btn">
                                            <button class="btn-default btn btn-sm" ng-click="searchClassText = ''">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div fixed-height="100px">
                                    <table class="table table-bordered table-condensed" >
                                        <thead>
                                            <th>S/N</th>
                                            <th>Class Name</th>
                                            <th>Selection Mode</th>
                                            <th></th>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="class in classes | filter:{subject_selection_mode:2} | filter:searchClassText | orderBy: 'class_name' ">
                                                <td ng-bind="$index + 1"></td>
                                                <td ng-bind="class.class_name"></td>
                                                <td ng-bind="class.subject_selection_mode | subjectSelectionMode"></td>
                                                <td>
                                                    <button class="btn btn-info btn-sm" ng-click="viewClassSubjects(class)"><i class="fa fa-eye"></i></button>
                                                    <button class="btn btn-info btn-sm" ng-click="addToClassSelection(class)"><i class="fa fa-plus-circle"></i></button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                              
                            </div>
                            <div class="col-md-6">
                                <h3>Select Subjects</h3>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">Search:</span>
                                        <input type="text" class="form-control input-sm" ng-model="searchSubjectText">
                                        <span class="input-group-btn">
                                            <button class="btn-default btn btn-sm" ng-click="searchSubjectText = ''">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div fixed-height="100px">
                                   <table class="table table-bordered table-condensed">
                                        <thead>
                                            <th>S/N</th>
                                            <th>Subject Name</th>
                                            <th></th>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="subject in subjects | filter:searchSubjectText">
                                                <td ng-bind="$index + 1"></td>
                                                <td ng-bind="subject.subject_name"></td>
                                                <td><button class="btn btn-sm btn-primary" ng-click="addToSubjectSelection(subject)"><i class="fa fa-plus-circle"></i></button>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Selected Classes <button class="btn btn-sm btn-warning pull-right" ng-click="selectedClasses = []">Clear</button></h5>
                                <div max-height="200px">
                                     <table class="table table-bordered table-condensed">
                                        <thead>
                                            <th>S/N</th>
                                            <th>Class Name</th>
                                            <th></th>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="class in selectedClasses | orderBy: 'class_name'">
                                                <td ng-bind="$index + 1"></td>
                                                <td ng-bind="class.class_name"></td>
                                                <td><button class="btn btn-sm btn-danger" ng-click="removeFromSelectedClasses(class)"><i class="fa fa-times"></i></button></td>
                                            </tr>                                        
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Selected Subjects <button class="btn btn-sm btn-warning pull-right" ng-click="selectedSubjects = []">Clear</button></h5>
                                <div max-height="200px">
                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Subject Name</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="subject in selectedSubjects | orderBy: 'subject_name'">
                                                <td ng-bind="$index + 1"></td>
                                                <td ng-bind="subject.subject_name"></td>
                                                <td><button class="btn-danger btn btn-sm" ng-click="removeFromSelectedSubjects(subject)"><i class="fa fa-times"></i></button>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="text-center padding-sm">
                            <button class="btn-primary btn" ng-disabled="selectedClasses.length < 1 || selectedSubjects.length < 1" ng-click="saveClassSubjects()">Save Changes</button>
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

            <div class="tab-pane" id="student_subject_config">
                <div class="panel panel-default">
                     <div class="panel-heading ">
                         <h3 class="panel-title text-center text-uppercase"></h3>
                    </div>
                    <div class="panel-body">
                         <div class="row">
                            <div class="col-md-6" ng-controller="StudentSearchCtrl">
                                <h4>
                                    <div class="form-inline" ng-init="search.mode = 'select_from_class'">
                                        <label class="text-info">Search Mode</label>
                                         <select ng-model="search.mode" class="form-control input-sm pull-right">
                                            <option value="search_for_student">Search for student</option>
                                            <option value="select_from_class">Select from class</option>
                                        </select>
                                    </div>
                                </h4>
                                 <div id="search-form" ng-show="search.mode == 'search_for_student'">
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
                                </div>
                                <div id='search-results' max-height="150px">
                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                            <th>S/N</th>
                                            <th>Name</th>
                                            <th>Current Class</th>
                                            <th></th>
                                            <th></th>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="record in students">
                                                <td ng-bind="$index + 1"></td>
                                                <td ng-bind="record.student_name"></td>
                                                <td ng-bind="record.current_class"></td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" ng-click="viewStudentSubjects(record)"><i class="fa fa-eye"></i></button>
                                                </td>
                                                <td>
                                                    <button ng-click="addToStudentSelection(record)" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-plus-circle"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 class="text-info control-label">
                                    <label class="text-info"> Select Subjects</label>
                                </h4>
                                <div class="form-group">
                                    <label class="control-label">Search for Subject</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">Search:</span>
                                        <input type="text" class="form-control input-sm" ng-model="searchSubjectText">
                                        <span class="input-group-btn">
                                            <button class="btn-default btn btn-sm" ng-click="searchSubjectText = ''">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div max-height="150px">
                                   <table class="table table-bordered table-condensed">
                                        <thead>
                                            <th>S/N</th>
                                            <th>Subject Name</th>
                                            <th></th>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="subject in subjects | filter:searchSubjectText">
                                                <td ng-bind="$index + 1"></td>
                                                <td ng-bind="subject.subject_name"></td>
                                                <td><button class="btn btn-sm btn-primary" ng-click="addToSubjectSelection(subject)"><i class="fa fa-plus-circle"></i></button>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <br/>
                         <div class="row" max-height="250px">
                            <div class="col-md-6">
                                <h5>Selected Students <button class="btn btn-sm btn-warning pull-right" ng-click="selectedStudents = []">Clear</button></h5>
                                <div>
                                     <table class="table table-bordered table-condensed">
                                        <thead>
                                            <th>S/N</th>
                                            <th>Student Name</th>
                                            <th></th>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="student in selectedStudents | orderBy: 'student_name'">
                                                <td ng-bind="$index + 1"></td>
                                                <td ng-bind="student.student_name"></td>
                                                <td><button class="btn btn-sm btn-danger" ng-click="removeFromSelectedStudents(class)"><i class="fa fa-times"></i></button></td>
                                            </tr>                                        
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Selected Subjects <button class="btn btn-sm btn-warning pull-right" ng-click="selectedSubjects = []">Clear</button></h5>
                                <div>
                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Subject Name</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="subject in selectedSubjects | orderBy: 'subject_name'">
                                                <td ng-bind="$index + 1"></td>
                                                <td ng-bind="subject.subject_name"></td>
                                                <td><button class="btn-danger btn btn-sm" ng-click="removeFromSelectedSubjects(subject)"><i class="fa fa-times"></i></button>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary" ng-disabled="selectedStudents.length < 1 || selectedSubjects.length < 1" ng-click="saveStudentsSubjects()">Save</button>
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