<?php
if ( $_con )
{
	$year = ( !empty($_GET['year']) ) ? $_GET['year'] : date('Y');
	$date = ( !empty($_GET['date']) ) ? $_GET['date'] : date('Y-m');

	if ( $_GET['view'] == "holiday" )
	{
		$data = $_call->get_calendar($year, '1');
		$_temp['success'] = true;
		$_temp['message'] = "OK! done.";
		$_temp['results'] = $data;
	}
	else if ( $_GET['view'] == "weekly" )
	{
		$obj = array();
		foreach ( $_call->get_dept() as $kd => $vd )
		{
			$w['dept'] = $vd['name_en'];
			$w['abb'] = $vd['abb'];
			$w['emp'] = array();

			foreach ( $_call->get_emp("dept", $vd['id'], 'N') as $ke => $ve )
			{
				
				if ( !$_call->get_permit($ve['id'], 'WN') )
				{
					$obj_emp = array();
					$obj_emp['nick'] = $ve['nick'];
					$obj_emp['name'] = $ve['name']." ".$ve['sur'];
					$obj_emp['pos'] = $ve['pos'];
					$obj_w = array();
					$obj_w['w1'] = array();
					$obj_w['w2'] = array();
					$obj_w['w3'] = array();
					$obj_w['w4'] = array();
					$obj_w['w5'] = array();
					$obj_w['w6'] = array();

					foreach ( $_call->get_weekly($date, $ve['id']) as $kw => $vw )
					{						
						$start = date('W', strtotime($date."-01"));
						if($start==53 || $start==52) {$start=01;}else{$tart=$start;}
						$now = date('W', strtotime($vw['date']));
					
						if ( $now == $start )
						{
							array_push($obj_w['w1'], $vw);
						}
						else if ( ($now-$start) == 1 )
						{
							array_push($obj_w['w2'], $vw);
						}
						else if ( ($now-$start) == 2 )
						{
							array_push($obj_w['w3'], $vw);
						}
						else if ( ($now-$start) == 3 )
						{
							array_push($obj_w['w4'], $vw);
						}
						else if ( ($now-$start) == 4 )
						{
							array_push($obj_w['w5'], $vw);
						}
						else if ( ($now-$start) == 5 )
						{array_push($obj_w['w6'], $vw);}
					}

					$obj_emp['weekly'] = $obj_w;
					array_push($w['emp'], $obj_emp);
				}
			}

			array_push($obj, $w);
		}

		$_temp['success'] = true;
		$_temp['message'] = "OK! done.";
		$_temp['results'] = $obj;
	}	
	else
	{
		$_temp['success'] = false;
		$_temp['message'] = "ERROR: team";
	}
}
?>