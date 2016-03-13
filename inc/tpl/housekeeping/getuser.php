<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN)
{
	exit;
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">List of Users</h1>
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
					<p>Here you have a list of all users of your Hotel.</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Table
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="dataTables-example">
							<thead>
								<tr>
									<th>Username</th>
									<th>Rank</th>
									<th>E-Mail</th>
									<th>Credits</th>
									<th>Pixels</th>
									<th>Gender</th>
									<th>Online</th>
								</tr>
							</thead>
							<tbody>
								<?php
								
								$get = dbquery("SELECT id,rank,mail,credits,activity_points,gender,online FROM users WHERE rank >= 1 ORDER BY rank DESC");
								
								while ($user = mysql_fetch_assoc($get))
								{
									echo '<tr>';
									echo '<td>' . $users->formatUsername($user['id']) . '</td>';
									echo '<td>' . $users->getRankName($user['rank']) . '</td>';
									echo '<td><a href="mailto:' . $user['mail'] . '">' . $user['mail'] . '</a></td>';
									echo '<td> ' . $user['credits'] . '</td>';
									echo '<td> ' . $user['activity_points'] . '</td>';
									echo '<td> ' . $user['gender'] . '</td>';
									echo '<td> ' . $user['online'] . '</td>';
									echo '</tr>';
								}
								
								?>
							</tbody>
						</table>
					</div>
					<!-- /.table-responsive -->
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