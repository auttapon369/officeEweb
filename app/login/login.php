<?php
session_start();
//session_destroy();
if ( !empty($_SESSION['ses_user']) )
{
	header('location:../');
	exit();
}
include ("../config.php");
echo connectDB();
?>
<!DOCTYPE HTML>
<HTML LANG="th-TH">
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8" />
<META NAME="viewport" CONTENT="initial-scale=1.0, user-scalable=no" />
<META NAME="author" CONTENT="Expert Engineering & Com." />
<META NAME="copyright" CONTENT="Expert Engineering & Com." />
<LINK HREF="../_img/favicon.ico" REL="shortcut icon" />
<LINK HREF="../_img/apple-touch-icon.png" REL="apple-touch-icon" />
<LINK HREF="style.css?v=14725" REL="stylesheet" TYPE="text/css" MEDIA="screen" />
<TITLE>eWEB: Login</TITLE>
</HEAD>
<BODY>
<DIV ID="login">
<?php
if ( $_REQUEST['submit'] )
{
	if ( $_POST['input_user'] == "" && $_POST['input_pass'] == "****" )
	{
		$_SESSION['ses_user'] = 99;
		$_SESSION['ses_level'] = 99;
		$_SESSION['ses_nick'] = "Guest";
		echo '<SPAN CLASS="yes">Logging in, Please wait.</SPAN>';
	}
	else
	{		
		$sql = 'SELECT * FROM ass_emp WHERE ae_id="'.$_POST['input_user'].'" AND ae_pass="'.md5($_POST['input_pass']).'" AND ae_active="Y"';
		$res = mysql_query($sql) or die ('Error #1');
		$num = mysql_num_rows($res);

		if ( $num == 1 )
		{
			$arr = mysql_fetch_array($res);
			$_SESSION['ses_user'] = $_POST['input_user'];
			$_SESSION['ses_level'] = $arr['ae_level'];
			$_SESSION['ses_nick'] = $arr['ae_nick'];
			echo '<SPAN CLASS="yes">Logging in, Please wait.</SPAN>';
		}
		else
		{
			echo '<SPAN CLASS="no">Username/ Password incorrect.</SPAN>';
		}
	}

	echo '<META HTTP-EQUIV="refresh" CONTENT="2; URL=./">';
}
else
{
?>
	<FORM NAME="login" METHOD="post">
		<!--<H1>Expert & ATA<Q>Systems in Organization</Q></H1>-->
		<TABLE>
			<TR>
				<TD>
					<!--<INPUT TYPE="text" ID="user" NAME="input_user" VALUE="id" />-->
					<LABEL>Log in as</LABEL>
					<SELECT NAME="input_user">
						<OPTION VALUE="">Guest</OPTION>
						<?php
						$sql = 'SELECT * FROM ass_emp WHERE ae_active="Y" ORDER BY ad_id, ae_level DESC';
						$res = mysql_query($sql);
						while ( $arr = mysql_fetch_array($res) )
						{
							echo '<OPTION VALUE="'.$arr['ae_id'].'">'.$arr['ae_nick'].' '.$arr['ae_name'].'</OPTION>';
						}
						?>
					</SELECT>
				</TD>
			</TR>
			<TR>
				<TD>
					<LABEL>Password</LABEL>
					<INPUT TYPE="password" ID="pass" NAME="input_pass" VALUE=" " />
				</TD>
			</TR>
			<TR>
				<TD><INPUT TYPE="submit" NAME="submit" VALUE="Sign In" /></TD>
			</TR>
		</TABLE>
	</FORM>
<?php
}
?>
</DIV>
</BODY>
</HTML>