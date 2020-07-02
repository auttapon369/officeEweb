<?php
$results = $_call->get_task_year();

if ( $results )
{
	$_temp['success'] = true;
	$_temp['results'] = $results;
}
else
{
	$_temp['success'] = false;
	$_temp['message'] = "ERROR: year";
}
?>