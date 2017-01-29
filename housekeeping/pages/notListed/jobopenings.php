<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_housekeeping_sitemanagement'))
{
	exit;
}

if (isset($_GET['doDel']))
{
	dbquery("DELETE FROM site_app_openings WHERE id = '" . intval(filter($_GET['doDel'])) . "' LIMIT 1");
	
	if (mysql_affected_rows() >= 1)
	{
		fMessage('ok', 'Deleted listed opening.');
	}
	
	header("Location: ?p=jobopenings");
	exit;
}

if (isset($_POST['n-name']) && strlen($_POST['n-name']) >= 1)
{
	dbquery("INSERT INTO site_app_openings (id,name,text_descr,text_reqs,text_duties) VALUES (NULL,'" . filter($_POST['n-name']) . "','" . filter($_POST['n-descr']) . "','" . filter($_POST['n-reqs']) . "','" . filter($_POST['n-duties']) . "')");
	fMessage('ok', 'Job opening listed!');
	header("Location: ?p=jobopenings");
	exit;
}

if (isset($_POST['edit']) && is_numeric($_POST['edit']))
{
	dbquery("UPDATE site_app_openings SET name = '" . filter($_POST['e-name']) . "', text_descr = '" . filter($_POST['e-descr']) . "', text_reqs = '" . filter($_POST['e-reqs']) . "', text_duties = '" . filter($_POST['e-duties']) . "' WHERE id = '" . intval($_POST['edit']) . "' LIMIT 1");
	fMessage('ok', 'Job opening updated!');
	header("Location: ?p=jobopenings");
	exit;	
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Job openings</h1>
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
					<p>
						Job openings will be shown in the Support Center on the website. Users will be given a chance to apply for them.
						They may also submit an open application if their desired position is not listed. Submitted applications can be
						moderated via <a href="?p=jobapps">Moderate Job Applications</a>.
					</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Add new opening
				</div>
				<div class="panel-body">
					<form method="post">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Name</label>
								<input class="form-control" type="text" maxlength="100" name="n-name">
							</div>
							<div class="form-group">
								<label>Description</label>
								<input class="form-control" type="text" maxlength="100" name="n-name">
							</div>
							<div class="form-group">
								<label>Requirements</label>
								<textarea class="form-control" name="n-reqs" cols="50" rows="4"></textarea>
							</div>
							<div class="form-group">
								<label>Responsibilites & Duties</label>
								<textarea class="form-control" name="n-duties" cols="50" rows="4"></textarea>
							</div>
						</div>
						<!-- /.col-lg-6 -->
						<div class="col-lg-12">
							<div class="form-group">
								<hr />
								<button type="submit" class="btn btn-outline btn-success">Add</button>
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
					Current openings listed
				</div>
				<div class="panel-body">
					<?php
					/*
					$get = dbquery("SELECT * FROM site_app_openings");
					
					while ($opening = mysql_fetch_assoc($get))
					{*/
					
					?>
					<a><b style="font-size: 135%;"><?php echo clean($opening['name']); ?></b></a>
					<br />
					<?php echo clean($opening['text_descr']); ?>
					&nbsp;
					(<a style="cursor: pointer;" onclick="t('edit-<?php echo $opening['id']; ?>');">Edit</a>)
					&nbsp;
					(<a href="?p=jobopenings&doDel=<?php echo $opening['id']; ?>">Remove</a>)
					<div id="edit-<?php echo $opening['id']; ?>" style="display: none;">
						<br />
						<h3>Edit job openings</h3>
						<br />
						<form method="post">
							<input type="hidden" name="edit" value="<?php echo $opening['id']; ?>">
							Name:<br />
							<input type="text" maxlength="100" name="e-name" value="<?php echo clean($opening['name']); ?>">
							<br />
							<br />
							Description:<br />
							<textarea name="e-descr" cols="50" rows="4"><?php echo clean($opening['text_descr']); ?></textarea>
							<br />
							<br />
							Requirements:<br />
							<textarea name="e-reqs" cols="50" rows="4"><?php echo clean($opening['text_reqs']); ?></textarea>
							<br />
							<br />
							Responsibilites & Duties:<br />
							<textarea name="e-duties" cols="50" rows="4"><?php echo clean($opening['text_duties']); ?></textarea>
							<br />
							<br />
							<input type="submit" value="Save changes"><br />
						</form>
					</div>
					<?php
					
					/*}*/
					
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