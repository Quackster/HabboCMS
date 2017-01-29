<?php
$pagename= "Give Pixels";
if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_sysadmin'))
{
	exit;
}

$data = null;
$u = 0;

if (isset($_POST['pixels']))
{
	$user = filter($_POST['user']);
	$amount = filter($_POST['pixels']);
	dbquery("UPDATE users SET activity_points  = activity_points + ".$amount." WHERE username = '".$user."' LIMIT 1");
	
	$msg = "You gave ".$user." ".$amount." Pixels.";

	}	

require_once "top.php";
?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo $pagename; ?></h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Give
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<?php
					
					if(isset($msg))
					{
						echo '<div class="alert alert-success alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								'. $msg .'
							</div>';
					}
					
					?>
					<form method="post" name="pixels" action="?p=givepixels&do=give">
						<div class="col-lg-4">
							<div class="form-group">
								<label>Username</label>
								<input class="form-control" type="text" name="user" />
							</div>
							<div class="form-group">
								<label>Pixels</label>
								<input class="form-control" type="text" name="pixels" />
							</div>
						</div>
						<!-- /.col-lg-4 -->
						<div class="col-lg-12">
							<div class="form-group">
								<hr />
								<button type="submit" class="btn btn-outline btn-success">Give pixels</button>
							</div>
						</div>
						<!-- /.col-lg-12 -->
					</form>
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