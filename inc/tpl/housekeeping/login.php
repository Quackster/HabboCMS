<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (HK_LOGGED_IN)
{
	exit;
}

if (isset($_POST['usr']) && isset($_POST['pwd']))
{
	$username = filter($_POST['usr']);
	$password = $core->uberHash($_POST['pwd']);

	if ($users->validateUser($username, $password))
	{		
		$hkId = $users->name2id($username);
		
		if ($users->GetUserVar($hkId, 'rank') > 4)
		{	
			session_destroy();
			session_start();
		
			$_SESSION['UBER_USER_N'] = $users->getUserVar($hkId, 'username');
			$_SESSION['UBER_USER_H'] = $password;
			$_SESSION['UBER_HK_USER_N'] = $_SESSION['UBER_USER_N'];
			$_SESSION['UBER_HK_USER_H'] = $_SESSION['UBER_USER_H'];
			
			header("Location: " . HK_WWW . "/?=main");
			
			exit;
		}
		else
		{
			$_SESSION['HK_LOGIN_ERROR'] = "<div class=\"alert alert-danger\"><p style=\"text-align: center;\">Du hast keine Rechte um auf das Housekeeping zuzugreifen!</p></div>";
		}
	}		
	else
	{
		$_SESSION['HK_LOGIN_ERROR'] = '<div class="alert alert-danger"><p style=\"text-align: center;\">Fehlerhafte Angaben</p></div>';
	}
}

?>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="robots" content="noindex,nofollow">
		
		<title>OrbitCMS -> Login</title>
		
		<!-- Core CSS - Include with every page -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="font-awesome/css/font-awesome.css" rel="stylesheet">
		
		<!-- SB Admin CSS - Include with every page -->
		<link href="css/sb-admin.css" rel="stylesheet">
	</head>
	<body class="login">
		<div>
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<?php if(isset($_SESSION['HK_LOGIN_ERROR'])) { ?>
					<div><?php echo $_SESSION['HK_LOGIN_ERROR']; ?></div>
					<?php } ?>
					<div class="login-panel panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Please Sign In</h3>
						</div>
						<div class="panel-body">
							<form method="post" role="form">
								<fieldset>
									<div class="form-group">
										<input class="form-control" id="user_login" placeholder="Username" name="usr" type="text" autofocus value="<?php if (LOGGED_IN) { echo USER_NAME; } ?>">
									</div>
									<div class="form-group">
										<input class="form-control" placeholder="Password" name="pwd" type="password" value="">
									</div>
									<!-- Change this to a button or input when using this as a form -->
									<input type="submit" class="btn btn-lg btn-success btn-block" value="Login">
									<input type="button" class="btn btn-lg btn-default btn-block" onClick="document.location = '/';" value="Back to site">
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<script type="text/javascript">
		function wp_attempt_focus() {
			setTimeout( function() {
				try {
					d = document.getElementById('user_login');
					d.value = '';
					d.focus();
				} catch(e){}
			}, 200 );
		}
		wp_attempt_focus();
		if(typeof wpOnload=='function')wpOnload()
		</script>
		
		<!-- Core Scripts - Include with every page -->
		<script src="js/jquery-1.10.2.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
		
		<!-- SB Admin Scripts - Include with every page -->
		<script src="js/sb-admin.js"></script>
	</body>
</html>