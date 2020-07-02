<?php
function connect()
{
	$host = "localhost";
	//$user = "eec";
	//$pass = "eec2015";
	$user = "root";
	$pass = "1234";
	$db = "eweb";
	$con = mysql_connect($host, $user, $pass) OR trigger_error(mysql_error(), E_USER_ERROR);
	$db = mysql_select_db($db);

	//mysql_set_charset('utf8');
	if ( $con ) 
	{
		@mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $con);

		return true;
	}
	else
	{
		return false;
	}
}
?>