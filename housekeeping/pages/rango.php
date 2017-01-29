<?php
$pagename= "Give Rank";
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

if (isset($_POST['rank']))
{
	$user = filter($_POST['user']);
	$rank = filter($_POST['rank']);
	dbquery("UPDATE users SET rank = '".$rank."' WHERE username = '".$user."' LIMIT 1");
	$msg = "Changed <b>". $user ."</b> rank to <b>". $rank ."</b>.";

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
					Give rank
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
					<form method="post" name="rank" action="?p=rango&do=give">
						<div class="col-lg-4">
							<div class="form-group">
								<label>Username</label>
								<input class="form-control" type="text" name="user" />
							</div>
							<div class="form-group">
								<label>Rank</label>
								<select class="form-control" name="rank">
									<option value="7">7</option>
									<option value="6">6</option>
									<option value="5">5</option>
									<option value="4">4</option>
									<option value="3">3</option>
									<option value="2">2</option>
									<option value="1">1</option>
								</select>
							</div>
						</div>
						<!-- /.col-lg-4 -->
						<div class="form-group col-lg-12">
							<hr/>
							<button type="submit" class="btn btn-outline btn-success">Give Rank</button>
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