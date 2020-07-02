<?php
//session_start();
//if ( empty($_SESSION['ses_user']) )
//{
//	header('location:login');
//	exit();
//}
?>
<!doctype html>
<html lang="en" ng-app="ewebApp">
	
	<head>

		<title>eWEB</title>
		<meta charset="utf-8">
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		
		<!-- css -->
		<link href="lib/img/favicon.ico" rel="icon" />
		<link href="lib/script/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="lib/script/bootstrap/css/bootstrap-datepicker.min.css" rel="stylesheet">
		<link href="lib/script/mCustomScrollbar/jquery.mCustomScrollbar.css" rel="stylesheet" />
		<link href="main.css" rel="stylesheet">
		
		<!-- script -->
		<script src="lib/script/jquery-1.11.2.min.js"></script>
		<script src="lib/script/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
		<script src="lib/script/bootstrap/js/bootstrap.min.js"></script>
		<script src="lib/script/bootstrap/js/bootstrap-datepicker.min.js"></script>
		<script src="lib/script/bootstrap/js/bootstrap-datepicker.th.min.js"></script>
		<script src="lib/script/angular/angular.min.js"></script>
		<script src="lib/script/angular/angular-route.min.js"></script>
		<script src="app.js"></script>

	</head>

	<body ng-controller="mainCtrl">
		
		<!-- nav -->
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
					
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigationbar">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand"><img src="lib/img/logo.png" height="24" alt="eWEB"></a>
					</div>

					<div class="nav navbar-collapse collapse" id="navigationbar">
						<ul class="nav navbar-nav">
							<li ng-class="{ active : isActive('/') }"><a href="#/" class="nav-main">หน้าแรก</a></li>
							<li ng-class="{ active : isActive('/reminder') }"><a href="#/reminder" class="nav-main">งาน</a></li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-option-horizontal"></span></a>
								<ul class="dropdown-menu">
									<li><a href="#/project"><span class="glyphicon glyphicon-list"></span> ข้อมูลโครงการ</a></li>
									<li><a href="#/team"><span class="glyphicon glyphicon-user"></span> ข้อมูลพนักงาน</a></li>
									<!--
									<li><a href="#/customer"><span class="glyphicon glyphicon-gift"></span> ข้อมูลลูกค้า</a></li>
									<li><a href="#/contact"><span class="glyphicon glyphicon-earphone"></span> ข้อมูลผู้ติดต่อ</a></li>
									-->
									<li class="divider"></li>
									<li><a href="#/weekly"><span class="glyphicon glyphicon-list-alt"></span> Weekly Report</a></li>
									<!--
									<li><a href="#/calendar/engage"><span class="glyphicon glyphicon-plane"></span> การนัดหมาย</a></li>
									<li><a href="#/calendar/absence"><span class="glyphicon glyphicon-time"></span> ขาดลามาสาย</a></li>
									<li><a href="#/meeting"><span class="glyphicon glyphicon-book"></span> บันทึกการประชุม</a></li>
									-->
									<li><a href="#/holiday"><span class="glyphicon glyphicon-calendar"></span> ปฏิทินบริษัท</a></li>
									<li class="divider"></li>
									
									<!--<li><a href="#/statistic"><span class="glyphicon glyphicon-pencil"></span> สถิติการใช้งาน</a></li>-->
									
									<li><a href="login?sign=out" onclick="return confirm('Do you want to logout ?')"><span class="glyphicon glyphicon-off"></span> ออกจากระบบ</a></li>
								</ul>
							</li>
							<!--<li><a href="login?sign=out" onclick="return confirm('Do you want to logout ?')"><span class="glyphicon glyphicon-off"></span></a></li>-->
						</ul>
					</div>

			</div>
		</nav>

		<!-- content -->
		<div class="container">
			<div class="row">

				<div class="col-sm-12" ng-class="{'col-sm-9 line':!hideMe()}" ng-view></div>
				
				<div class="clearfix visible-xs-block"></div>
				
				<div class="col-sm-3 sidebar" ng-hide="hideMe()">		
					
					<!-- display -->
					<div class="media box-user">
	  					<div class="media-left hidden-sm">
	    					<a><img src="{{me.avatar}}" class="media-object img-circle bg-fade" alt="avater"></a>
	  					</div>
					  	<div class="media-body">
					    	<h4 class="media-heading">{{me.nick+' '+me.name}}</h4>
					    	<small class="text-nowrap"><span class="label c1">{{me.dept}}</span> {{me.pos}}</small>
					  	</div>
					</div>
					
					<!-- mybox -->
					<table class="table table-box">
						<tr>
							<td><a data-toggle="modal" data-target="#modal-abs" ng-click="popAbs(me.engage,'นัดหมาย', 'detail', 'date', 'Y')"><span class="glyphicon glyphicon-briefcase"></span> นัดหมาย <span class="pull-right">{{me.engage.length}}</span></a></td>
						</tr>
						<tr>
							<td><a data-toggle="modal" data-target="#modal-abs" ng-click="popAbs(me.task,'รับงาน', 'detail', 'date_plan', 'Y', '#/reminder/*/tag/'+me.nick)"><span class="glyphicon glyphicon-tasks"></span> รับงาน <span class="pull-right">{{me.task.length}}</span></a></td>
						</tr>
						<tr>
							<td><a data-toggle="modal" data-target="#modal-abs" ng-click="popAbs(me.role,'หน้าที่', 'detail', 'date', 'N')"><span class="glyphicon glyphicon-pushpin"></span> หน้าที่ <span class="pull-right">{{me.role.length}}</span></a></td>
						</tr>
			
						<tr>
							<td><a data-toggle="modal" data-target="#modal-abs" ng-click="popAbs(me.grant,'สิทธิ์', 'name_th', 'date', 'N')"><span class="glyphicon glyphicon-eye-open"></span> สิทธิ์ <span class="pull-right">{{me.grant.length}}</span></a></td>
						</tr>
						<tr>
							<td>
								<h6 class="text-fade">สถิติการลา</h6>
								<ul class="list-unstyled">
									<li>มาสาย <span class="pull-right">{{ abs.a1 }}</span></li>
									<li>ขาดงาน <span class="pull-right">{{ abs.a2 }}</span></li>
									<li>ลาป่วย <span class="pull-right">{{ abs.a3 }}</span></li>
									<li>ลากิจ <span class="pull-right">{{ abs.a4 }}</span></li>
									<li>พักร้อน <span class="pull-right">{{ abs.a5 }}</span></li>
								</ul>
							</td>
						</tr>
					</table>

				</div>

			</div>
		</div>


		<!-- modal -->
		<popup id="modal-abs" class="modal fade" role="dialog" aria-labelledby="p-title"></popup>

		<!-- add -->
		<!--<div data-backdrop="static">-->
		<div id="add" class="modal fade" role="dialog" aria-labelledby="title">
			<div class="modal-dialog" role="document">
			    <div class="modal-content">

			      		<!--
			      		<div class="modal-header bg-fade">
			        		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
			        		<h4 class="modal-title" id="title"><small></small></h4>
			      		</div>
			      		-->
			      		
			      		<div class="modal-body row">
							<div class="col-sm-6">
								<a href="#/reminder/new">
									<div class="add-group bd-fade">
										<span class="glyphicon glyphicon-list"></span>
										<h3 class="">งานใหม่</h3>
									</div>
								</a>
							</div>
							<div class="col-sm-6">
								<a href="">
									<div class="add-group bd-fade">
										<span class="glyphicon glyphicon-time"></span>
										<h3 class="">นัดหมาย</h3>
									</div>
								</a>
							</div>
							<div class="col-sm-6">
								<a href="#/project/new">
									<div class="add-group bd-fade" >
										<span class="glyphicon glyphicon-blackboard"></span>
										<h3 class="">เปิดโครงการใหม่</h3>
									</div>
								</a>
							</div>
							<div class="col-sm-6">
								<a href="">
									<div class="add-group bd-fade">
										<span class="glyphicon glyphicon-calendar"></span>
										<h3 class="">ลางาน</h3>
									</div>
								</a>
							</div>
			      		</div>

			      		<!--
			      		<div class="modal-footer bg-fade">
			      			<div class="text-center">
				        		<button type="button" class="close clearfix" data-dismiss="modal">
				        			<span aria-hidden="true">&times;</span>
				        		</button>
			        		</div>
			      		</div>
			      		-->
				</div>
			</div>
		</div>


		<!-- fixed -->
		<div class="fixed" ng-hide="hideMe()">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 text-right">
					<a href="#/add" class="btn-circle bg-main"><span class="glyphicon glyphicon-plus"></span></a>
				</div>
			</div>
		</div>
		</div>

	</body>

</html>