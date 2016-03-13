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

	if (!isset($_POST['search-query']) && isset($_GET['sq']))
	{
		$_POST['search-query'] = $_GET['sq'];
	}
	
	if ($_GET['sq'] == '' || !isset($_GET['sq']) && isset($_POST['search-query']) && strlen($_POST['search-query']) > 0)
	{
		$_GET['sq'] = $_POST['search-query'];
	}

if (isset($_GET['new']))
{
	dbquery("INSERT INTO furniture (public_name,item_name,type,length,width,stack_height,can_stack,sprite_id,interaction_type) VALUES ('','newitem', 's', '1', '1', '1', '1', '1', 'switch')");
	fMessage('ok', 'New item def stub added');
	
	$newId = mysql_result(dbquery("SELECT id FROM furniture ORDER BY id DESC LIMIT 1"), 0);
		
	header("Location: ?p=ot-def&edit=". $newId);
	exit;
}

if (isset($_GET['del']))
{
	fMessage('error', 'Are you <b>SURE</b> you want to delete that item def? This CAN NOT be reversed.<br /><a href="?p=ot-def&realdel=' . $_GET['del'] . '&sq=' . $_GET['sq'] . '">YES, DELETE IT</a> - or - <a href="?p=ot-def">CANCEL</a>');
}

if (isset($_GET['realdel']))
{
	fMessage('ok', 'Item def deleted!');
	dbquery("DELETE FROM furniture WHERE id = '" . intval($_GET['realdel']) . "' LIMIT 1");
	header("Location: ?p=ot-def&sq=". $_GET['sq']);
	exit;	
}

$data = null;
$lockedVars = array('id','gonew','search-query');

if (isset($_GET['edit']))
{
	$i = intval($_GET['edit']);	
	$get = dbquery("SELECT * FROM furniture WHERE id = '" . $i . "' LIMIT 1");
	
	if (mysql_num_rows($get) == 0)
	{
		fMessage('error', 'Oops! Invalid item.');
	}
	else
	{
		$data = mysql_fetch_assoc($get);
		
		if (isset($_POST['public_name']))
		{
			$i = 0;
			
			$qB = '';

			foreach ($_POST as $key => $value)
			{
				if (in_array($key, $lockedVars))
				{
					continue;
				}
				
				$i++;
				
				if ($i > 1)
				{
					$qB .= ',';
				}
				
				$qB .= $key . " = '" . filter($value) . "'";
			}
			
			dbquery("UPDATE furniture SET " . $qB . " WHERE id = '" . intval($_GET['edit']) . "' LIMIT 1");
			fMessage('ok', 'Updated item successfully');
			
			if (isset($_POST['gonew']) && $_POST['gonew'] == "y")
			{
				header("Location: ?p=ot-def&new");
			}
			else
			{
				header("Location: ?p=ot-def&edit=" . $data['id'] . "&sq=" . $_GET['sq']);
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
			<h1 class="page-header">Manage item defenitions</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<?php
			
			$checkBlankItems = dbquery("SELECT id FROM furniture WHERE item_name = 'newitem'");
			
			if (mysql_num_rows($checkBlankItems) > 0)
			{
				echo '<div class="alert alert-danger">
						<p>Warning!</b> There are blank items in the database:</p>
						<ul>';
				
				while ($item = mysql_fetch_assoc($checkBlankItems))
				{
					if (isset($_GET['edit']) && $item['id'] == $_GET['edit'])
					{
						echo '<li><i>You are currently editing this item (ID #'. $item['id'] .').</i></li>';
					}
					else
					{
						echo '<li><a href="?p=ot-def&edit='. $item['id'] .'" target="_self">Item (ID #'. $item['id'] .')</a> (or <a href="?p=ot-def&del='. $item['id'] .'">Delete</a>)</li>';
					}
				}
				
				echo '</ul>
					</div>';
			}
			
			if ($data != null)
			{
				echo '<div class="panel panel-default">
						<div class="panel-heading">
							Editing item - "'. $data['public_name'] .'"
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<form method="post" action="?p=ot-def&edit='. $data['id'] .'&sq='. $_GET['sq'] .'">
								<input type="hidden" name="search-query" value="'. $_GET['sq'] .'">
								<div style="margin: 10px; padding: 10px; border: 1px dotted #000;">
									<label>Insert magic block here</label>
									<textarea class="form-control" id="magicinput"></textarea>
									<hr />
									<button type="button" onClick="MagicBlockProcessor(document.getElementById(\'magicinput\').value);" class="btn btn-outline btn-success">Fill in form for me</button>
								</div>
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
					
					echo 'class="form-control" cname="'. $key .'" id="'. $key .'" rows="4">'. $value .'</textarea>';
				}
				
				echo '</div>
						<div class="col-lg-12">
							<hr />
							<div class="checkbox">
								<label>
									<input type="checkbox" name="gonew" value="y"> Create & go to new stub after saving
								</label>
							</div>
							<button type="submit" class="btn btn-outline btn-success">Save</button>
							<button type="button" onClick="window.location=\'?p=ot-def&sq='. $_GET['sq'] .'\';" class="btn btn-outline btn-danger">Cancel / Back</button>
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
				
				if (isset($_POST['search-query']))
				{
					$_POST['search-query'] = filter($_POST['search-query']);
					
					$getPages = dbquery("SELECT * FROM furniture WHERE item_name LIKE '%" . $_POST['search-query'] . "%' OR public_name LIKE '%" . $_POST['search-query'] . "%' OR id = '" . $_POST['search-query'] . "'");					
					
				?>
				<div class="panel panel-default">
					<div class="panel-heading">
						Table
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<p style="text-align: center;"><a href="?p=ot-def&new"><b>Generate new item defenition stub</b></a></p>
						<hr />
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id="dataTables-example">
								<thead>
									<tr>
										<td>ID</td>
										<td>Public Name</td>
										<td>Internal Name</td>
										<td>Type</td>
										<td>Interaction Type</td>
										<td>Options</td>
										<td>Has cata entry</td>
									</tr>
								</thead>
								<tbody>
					<?php
					
					while ($page = mysql_fetch_assoc($getPages))
					{
						$res = '<b style="color: darkred;">NO</b>';
					
						if (mysql_num_rows(dbquery("SELECT null FROM catalog_items WHERE item_ids = '" . $page['id'] . "' LIMIT 1")) >= 1)
						{
							$res = '<b style="color: darkgreen;">YES</b>';
						}
						
						if ($res == '<b style="color: darkgreen;">YES</b>' && strlen($_POST['search-query']) < 1 && strlen($_GET['sq']) < 1)
						{
							continue;
						}
						
						echo '<tr>
								<td>'. $page['id'] .'</td>
								<td>'. $page['public_name'] .'</td>
								<td>'. $page['item_name'] .'</td>
								<td>'. $page['type'] .'</td>
								<td>'. $page['interaction_type'] .'</td>
								<td>
									<button onClick="document.location = \'?p=ot-def&edit='. $page['id'] .'&sq='. $_POST['search-query'] .'\';" class="btn btn-outline btn-default">View detail/edit</button>
									&nbsp;
									<button onClick="document.location = \'?p=ot-def&del='. $page['id'] .'&sq='. $_POST['search-query'] .'\';" class="btn btn-outline btn-danger">Remove</button>
								</td>
								<td>'. $res .'</td>
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
			
			echo '<hr />
					<p style="text-align: center;"><a href="?p=ot-def&new"><b>Generate new item defenition stub</b></a></p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->';
			}
			
			?>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
<script type="text/javascript">
	function MagicBlockProcessor(Input) {
		Input = Input.substring(1, (Input.length - 1));
		Bits = Input.split('","');
		
		i = 0;
		
		while (i < Bits.length) {
			Bit = Bits[i];
			
			if (i == 0) {
				Bit = Bit.substring(1);
			}
			
			if (i == (Bits.Length - 1)) {
				Bit = Bit.substring(0, (Bit.Length - 1));
			}
		
			switch (i) {
				// ["i","4326","xm09_frplc","23454","","","","","Festive Fireplace","Watch the yule log glow",""]
			
				case 0:
				
					FillIn('type', Bit);
					break;
					
				case 1: 
				
					FillIn('sprite_id', Bit);
					break;
					
				case 2:
				
					FillIn('item_name', Bit);
					break;
					
				case 5:
				
					if (Bit.Length == 0) {
						Bit = "0";
					}
				
					FillIn('width', Bit);
					break;
					
				case 6:
				
					if (Bit.Length == 0) {
						Bit = "0";
					}
				
					FillIn('length', Bit);
					break;
					
				case 8:
				
					FillIn('public_name', Bit);
					break;
			
				case 3:
				case 4:
				case 7:
				case 9:
				case 10:
				
					break;
			
				default:
				
					alert('Unrecognized bit: ' + i + ': ' + Bit);
					break;
			}
		
			i++;
		}
		
		alert('Filled in data successfully.');
	}

	function FillIn(Id, Value) {
		document.getElementById(Id).value = Value;
	}
</script>
<?php

require_once "bottom.php";

?>								