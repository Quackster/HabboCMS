<?php

if(!defined('IN_HK') || !IN_HK)
{
	exit;
}

if(!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_admin'))
{
	exit;
}

$data = null;
$u = 0;

if(isset($_GET['u']) && is_numeric($_GET['u']))
{
	$u = intval($_GET['u']);
	$getData = dbquery("SELECT id,username FROM users WHERE id = '". $u ."' LIMIT 1");
	
	if(mysql_num_rows($getData) > 0)
	{
		$data = mysql_fetch_assoc($getData);
	}
}
elseif(isset($_POST['usrsearch']))
{
	$usrSearch = filter($_POST['usrsearch']);
	$getData = dbquery("SELECT id,username FROM users WHERE username = '". $usrSearch ."' LIMIT 1");
	
	if(mysql_num_rows($getData) > 0)
	{
		$data = mysql_fetch_assoc($getData);
		
		header("Location: index.php?p=badges&u=". $data['id']);
		exit;
	}	
	else
	{
		fMessage('error', 'User not found!');
	}
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Manage user badges</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<?php
			
			if($data == null)
			{
			
			?>
			<div class="panel panel-info">
				<div class="panel-heading">
					Info
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<p><i>No user set or invalid user supplied.</i> To edit an user's badges, search for one below.</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Search
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<form method="post">
						<div class="form-group col-lg-4">
							<div class="form-group">
								<label>By UID</label>
								<input class="form-control" id="uidval" type="text" size="5" name="uid" />
								<br />
								<button class="btn btn-outline btn-default" onClick="window.location = '?p=badges&u=' + document.getElementById('uidval').value;">Go</button>
							</div>
							<div class="form-group">
								<label>By username</label>
								<input class="form-control" type="text" name="usrsearch" value="" />
								<br />
								<button type="submit" class="btn btn-outline btn-default">Go</button>
							</div>
						</div>
					</form>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<?php

			}
			else
			{
				if(isset($_GET['take']))
				{
					dbquery("DELETE FROM user_badges WHERE user_id = '". $data['id'] ."' AND badge_id = '". filter($_GET['take']) ."'");
					
					if(mysql_affected_rows() >= 1)
					{
						echo '<b>Took badge '. $_GET['take'] .' from '. $data['username'] .'.</b>';
					}
				}	
				
				if(isset($_POST['newbadge']))
				{
					dbquery("INSERT INTO user_badges (user_id,badge_id,badge_slot) VALUES ('". $data['id'] ."','". filter($_POST['newbadge']) ."','0')");
					echo '<b>Gave badge!</b>';
				}

				?>
			<div class="panel panel-info">
				<div class="panel-heading">
					Info
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<p>Edting badges from: <b><?php echo $data['username']; ?></b> (<a href="?p=badges">Back to user search</a>)</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<?php
			
			$getBadges = dbquery("SELECT badge_id,badge_slot FROM user_badges WHERE user_id = '". $data['id']. "'");
			
			?>
			<div class="panel panel-default">
				<div class="panel-heading">
					Full Table
				</div>
				<br />
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="dataTables-example">
							<thead>
								<tr>
									<td>Image</td>
									<td>Badge code</td>
									<td>Status</td>
									<td>Defenition</td>
									<td>Controls</td>
								</tr>
							</thead>
							<tbody>
							<?php
							
							$tryGet1 = dbquery("SELECT sval FROM external_texts WHERE skey = 'badge_name_". $b['badge_id'] ."'");
							$tryGet2 = dbquery("SELECT sval FROM external_texts WHERE skey = 'badge_desc_". $b['badge_id'] ."'");
									
							while ($b = mysql_fetch_assoc($getBadges))
							{
								echo '<tr>
										<td><center><img src="../swf/c_images/Badges/'. $b['badge_id'] .'.gif"></center></td>
										<td>'. $b['badge_id'] .'</td>
										<td>';
								
								if ($b['badge_slot'] == 0)
								{
									echo 'Not equipped';
								}
								else
								{
									echo 'Equipped in slot '. $b['badge_slot'];
								}
								
								echo '</td>
										<td><a href="?p=badgedefs">';
								
								
								if (mysql_num_rows($tryGet1) > 0)
								{
									echo '<b>'. mysql_result($tryGet1, 0) .'</b><br>';
								}
								else
								{
									echo '<b><i>(No name def)</i></b><br>';
								}
								
								if (mysql_num_rows($tryGet2) > 0)
								{
									echo mysql_result($tryGet2, 0);
								}
								else
								{
									echo '<i>(No descr def)</i><br>';
								}		
								
								echo '</a></td>
										<td>
											<center>
												<button onClick="window.location = \'?p=badges&u='. $u .'&take='. $b['badge_id'] .'\';" class="btn btn-outline btn-danger">Take</button>
											</center>
										</td>
									</tr>';
							}
							
							?>
								<tr>
									<form method="post">
										<td><center>New</center></td>
										<td><input class="form-control" type="text" name="newbadge" value="" /></td>
										<td><center>(New badge)</center></td>
										<td>&nbsp;</td>
										<td><center><button type="submit" class="btn btn-outline btn-success">Give</button></center></td>
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
			<?php
			
			}

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