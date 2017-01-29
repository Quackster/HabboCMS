<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_sysadmin'))
{
	exit;
}

if (isset($_GET['new']))
{
	dbquery("INSERT INTO moderation_presets (type,enabled,message) VALUES ('message','0','Newly generated preset - please update')");
	
	fMessage('ok', 'New preset generated.');
	
	header("Location: ?p=presets");
	exit;
}

if (isset($_GET['delete']) && is_numeric($_GET['delete']))
{
	dbquery("DELETE FROM moderation_presets WHERE id = '" . intval($_GET['delete']) . "' LIMIT 1");
	
	if (mysql_affected_rows() >= 1)
	{
		fMessage('ok', 'Deleted preset successfully.');
	}
	
	header("Location: ?p=presets");
	exit;	
}

if (isset($_POST['preset-save']) && is_numeric($_POST['preset-save']))
{
	$id = intval($_POST['preset-save']);
	$type = filter($_POST['type']);
	$enabled = filter($_POST['enabled']);
	$message = filter($_POST['message']);
	
	dbquery("UPDATE moderation_presets SET type = '" . $type . "', enabled = '" . $enabled . "', message = '" . $message . "' WHERE id = '" . $id . "' LIMIT 1");
	
	if (mysql_affected_rows() >= 1)
	{
		fMessage('ok', 'Updated preset.');
	}
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Mod tool presets</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Room tool presets
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="dataTables-example">
							<thead>
								<tr>
									<td>ID</td>
									<td>Type</td>
									<td>Enabled</td>
									<td>Message</td>
									<td>Options</td>
								</tr>
							</thead>
							<tbody>
								<?php
								
								$get = dbquery("SELECT * FROM moderation_presets ORDER BY id DESC");
								
								while ($p = mysql_fetch_assoc($get))
								{
									echo '<tr>
											<form method="post">
												<input type="hidden" name="preset-save" value="'. $p['id'] .'">
												<td>#'. $p['id'] .'</td>
												<td>
													<select class="form-control" name="type">
														<option value="message">User message (friendly)</option>
														<option value="roommessage"';
									
									if ($p['type'] == "roommessage")
									{
										echo ' selected';
									}
									
									echo '>Room message</option></select></td>';
									echo '<td>
											<select class="form-control" name="enabled">
												<option value="1">Enabled</option>
												<option value="0"';
									
									if ($p['enabled'] == "0")
									{
										echo ' selected';
									}
									
									echo '>Disabled</option></select></td>
											<td>
												<textarea class="form-control" name="message" cols="40">'. clean($p['message']) .'</textarea>
											</td>
											<td>
												<center>
													<button type="submit" class="btn btn-outline btn-success">Save</button>&nbsp;
													<button type="button" onClick="document.location = \'?p=presets&delete='. $p['id'] .'\';" class="btn btn-outline btn-danger">Delete</button>
												</center>
											</td>
										</form>
									</tr>';
								}
									
								?>
							</tbody>
						</table>
						<hr />
						<center>
							<a href="?p=presets&new"><b>Add new preset</b></a>
							<br />
							<br />
							<i style="color: darkred;">NOTE: Changes will not be seen in the hotel until the server restarts.</i>
						</center>
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