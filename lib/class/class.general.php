<?php
class general
{	
	// define properties
	private $month = array
	(
		"m_full_th" => array
		(
			"","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"
		),
		"m_sub_th" => array
		(
			"","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค."
		)
	);


	// constructor
	public function __construct()
	{

	}


	// date
	public function date_thai($text)
	{
		$strYear = date("y",strtotime($text))+43;
		$strMonth = date("n",strtotime($text));
		//$strMonth = date("m",strtotime($text));
		//$strDay = date("j",strtotime($text));
		$strDay = date("d",strtotime($text));
		$strHour = date("H",strtotime($text));
		$strMinute = date("i",strtotime($text));
		$strSeconds = date("s",strtotime($text));
		$strMonthThai = $strMonthCut[$strMonth];
		
		if ( count(explode('-', $text)) == 3 )
		{
			$time = explode(' ', $text);
			$t = ( count($time) == 2 ) ? $time[1] : '';

			//return $template = $strDay."/".$strMonth."/".$strYear.' '.$t;
			$date = (int)$strDay." ".$this->month['m_full_th'][$strMonth]." ".$strYear;
		}
		else
		{
			$date = '-';
		}

		return $date;
	}


	public function date_calendar($date)
	{
		$y = date("y",strtotime($date))+43;
		$n = date("n",strtotime($date));
		$m_th = Array
		(
			"",
			"มกราคม",
			"กุมภาพันธ์",
			"มีนาคม",
			"เมษายน",
			"พฤษภาคม",
			"มิถุนายน",
			"กรกฎาคม",
			"สิงหาคม",
			"กันยายน",
			"ตุลาคม",
			"พฤศจิกายน",
			"ธันวาคม"
		);

		return $temp = $m_th[$n]." ".$y;
	}


	public function date_current($type, $date, $x = null)
	{
		$arr = explode('-', $date);
		$d = ( $type == "month" ) ? $arr[0].'-'.$arr[1].'-01' : $date;
		$n = ( empty($x) ) ? 0 : $x;
		$num = $n.' '.$type;
		$current = date("Y-m-d", strtotime($num, strtotime($d)));

		return $current;
	}


	public function date_week($date)
	{
		$format = substr($date, 0, -3).'-';
		$t = date("t", strtotime($date));

		for ( $i = 1; $i <= $t; $i++ )
		{
			$now = $format.sprintf('%02d', $i);
	
			if ( date("N", strtotime($now)) == 1 )
			{
				break;
			}
		}

		$w1 = $now;
		$w2 = $this->date_current("day", $w1, 7);
		$w3 = $this->date_current("day", $w2, 7);
	    $w4 = $this->date_current("day", $w3, 7);
		$w5 = $this->date_current("day", $w4, 7);
		$w6 = ( $w5 > $format.$t ) ? '' : $w5;

		return $x = array($w1, $w2, $w3, $w4, $w5,$w6);
	}


	// text
	public function text_html($text)
	{
		$text = trim($text);
		$text = stripslashes($text);
		$text = preg_replace("/\"/", "", $text);
		$text = preg_replace("/[\n\r\f]+/m", '\n', $text);

		return $text;
	}
	
	public function text_clean($text)
	{
		// Replaces all spaces with hyphens.
		$text = str_replace(' ', '-', $text);

		// Removes special chars.
		$text = preg_replace('/[^A-Za-z0-9\-\*\@\_\.]/', '', $text);
		
		// Replaces multiple hyphens with single one.
		$text = preg_replace('/-+/', '-', $text);

		return $text;
	}

	// array
	public function array_filter($arr, $key, $search)
	{
		$temp = array();

		if ( is_array($arr) || is_object($arr) )
		{
			foreach ( $arr as $_val )
			{
				if ( $_val[$key] === $search )
				{
					array_push($temp, $_val);
				}
			}
		}

		return $temp;
	}


	// clear cache
	function __destruct()
	{
		//clearstatcache();	
	}
}
?>