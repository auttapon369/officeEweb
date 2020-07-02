<?php
class sql
{			
	// result array obj
	public function res($sql)
	{
		if ( $res = mysql_query($sql) )
		{
			$temp = array();
			//$obj = array();

			while ( $arr = mysql_fetch_assoc($res) )
			{
				$i = 0;

				while ( $i < mysql_num_fields($res) )
				{
					$meta = mysql_fetch_field($res, $i);
					$obj[$meta->name] = $arr[$meta->name];
					$i++;
				}

				array_push($temp, $obj);
			}
		}

		return $temp;
	}


	// insert
	public function insert($tb, $data)
	{
		$sql = null;

		if ( is_array($data) )
		{
			$sql = "INSERT INTO ".$tb." (";

			$k = 0;
			foreach ($data as $key => $value)
			{
				$comma = ( $k > 0 ) ? ", " : null;
				$sql .= $comma.$key;
				$k++;
			}

			$sql .= ")";
			$sql .= " VALUES (";

			$v = 0;
			foreach ($data as $key => $value)
			{
				$comma = ( $v > 0 ) ? ", " : null;
				$sql .= $comma."'".$value."'";
				$v++;
			}

			$sql .= ");";
		}

		return $sql;
	}


	// update
	public function update($tb, $data, $where = null)
	{
		$sql = null;

		if ( is_array($data) )
		{
			$sql = "UPDATE ".$tb." SET ";

			$k = 0;
			foreach ($data as $key => $value)
			{
				$comma = ( $k > 0 ) ? ", " : null;
				$sql .= $comma.$key."='".$value."'";
				$k++;
			}

			if ( !empty($where) )
			{
				$sql .= " WHERE ".$where;
			}

			//$sql .= ";";
		}		

		return $sql;
	}


	// query
	public function exec($sql)
	{
		$query = mysql_query($sql);

		if ($query)
		{
			return true;
		}
		else
		{
			return false;
		}
	}


}
?>