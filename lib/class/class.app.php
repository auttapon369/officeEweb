<?php
class app extends general 
{			
	// define properties
	private $sql;


	// constructor
	public function __construct()
	{
		$this->sql = new sql();
	}


	// login
	public function get_login($u, $p)
	{
		$sql =	"SELECT e.emp_id as 'id'".
					", e.emp_nick as 'nick'".
					", e.emp_name as 'name'".
					", e.emp_pos as 'pos'".
					", d.dept_abb as 'dept'".
				" FROM employee e".
				" LEFT JOIN department d ON d.dept_id = e.dept_id".
				" WHERE emp_mail='".$u."' AND emp_pass='".md5($p)."' AND emp_disable='N'";

		return $this->sql->res($sql);
	}


	// my role
	public function get_resp($emp)
	{
		$sql =	"SELECT rps_id as 'id'".
					", rps_detail as 'detail'".
					", rps_date_start as 'date'".
					//", rps_disable as 'disable'".
				" FROM responsibility".
				" WHERE emp_id = ".$emp." AND rps_disable='N'".
				" ORDER BY rps_date_start DESC";

		return $this->sql->res($sql);
	}

	public function add_role($data)
	{
		$field = array
		(
			'rps_detail',
			'rps_date_start',
			'emp_id'
		);

		$combine = array_combine($field, $data);
		$sql = $this->sql->insert('responsibility', $combine);

		if( $this->sql->exec($sql) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	// my permit
	public function get_login_permit($emp)
	{
		$sql =	"SELECT pd.pmd_date as 'date'".
					", p.pm_code as 'code'".
					", p.pm_name_th as 'name_th'".
					", p.pm_name_en as 'name_en'".
				" FROM permit_detail pd".
				" LEFT JOIN permit p ON p.pm_id = pd.pm_id".
				" WHERE emp_id = ".$emp.
				" ORDER BY pmd_date DESC";

		return $this->sql->res($sql);
	}


	// project
	public function get_pro($id = null)
	{
		//$where = ( !empty($id) ) ? " WHERE pro_id = '".$id."'" : null;
		$where = ( !empty($id) ) ? " WHERE pro_short_name LIKE '".$id."'" : null;

		$sql =	"SELECT a.pro_id as 'id'".
					", a.pro_code as 'code'".
					", a.pro_full_name as 'fullname'".
					", a.pro_short_name as 'shortname'".
					", a.pro_link as 'link'".
					", a.pro_date_start as 'date_start'".
					", a.pro_date_end as 'date_end'".
					", b.prs_name_en as 'stat_en'".
					", b.prs_name_th as 'stat_th'".
					", c.cus_abb as 'abb'".
				" FROM project a".
					" LEFT JOIN project_status b ON a.prs_id = b.prs_id".
					" LEFT JOIN customer c ON a.cus_id = c.cus_id".
				$where.
				" ORDER BY prs_ord, pro_code";

		return $this->sql->res($sql);
	}

	public function get_pro_status()
	{
		$sql =	"SELECT prs_id as 'id'".
					", prs_name_en as 'en'".
					", prs_name_th as 'th'".
				" FROM project_status".
				" ORDER BY prs_ord";

		return $this->sql->res($sql);
	}

	public function add_pro($data)
	{
		$field = array
		(
			'pro_code',
			'pro_full_name',
			'pro_short_name',
			'pro_date_start',
			'prs_id',
			'cus_id'
		);

		$combine = array_combine($field, $data);
		$sql = $this->sql->insert('project', $combine);

		if ( $this->sql->exec($sql) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function update_pro($data, $id)
	{
		$field = array
		(
			'pro_code',
			'pro_full_name',
			'pro_short_name',
			'pro_link',
			'pro_date_end',
			'prs_id',
			'cus_id'
		);

		$combine = array_combine($field, $data);
		$where = "pro_id=".$id;
		$sql = $this->sql->update("project", $combine, "$where");

		if ( $this->sql->exec($sql) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	// task
	public function get_task($id, $year, $view)
	{
		$type = null;
		$type = ( $view=='pro' ) ? " pro_id = '".$id."'" : $type;
		$type = ( $view=='emp' ) ? " emp_nick LIKE '".$id."'" : $type;
		
		if ( $year == "*" )
		{
			$where = " AND as_date_clear is null";
		}
		else if ( !empty($year) )
		{
			$where = " AND year(as_date_create) = ".$year;
		}
		else
		{
			$where = null;
		}

		$sql =	"SELECT a.as_id as 'id'".
				", a.as_detail as 'detail'".
				", a.as_attach as 'attach'".
				", a.as_during as 'during'".
				", a.as_date_create as 'date_create'".
				", a.as_date_plan as 'date_plan'".
				", a.as_date_clear as 'date_clear'".
				", a.as_date_approve as 'date_approve'".
				", a.as_ref as 'ref'".
				" FROM assign a".
				" LEFT JOIN assign_detail b ON a.as_id = b.as_id".
				" LEFT JOIN employee e ON e.emp_id = b.emp_id".
				" WHERE".$type.$where.
				" ORDER BY as_date_approve DESC, as_date_clear DESC, as_date_plan";

		return $this->sql->res($sql);
	}

	public function get_task_year()
	{
		$sql =	"SELECT DISTINCT year(as_date_create) as 'year'".
				" FROM assign".
				" ORDER BY as_date_create DESC";

		return $this->sql->res($sql);
	}

	public function add_task($data)
	{
		$id = 0;
		$field = array
		(
			'as_detail',
			'as_during',
			'as_date_create',
			'as_date_plan',
			'as_ref'
		);

		$new = array();

		foreach ($data as $key => $value)
		{
			if ( !is_array($value) )
			{
				array_push($new, mysql_real_escape_string($value));
			}
		}

		$combine = array_combine($field, $new);
		$sql = $this->sql->insert('assign', $combine);

		if ( $this->sql->exec($sql) )
		{
			$sql_id = 	"SELECT as_id as 'id'".
						" FROM assign".
						" WHERE as_detail = '".$combine['as_detail']."'".
							" AND as_date_create = '".$combine['as_date_create']."'".
							" AND as_date_plan = '".$combine['as_date_plan']."'";
			
			$id = $this->sql->res($sql_id);
		}

		return $id;
	}

	public function update_task($type, $id, $date)
	{
		if ( $type == "clear" )
		{
			$field = "as_date_clear";
		}
		else if ( $type == "approve" )
		{
			$field = "as_date_approve";
		}
		else
		{
			return false;
		}

		$data = array("$field"=>"$date");
		$where = "as_id=".$id;
		$sql = $this->sql->update("assign", $data, "$where");

		if ( $this->sql->exec($sql) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	// tag
	public function get_tag($id)
	{
		$sql =	"SELECT a.asd_id as 'id'".
					", b.pro_short_name as 'pro_name'".
					", c.emp_nick as 'nick'".
					", c.dept_id as 'dept'".
				" FROM assign_detail a".
					" LEFT JOIN project b ON a.pro_id = b.pro_id".
					" LEFT JOIN employee c ON a.emp_id = c.emp_id".
				" WHERE as_id = '".$id."'";

		return $this->sql->res($sql);
	}

	public function add_tag($id, $data, $type)
	{
		$field = array
		(
			'as_id',
			'pro_id',
			'emp_id'
		);

		foreach ($data as $value)
		{
			if ( $type == "pro" )
			{
				$arr = array($id, $value, null);
			}
			else if ( $type == "emp" )
			{
				$arr = array($id, null, $value);
			}
			else
			{
				$arr = array();
			}

			$combine = array_combine($field, $arr);
			$sql = $this->sql->insert('assign_detail', $combine);
			$n = 0;

			if( $this->sql->exec($sql) )
			{
				$n++;
			}
		}

		return $n;
	}

	// comment
	public function get_comment($id)
	{
		$sql =	"SELECT a.asc_id as 'id'".
					", a.asc_text as 'text'".
					", a.asc_date as 'date'".
					", c.emp_nick as 'nick'".
					", c.dept_id as 'dept'".
				" FROM assign_comment a".
					" LEFT JOIN employee c ON a.emp_id = c.emp_id".
				" WHERE as_id = '".$id."'".
				" ORDER BY asc_date";

		return $this->sql->res($sql);
	}

	public function add_comment($id, $text, $date, $me)
	{
		$data = array
		(
			"asc_text" => "$text",
			"asc_date" =>"$date",
			"as_id" => "$id",
			"emp_id" => "$me"
		);

		$sql = $this->sql->insert('assign_comment', $data);

		if( $this->sql->exec($sql) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	// event
	public function get_event($date = null)
	{
		$where = ( !empty($date) ) ? " AND cl_date LIKE '".$date."%' " : null;
		$sql =	"SELECT ".
					"a.cl_id as 'id', ".
					"b.clt_group as 'group', ".
					"a.cl_date as 'date', ".
					"b.clt_name as 'type', ".
					"a.cl_detail as 'detail', ".
					"a.cl_time as 'time', ".
					"c.emp_nick as 'nick' ".
				"FROM calendar a ".
				"LEFT JOIN calendar_type b ON a.clt_id = b.clt_id ".
				"LEFT JOIN employee c ON a.emp_id = c.emp_id ".
				"WHERE cl_disable = 'N'".$where.
				"ORDER BY cl_date";

		$res = mysql_query($sql);
		$event = array();

		while ( $arr = mysql_fetch_assoc($res) )
		{
			$i = 0;
			while ( $i < mysql_num_fields($res) )
			{
			    $meta = mysql_fetch_field($res, $i);
			    $obj[$meta->name] = $arr[$meta->name];
				$i++;
			}
			if ( $obj['group'] == "1" )
			{
				$event['holiday'][] = $obj;
			}
			if ( $obj['group'] == "2" )
			{
				$event['absence'][] = $obj;
			}
			if ( $obj['group'] == "3" )
			{
				$event['appoint'][] = $obj;
			}			
		}
		
		return $event;
	}


	// weekly
	public function get_weekly($date, $emp)
	{
		$sql =	"SELECT".
					" w_id as 'id'".
					", w_detail as 'detail'".
					", w_trouble as 'trouble'".
					", w_result as 'result'".
					", w_place as 'place'".
					", w_date as 'date'".
					", w_status as 'status'".
				" FROM weekly".
				" WHERE w_date LIKE '".$date."%' AND emp_id = '".$emp."'".
				" ORDER BY w_date";

		return $this->sql->res($sql);
	}

	public function add_weekly($data)
	{
		$field = array
		(
			'w_detail',
			'w_trouble',
			'w_result',
			'w_place',
			'w_date',
			'emp_id'
		);

		$combine = array_combine($field, $data);
		$sql = $this->sql->insert('weekly', $combine);

		if( $this->sql->exec($sql) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function update_weekly($data, $id)
	{
		$field = array
		(
			'w_detail',
			'w_trouble',
			'w_result',
			'w_place'
		);

		$combine = array_combine($field, $data);
		$where = "w_id=".$id;
		$sql = $this->sql->update("weekly", $combine, "$where");

		if ( $this->sql->exec($sql) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function check_weekly($data)
	{
		$field = array('w_status');
		$combine = array_combine($field, array('Y'));

		foreach ( $data as $key => $value )
		{
			$where = "w_id=".$value;
			$sql = $this->sql->update("weekly", $combine, "$where");
			if ( $this->sql->exec($sql) )
			{
				$res = true;
			}
			else
			{
				$res = false;
			}
		}

		return $res;
	}


	// calendar
	public function get_calendar($year, $group, $emp=null)
	{
		$where = ( strpos($year, '-') ) ? " AND cl_date>='".$year."'" : " AND year(cl_date)='".$year."'";
		$where_emp = ( !empty($emp) ) ? " AND c.emp_id=".$emp : null;

		$sql =	"SELECT c.cl_id as 'id'".
				", ct.clt_id as 'code'".
				", ct.clt_name as 'type'".
				", c.cl_detail as 'detail'".
				", c.cl_date as 'date'".
				", c.cl_time as 'time'".
				//", c.cl_disable as 'disable'".
				", e.emp_nick as 'nick'".
				" FROM calendar c".
				" LEFT JOIN calendar_type ct ON c.clt_id = ct.clt_id".
				" LEFT JOIN employee e ON e.emp_id = c.emp_id".
				" WHERE cl_disable='N' AND clt_group=".$group.$where.$where_emp.
				" ORDER BY cl_date";

		return $this->sql->res($sql);
	}

	public function add_calendar($data)
	{
		$field = array
		(
			'cl_detail',
			'cl_date',
			'clt_id',
			'emp_id'
		);

		$combine = array_combine($field, $data);
		$sql = $this->sql->insert('calendar', $combine);

		if( $this->sql->exec($sql) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	// permit
	public function get_permit($emp, $type = null)
	{
		$where = ( !empty($type) ) ? " AND pm_code = '".$type."'" : null;

		$sql =	"SELECT".
					" a.pm_code as 'code'".
					", a.pm_name_en as 'name_en'".
					", a.pm_name_th as 'name_th'".
					", b.pmd_date as 'date'".
				" FROM permit a".
				" LEFT JOIN permit_detail b ON a.pm_id = b.pm_id".
				" WHERE emp_id = ".$emp.$where.
				" ORDER BY pmd_date, pm_code";

		return $this->sql->res($sql);
	}


	// department
	public function get_dept($id = null)
	{
		$where = ( !empty($id) ) ? " AND dept_id = ".$id : null;

		$sql =	"SELECT dept_id as 'id'".
					", dept_name_en as 'name_en'".
					", dept_name_th as 'name_th'".
					", dept_abb as 'abb'".
				" FROM department".
				" WHERE dept_disable = 'N'".$where.
				" ORDER BY dept_ord";

		return $this->sql->res($sql);
	}


	// employee
	public function get_emp($type, $id = null, $all)
	{
		$col = ( $type == "dept" ) ? "e.dept_id" : null;
		$col = ( $type == "emp" ) ? "e.emp_id" : $col;
		$show = ( $all == "N" ) ? " AND e.emp_disable = '".$all."'" : null;
		$where = ( !empty($id) ) ? " WHERE ".$col." = ".$id.$show : null;

		$sql =	"SELECT e.emp_id as 'id'".
					", e.emp_code as 'code'".
					", e.emp_nick as 'nick'".
					", e.emp_name as 'name'".
					", e.emp_sur as 'sur'".
					", e.emp_mail as 'mail'".
					//", e.emp_pass as 'pass'".
					", e.emp_phone as 'phone'".
					", e.emp_pos as 'pos'".
					", e.emp_desk as 'desk'".
					", e.emp_level as 'level'".
					", e.emp_disable as 'disable'".
					", e.dept_id as 'dept'".
				" FROM employee e".
				" LEFT JOIN department d ON e.dept_id = d.dept_id".
				$where.
				" ORDER BY d.dept_ord, e.emp_level DESC, e.emp_id";
				//	" ORDER BY e.emp_code";

		return $this->sql->res($sql);
	}

	public function update_emp($data, $id)
	{
		$field = array
		(
			'dept_id',
			'emp_pos',
			'emp_phone',
			'emp_desk',
			'emp_level',
			'emp_disable'
		);

		if ( count($data) > count($field) )
		{
			array_push($field, "emp_pass");
		}

		$combine = array_combine($field, $data);

		$where = "emp_id=".$id;
		$sql = $this->sql->update("employee", $combine, "$where");

		if ( $this->sql->exec($sql) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	// customer
	public function get_cus($all = null)
	{
		$where = ( $all == "Y" ) ? null : " WHERE cus_disable='N'";
		$sql =	"SELECT cus_id as 'id'".
					", cus_name_th as 'th'".
					", cus_name_en as 'en'".
					", cus_abb as 'abb'".
					", cus_link as 'link'".
				" FROM customer".
				$where.
				" ORDER BY cus_ord";

		return $this->sql->res($sql);
	}


	// destruct
    public function __destruct()
    {
		//unset($this->db);
		//unset($this->table);
	}
}
?>