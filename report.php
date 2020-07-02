<?php
session_start();
@include('lib/class/index.php');
$_call = new app();
if ( empty($_SESSION['ses_user']) )
{
	header('location:./');
}
else
{
?>
<!doctype html>
<html lang="en" ng-app="reportApp">
	<head>
		<title>Weekly <?php echo $_GET['week'].": ".$_GET['name']." (".$_GET['pos'].")"; ?></title>
		<meta charset="utf-8">
		<!--<meta name="viewport" content="initial-scale=1.0, user-scalable=no">-->
		<link href="lib/img/favicon.ico" rel="icon" />
		<link href="lib/script/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="main.css" rel="stylesheet" />
		<style>
		html {
			overflow-y: hidden;
			height: 100%;
		}
		body {
			padding: 0 !important;
			height: inherit;
			color: black;
		}
		table {
			width: 100%;
			height: inherit;
		}
		table thead,
		table tbody,
		table tfoot {
			outline: 1px solid #a0495d;
		}
		table .thead {
			height: 100px;
		}
		table tbody {
			/*height: 100%;*/
		}
		table tfoot tr td {
			height: 32px;
		}
		table thead tr th {
			white-space: nowrap;
		}
		table tbody tr td {
			white-space: nowrap;
			vertical-align: top;
		}
		table tbody tr td.nopad {
			padding: 0 !important;
		}
		table tbody tr td textarea {
			width: 100%;
			height: 100%;
			border: none;
		}
		form {
			margin: 0;
			padding: 0;
		}
		</style>
	</head>
	<body class="bg-white" ng-controller="reportCtrl">

		<div class="alert alert-danger" role="alert" ng-hide="allowAll || abb">คุณไม่ได้รับอนุญาต</div>

		<div class="alert alert-success" role="alert" ng-show="success">ตรวจงานเรียบร้อย, กรุณาปิดหน้าต่างนี้</div>

		<TABLE class="table table-bordered" style="display:none" ng-show="allowAll || abb" ng-hide="success">
			<THEAD>
				<TR>
					<th COLSPAN="6" class="text-center thead">
						<H3>รายงานผลการปฏิบัติงานประจำสัปดาห์ <?php echo $_GET['dn']?></H3>
						<P>สัปดาห์ที่ <?php echo $_GET['week']." ประจำเดือน ".$_call->date_calendar($_GET['date']) ?></P>
						<P>ผู้ปฏิบัติงาน : <?php echo $_GET['name']." (".$_GET['pos'].")";  ?></P>
					</th>
				</TR>
			</THEAD>
			<TBODY>
				<TR>
					<td HEIGHT="20" WIDTH="25" class="text-center">ลำดับ</td>
					<td WIDTH="50" class="text-center">ว/ด/ป</td>
					<td WIDTH="125" class="text-center">สถานที่ปฏิบัติงาน</td>
					<td class="text-center">หัวเรื่อง/รายละเอียด</td>
					<td class="text-center">อุปสรรค/ปัญหา</td>
					<td class="text-center">ผลการปฏิบัติงาน/หมายเหตุ</td>
				</TR>
				<tr ng-repeat="(n,item) in data">
					<td>{{n+1}}</td>
					<td>{{item.date | date:'dd/MM/yyyy'}}</td>
					<td>{{item.place}}</td>
					<td class="nopad"><textarea readonly>{{item.detail}}</textarea></td>
					<td class="nopad"><textarea readonly>{{item.trouble}}</textarea></td>
					<td class="nopad"><textarea readonly>{{item.result}}</textarea></td>
				</tr>
			</TBODY>
			<TFOOT>
				<TR>
					<TD COLSPAN="6">หัวหน้าหน่วยงาน : <button type="button" class="btn btn-sm btn-main pull-right" ng-show="data[0].status=='N' && allow && abb" ng-click="checkWeek()">ตรวจรายงาน</button></td>
				</TR>
			</TFOOT>
		</TABLE>

	<div class="loader"></div>
	<script src="lib/script/jquery-1.11.2.min.js"></script>
	<script src="lib/script/angular/angular.min.js"></script>
	<script src="lib/script/angular/angular-route.min.js"></script>
	<script>
	var app = angular.module('reportApp', ['ngRoute']);

	app.controller
	(
		'reportCtrl',
		function($scope, $http, $parse)
		{
			$('.loader').show();

	  		$scope.checkGrant = function(obj,id)
	  		{
	  			var x = false;
				angular.forEach
				(
					obj,
					function(value)
					{
						if (value['code']==id) {
							x = true;
						}
					}
				);
				return x;
			}

			$http.get('json?app=me').success
			(
				function(data)
				{
					var abb = "<?php echo $_GET['abb'] ?>";

					$scope.me = data.results;
				    $scope.allow = $scope.checkGrant($scope.me.grant,'WD');
				    $scope.allowAll = $scope.checkGrant($scope.me.grant,'WA');
				    $scope.abb = ($scope.me.dept==abb) || false;
				    console.log($scope.abb);
				}
			);

			var date = "<?php echo $_GET['date'] ?>";
			$scope.data = {};
	        $http.get('json?app=calendar&view=weekly&date='+date).success
	  		(
	  			function(data)
	  			{		
			  		var dept = <?php echo $_GET['dept'] ?>;
					var emp = <?php echo $_GET['emp'] ?>;
					var week = <?php echo $_GET['week'] ?>;

					$scope.res = data.results[dept].emp[emp].weekly;

					if (week=="1") { $scope.data = $scope.res.w1 };
					if (week=="2") { $scope.data = $scope.res.w2 };
					if (week=="3") { $scope.data = $scope.res.w3 };
					if (week=="4") { $scope.data = $scope.res.w4 };
					if (week=="5") { $scope.data = $scope.res.w5 };
					if (week=="6") { $scope.data = $scope.res.w6 };

					//console.log($scope.res);
	  				//$scope.data = $scope.res[dept].emp[emp].weekly.week;
	  				//console.log($scope.data);
	  				$('.loader').fadeOut();
	  				$('table').fadeIn();
	  			}
	  		);

	  		$scope.checkWeek = function(obj)
	  		{
	  			//$scope.data = {};
	  			//console.log($scope.data);
	  			$scope.check = {};
				$scope.check.data = [];

				angular.forEach
				(
					$scope.data,
					function(value)
					{
						$scope.check.data.push(value.id);
					}
				);
				//console.log($scope.check);

				$http
				(
					{
						headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
						method: "POST",
						url: 'json?app=calendar&view=weekly',
						data: $.param($scope.check)
					}
				)
				.success
				(
	  				function(data)
	  				{	
	  					$scope.success = data.success;
						$scope.message = data.message;
	  				}
				);
				//$scope.show = true;
	  		}
		}
	);
	</script>
	</body>
</html>
<?php } ?>