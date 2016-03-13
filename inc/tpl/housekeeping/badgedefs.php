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
	dbquery("UPDATE external_texts SET sval = '". filter($_POST['nm']) ."' WHERE skey = 'badge_name_". filter($_POST['edit-no']) ."' LIMIT 1");
	dbquery("UPDATE external_texts SET sval = '" . filter($_POST['dc']) . "' WHERE skey = 'badge_desc_". filter($_POST['edit-no']) ."' LIMIT 1");
	
	fMessage('ok', 'Updated badge def.');
}

if (isset($_POST['newbadge']))
{
	dbquery("INSERT INTO external_texts (skey,sval) VALUES ('badge_name_". filter($_POST['newbadge']) ."','". filter($_POST['newname']) ."')");
	dbquery("INSERT INTO external_texts (skey,sval) VALUES ('badge_desc_". filter($_POST['newbadge']) . "','". filter($_POST['newdescr']) ."')");
}

if (isset($_GET['doDel']))
{
	dbquery("DELETE FROM external_texts WHERE skey = 'badge_name_". filter($_GET['doDel']) ."' LIMIT 1");
	dbquery("DELETE FROM external_texts WHERE skey = 'badge_desc_". filter($_GET['doDel']) ."' LIMIT 1");
	
	fMessage('ok', 'Badge defenition removed.');
	
	header("Location: ?p=badgedefs");
	exit;
}

require_once "top.php";
?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Badge defenitions</h1>
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
					<p>This tool can be used to manage badge defenitions (the text that appears).</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Full Table
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="dataTables-example">
							<thead>
								<tr>
									<th>Badge</th>
									<th>Name</th>
									<th>Description</th>
									<th>Option</th>
								</tr>
							</thead>
							<tbody>
								<?php
								
								$get = dbquery("SELECT * FROM external_texts WHERE skey LIKE '%badge_name_%'");

								while ($text = mysql_fetch_assoc($get))
								{
									$badgeName = substr($text['skey'], 11);
									$badgeTName = $text['sval'];
									$badgeTDescr = mysql_result(dbquery("SELECT sval FROM external_texts WHERE skey = 'badge_desc_". $badgeName ."' LIMIT 1"), 0);
								
								
									echo '<tr>
											<form method="post">
												<td style="width: 100px;">
													<div class="form-group">
														<img src="../swf/c_images/Badges/'. $badgeName .'.gif">&nbsp;&nbsp;'. $badgeName .'
													</div>
												</td>
												<input type="hidden" name="edit-no" value="'. clean($badgeName) .'" />
												<td>
													<div class="form-group">
														<input class="form-control" type="text" name="nm" value="'. clean($badgeTName) .'">
													</div>
												</td>
												<td>
													<div class="form-group">
														<textarea class="form-control" name="dc">'. clean($badgeTDescr) .'</textarea>
													</div>
												</td>
												<td>
													<div class="form-group">
														<center><button type="submit" class="btn btn-outline btn-success">Update</button>&nbsp;<button class="btn btn-outline btn-danger" onClick="window.location = \'?p=badgedefs&doDel='. $badgeName .'\';">Delete</button></center>
													</div>
												</td>
											</form>
										</tr>'; 
								}
								
								?>
								<tr>
									<form method="post">
										<td>
											<div class="form-group">
												<input class="form-control" type="text" name="newbadge">
											</div>
										</td>
										<td>
											<div class="form-group">
												<input class="form-control" type="text" name="newname">
											</div>
										</td>
										<td>
											<div class="form-group">
												<textarea class="form-control" name="newdescr"></textarea>
											</div>
										</td>
										<td>
											<div class="form-group">
												<center><button type="submit" class="btn btn-outline btn-success">Add</button></center>
											</div>
										</td>
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