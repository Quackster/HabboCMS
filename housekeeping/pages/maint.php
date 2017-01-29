<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->GetuserVar(USER_ID, 'rank') > 6)
{
	exit;
}

$maintMode = mysql_result(dbquery("SELECT maintenance FROM site_config LIMIT 1"), 0);

if (isset($_GET['switch']))
{
	$newState = "1";

	if ($maintMode == "1")
	{
		$newState = "0";
	}
	
	dbquery("UPDATE site_config SET maintenance = '" . $newState . "' LIMIT 1");
	$maintMode = $newState;
	
	header("Location: ?p=maint");
	exit;
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Maintenance Mode</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					Info
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<p>
						Maintenance mode can be used to disable the site and effectively prevent new logins to the server. Please note that any users still
						connected to the server or have a login session generated for them, will still be able to use the server until they are disconnected
						or it reboots.
					</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					State
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<?php
					
					if ($maintMode == "1")
					{
						echo '<center>
								<h3 style="color: darkred;">Maintenance mode is currently ENABLED. Site is not accessible to regular users.</h3>
								<hr />
								<button style="font-weight: bold;" onClick="document.location = \'?p=maint&switch\';" class="btn btn-lg btn-outline btn-danger">Restore site, disable maintenance</button>
							</center>';
					}
					else
					{
						echo '<center>
								<h3 style="color: darkred;">Maintenance mode is currently disabled.</h3>
								<hr />
								<button style="font-weight: bold;" onClick="document.location = \'?p=maint&switch\';" class="btn btn-lg btn-outline btn-warning">Enable maintenance</button>
							</center>';
					}
					
					?>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
<?php

require_once "bottom.php";

?>