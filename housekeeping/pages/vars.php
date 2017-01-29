<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_sysadmin'))
{
	exit;
}

if (isset($_POST['edit-no']))
{
	dbquery("UPDATE external_variables SET skey = '" . filter($_POST['key']) . "', sval = '" . filter($_POST['value'])	. "' WHERE skey = '" . filter($_POST['edit-no']) . "' LIMIT 1");
}

if (isset($_POST['newkey']))
{
	dbquery("INSERT INTO external_variables (skey,sval) VALUES ('" . filter($_POST['newkey']) . "','" . filter($_POST['newval']) . "')");
}

if (isset($_GET['doDel']))
{
	dbquery("DELETE FROM external_variables WHERE skey = '" . filter($_GET['doDel']) . "' LIMIT 1");
	fMessage('ok', 'Key removed.');
	header("Location: ?p=vars");
	exit;
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">External variables (overrides)</h1>
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
					<p>This tool can be used to override external variables keys.</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Other
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<a href="http://hotel-uk.habbo.com/gamedata/external?id=external_variables" id="">Show Habbo\'s external variables</a>
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
									<td>Key</td>
									<td>Value</td>
									<td>Controls</td>
								</tr>
							</thead>
							<tbody>
								<?php
								
								$get = dbquery("SELECT * FROM external_variables");
								
								while ($text = mysql_fetch_assoc($get))
								{
									echo '<tr>
											<form method="post">
												<input type="hidden" name="edit-no" value="'. clean($text['skey']) .'">
												<td><input class="form-control" type="text" name="key" value="'. clean($text['skey']) .'"></td>
												<td><textarea class="form-control" name="value">'. clean($text['sval']) .'</textarea></td>
												<td><center><button type="submit" class="btn btn-outline btn-success">Update</button>&nbsp;<input type="button" class="btn btn-outline btn-danger" value="Delete" onClick="window.location = \'?p=vars&doDel='. $text['skey'] .'\';"></center></td>
											</form>
										</tr>';
								}
								
								?>
								<tr>
									<form method="post">
										<td><input class="form-control" type="text" name="newkey" /></td>
										<td><textarea class="form-control" name="newval"></textarea>
										<td><center><button type="submit" class="btn btn-outline btn-success">Add</button></center></td>
									</form>
								</tr>
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