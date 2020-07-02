<?php
if ( $_con )
{
	$all = ( true ) ? "Y" : "N";

	if ( $dept = $_call->get_dept() )
	{
		$res = array();
		$outp = array();

		foreach ( $dept as $v_dept )
		{

			$outp['id'] = $v_dept['id'];
			$outp['name']['th'] = $v_dept['name_th'];
			$outp['name']['en'] = $v_dept['name_en'];
			$outp['abb'] = $v_dept['abb'];
			$outp['emp'] = array();
			$e = array();

			foreach ( $_call->get_emp("dept", $v_dept['id'], $all) as $v_emp )
			{
				foreach ( $v_emp as $k => $v )
				{
					$e[$k] = $v;
				}

				$e['role'] = array();
				$r = array();

				foreach ( $_call->get_resp($v_emp['id']) as $v_resp )
				{
					$r['id'] = $v_resp['id'];
					$r['detail'] = $v_resp['detail'];
					$r['date'] = $_call->date_thai($v_resp['date']);
					$r['disable'] = $v_resp['disable'];

					array_push($e['role'], $r);
				}
				
				array_push($outp['emp'], $e);
			}

			array_push($res, $outp);
		}

		$_temp['success'] = true;
		$_temp['message'] = "OK! done.";
		$_temp['results'] = $res;
	}
	else
	{
		$_temp['success'] = false;
		$_temp['message'] = "ERROR: team";
	}
}
?>