<?php
header("Content-Type: application/json; charset=UTF-8");

$id = $_POST['id'];
$cmd = $_POST['cmd'];
$data = array();

if ( empty($id) )
{
	$data['success'] = false;
	$data['message'] = 'ERROR #id';
}
else
{
	@include('../../lib/class/index.php');
	@include('../../config.php');
	$con = connect();
	$_call = new app();
	$update = $_call->update_task("$cmd", $id, date('Y-m-d'));

	if ( $update )
	{
		$data['success'] = true;
		$data['message'] = $update;
	}
	else
	{
		$data['success'] = false;
		$data['message'] = "ERROR #update";
	}
}

echo json_encode($data);