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
			<h1 class="page-header">Staffliste</h1>
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
				<div class="panel-body">
					<p>Hier sind alle user mit einem Rang aufgelistet der h&ouml;her wie 2 ist.</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Staffs
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="dataTables-example">
							<thead>
								<tr>
									<td>User</td>
									<td>Rank</td>
									<td>Contact</td>
								</tr>
							</thead>
							<tbody>
								<?php
								
								$get = dbquery("SELECT id,rank,mail FROM users WHERE rank >= 3 ORDER BY rank DESC");
								
								while ($user = mysql_fetch_assoc($get))
								{
									echo '<tr>
											<td>'. $users->formatUsername($user['id']) .'</td>
											<td>'. $users->getRankName($user['rank']) .'</td>
											<td><a href="mailto:'. $user['mail'] .'">'. $user['mail'] .'</a></td>
										</tr>';
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