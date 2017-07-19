  <div class="row" ng-controller="PinsController">
    <div class="col-md-12">

        <loader-view></loader-view>
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#view-class-pins" data-toggle="tab">Class</a>
            </li>
            <li class="">
                <a href="#view-student-pins" data-toggle="tab">Student</a>
            </li>
        </ul>
        
        <!-- Tab Content -->
        <div class="tab-content">

            <div class="tab-pane active" id="view-class-pins">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="text-center text-uppercase panel-title">CLASS VIEW</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-7">
                                <h4>
                                    Generated Pins
                                     <div class="pull-right text-right">
                                        <button ng-click="downloadAll()" class="btn btn-primary btn-sm">Print All <i class="fa fa-download"></i></button>
                                    </div>
                                </h4>
                                <br/>
                                <p class="text-center" ng-if="fileGenerated">
                                    <a ng-href="{{file.data}}" download="{{file.name}}" target="_blank">{{file.name}} <i class="fa fa-download"></i></a>
                                </p>

                                <table class="table table-bordered table-condensed">
                                    <thead>
                                        <th>S/N</th>
                                        <th>Class Name</th>
                                        <th></th>
                                    </thead>

                                    <tbody>
                                        <tr ng-repeat="record in generatedClasses track by $index">
                                            <td>{{$index + 1}}</td>
                                            <td><span ng-bind="record.class_name"></span></td>
                                            <td class="text-right">
                                                <button data-toggle="tooltip" title="Undo Generation" ng-click="undoGenerate(record)" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-undo"></i>
                                                </button>
                                                <button ng-click="viewPins(record)" data-toggle="tooltip" title="View Pins" class="btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                 <button ng-click="downloadClassPins(record)" data-toggle="tooltip" title="Download Pins" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-download"></i>
                                                </button>
                                                <button ng-click="printClassPins(record)" data-toggle="tooltip" title="Print Pins" class="btn btn-info btn-sm">
                                                    <i class="fa fa-print"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-4 col-md-offset-1">
                                <h4>Generate Pins for class</h4>

                                <div class="panel panel-default  ">
                                     <div class="panel-heading ">
                                         <h3 class="text-center text-uppercase panel-title">Generate Pins</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                          <!--   <div class="col-md-3 col-md-offset-1">
                                                <label class="control-label">Select Session</label>
                                                <sessions class="input-sm" ng-model="pinGenerationParams.session_id"></sessions>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label">Select Term</label>
                                                <terms class="input-sm" ng-model="pinGenerationParams.term_id"></term>
                                            </div> -->
                                                <label class="control-label">Select Class</label>
                                                <classes class="input-sm" ng-model="pinGenerationParams.class_id"></classes>
                                        </div>
                                        <div class="form-group">
                                                <button class="btn btn-primary btn-block btn-sm" style="margin-top:22px" ng-disabled="generatingPins" ng-click="generatePins()">
                                                    <span ng-if="!generatingPins">Generate Pins</span>
                                                    <span ng-if="generatingPins"><i class="fa circle-o-notch fa-spin"></i></span>
                                                </button>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="view-student-pins">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="text-center text-uppercase panel-title">STUDENT VIEW</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <search-for-student select-action="viewStudentPin(student)"></search-for-student>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- End of Tab Content -->
    </div>
</div>