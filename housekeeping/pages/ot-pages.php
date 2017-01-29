<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->hasFuse(HK_USER_ID, 'fuse_sysadmin'))
{
	exit;
}

if (isset($_GET['unsetCat']))
{
	unset($_SESSION['OT_PAGE_CAT']);
}

if (!isset($_SESSION['OT_PAGE_CAT']))
{
	if (isset($_POST['OT_PAGE_CAT']))
	{
		$_SESSION['OT_PAGE_CAT'] = $_POST['OT_PAGE_CAT'];
	}
	else
	{
		require_once "top.php";
		
		?>
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Manage catalogue pages</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							Select a category to edit
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<form method="post">
								<div class="col-lg-4">
									<select class="form-control" name="OT_PAGE_CAT">
										<option value="3">Furni Shop</option>
										<option value="91">Staff Pages</option>
										<option value="4">Pixel shop</option>
										<option value="6">Trax Shop</option>
										<option value="5">Habbo Club</option>
										<option value="1000">Game Shop</option>
										<option value="-1">Root pages</option>
									</select>
								</div>
								<!-- /.col-lg-4 -->
								<div class="col-lg-12">
									<hr />
									<button type="submit" class="btn btn-outline btn-default">Go</button>
								</div>
								<!-- /.col-lg-12 -->
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
		
		exit;
	}
}

$newOrderId = mysql_result(dbquery("SELECT order_num FROM catalog_pages WHERE parent_id = '" . $_SESSION['OT_PAGE_CAT'] . "' ORDER BY order_num DESC"), 0) + 1;

if (!isset($_GET['sq']))
{
	$_GET['sq'] = "";
}

if (isset($_GET['new']))
{
	dbquery("INSERT INTO catalog_pages (parent_id,caption,icon_color,icon_image,visible,enabled,coming_soon,order_num,page_layout,page_text_details,page_headline) VALUES ('" . $_SESSION['OT_PAGE_CAT'] . "','', '0', '1', '1', '1', '0', '" . $newOrderId . "', 'default_3x3','Click on an item for more information.','catalog_frontpage_headline2_en')");
	fMessage('ok', 'New page stub added');
	
	$newId = mysql_result(dbquery("SELECT id FROM catalog_pages ORDER BY id DESC LIMIT 1"), 0);
	
	header("Location: ?p=ot-pages&edit=". $newId);
	exit;
}

if (isset($_GET['del']))
{
	fMessage('error', 'Are you <b>SURE</b> you want to delete that page? This CAN NOT be reversed.<br /><a href="?p=ot-pages&realdel=' . $_GET['del'] . '">YES, DELETE IT</a> - or - <a href="?_p=ot-pages">CANCEL</a>');
}

if (isset($_GET['realdel']))
{
	fMessage('ok', 'Page deleted!');
	dbquery("DELETE FROM catalog_pages WHERE id = '" . intval($_GET['realdel']) . "' AND parent_id = '" . $_SESSION['OT_PAGE_CAT'] . "' LIMIT 1");
	header("Location: ?p=ot-pages&");
	exit;	
}

$data = null;
$lockedVars = array('id','parent_id','type','gonew');

if (isset($_GET['edit']))
{
	$i = intval($_GET['edit']);	
	$get = dbquery("SELECT * FROM catalog_pages WHERE id = '" . $i . "' AND parent_id = '" . $_SESSION['OT_PAGE_CAT'] . "' LIMIT 1");
	
	if (mysql_num_rows($get) == 0)
	{
		fMessage('error', 'Oops! Invalid item.');
	}
	else
	{
		$data = mysql_fetch_assoc($get);
		
		if (isset($_POST['caption']))
		{
			$i = 0;
			
			$qB = '';

			foreach ($_POST as $key => $value)
			{
				$i++;
				
				if (in_array($key, $lockedVars))
				{
					continue;
				}
				
				if ($i > 1)
				{
					$qB .= ',';
				}
				
				$qB .= $key . " = '" . filter($value) . "'";
			}
			
			dbquery("UPDATE catalog_pages SET " . $qB . " WHERE id = '" . intval($_GET['edit']) . "' LIMIT 1");
			fMessage('ok', 'Updated item successfully');
			
			if (isset($_POST['gonew']) && $_POST['gonew'] == "y")
			{
				header("Location: ?p=ot-pages&new");
			}
			else
			{
				header("Location: ?p=ot-pages&edit=" . $data['id']);
			}
			
			exit;
		}
	}
}

if (isset($_POST['update-order']))
{
	foreach ($_POST as $key => $value)
	{
		if ($key == 'update-order')
		{
			continue;
		}
	
		if (substr($key, 0, 4) != 'ord-')
		{
			die("Invalid: " . $key);
			continue;
		}
		
		$id = intval(substr($key, 4));

		dbquery("UPDATE catalog_pages SET order_num = '" . intval($value) . "' WHERE id = '" . $id .  "' AND parent_id = '" . $_SESSION['OT_PAGE_CAT'] . "' LIMIT 1");
	}
	
	fMessage('ok', 'Updated page order.');
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Manage catalogue pages</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Change
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<p>Editing category: <b><?php mysql_result(dbquery("SELECT caption FROM catalog_pages WHERE id = '". $_SESSION['OT_PAGE_CAT'] ."' LIMIT 1"), 0); ?></b> (<a href="?p=ot-pages&unsetCat">Change</a>)
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<?php
			
			if ($data != null)
			{
				echo '<div class="panel panel-default">
						<div class="panel-heading">
							Editing item - "'. $data['caption'] .'"
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<form method="post">
								<div class="col-lg-6">';
				
				foreach ($data as $key => $value)
				{
					$lck = false;
					
					if (in_array($key, $lockedVars))
					{
						$lck = true;
					}
					
					echo '<br /><br /><b>'. $key .'</b>
							<br />
							<textarea ';
					
					if ($lck)
					{
						echo 'readonly="readonly" disabled="disabled" ';
					}
					
					echo 'class="form-control" name="'. $key .'" rows="4">'. $value .'</textarea>';
				}
				
				echo '</div>
						<div class="col-lg-12">
							<hr />
							<div class="checkbox">
								<label>
									<input type="checkbox" name="gonew" value="y" checked> Create & go to new stub after saving
								</label>
							</div>
							<button type="submit" class="btn btn-outline btn-success">Save</button>
							<button type="button" onClick="window.location=\'?p=ot-pages&sq='. $_GET['sq'] .'\';" class="btn btn-outline btn-danger">Cancel</button>
						</div>
					</form>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->';
			
			}
			
			$getPages = dbquery("SELECT * FROM catalog_pages WHERE parent_id = '" . $_SESSION['OT_PAGE_CAT'] . "' ORDER BY order_num ASC");					
			
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
					Table
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<p style="text-align: center;"><a href="?p=ot-pages&new"><b>Generate new page stub</b></a></p>
					<hr />
					<form method="post">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id="dataTables-example">
								<thead>
									<tr>
										<td>ID</td>
										<td>Caption</td>
										<td>Visible</td>
										<td>Enabled</td>
										<td>Layout</td>
										<td>Order</td>
										<td>Options</td>
									</tr>
								</thead>
								<tbody>
									<?php
								
									echo '';
									
									while ($page = mysql_fetch_assoc($getPages))
									{
										echo '<tr>
												<td>'. $page['id'] .'</td>
												<td>'. $page['caption'] .'</td>
												<td>'. $page['visible'] .'</td>
												<td>'. $page['enabled'] .'</td>
												<td>'. $page['page_layout'] .'</td>
												<td><input class="form-control" type="text" size="3" value="'. $page['order_num'] .'" name="ord-'. $page['id'] .'"></td>
												<td>
													<button type="submit" onClick="document.location = \'?p=ot-pages&edit='. $page['id'] .'\';" class="btn btn-outline btn-default">View detail/edit</button>
													<button type="submit" onClick="document.location = \'?p=ot-pages&del='. $page['id'] .'\';" class="btn btn-outline btn-danger">Remove</button>
												</td>
											</tr>';
									}
			
									?>
								</tbody>
							</table>
							<hr />
							<button type="submit" name="update-order" class="btn btn-outline btn-success">Save page order</button> or <button type="button" name="update-order" class="btn btn-outline btn-danger" onClick="location.reload(true);">Cancel/Reset</button>
						</div>
					</form>
					<!-- /.table-responsive -->
					<hr />
					<p style="text-align: center;"><a href="?p=ot-pages&new"><b>Generate new page stub</b></a></p>
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