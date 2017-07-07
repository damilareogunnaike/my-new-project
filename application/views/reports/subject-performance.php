<div class="row bg-grey padding-sm fill-parent" ng-controller="ReportsController">
    <div class="loader-view"></div>
    <div class="col-md-12 bg-white padding-sm">
        <p class="alert alert-info">
            Generate a dynamic list of student's scores in particular subjects. Set a threshold score for each subject
            and get list of top performers in the subject
        </p>

        <hr>
        <div ng-hide="showReport">
            <div class="row">
                <div class="col-md-3" >
                    <label>Select Class(es)</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" placeholder="Search Classes" class="form-control input input-sm" ng-model="classFilter">
                    </div>
                    <div style="max-height:300px;min-height:300px;overflow:scroll">
                        <table class="table table-bordered text-sm table-condensed">
                            <tbody>
                                <tr ng-repeat="class in classes | filter:classFilter">
                                    <td>{{$index+1}}</td>
                                    <td>{{class.class_name}}</td>
                                    <td><input type="checkbox" ng-model="class.checked" ng-click="getClassSubjects(class.class_id,class.checked)" ng-false-value="0" ng-true-value="1"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
                 <div class="col-md-3" ng-show="classSubjects.length > 0">
                    <label>Select Subject(s)</label>
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" placeholder="Search Subjects" class="form-control input input-sm" ng-model="subjectFilter">
                    </div>
                    <div style="max-height:300px;min-height:300px;overflow:scroll">
                         <table class="table table-bordered text-sm table-condensed">
                            <tbody>
                                <tr ng-repeat="subject in classSubjects | filter:subjectFilter">
                                    <td>{{$index+1}}</td>
                                    <td>{{subject.subject_name}}</td>
                                    <td><input type="checkbox" ng-model="subject.checked" ng-click="setSubject(subject,subject.checked)" ng-false-value="0" ng-true-value="1"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4" ng-show="selectedSubjects.length > 0">
                    <label>Set Threshold</label>
                    <table class="table-bordered table-condensed text-sm">
                        <tr ng-repeat="subject in selectedSubjects">
                            <td style="min-width:150px">{{subject.subject_name}}</td>
                            <td><input score-input min="0" max="100" value="40" type="number" class="form-control input-sm" ng-model="subject.from"></td>
                            <td><input score-input min="0" max="100" value="100" type="number"  class="form-control input-sm" ng-model="subject.to" min="40"></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Select Session</label>
                        <school-setup ng-model="selections.session_id" data-which="sessions" data-display-key="school_session_name" data-value-key="school_session_id">
                        </school-setup>
                    </div>
                    <div class="form-group">
                        <label>Select Term</label>
                        <school-setup ng-model="selections.term_id" data-which="terms" data-display-key="school_term_name" data-value-key="school_term_id">
                        </school-setup>
                    </div>
                     <div class="form-group  text-center">
                        <button class="btn btn-primary btn-block" ng-click="getReport()">Get Report</button>
                </div>
                </div>
            </div>
        </div>
        <div ng-hide="showReport">
        </div>
         <div ng-show="showReport">
            <button class="btn btn-primary pull-right" ng-click="showReport = false"><i class="fa fa-refresh"></i> Search again</button>
            <div class="row">
                <div class="col-md-8 col-md-offset-1">
                     <table class="table table-bordered table-condensed text-sm">
                        <thead>
                            <th></th>
                            <th>Student Name</th>
                            <th ng-repeat="subject in reqSubjects">
                                {{subject.subject_name}}
                            </th>
                            <th>Total Score</th>
                            <th>Average Score</th>
                        </thead>
                        <tr ng-repeat="record in report">
                            <td>{{$index+1}}</td>
                            <td>{{record.student_name}}</td>
                            <td ng-repeat="score in record.scores">
                                {{score.total_score}}
                            </td>
                            <td>{{record.total_score}}</td>
                            <td>{{record.avg_score}}</td>
                        </tr>
                    </table>
                    <p class="text-info" ng-hide="report.length > 0">
                        Sorry, no result found for selection. Check parameters!
                    </p>
                </div>
            </div>
           
         </div>
    </div>
    
    
</div>