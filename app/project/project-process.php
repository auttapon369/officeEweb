<?php
if ( $_con && $_SERVER['REQUEST_METHOD'] == "POST" )
{
	if ( $_GET['act'] == "add" )
	{
		$insert = array
		(
			9999,
			trim($_POST['name']),
			trim($_POST['nick']),
			date('Y-m-d'),
			1,
			$_POST['cus']
		);

		if ( $_call->add_pro($insert) )
		{
			$_temp['success'] = true;
			$_temp['message'] = "เพิ่มข้อมูลเรียบร้อย!! ".$_POST['nick'];
		}
		else
		{
			$_temp['success'] = false;
			$_temp['message'] = "ERROR: ไม่สามารถเพิ่มโครงการได้";
		}
	}
	else if ( $_GET['act'] == "edit" )
	{
		$date = ( in_array(array(4,5), $_POST['stat']) ) ? date('Y-m-d') : null;

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
		}
	}
	else if ( $_GET['act'] == "status" )
	{
		$results = $_call->get_pro_status();

		if ( $results )
		{
			$_temp['success'] = true;
			$_temp['results'] = $results;
		}
		else
		{
			$_temp['success'] = false;
			$_temp['message'] = "ERROR: ".$results;
		}
	}	
	else
	{
		// nothing
	}
}