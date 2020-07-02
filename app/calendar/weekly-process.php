<?php
if ( $_con )
{
	if ( $_GET['act'] == "add" )
	{
		$insert = array
		(
			trim($_POST['detail']),
			trim($_POST['trouble']),
			trim($_POST['result']),
			trim($_POST['place']),
			$_POST['date'],
			$_SESSION['ses_user']
		);
		if ( $_call->add_weekly($insert) )
		{
			$_temp['success'] = true;
			$_temp['message'] = "เรียบร้อย";
		}
		else
		{
			$_temp['success'] = false;
			$_temp['message'] = "ERROR, add!!";
		}
	}
	else if ( $_GET['act'] == "edit" )
	{
		$update = array
		(
			trim($_POST['detail']),
			trim($_POST['trouble']),
			trim($_POST['result']),
			trim($_POST['place'])
		);
		if ( $_call->update_weekly($update, $_POST['id']) )
		{
			$_temp['success'] = true;
			$_temp['message'] = "เรียบร้อย";
		}
		else
		{
			$_temp['success'] = false;
			$_temp['message'] = "ERROR, update!!";
		}
	}
	else
	{
		// nothing
	}
}