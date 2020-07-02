<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

$id = $_POST['id'];
$text = $_POST['comment'];
$me = $_SESSION['ses_user'];
//$me = 3;
$data = array();

if ( empty($id) )
{
	$data['success'] = false;
	$data['message'] = 'ERROR #id';
}
else if ( empty($me) )
{
	$data['success'] = false;
	$data['message'] = 'ERROR #please login';
}
else
{
	@include('../../lib/class/index.php');
	@include('../../config.php');
	$con = connect();
	$_call = new app();
	$update = $_call->add_comment($id, "$text", date('Y-m-d H:i:s'), $me);

	if ( $update )
	{
		$data['success'] = true;
		$data['message'] = "OK";
	}
	else
	{
		$data['success'] = false;
		$data['message'] = "ERROR #".$update;
	}
}

echo json_encode($data);