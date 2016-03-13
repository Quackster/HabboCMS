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

if (isset($_GET['doDenyAppeal']) && is_numeric($_GET['doDenyAppeal']))
{
	dbquery("UPDATE bans SET appeal_state = '2' WHERE id = '" . intval($_GET['doDenyAppeal']) . "'" . (($users->HasFuse(USER_ID, 'fuse_admin')) ? "" : " AND added_by = '" . HK_USER_NAME . "'") . " LIMIT 1");
	
	if (mysql_affected_rows() >= 1)
	{
		dbquery("DELETE FROM bans_appeals WHERE ban_id = '" . intval($_GET['doDenyAppeal']) . "' LIMIT 1");
		fMessage('ok', 'Ban appeal denied.');
		
		header("Location: ?p=bans");
		exit;		
	}
}

if (isset($_GET['unban']) && is_numeric($_GET['unban']))
{
	dbquery("DELETE FROM bans WHERE id = '" . intval($_GET['unban']) . "'" . (($users->HasFuse(USER_ID, 'fuse_admin')) ? "" : " AND added_by = '" . HK_USER_NAME . "'") . " LIMIT 1");
	
	if (mysql_affected_rows() >= 1)
	{
		dbquery("DELETE FROM bans_appeals WHERE ban_id = '" . intval($_GET['unban']) . "' LIMIT 1");
		fMessage('ok', 'Ban removed.');
		
		$core->Mus('reloadbans');
		
		header("Location: ?p=bans");
		exit;
	}
}

if (isset($_POST['bantype']))
{
	$bantype = filter($_POST['bantype']);
	$value = filter($_POST['value']);
	$reason = filter($_POST['reason']);
	$length = filter($_POST['length']);
	$noAppeal = '';
	
	if (isset($_POST['no-appeal']))
	{
		$noAppeal = filter($_POST['no-appeal']);
	}
	
	if ($bantype != "ip" && $bantype != "user")
	{
		$bantype = "user";
	}
	
	if (strlen($value) <= 0 || strlen($reason) <= 0 || !is_numeric($length) || intval($length) < 600)
	{
		fMessage('error', 'Please fill in all fields correctly! (Also take note a ban must be at least 10 minutes in length!)');
		header("Location: ?p=bans");
		exit;
	}
	
	// $type, $value, $reason, $expireTime, $addedBy
	uberCore::AddBan($bantype, $value, $reason, time() + $length, HK_USER_NAME, (($noAppeal == "checked") ? true : false));
	$core->Mus('reloadbans');
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Manage bans & appeals</h1>
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
					<p>This tool allows you to place and manage the bans and the prohibition of appeal for review.</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Resources for the ban pending review</b>
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<p>This is an overview of the appeals against the bans you put. Take note that other administrators can also manage the resources of its prohibition.</p>
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
									<th>Details</th>
									<th>IP Address</th>
									<th>Data</th>
									<th>Email</th>
									<th>Plea</th>
									<th>Review</th>
								</tr>
							</thead>
							<tbody>
								<?php
								
								$getMyBans = dbquery("SELECT id,bantype,value,expire,added_date,appeal_state FROM bans WHERE appeal_state = '1'" . (($users->HasFuse(USER_ID, 'fuse_admin')) ? "" : " AND added_by = '" . HK_USER_NAME . "'"));
								
								while ($ban = mysql_fetch_assoc($getMyBans))
								{
									$findAppeal = dbquery("SELECT * FROM bans_appeals WHERE ban_id = '" . $ban['id'] . "' LIMIT 1");
									
									if (mysql_num_rows($findAppeal) == 1)
									{
										$data = mysql_fetch_assoc($findAppeal);
										
										if ($data['plea'] == '')
										{
											continue;
										}
									
										echo '<tr>
										<td>' . strtoupper($ban['bantype']) . ' Ban: <b>' . clean($ban['value']) . '</b><br />
										Placed on <u>' . $ban['added_date'] . '</u>,<br />set to expire on <u>' . date('d F, Y', $ban['expire']) . '</u>.</td>
										<td>' . $data['send_ip'] . '</td>
										<td>' . $data['send_date'] . '</td>
										<td>' . clean($data['email']) . '</td>
										<td style="background-color: #CEE3F6; text-align: center; font-size: 90%;">' . nl2br(clean($data['plea'])) . '</td>
										<td><input type="button" style="color: darkgreen;" onclick="document.location = \'index.php?_cmd=bans&unban=' . $data['ban_id'] . '\';" value="Accept and unban">&nbsp;<input style="color: darkred;" type="button" onclick="document.location = \'index.php?_cmd=bans&doDenyAppeal=' . $ban['id'] . '\';" value="Deny"></td>
										</tr>';
									}
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
			<div class="panel panel-danger">
				<div class="panel-heading">
					<b>Add new ban</b>
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<form method="post">
						<div class="col-lg-7">
							<div class="form-group">
								<label>Bantype</label>
								<select class="form-control" name="bantype" onClick="onchange="if (this.value == 'ip') { document.getElementById('ban-value-heading').innerHTML = 'IP Address'; } else { document.getElementById('ban-value-heading').innerHTML = 'Username'; }" onKeyup="onchange="if (this.value == 'ip') { document.getElementById('ban-value-heading').innerHTML = 'IP Address'; } else { document.getElementById('ban-value-heading').innerHTML = 'Username'; }" onchange="if (this.value == 'ip') { document.getElementById('ban-value-heading').innerHTML = 'IP Address'; } else { document.getElementById('ban-value-heading').innerHTML = 'Username'; }">
									<option value="ip">Ban by IP</option>
									<option value="user">Ban by User</option>
								</select>
							</div>
							<div class="form-group">
								<label id="ban-value-heading">IP Address</label>
								<input class="form-control" type="text" name="value">
							</div>
							<div class="form-group">
								<label>Reason</label>
								<input class="form-control" type="text" name="reason">
							</div>
							<label>Duration (in seconds!)</label>
							<div class="form-group input-group">
								<input class="form-control" type="text" name="length" id="banlength">
								<span class="input-group-addon">secs</span>
							</div>
							<small>(Options:
								<a style="cursor:pointer" onclick="banPreset(3600);">1hr</a>
								<a style="cursor:pointer" onclick="banPreset(10800);">3hr</a>
								<a style="cursor:pointer" onclick="banPreset(43200);">12hr</a>
								<a style="cursor:pointer" onclick="banPreset(86400);">1day</a>
								<a style="cursor:pointer" onclick="banPreset(259200);">3days</a>
								<a style="cursor:pointer" onclick="banPreset(604800);">1week</a>
								<a style="cursor:pointer" onclick="banPreset(1209600);">2weeks</a>
								<a style="cursor:pointer" onclick="banPreset(2592000);">1month</a>
								<a style="cursor:pointer" onclick="banPreset(7776000);">3month</a>
								<a style="cursor:pointer" onclick="banPreset(1314000);">1year</a>
								<a style="cursor:pointer" onclick="banPreset(2628000);">2years</a>
								<a style="cursor:pointer" onclick="banPreset(360000000);">Permanent</a>)
							</small>
							<script type="text/javascript">
								function banPreset(val) {
									document.getElementById('banlength').value = val;
								}
							</script>
							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="no-appeal" value="checked">DO NOT allow the user to appeal ban.
									</label>
								</div>
							</div>
						</div>
						<!-- /.col-lg-7 -->
						<div class="col-lg-12">
							<hr />
							<div class="form-group">
								<button type="submit" class="btn btn-outline btn-danger">Ban</button>
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
					List of Bans
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="dataTables-example">
							<thead>
								<tr>
									<th>Ban ID</th>
									<th>Data</th>
									<th>Reason</th>
									<th>Expiration</th>
									<th>Added</th>
									<th>Status of Appeals</th>
									<th>Options</th>
								</tr>
							</thead>
							<tbody>
								<?php

								$getBans = dbquery("SELECT * FROM bans ORDER BY expire DESC");

								while($ban = mysql_fetch_assoc($getBans))
								{
									echo '<tr>
											<td>'. $ban['id'] .'</td>
											<td>'. strtoupper($ban['bantype']) .' Ban: <b>'. clean($ban['value']) .'</b></td>
											<td>'. clean($ban['reason'], true, true) .'</td>
											'. (($ban['expire'] <= time()) ? '<td>Expired '. date('d F, Y', $ban['expire']) .'</td>' : '<td>Expires '. date('d F, Y', $ban['expire']) .'</td>') .'
											<th>On '. $ban['added_date'] .' by '. clean($ban['added_by']) .'</td>
											<td>';
									
									if($ban['appeal_state'] == "0")
									{
										echo 'Not allowed to appeal!';
									}
									elseif($ban['appeal_state'] == "1")
									{
										if(mysql_num_rows(dbquery("SELECT null FROM bans_appeals WHERE ban_id = '". $ban['id'] ."' AND plea != '' LIMIT 1")) > 0)
										{
											echo '<b style="color: blue;">User has appealed ban, awaiting reviewal by moderator</b>';
										}
										else
										{
											echo 'User has not appealed ban yet';
										}
									}
									else if ($ban['appeal_state'] == "2")
									{
										echo '<b style="color: red;">Appeal reviewed and declined</b>';
									}
									
									echo '</td>
											<td>';
									
									if(strtolower($ban['added_by']) == strtolower(HK_USER_NAME) || $users->HasFuse(USER_ID, 'fuse_admin'))
									{
										echo '<button type="submit" class="btn btn-outline btn-danger" onClick="document.location = \'?p=bans&unban='. $ban['id'] .'\';" value="'. (($ban['expire'] <= time()) ? 'Remove' : 'Unban') .'">Remove</button>';
									}
									
									echo '</td>	
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