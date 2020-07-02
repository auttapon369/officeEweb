<?php
if ( $_con )
{
	if ( $_GET['act'] == "status" )
	{
		$results = $_call->get_pro_status();

		if ( $results )
		{
			$_temp['success'] = true;
			$_temp['message'] = "OK: done";
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