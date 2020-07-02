<?php
if ( $_con )
{
	if ( $_GET['act'] == "add" )
	{
		$insert = array
		(
			trim($_POST['detail']),
			$_POST['date'],
			$_POST['type'],
			$_POST['team']
		);
		if ( $_call->add_calendar($insert) )
		{
			$_temp['success'] = true;
			$_temp['message'] = "เรียบร้อย, ".$_POST['detail'];
		}
		else
		{
			$_temp['success'] = false;
			$_temp['message'] = "ERROR, add!!";
		}
	}
	else if ( $_GET['view'] == "weekly" )
	{
		if ( $_call->check_weekly($_POST['data']) )
		{
			$_temp['success'] = true;
			$_temp['message'] = "OK! done.";
		}
		else
		{
			$_temp['success'] = false;
			$_temp['message'] = "ERROR: check weekly";
		}
	}
	else
	{
		// nothing
	}
}