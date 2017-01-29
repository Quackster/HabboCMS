<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_housekeeping_moderation'))
{
	exit;
}

$ip = '';

if (isset($_POST['ip'])) { $ip = filter($_POST['ip']); }

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">IP Tool</h1>
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
					<p>Mit dem IP Adressen Tool kannst du herausfinden welche IP ein User hat und ob er Klone besitzt.</p>
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
					<div class="col-lg-4">
						<form method="post">
							<div class="form-group">
								<label>Username</label>
								<input class="form-control" type="text" name="user" />
								<br />
								<button type="submit" class="btn btn-outline btn-default">Search</button>
							</div>
						</form>
						<form method="post">
							<div class="form-group">
								<label>IP Addresse</label>
								<input class="form-control" type="text" name="ip" />
								<br />
								<button type="submit" class="btn btn-outline btn-default">Search</button>
							</div>
						</form>
					</div>
					<!-- /.col-lg-4 -->
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Result
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<?php
					
					if (isset($_POST['user']))
					{
						$user = filter($_POST['user']);
						$get = dbquery("SELECT ip_last FROM users WHERE username = '" . $user . "' LIMIT 1");
						
						if (mysql_num_rows($get) > 0)
						{
							$ip = mysql_result($get, 0);
						}
						
						echo '<center><p><b>' . $user . '\'s</b> IP is <h1>'. $ip .'</h1></p></center><hr />';
					}
					
					if (isset($ip) && strlen($ip) > 0)
					{
						echo '<center><p>Users on this IP: <h1>'. $ip .'</h1></p></center>';
						$get = dbquery("SELECT * FROM users WHERE ip_last = '" . $ip . "' LIMIT 50");
						while ($user = mysql_fetch_assoc($get))
						{
						
						?>
						<div class="col-lg-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<b><?php echo clean($user['username']); ?></b> <small>(ID: <?php echo $user['id']; ?>)</small>
								</div>
								<!-- /.panel-heading -->
								<div class="panel-body">
									<div style="float:left; width: 70px; height: 130px; background: url(http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=<?php echo $user['look']; ?>);">
									</div>
									<p><b>Last online:</b> <?php echo $user['last_online']; ?></p>
									<p><b>E-mail:</b> <?php echo $user['mail']; ?></p>
									<p>This user is <?php echo (($user['online'] == "1") ? '<b style="color: darkgreen;">online!</b>' : '<b style="color: red;">offline</b>'); ?></p>
									<img src="images/credits.png">&nbsp;<?php echo $user['credits']; ?>&nbsp;&nbsp;<img src="images/pixels.png">&nbsp;<?php echo $user['activity_points']; ?>
								</div>
								<!-- /.panel-body -->
							</div>
							<!-- /.panel -->
						</div>
					<?php
					
						}
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