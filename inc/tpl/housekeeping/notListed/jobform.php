<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_housekeeping_sitemanagement'))
{
	exit;
}
/*
$newOrderNum = intval(mysql_result(dbquery("SELECT MAX(order_num) FROM site_app_form LIMIT 1"), 0)) + 1;
*/
if (isset($_GET['doDel']))
{
	dbquery("DELETE FROM site_app_form WHERE id = '" . filter($_GET['doDel']) . "' LIMIT 1");
	
	if (mysql_affected_rows() >= 1)
	{
		fMessage('ok', 'Deleted form element.');
	}
	
	header("Location: ?p=jobform");
	exit;
}

if (isset($_GET['doUp']))
{
	dbquery("UPDATE site_app_form SET order_num = order_num + 1 WHERE id = '" . filter($_GET['doUp']) . "' LIMIT 1");
	
	if (mysql_affected_rows() >= 1)
	{
		fMessage('ok', 'Moved element up.');
	}
	
	header("Location: ?p=jobform");
	exit;
}

if (isset($_GET['doDown']))
{
	dbquery("UPDATE site_app_form SET order_num = order_num - 1 WHERE id = '" . filter($_GET['doDown']) . "' LIMIT 1");
	
	if (mysql_affected_rows() >= 1)
	{
		fMessage('ok', 'Moved element down.');
	}
	
	header("Location: ?p=jobform");
	exit;
}

if (isset($_POST['new-element-id']))
{
	$id = filter(strtolower($_POST['new-element-id']));
	$type = filter(strtolower($_POST['new-element-type']));
	$name = filter($_POST['new-element-name']);
	$descr = filter($_POST['new-element-descr']);
	$required = "no";
	
	if (isset($_POST['new-element-required']))
	{
		$required = filter(strtolower($_POST['new-element-required']));
	}
	
	$errors = Array();
	
	if (strlen($id) == 0 || strlen($id) > 24)
	{
		$errors[] = "Invalid ID supplied. Must be 0 - 24 chars long.";
	}
	
	if ($type != "textbox" && $type != "textarea"
	&& $type != "checkbox")
	{
		$type = "textbox";
	}
	
	if (count($errors) == 0)
	{
		fMessage('ok', 'Element added to application form!');
		
		$req = "0";
		
		if ($required == "yes")
		{
			$req = "1";
		}
		
		dbquery("INSERT INTO site_app_form (id,caption,descr,field_type,required,order_num) VALUES ('" . $id . "','" . $name . "','" . $descr . "','" . $type . "','" . $req . "','" . $newOrderNum . "')");
	}
	else
	{
		fMessage('error', 'Could not add element, please verify input.');
	}
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Job Application Form</h1>
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
					<p>Whenever a user applies for a job, they will need to fill in a predefined application form, which you can manage here.</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Add new form element</b> (<a style="cursor:pointer" onclick="t('elform');">Hide/show</a>)
				</div>
				<div class="panel-body">
					<div id="elform">
						<form method="post">
							<div class="col-lg-6">
								<div class="form-group">
									<label>Field type</label>
									<select class="form-control" name="new-element-type">
										<option value="textbox">Regular Text box</option>
										<option value="textarea">Text area (for large text/multipile lines)</option>
										<option value="checkbox">Checkbox (Description will be used as text)</option>
									</select>
								</div>
								<div class="form-group">
									<label>Element ID (short, <u>unique</u>, and internal name to identify this field - no special chars please)</label>
									<input class="form-control" type="text" value="" maxlength="24" name="new-element-id">
								</div>
								<div class="form-group">
									<label>Name on form</label>
									<input class="form-control" type="text" value="" maxlength="120" name="new-element-name">
								</div>
								<div class="form-group">
									<label>Description on form</label>
									<textarea class="form-control" name="new-element-descr" cols="50" rows="4"></textarea>
								</div>
							</div>
							<!-- /.col-lg-6 -->
							<div class="col-lg-12">
								<div class="form-group">
									<hr />
									<div class="checkbox">
										<label>
											<input type="checkbox" value="yes" name="new-element-required" /> This is a required field
										</label>
									</div>
									<button type="submit" class="btn btn-outline btn-success">Add new element to form</button>
								</div>
							</div>
							<!-- /.col-lg-12 -->
						</form>
					</div>
					
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>Manage/Preview Application Form</b>
				</div>
				<div class="panel-body">
					<form method="post">
						<?php
						
						$getElements = dbquery("SELECT * FROM site_app_form ORDER BY order_num ASC");
						
						echo '<ol style="margin-left: 20px;">';
						
						while ($el = mysql_fetch_assoc($getElements))
						{
							echo '<li>';
							
							echo $el['id'] . '&nbsp;';
							
							if ($el['required'] == "1")
							{
								echo '<b style="color: darkred;"><small>(This is a required field)</small></b><br />';
							}
							
							echo '<div style="width: 75%; border: 1px dotted; background-color: #F2F2F2; margin-top: 5px; padding: 10px;">';
							
							switch ($el['field_type'])
							{
								case "checkbox":
								
									echo '<input type="checkbox" value="checked" name="' . $el['id'] . '"> ' . clean($el['descr']);
									break;
							
								case "textarea":
								
									echo clean($el['caption']) . '
											<br />
											<textarea class="form-control" name="' . $el['id'] . '"></textarea>
											<br />
											<small>' . $el['descr'] . '</small>';			
									break;
									
								case "textbox":
								default:
								
									echo clean($el['caption']) . '
											<br />
											<input class="form-control" type="text" name="' . $el['id'] . '" value="">
											<br />
											<small>' . $el['descr'] . '</small>';			
									break;
							}
							
							echo '</div>
									<br />
									Order num: ' . $el['order_num'] . ' |
									<a href="?p=jobform&doUp=' . $el['id'] . '">Move up</a> |
									<a href="?p=jobform&doDown=' . $el['id'] . '">Move down</a> |
									<a href="?p=jobform&doDel=' . $el['id'] . '">Delete this element</a>
									<br />
									<br />
									<br />
								</li>';
						}
						
						echo '<li>
								<i>Submit button</i>
								<br />
								<div style="border: 1px dotted; width: 50px; padding: 10px;">
									<input type="submit" value="Submit">
								</div>
							</li>
						</ol>
						<br />';
						
						?>
					</form>
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