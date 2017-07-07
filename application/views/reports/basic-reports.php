<div class="row" ng-controller="ReportsCtrl" ng-cloak>
	<div class="col-md-12">
		<ul class="nav nav-tabs">
			<li class='active'><a href="#class_report" data-toggle="tabs">Class Report</a></li>
			<li><a href="#subject_report" data-toggle="tabs">Subject Report</a></li>
		</ul>
		<loader-view></loader-view>

		<div class="tab-content">
			<div class="tab-pane active" id="class_report">
				<div class="panel panel-default">
					<div class="panel-heading">
	        			<h3 class="panel-title text-center text-uppercase">Class Report</h3>
	        		</div>
	        		<div class="panel-body">
	        			<div class="padding-sm">
	        				<form class="form row">
	        					<div class="col-md-3">
	        						<div class="form-group">
	        							<label>Session</label>
	        							<sessions ng-model="classReport.session_id"></sessions>
	        						</div>
	        					</div>
	        					<div class="col-md-3">
	        						<div class="form-group">
	        							<label>Term</label>
	        							<terms ng-model="classReport.term_id"></terms>
	        						</div>
	        					</div>
	        					<div class="col-md-3">
	        						<div class="form-group">
	        							<label>Class</label>
	        							<classes ng-model="classReport.class_id"></classes>
	        						</div>
	        					</div>
	        					<div class="col-md-3">
	        						<div class="form-group">
	        							<button style="margin-top:23px" ng-click="getClassReport()" class="btn-block btn btn-primary">View Report</button>
	        						</div>
	        					</div>

	        				</form>

	        				<p id="status-msg" ng-bind-html="classReportData.msg">
	        				</p>

	        			<hr/>

	        				<div class="row" ng-show="classReportData.data.length > 0">
	        					<div class="col-md-10 col-md-offset-1">
	        						<table class="table table-bordered ">
	        							<thead>
		        							<tr>
		        								<th>S/N</th>
		        								<th>Name</th>
		        								<th>Total Score</th>
		        								<th>Average Score</th>
		        								<th>Position</th>
		        								<th></th>
		        							</tr>
		        						</thead>
	        							<tbody>
	        								<tr ng-repeat="record in classReportData.data">
	        									<td ng-bind="$index + 1"></td>
	        									<td ng-bind="record.student_name"></td>
	        									<td ng-bind="record.total_score"></td>
	        									<td ng-bind="record.average"></td>
	        									<td ng-bind="record.position"></td>
	        									<td><button class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button></td>
	        								</tr>
	        							</tbody>
	        						</table>

	        						<hr/>
	        						<div class="text-center">
	        							<a target="_blank" href="class_report/print/{{classReport.session_id}}/{{classReport.term_id}}/{{classReport.class_id}}" class="btn btn-lg btn-primary"><i class="fa fa-print"></i> Print</a>
	        						</div>
	        					</div>
	        				</div>
	        			</div>
        			</div>
				</div>
			</div>
		</div> 
	</div>
</div>