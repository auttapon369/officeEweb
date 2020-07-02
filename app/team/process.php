<?php
if ( $_con )
{
	if ( $_GET['act'] == "add" )
	{
		// nothing
	}
	else if ( $_GET['act'] == "edit" )
	{
		$lead = ( $_POST['lead'] == "true" ) ? 1 : 0;
		$leave = ( $_POST['leave'] == "true" ) ? 'Y' : 'N';

		$update = array
		(
			$_POST['dept'],
			trim($_POST['pos']),
			trim($_POST['phone']),
			trim($_POST['desk']),
			$lead,
			$leave
		);

		$pass = $_call->text_clean($_POST['pass']);

		if ( !empty($pass) )
		{
			array_push($update, md5($pass));
		}

		if ( $_call->update_emp($update, $_POST['id']) )
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
	else
	{
		// nothing
	}
}