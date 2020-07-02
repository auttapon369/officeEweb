<?php
session_start();
if ( $_GET['sign'] == "out" )
{
	session_destroy();
	header('location:login');
}
else if ( !empty($_SESSION['ses_user']) )
{
	header('location:./');
}
else
{
?>
<!doctype html>
<html lang="en">
	<head>
		<title>eWEB</title>
		<meta charset="utf-8">
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<link href="lib/img/favicon.ico" rel="icon" />
		<link href="lib/script/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="main.css" rel="stylesheet" />
		<style>
		html {
			overflow-y: hidden;
		}
		</style>
	</head>
	<body class="bg-main">
		<div class="container">
			<div class="row">
				<div class="col-sm-offset-4 col-sm-4 text-center">
				<img src="lib/img/logo.png" height="60" alt="eWEB" class="logo">
				<br><br><br><br>			
				<?php
				if ( $_REQUEST['submit'] )
				{
					@include("config.php");
					@include("lib/class/index.php");
					$_con = connect();
					$_call = new app();
					$_user = $_call->text_clean(trim($_POST['input_user']));
					$_pass = $_call->text_clean(trim($_POST['input_pass']));

					if ( $_user!="" && $_pass!="" )
					{	
						if ( $_res = $_call->get_login($_user, $_pass) )
						{
							//print_r($_res);
							$_SESSION['ses_user'] = $_res[0]['id'];
							$_SESSION['ses_nick'] = $_res[0]['nick'];
							$_SESSION['ses_name'] = $_res[0]['name'];
							$_SESSION['ses_pos'] = $_res[0]['pos'];
							$_SESSION['ses_dept'] = $_res[0]['dept'];
							echo '<span class="text-white">Logging in, Please wait.</span>';
						}
						else
						{
							echo '<span class="text-white">Username/ Password incorrect.</span>';
						}
						echo '<meta http-equiv="refresh" content="4; url=login">';
					}
				}
				else
				{
				?>
					<form name="login" method="post">
						<div class="form-group">
         					<input type="text" name="input_user" class="form-control" placeholder="email" required />
						</div>
						<div class="form-group">
         					<input type="password" name="input_pass" class="form-control" placeholder="password" required />
						</div>
						<input type="submit" name="submit" class="btn btn-block btn-lg btn-sub" value="เข้าสู่ระบบ" />
					</form>
				<?php
				}
				?>
				</div>	
			</div>
		</div>
	</body>
</html>
<?php } ?>