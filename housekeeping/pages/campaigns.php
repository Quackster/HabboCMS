<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_housekeeping_sitemanagement'))
{
	exit;
}

if (isset($_POST['edit']))
{
	$id = intval($_POST['edit']);
	$image = filter($_POST['image']);
	$title = filter($_POST['title']);
	$descr = filter($_POST['descr']);
	$enabled = filter($_POST['enabled']);
	$order = filter($_POST['order']);
	$url = filter($_POST['url']);
	
	if (!is_numeric($order) || intval($order) <= 0)
	{
		$order = 0;
	}
	
	dbquery("UPDATE site_hotcampaigns SET image_url = '" . $image . "', caption = '" . $title . "', descr = '" . $descr . "', enabled = '" . $enabled . "', order_id = '" . $order . "', url = '" . $url . "' WHERE id = '" . $id . "' LIMIT 1");
	fMessage('ok', 'Updated campaign.');
}

if (isset($_POST['add-new']))
{
	$image = filter($_POST['image']);
	$title = filter($_POST['title']);
	$descr = filter($_POST['descr']);
	$enabled = filter($_POST['enabled']);
	$order = filter($_POST['order']);
	$url = filter($_POST['url']);
	
	if (!is_numeric($order) || intval($order) <= 0)
	{
		$order = 0;
	}
	
	dbquery("INSERT INTO site_hotcampaigns (id,order_id,enabled,image_url,caption,descr,url) VALUES (NULL,'" . $order . "','" . $enabled . "','" . $image . "','" . $title . "','" . $descr . "','" . $url . "')");
	fMessage('ok', 'Added campaign.');
}

if (isset($_GET['doDel']) && is_numeric($_GET['doDel']))
{
	dbquery("DELETE FROM site_hotcampaigns WHERE id = '" . $_GET['doDel'] . "' LIMIT 1");
	fMessage('ok', 'Deleted.');
	header("Location: ?p=campaigns");
	exit;
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Hot Campaigns (Homepage)</h1>
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
					<p>Mit diesem Tool kannst du die Hot Campaigns auf der Seite bearbeiten. Du kannst sie auf bestimmte Seiten verlinken und deinene Usern das aktuellste mitteilen selbst wenn sie eine weile lang offline war. Dann sehen sie was zurzeit ansteht.</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Verwalte Kampagnen
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="dataTables-example">
							<thead>
								<tr>
									<th>ID</th>
									<th>Bild</th>
									<th>Titel</th>
									<th>Ziel URL</th>
									<th width="20%">Beschreibung</th>
									<th width="120px">Status</th>
									<th>Sortierung</th>
									<th width="140px">Optionen</th>
								</tr>
							</thead>
							<tbody>
								<?php
								
								$getItems = dbquery("SELECT * FROM site_hotcampaigns ORDER BY order_id ASC");
								
								while ($item = mysql_fetch_assoc($getItems))
								{
									echo '<tr>
											<form method="post">
												<input type="hidden" name="edit" value="'. $item['id'] .'" />
												<td>'. $item['id'] .'</td>
												<td><input class="form-control" type="text" name="image" value="'. clean($item['image_url']) .'" /></td>
												<td><input class="form-control" type="text" name="title" value="'. clean($item['caption']) .'" /></td>
												<td><input class="form-control" type="text" name="url" value="'. clean($item['url']) .'" /></td>
												<td><textarea class="form-control" name="descr">'. clean($item['descr']) .'</textarea></td>
												<td><select class="form-control" name="enabled"><option value="1">Enabled</option><option value="0" '. (($item['enabled'] == "0") ? 'selected' : '') .'>Disabled</option></select></td>
												<td><input class="form-control" type="text" name="order" size="3" value="'. $item['order_id'] .'" /></td>
												<td><center><input class="btn btn-outline btn-success" type="submit" value="Save" />&nbsp;<input class="btn btn-outline btn-danger" type="submit" onClick="document.location = \'index.php?p=campaigns&doDel='. $item['id'] .'\';" value="Delete" /></center></td>
											</form>
										</tr>';
								}
								
								?>
								<tr>
									<form method="post">
										<input type="hidden" name="add-new" value="1" />
										<td>New</td>
										<td><input class="form-control" type="text" name="image" /></td>
										<td><input class="form-control" type="text" name="title" /></td>
										<td><input class="form-control" type="text" name="url" /></td>
										<td><textarea class="form-control" name="descr"></textarea></td>
										<td><select class="form-control" name="enabled"><option value="1">Enabled</option><option value="0">Disabled</option></select></td>
										<td><input class="form-control" type="text" name="order" size="3" value="1" /></td>
										<td><center><input class="btn btn-outline btn-success" type="submit" value="Add" /></center></td>
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