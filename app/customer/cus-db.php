<?php
if ( $_con )
{
	if ( $data = $_call->get_cus() )
	{
		$_temp['success'] = true;
		$_temp['message'] = $data;
	}
	else
	{
		$_temp['success'] = false;
		$_temp['message'] = "ERROR: Can't get customer data.";
	}
}
?>