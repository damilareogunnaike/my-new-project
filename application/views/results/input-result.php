<div ng-controller="ResultsCtrl" ng-cloak>
    <ul class="nav nav-tabs">
        <li class="active">
            <a data-toggle="tab" href="#input_for_student">Enter Student Result</a>
        </li>
        <li>
            <a data-toggle="tab" href="#input_for_class">Enter Class Result</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="input_for_student">
            <div class="padding-sm">
                <div class="row">
                    <div class="col-md-5">
                        <h4 class="text-info text-strong no-padding">Selection</h4>
                         <hr/>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Result Session</label>
                                <sessions ng-model="resultView.session_id" class="form-control input-sm"></sessions>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Result Term</label>
                                <terms ng-model="resultView.term_id" class="form-control input-sm"></terms>
                            </div>
                        </div>
                        <hr/>
                        <search-for-student session-id="resultView.session_id" edit-session="false" select-action="prepareFormForStudent(student)">
                        </search-for-student>
                    </div>

                    <div class="col-md-7">
                        <h4 class="text-info text-strong no-padding">
                            Result Form
                        </h4>
                        <hr/>
                        <div >
                            <strong><h5 ng-bind="resultForm.student.student_name + ' - ' + resultForm.student.current_class"></h5></strong>
                            <p class="text-info text-center" ng-show="resultForm.student && resultForm.scores.length < 1">
                                <i class="fa fa-warning"></i> No scores for the selection!
                            </p>
                            <p class="text-info text-center" ng-show="loading">
                                <i class="fa fa-spinner fa-spin"></i> Processing Request...
                            </p>
                            <div ng-show="resultForm.scores.length > 0">
                                <table class="table table-bordered table-condensed">
                                    <thead>
                                        <th>S/N</th>
                                        <th>Subject</th>
                                        <th>CA 1</th>
                                        <th>CA 1</th>
                                        <th>Exam</th>
                                        <th>Total</th>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="score in resultForm.scores">
                                            <td ng-bind="$index + 1"></td>
                                            <td style="width:230px;" ng-bind="score.subject_name"></td>
                                            <td>
                                                <score-input ng-model="score.ca1"></score-input>
                                            </td>
                                            <td>
                                                <score-input ng-model="score.ca2"></score-input>
                                            </td>
                                            <td>
                                                <score-input ng-model="score.exam"></score-input>
                                            </td>
                                            <td>
                                                <score-input disabled="disabled" ng-value="computeTotal(score.ca1, score.ca2 , score.exam)" ng-model="score.total"></score-input>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button class="btn btn-primary btn-sm" ng-click="updateScores()">Update Scores</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane" id="input_for_class">
            <div class="row">
                <div class="col-md-3">
                    <h3>Selection Filters</h3>
                    <div class="form-group">
                        <label>Select Session</label>
                        <sessions class="form-control input-sm" ng-model="selection.session_id"></sessions>
                    </div>
                     <div class="form-group">
                        <label>Select Term</label>
                        <terms class="form-control input-sm" ng-model="selection.term_id"></terms>
                    </div>
                     <div class="form-group">
                        <label>Select Class</label>
                        <classes class="form-control input-sm" value-key="object" ng-model="selection.class"></classes>
                    </div>
                    <div class="form-group">
                        <label>Select Subject</label>
                        <select class="form-control" ng-model="selection.subject" ng-options="subject as subject.subject_name for subject in classSubjects">
                        </select>
                    </div>
                    <div class="form-group">
                        <button ng-disabled="selection.subject == undefined  || selection.subject == ''" ng-click="prepareFormForClassSubject()" class="btn btn-primary btn-sm">Get Result Form</button>
                    </div>
                </div>
                <div class="col-md-9">
                    <h3>Class Subject Result Form</h3>

                   <div >
                        <strong><h5 ng-bind="classResultForm.class.class_name + ' - ' + classResultForm.subject.subject_name"></h5></strong>
                        <p class="text-info text-center" ng-show="classResultForm.class && classResultForm.scores.length < 1">
                            <i class="fa fa-warning"></i> No scores for the selection!
                        </p>
                        <p class="text-info text-center" ng-show="loading">
                            <i class="fa fa-spinner fa-spin"></i> Processing Request...
                        </p>
                        <div ng-show="classResultForm.scores.length > 0">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <th>S/N</th>
                                    <th>Student Name</th>
                                    <th>CA 1</th>
                                    <th>CA 1</th>
                                    <th>Exam</th>
                                    <th>Total</th>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="score in classResultForm.scores">
                                        <td ng-bind="$index + 1"></td>
                                        <td style="width:230px;" ng-bind="score.student_name"></td>
                                        <td>
                                            <score-input ng-model="score.ca1"></score-input>
                                        </td>
                                        <td>
                                            <score-input ng-model="score.ca2"></score-input>
                                        </td>
                                        <td>
                                            <score-input ng-model="score.exam"></score-input>
                                        </td>
                                        <td>
                                            <score-input disabled="disabled" ng-value="computeTotal(score.ca1, score.ca2 , score.exam)" ng-model="score.total"></score-input>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button class="btn btn-primary btn-sm" ng-disabled="classResultForm.scores.length < 1" ng-click="updateClassSubjectScores()">Update Scores</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
