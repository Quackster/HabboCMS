<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->hasFuse(HK_USER_ID, 'fuse_sysadmin'))
{
	exit;
}

if (!isset($_GET['sq']))
{
	$_GET['sq'] = "";
}

if (isset($_GET['new']))
{
	dbquery("INSERT INTO catalog_items (page_id,item_ids,catalog_name,cost_credits,cost_pixels,amount) VALUES (137,1,'',10,0,1)");
	fMessage('ok', 'New catalog item stub added');
	
	$newId = mysql_result(dbquery("SELECT id FROM catalog_items ORDER BY id DESC LIMIT 1"), 0);
	
	header("Location: ?p=ot-cata-items&edit=" . $newId);
	exit;
}

if (isset($_GET['del']))
{
	fMessage('error', 'Are you <b>SURE</b> you want to delete that catalog item? This CAN NOT be reversed.<br /><a href="?p=ot-cata-items&realdel=' . $_GET['del'] . '&sq=' . $_GET['sq'] . '">YES, DELETE IT</a> - or - <a href="?p=ot-cata-items">CANCEL</a>');
}

if (isset($_GET['realdel']))
{
	fMessage('ok', 'Item def deleted!');
	dbquery("DELETE FROM catalog_items WHERE id = '" . intval($_GET['realdel']) . "' LIMIT 1");
	header("Location: ?p=ot-cata-items&sq=" . $_GET['sq']);
	exit;	
}

$data = null;
$lockedVars = array('id','gonew');

if (isset($_GET['edit']))
{
	$i = intval($_GET['edit']);	
	$get = dbquery("SELECT * FROM catalog_items WHERE id = '" . $i . "' LIMIT 1");
	
	if (mysql_num_rows($get) == 0)
	{
		fMessage('error', 'Oops! Invalid item.');
	}
	else
	{
		$data = mysql_fetch_assoc($get);
		
		if (isset($_POST['page_id']))
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
			
			dbquery("UPDATE catalog_items SET " . $qB . " WHERE id = '" . intval($_GET['edit']) . "' LIMIT 1");
			fMessage('ok', 'Updated cata item successfully');
			
			if (isset($_POST['gonew']) && $_POST['gonew'] == "y")
			{
				header("Location: ?p=ot-cata-items&new");
			}
			else
			{
				header("Location: ?p=ot-cata-items&edit=" . $data['id']);
			}
			
			exit;
		}
	}
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Manage catalogue items</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<?php
			
			$checkBlankItems = dbquery("SELECT id,page_id FROM catalog_items WHERE item_ids = '1' AND catalog_name = ''");
			
			if (mysql_num_rows($checkBlankItems) > 0)
			{
				echo '<div style="margin: 5px; padding: 10px; border: 2px solid #000; color: darkred;">';
				echo '<p>';
				echo '<b>Warning!</b> There are blank items in the database:<br />';
				echo '<ul class="styled">';
				
				while ($item = mysql_fetch_assoc($checkBlankItems))
				{
					if (isset($_GET['edit']) && $item['id'] == $_GET['edit'])
					{
						echo '<li><i>You are currently editing this item (ID #' . $item['id'] . ').</i></li>';
					}
					else
					{
						echo '<li><a href="?p=ot-cata-items&edit=' . $item['id'] . '" target="_self">Item (ID #' . $item['id'] . ') on page ' . $item['page_id'] . '</a> (or <a href="?p=ot-cata-items&del=' . $item['id'] . '">Delete</a>)</li>';
					}
				}
				
				echo '</ul>';
				echo '</p>';
				echo '</div>';
			}
			
			if ($data != null)
			{
				echo '<div class="panel panel-default">
						<div class="panel-heading">
							Editing item - "'. $data['catalog_name'] .'"
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<form method="post" action="?p=ot-cata-items&edit='. $data['id'] .'">
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
							<button type="button" onClick="window.location=\'?p=ot-cata-items&sq='. $_GET['sq'] .'\';" class="btn btn-outline btn-danger">Cancel</button>
						</div>
					</form>
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
						Search
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<form method="post">
							<label>Search <small>(by ID)</small></label>
							<input class="form-control" type="text" value="Search.." style="font-size: 150%;" size="70" onClick="if(this.value == 'Search..') { this.value=''; }" name="search-query">
						</form>
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->
				<?php
				
				if (!isset($_POST['search-query']) && isset($_GET['sq']))
				{
					$_POST['search-query'] = $_GET['sq'];
				}
				
				if (isset($_POST['search-query']))
				{
					$_POST['search-query'] = filter($_POST['search-query']);
					
					$getPages = dbquery("SELECT * FROM catalog_items WHERE catalog_name LIKE '%" . $_POST['search-query'] . "%' OR id = '" . $_POST['search-query'] . "' OR item_ids = '" . $_POST['search-query'] . "' OR page_id = '" . $_POST['search-query'] . "' ORDER BY item_ids ASC");					
					
				?>
				<div class="panel panel-default">
					<div class="panel-heading">
						Table
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<p style="text-align: center;"><a href="?p=ot-cata-items&new"><b>Generate new cata item stub</b></a></p>
						<hr />
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id="dataTables-example">
								<thead>
									<tr>
										<td>ID</td>
										<td>Page ID</td>
										<td>Defenition Ref</td>
										<td>Name</td>
										<td>Cost</td>
										<td>Amount</td>
										<td>Options</td>
									</tr>
								</thead>
								<tbody>
					<?php
					
					while ($page = mysql_fetch_assoc($getPages))
					{
						echo '<tr>
								<td>'. $page['id'] .'</td>
								<td>'. $page['page_id'] .'</td>
								<td><a href="ot-def.php?edit='. $page['item_ids'] .'">'. $page['item_ids'] .'</a></td>
								<td>'. $page['catalog_name'] .'</td>
								<td>'. $page['cost_credits'] .' CR, '. $page['cost_pixels'] .' PX</td>
								<td>'. $page['amount'] .'</td>
								<td>
									<button onClick="document.location = \'?p=ot-cata-items&edit='. $page['id'] .'&sq='. $_POST['search-query'] .'\';" class="btn btn-outline btn-default">View detail/edit</button>
									&nbsp;
									<button onClick="document.location = \'?p=ot-cata-items&del='. $page['id'] .'&sq='. $_POST['search-query'] .'\';" class="btn btn-outline btn-danger">Remove</button>
								</td>
							</tr>';
					}
					
					echo '</tbody>
						</table>
					</div>
					<!-- /.table-responsive -->';
				}
				else
				{
					echo '<p><b>Please search for something first.</b></p>';
				}
			}
			
			echo '<hr />
					<p style="text-align: center;"><a href="?p=ot-cata-items&new"><b>Generate new cata item stub</b></a></p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->';
			
			?>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
<?php

require_once "bottom.php";

?>								