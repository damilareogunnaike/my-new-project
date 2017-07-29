<div ng-controller="ResultsComputationCtrl" ng-cloak>
    <ul class="nav nav-tabs">
        <li class="active">
            <a data-toggle="tab" href="#calculate_result">Calculate Results</a>
        </li>
        <li>
            <a data-toggle="tab" href="#auto_calculate_config">Auto-Calculate Config</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="calculate_result">
        	<div class="panel panel-default">
        		<div class="panel-heading">
        			<h3 class="panel-title text-center text-uppercase">Calculate Result</h3>
        		</div>
        		<div class="panel-body">
		        	<div class="padding-sm">
		        		<div class="row">
			        		<div class="col-md-10 col-md-offset-1">
				        		<p class="alert alert-info text-white text-center">
				        			Use this to calculate results for a class after all scores have been entered 
				        			for students in the class. This is a compulsory preliminary to generation of reports.
				        		</p>
				        	</div>
				        </div>
			        	<hr/>

		        		<div class="form-group">
		        			<div class="col-md-2 col-md-offset-1">
		        				<label class="control-label">Select Session</label>
		    					<sessions class="input-sm" ng-model="computationParam.session_id"></sessions>
		        			</div>
		        			<div class="col-md-2">
		        				<label class="control-label">Select Term</label>
		    					<terms class="input-sm" ng-model="computationParam.term_id"></terms>
		        			</div>
		        			<div class="col-md-3">
		        				<label class="control-label">Select Class</label>
		    					<classes class="input-sm" ng-model="computationParam.class_id"></classes>
		        			</div>
		        			<div class="col-md-2">
		        				<button class="btn btn-primary btn-sm" style="margin-top:22px" ng-click="computeReport()">Compute Report!</button>
		        			</div>
		        		</div>
		        		<div class="row" ng-show="computingResults">
		        			<div class="col-md-6 col-md-offset-3">
		        				<h3>Steps </h3>

		        				<table class="table table-condensed table-bordered">
		        					<thead>
		        						<th>Step</th>
		        						<th>Status</th>
		        						<th>Remark</th>
		        					</thead>
		        					<tbody>
		        						<tr ng-repeat="step in steps">
		        							<td ng-bind="step.name"></td>
		        							<td class="text-center">
		        								<span ng-show="step.completed" class="text-success"><i class="fa fa-check"></i></span>
		        								<span ng-show="step.in_progress && !step.completed" class="text-info"><i class="fa fa-spinner fa-spin"></i></span>
		        							</td>
		        							<td>
		        								<span ng-bind="step.remark">
		        								</span>
		        							</td>
		        						</tr>
		        					</tbody>
		        				</table>
		        			</div>
		        		</div>
		        	</div>
		        </div>
		        <div class="panel-footer">

		        </div>
	        </div>
        </div>

        <div class="tab-pane" id="auto_calculate_config">
        </div>
    </div>
</div>
