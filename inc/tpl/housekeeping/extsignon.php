<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_admin'))
{
	exit;
}

$popClient = '';

if (isset($_POST['username']))
{
	$username = filter($_POST['username']);
	$get = dbquery("SELECT id FROM users WHERE username = '" . $username . "' LIMIT 1");
	
	if (mysql_num_rows($get) == 1)
	{
		$id = intval(mysql_result($get, 0));
		$ticket = $core->GenerateTicket();
		
		dbquery("UPDATE users SET auth_ticket = '" . $ticket . "' WHERE id = '" . $id . "' LIMIT 1");
		$core->Mus('signOut', $id);
		$popClient = $ticket;
		
		fMessage('ok', 'Creating temporary SSO ticket.');
	}
	else
	{
		fMessage('error', 'Could not locate that user.');
	}
}

require_once "top.php";			

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">External user sign on</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<?php
			
			if ($popClient != '')
			{
			
			?>
			<div class="panel panel-info">
				<div class="panel-heading">
					Info
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<p>Ticket created</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Sign in
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<form method="post">
						<div class="form-group">
							<input type="button" onClick="popSsoClient('<?php echo $popClient; ?>'); window.location = '?p=extsignon'" value="Sign in (as <?php echo $username; ?>)" class="btn btn-outline btn-default">
							&nbsp;
							<button onClick="window.location = '?p=extsignon';" class="btn btn-outline btn-success">Done</button>
						</div>
					</form>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<?php
			
			}
			else
			{
			
			?>
			<div class="panel panel-info">
				<div class="panel-heading">
					Info
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<p>This tool allows hotel managament to sign in to the hotel with another account. This may be useful for high level moderation, debugging, and/or supporting users.</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Search
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<form method="post">
						<div class="col-lg-7">
							<div class="form-group">
								<label>Username</label>
								<input class="form-control" type="text" name="username" value="">
							</div>
						</div>
						<!-- /.col-lg-7 -->
						<div class="col-lg-12">
							<div class="form-group">
								<hr />
								<button type="submit" class="btn btn-outline btn-success">Sign in</button>
							</div>
						</div>
						<!-- /.col-lg-12 -->
					</form>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<?php
			
			}
			
			?>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
<?php

require_once "bottom.php";

?>