<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");
include('../../lib/class/index.php');

$_call = new app();
$_view = $_GET['view'];
$_stat_json = 'ZERO_RESULTS';
//$_stat_report = 0;
$_user = ( !empty($_SESSION['ses_user']) ) ? $_SESSION['ses_user'] : 0;
//$_user_test = ( !empty($_GET['user']) ) ? $_GET['user'] : $_user;
$_today = date('Y-m-d');
$_date = ( !empty($_GET['date']) ) ? $_GET['date'] : $_today;
$_month = substr($_date, 0, -3);


/* -------------------------------------------------------------------- JSON */

$outp = '{';
$outp .= ' "results":[';

if ( !empty($_view) )
{
	include('../../config.php');
	connect();

	if ( $_view === "all" )
	{
		$empty_day = date("N", strtotime($_month.'-01'));
		$count_day = date('t', strtotime($_date));
		$event = $_call->get_event($_month);
		$weekly = $_call->get_weekly($_month, $_user);
		$permit = $_call->get_permit($_user, 'WN');
		$_stat_json = 'OK';
		$now = null;
		$d = null;
		
		//print_r($weekly);
		//exit();

		for ( $i = 1; $i <= 42; $i++ )
		{
			if ( $i > $empty_day )
			{
				$d = $i - $empty_day;
				$now = $_month."-".sprintf('%02d', $d);
			}
			if ( $d > $count_day )
			{
				$d = null;
				$now = null;
			}
			
			$_stat_report = ( count($permit) > 0 ) ? 0 : 1;
			$chk_today = ( $now == $_today ) ? "true" : "false";
			$holiday = $_call->array_filter($event['holiday'], 'date', $now);
			$absence = $_call->array_filter($event['absence'], 'date', $now);
			$appoint = $_call->array_filter($event['appoint'], 'date', $now);


			//$outp .= "\n";
			$outp .= ( $i > 1 ) ? ', {' : '{';

			$outp .= ' "no":"'.$i.'"';
			$outp .= ', "day":"'.$d.'"';
			$outp .= ', "date":"'.$_call->date_thai($now).'"';
			$outp .= ', "today":"'.$chk_today.'" ';


			// holiday
			$outp .= ', "holiday":[';

				foreach ( $holiday as $key => $value )
				{
					//$outp .= "\n";
					$outp .= ( $key > 0 ) ? ', {' : '{';

					$outp .= ' "detail":"'.$value['detail'].'"';

					$outp .= ' }';
				}

			$outp .= ']';


			// appoint
			$outp .= ', "appoint":[';

				foreach ( $appoint as $key => $value )
				{
					$cap = ( (int)$value['time'] == 24 ) ? "1" : "1/2";

					$outp .= ( $key > 0 ) ? ', {' : '{';

					$outp .= ' "nick":"'.$value['nick'].'"';
					$outp .= ', "type":"'.$value['type'].'"';
					$outp .= ', "detail":"'.$value['detail'].'"';
					$outp .= ', "cap":"'.$cap.'"';

					$outp .= ' }';
				}

			$outp .= ']';


			// absence
			$outp .= ', "absence":[';

				foreach ( $absence as $key => $value )
				{
					$cap = ( (int)$value['time'] == 24 ) ? "1" : "1/2";

					$outp .= ( $key > 0 ) ? ', {' : '{';

					$outp .= ' "nick":"'.$value['nick'].'"';
					$outp .= ', "type":"'.$value['type'].'"';
					$outp .= ', "detail":"'.$value['detail'].'"';
					$outp .= ', "cap":"'.$cap.'"';

					$outp .= ' }';
				}

			$outp .= ']';


			// weekly
			$outp .= ', "weekly":{';
			$outp .= ' "results":{';
				
				foreach ( $weekly as $key => $value )
				{
					if ( $value['date'] == $now )
					{
						$outp .= ' "id":"'.$_call->text_html($value['id']).'"';
						$outp .= ', "detail":"'.$_call->text_html($value['detail']).'"';
						$outp .= ', "trouble":"'.$_call->text_html($value['trouble']).'"';
						$outp .= ', "result":"'.$_call->text_html($value['result']).'"';
						$outp .= ', "place":"'.$_call->text_html($value['place']).'"';

						$_stat_report = ( $_stat_report == 1 ) ? $value['status'] : $_stat_report;

						break;
					}
				}

			$outp .= '}';
			$outp .= ', "status":"'.$_stat_report.'" ';
			$outp .= '}';

			// end results
			$outp .= ' }';

		// end for			
		}

	// end all
	}

// end view
}

$outp .= ']';
$outp .= ', "month":["'.$_call->date_calendar($_date).'", "'.$_month.'-01"]';
$outp .= ', "status":"'.$_stat_json.'" ';
$outp .= '}';

print_r($outp);
//echo json_encode($outp, JSON_PRETTY_PRINT);	// PHP 5.4
//echo json_encode($outp);
?>