<?php
if ( $_con )
{
	if ( $_GET['act'] == "add" )
	{
		$insert = array
		(
			trim($_POST['detail']),
			date('Y-m-d'),
			$_POST['team']
		);
		if ( $_call->add_role($insert) )
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
	else if ( $_GET['act'] == "edit" )
	{
		/*$date = ( in_array(array(4,5), $_POST['stat']) ) ? date('Y-m-d') : null;

		$update = array
		(
			trim($_POST['code']),
			trim($_POST['name']),
			trim($_POST['nick']),
			trim($_POST['link']),
			$date,
			$_POST['stat'],
			$_POST['cus']
		);

		if ( $_call->update_pro($update, $_POST['id']) )
		{
			$_temp['success'] = true;
			$_temp['message'] = "OK! done.";
		}
		else
		{
			$_temp['success'] = false;
			$_temp['message'] = "ERROR: update";
		}*/
	}
	else
	{
		// nothing
	}
}