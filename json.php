<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

//$_app = $_POST['app'];
//$_data = $_GET['data'];
$_temp = array();
$_temp['success'] = false;
$_temp['message'] = "ERROR: request jSON";

if ( !empty($_GET['app']) )
{
	@include('lib/class/index.php');
	@include('config.php');
	$_con = connect();
	$_call = new app();

	if ( $_GET['app'] == "me" )
	{
		$obj_engage = $_call->get_calendar(date('Y-m-d'), 3, $_SESSION['ses_user']);
		$obj_task = $_call->get_task($_SESSION['ses_nick'], '*', 'emp');
		$obj_role = $_call->get_resp($_SESSION['ses_user']);
		$obj_grant = $_call->get_login_permit($_SESSION['ses_user']);
		$obj_abs = $_call->get_calendar(date('Y'), 2, $_SESSION['ses_user']);

		$me = array
		(
			'id' => $_SESSION['ses_user'],
			'nick' => $_SESSION['ses_nick'],
			'name' => $_SESSION['ses_name'],
			'pos' => $_SESSION['ses_pos'],
			'dept' => $_SESSION['ses_dept'],
			'engage' => $obj_engage,
			'task' => $obj_task,
			'role' => $obj_role,
			'grant' => $obj_grant,
			'abs' => $obj_abs
		);

		$_temp['success'] = true;
		$_temp['message'] = "OK, done!";
		$_temp['results'] = $me;
	}
	else if ( $_GET['app'] == "project" )
	{
		if ( $_SERVER['REQUEST_METHOD'] == "POST" )
		{
			@include('app/project/project-process.php');
		}
		else
		{
			@include('app/project/data.php');
		}
	}
	else if ( $_GET['app'] == "team" )
	{
		if ( $_SERVER['REQUEST_METHOD'] == "POST" )
		{
			@include('app/team/process.php');
		}
		else
		{
			@include('app/team/data.php');
		}
	}
	else if ( $_GET['app'] == "role" )
	{
		if ( $_SERVER['REQUEST_METHOD'] == "POST" )
		{
			@include('app/team/role-process.php');
		}
		else
		{
			//@include('app/team/data.php');
		}
	}	
	else if ( $_GET['app'] == "customer" )
	{
		@include('app/customer/cus-db.php');
	}
	else if ( $_GET['app'] == "calendar" )
	{
		if ( $_SERVER['REQUEST_METHOD'] == "POST" )
		{
			@include('app/calendar/process.php');
		}
		else
		{
			@include('app/calendar/data.php');
		}
	}
	else if ( $_GET['app'] == "weekly" )
	{
		if ( $_SERVER['REQUEST_METHOD'] == "POST" )
		{
			@include('app/calendar/weekly-process.php');
		}
		else
		{
			//@include('app/calendar/data.php');
		}
	}	
	else if ( $_GET['app'] == "year" )
	{
		@include('app/project/task-db-year.php');
	}
	else
	{
		// nothing
	}
}

echo json_encode($_temp);