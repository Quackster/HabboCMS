<?php

require_once "../inc/class.rooms.php";

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_housekeeping_moderation'))
{
	exit;
}

$searchResults = null;

if (isset($_GET['timeSearch']))
{
	$_POST['searchQuery'] = $_GET['timeSearch'];
	$_POST['searchType'] = '4';
}

if (isset($_POST['searchQuery']))
{
	$query = filter($_POST['searchQuery']);
	$type = $_POST['searchType'];
	
	$q = "SELECT * FROM chatlogs WHERE ";
	
	switch ($type)
	{
		default:
		case '1':
		
			$q .= "user_name = '" . $query . "'";
			break;
			
		case '2':
		
			$q .= "message LIKE '%" . $query . "%'";
			break;
			
		case '3':
		
			$q .= "room_id = '" . $query . "'";
			break;
			
		case '4':
		
			$cutMin = intval($query) - 300;
			$cutMax = intval($query) + 300;
			
			$q .= "timestamp >= " . $cutMin . " AND timestamp <= " . $cutMax;
	}
	
	$searchResults = dbquery($q);
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Chatlogs</h1>
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
					<p>This tool may be used to look up and review room chatlogs. Special chat such as IM and minimail is not being monitored here. Seperate tools may be available for them.</p>
					<br>
					<p><b>IMPORTANT:</b> Chatlogs are only kept for a limited period of time.<br>Chatlogs older than 2 weeks will be permanently deleted. If you would like to keep them for a longer period, please save them locally.</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
<?php

if (isset($searchResults))
{
	echo '<div class="panel panel-default">
			<div class="panel-heading">
				<b>Search results</b> - You searched for "<b>' . clean($_POST['searchQuery']) . '</b>"
			</div>
			<div class="panel-body">
				<p><a href="?p=chatlogs&doReset">Clear search</a></p>
				<hr />
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="dataTables-example">
						<thead>
							<tr>
								<td>Date</td>
								<td>Time</td>
								<td>User</td>
								<td>Room</td>
								<td>Message</td>
								<td>Timestamp</td>
							</tr>
						</thead>';
	
			while ($result = mysql_fetch_assoc($searchResults))
			{
				if (strlen($result['hour']) < 2)
				{
					$result['hour'] = '0' . $result['hour'];
				}
				
				if (strlen($result['minute']) < 2)
				{
					$result['minute'] = '0' . $result['minute'];
				}		
	
				echo '<tbody>
						<tr>
							<td>'. $result['full_date'] . '</td>
							<td>'. $result['hour'] . ':' . $result['minute'] .'</td>
							<td><a href="#">'. clean($result['user_name']) .'</a> ('. $result['user_id'] .')</td>
							<td><a href="#">'. clean(RoomManager::GetRoomVar($result['room_id'], 'caption')) .'</a> ('. $result['room_id'] .')</td>
							<td>'. clean($result['message']) .'</td>
							<td>'. clean($result['timestamp']) .' (<a href="?p=chatlogs&timeSearch='. intval($result['timestamp']) .'">Search</a>)</td>
						</tr>
					</tbody>';
			}
	
			echo '</table>
				</div>
				<!-- /.table-responsive -->
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->';

}
else
{

?>
			<div class="panel panel-default">
				<div class="panel-heading">
					Search chatlogs
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<form method="post">
						<div class="col-lg-7">
							<div class="form-group">
								<label>Search type</label>
								<select class="form-control" name="searchType">
									<option value="1">By username</option>
									<option value="2">By message content</option>
									<option value="3">By room (by ID only!)</option>
									<option value="4">By timestamp (600 second range)</option>
								</select>
							</div>
							<div class="form-group">
								<label>Search query</label>
								<input class="form-control" type="text" name="searchQuery">
							</div>
						</div>
						<!-- /.col-lg-7 -->
						<div class="col-lg-12">
							<hr />
							<div class="form-group">
								<button type="submit" class="btn btn-outline btn-default">Search</button>
							</div>
						</div>
						<!-- /.col-lg-12 -->
					</form>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Recent activity
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="dataTables-example">
							<thead>
								<tr>
									<td>Date</td>
									<td>Time</td>
									<td>User</td>
									<td>Room</td>
									<td>Message</td>
									<td>Timestamp</td>
								</tr>
							</thead>
							<tbody>
								<?php
								
								$getRecent = dbquery("SELECT * FROM chatlogs ORDER BY id DESC LIMIT 30");
								
								while ($recent = mysql_fetch_assoc($getRecent))
								{
									if (strlen($recent['hour']) < 2)
									{
										$recent['hour'] = '0'. $recent['hour'];
									}
									
									if (strlen($recent['minute']) < 2)
									{
										$recent['minute'] = '0'. $recent['minute'];
									}		
								
									echo '<tr>
											<td>'. $recent['full_date'] .'</td>
											<td>'. $recent['hour'] .':'. $recent['minute'] .'</td>
											<td><a href="#">'. clean($recent['user_name']) .'</a> ('. $recent['user_id'] .')</td>
											<td><a href="#">'. clean(RoomManager::GetRoomVar($recent['room_id'], 'caption')) .'</a> ('. $recent['room_id'] .')</td>
											<td>'. clean($recent['message']) .'</td>
											<td>'. clean($recent['timestamp']) .' (<a href="?p=chatlogs&timeSearch='. intval($recent['timestamp']) .'">Search</a>)</td>
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

}

require_once "bottom.php";

?>