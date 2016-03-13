<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_housekeeping_moderation'))
{
	exit;
}

function formatType($t)
{
	switch (intval($t))
	{
		case 101:
		
			return 'Sex';
			
		case 102:
		
			return 'Pers.info';
			
		case 103:
		
			return 'Scam';
			
		case 104:
		
			return 'Abusive';
	
		case 105:
		
			return 'Blocking';
			
		case 106:
		
			return 'Other';
	
		default:
		
			return $t;
	}
}

function formatSent($stamp)
{
	$stamp = time() - $stamp;

	$x = '';

	if ($stamp >= 604800)
	{
		$x = ceil($stamp / 604800) . 'wks';
	}
	else if ($stamp > 86400)
	{
		$x = ceil($stamp / 86400) . 'day';
	}
	else if ($stamp >= 3600)
	{
		$x = ceil($stamp / 3600) . 'hr';
	}
	else if ($stamp >= 120)
	{
		$x  = ceil($stamp / 60) . 'min';
	}
	else
	{
		$x = $stamp . ' s';
	}
	
	$x .= ' ago';
	return $x;
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Calls for help</h1>
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
					<p>This is an overview of the hotel staff team with their contact details as defined in their account settings.</p>
					<br>
					<p><b>This primarily serves as an archive/overview for calls for help. To moderate these tickets please use the ingame moderation tool (any open issues will be sent to you upon login).</b></p>
					<br>
					<p><small>** Calls for help are pruned every now and then, but usually kept for a longer period of time.</small></p>
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
									<td>ID</td>
									<td>Type</td>
									<td>Status</td>
									<td>Sender</td>
									<td>Reported user</td>
									<td>Moderator</td>
									<td>Message</td>
									<td>Room</td>
									<td>Sent</td>
									<td>Chatlog</td>
								</tr>
							</thead>
							<?php
							
							echo '<tbody>';
							
							$get = dbquery("SELECT * FROM moderation_tickets ORDER BY id DESC");
							
							while($user = mysql_fetch_assoc($get))
							{
								echo '<td>'. clean($user['id']) .'</td>
										<td>'. formatType($user['type']) .'</td>
										<td>'. clean($user['status']) .'</td>
										<td>'. $users->formatUsername($user['sender_id']) .'</td>
										<td>';
								
								if ($user['reported_id'] >= 1)
								{
									echo $users->formatUsername($user['reported_id']);
								}
								else
								{
									echo '-';
								}
								
								echo '</td>
										<td>';
								
								if ($user['moderator_id'] >= 1)
								{
									echo $users->formatUsername($user['moderator_id']);
								}
								else
								{
									echo '-';
								}
								
								echo '</td>
										<td>'. clean($user['message']) .'</td>
										<td>'. clean($user['room_name']) .'</td>
										<td>'. formatSent($user['timestamp']) .'</td>
										<td><a href="?p=chatlogs&timeSearch='. $user['timestamp'] .'">View</a></td>
									</tr>
								</tbody>';
							}
							
							?>
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