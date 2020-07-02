<?php
header("Content-Type: application/json; charset=UTF-8");
@include('../../lib/class/index.php');
@include('../../config.php');
$con = connect();

$_call = new app();
//$_view = $_GET['view'];

/*
$_id = $_GET['id'];
$_today = date('Y-m-d');
$_year = $_GET['year'];
$_status = 'ZERO_RESULTS';

$outp = '{';
$outp .= '"results" : [';

if ( $con )
{
	if ( $data = $_call->get_pro($_id) )
	{
		foreach ($data as $key => $value)
		{
			$code = ( $value['code'] != "9999" ) ? $value['code'] : '';

			$outp .= ( $key > 0 ) ? ', {' : '{';
			$outp .= '"id" : '.$value['id'];
			$outp .= ', "code" : "'.$code.'"';
			$outp .= ', "fullname" : "'.$value['fullname'].'"';
			$outp .= ', "shortname" : "'.$value['shortname'].'"';
			$outp .= ', "link" : "'.$value['link'].'"';
			$outp .= ', "date_start" : "'.$value['date_start'].'"';
			$outp .= ', "date_end" : "'.$value['date_end'].'"';
			$outp .= ', "status" : "'.$value['stat_en'].'"';
			$outp .= ', "customer" : "'.$value['abb'].'"';
			$outp .= ', "task" : {';

				$outp .= '"list" : [';

					$remain = 0;
					$task = $_call->get_task($value['id'], $_year);

					foreach ($task as $k => $v)
					{
						if ( empty($v['date_approve']) ) { $remain++; }

						if ( !empty($_id) )
						{
							$outp .= ( $k > 0 ) ? ', {' : '{';
							$outp .= '"id" : '.$v['id'];
							$outp .= ', "detail" : "'.$_call->text_html($v['detail']).'"';
							$outp .= ', "attach" : "'.$v['attach'].'"';
							$outp .= ', "during" : '.$v['during'];
							$outp .= ', "create" : "'.$_call->date_thai($v['date_create']).'"';
							$outp .= ', "plan" : "'.$_call->date_thai($v['date_plan']).'"';
							$outp .= ', "clear" : "'.$_call->date_thai($v['date_clear']).'"';
							$outp .= ', "approve" : "'.$_call->date_thai($v['date_approve']).'"';
							$outp .= ', "ref" : "'.$v['ref'].'"';
							$outp .= ', "tag" : [';
							
								foreach ($_call->get_tag($v['id']) as $k_tag => $v_tag)
								{
									$name = ( empty($v_tag['nick']) ) ? $v_tag['pro_name'] : $v_tag['nick'];
									$outp .= ( $k_tag > 0 ) ? ', {' : '{';
									
									$outp .= '"name" : "'.$name.'"';
									$outp .= ', "format" : "c'.$v_tag['dept'].'"';

									$outp .= '}';
								}

							$outp .= ']';						
							$outp .= ', "comment" : [';

								foreach ($_call->get_comment($v['id']) as $k_com => $v_com)
								{
									$outp .= ( $k_com > 0 ) ? ', {' : '{';
									
									$outp .= '"text" : "'.$_call->text_html($v_com['text']).'"';
									$outp .= ', "date" : "'.$_call->date_thai($v_com['date']).'"';
									$outp .= ', "nick" : "'.$v_com['nick'].'"';
									//$outp .= ', "format" : "c'.$v_tag[3].'"';

									$outp .= '}';
								}

							$outp .= ']';
							$outp .= ', "status" : {';
								
								$task_status_over = ( empty($v['date_clear']) && $v['date_plan'] < $_today ) ? "true" : "false";
								$task_status_over = ( !empty($v['date_clear']) && $v['date_plan'] < $v['date_clear'] ) ? "true" : $task_status_over;
								$task_status_clear = ( !empty($v['date_clear']) ) ? "true" : "false";
								$task_status_approve = ( !empty($v['date_approve']) ) ? "true" : "false";

								$outp .= '"over" : "'.$task_status_over.'"';
								$outp .= ', "clear" : "'.$task_status_clear.'"';
								$outp .= ', "approve" : "'.$task_status_approve.'"';
								$outp .= '}';

							$outp .= '}';
						}
					}
					
				$outp .= ']';
				$outp .= ', "remain" : '.$remain;
				$outp .= ', "total" : '.count($task);
				$outp .= '}';
				
			$outp .= '}';	// end each
		}

		$_status = 'OK';
	}
}

$outp .= ']';
$outp .= ', "status" : "'.$_status.'"';
$outp .= '}';

print_r($outp);
*/


$success = array();		// array to hold validation errors
$errors = array();		// array to hold validation errors
$data = array();        // array to pass back data
$pro = array();
$emp = array();

// validate the variables ========
if ( empty($_POST['detail']) )
	$errors['detail'] = 'ลืมกรอกข้อมูลรายละเอียด?';

if ( empty($_POST['date']) )
	$errors['date'] = 'โปรดเลือกวันที่?';

if ( empty($_POST['during']) )
	$errors['during'] = 'ใช้เวลาทำเท่าไหร่?';

if ( empty($_POST['pro']) )
	$errors['pro'] = 'โครงการที่เกี่ยวข้องมีอะไรบ้าง';

if ( empty($_POST['emp']) )
	$errors['emp'] = 'มอบหมายใคร?';

//if ( empty($_POST['ref']) )
//	$errors['ref'] = 'no ref';

// response if there are errors
if ( !empty($errors) )
{
	// if there are items in our errors array, return those errors
	$data['success'] = false;
	$data['message'] = $errors;
}
else
{
/*
	$success['detail'] = $_POST['detail'];
	$success['date'] = $_POST['date'];
	$success['during'] = $_POST['during'];
	foreach ($_POST['pro'] as $key => $value)
	{
		$success['pro'][$key] = $value;
	}
	foreach ($_POST['emp'] as $key => $value)
	{
		$success['emp'][$key] = $value;
	}
	$success['ref'] = $_POST['ref'];
*/
	foreach ($_POST['pro'] as $value)
	{
		array_push($pro, $value);
	}

	foreach ($_POST['emp'] as $value)
	{
		array_push($emp, $value);
	}

	$insert = array
	(
		$_POST['detail'],
		$_POST['during'],
		date('Y-m-d'),
		$_POST['date'],
		$_POST['ref']
	);

	//$data['message'] = $_call->add_task($insert);

	$task = $_call->add_task($insert);
	
	if ( count($task) > 0 )
	{
		$id = $task[0]['id'];
		$run_pro = $_call->add_tag($id, $pro, 'pro');
		$run_emp = $_call->add_tag($id, $emp, 'emp');

		if ( $run_pro > 0 && $run_emp > 0 )
		{
			$data['success'] = true;
			$data['message'] = "เรียบร้อย!! ".$_POST['detail'];
			//$data['pro'] = $run_pro;
			//$data['emp'] = $run_emp;
			//$data['data'] = $run_pro." \n ".$run_emp;
		}
		else
		{
			$data['success'] = false;
			$data['message'] = "ERROR #tag";
			//$data['pro'] = $run_pro;
			//$data['emp'] = $run_emp;
		}
	}
	else
	{
		$data['success'] = false;
		$data['message'] = "ERROR #task";
	}

}

// return all our data to an AJAX call
echo json_encode($data);