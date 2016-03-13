<?php

if(!defined('IN_HK') || !IN_HK)
{
	exit;
}

if(!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_sysadmin'))
{
	exit;
}

if(isset($_POST['hatext']))
{
	fMessage('ok', 'Message sent:<br />"' . clean($_POST['hatext']) . '"');
	$core->Mus('ha', clean($_POST['hatext']));
}

require_once "top.php";

?>
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Staffs Application</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Full Table
				</div>
				<br />
				<center><p>Please read the applications below, To see for any new application that had been send by the users.</p></center>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="dataTables-example">
							<thead>
								<tr>
									<th>ID</th>
									<th>User Id</th>
									<th>Usernam</th>
									<th>Status</th>
									<th>Age</th>
									<th>Country</th>
									<th>T-Zone</th>
									<th>R Name</th>
									<th>Mod-N</th>
									<th>Time</th>
									<th>Exp</th>
									<th>Msg 1</th>
									<th>Msg 2</th>
									<th>Msg 3</th>
									<th>Users</th>
									<th>IP</th>
								</tr>
							</thead>
							<tbody>
								<?php
								
								$get = dbquery("SELECT id,userid,username,appstatus,age,country,timezone,realname,modname,time,experience,message1,message2,message3,users,visitoripaddy FROM applications ORDER BY id ASC");
								
								while($user = mysql_fetch_assoc($get))
								{
									echo '<tr>';
									echo '<td>'. $user['id'] .'</td>';
									echo '<td>'. $user['userid'] .'</td>';
									echo '<td>'. $user['username'] .' </td>';
									echo '<td>'. $user['appstatus'] .' </td>';
									echo '<td>'. $user['age'] .' </td>';
									echo '<td>'. $user['country'] .' </td>';
									echo '<td>'. $user['timezone'] .' </td>';
									echo '<td>'. $user['realname'] .' </td>';
									echo '<td>'. $user['modname'] .' </td>';
									echo '<td>'. $user['time'] . ' </td>';
									echo '<td>'. $user['experience'] .' </td>';
									echo '<td>'. $user['message1'] .' </td>';
									echo '<td>'. $user['message2'] .' </td>';
									echo '<td>'. $user['message3'] .' </td>';
									echo '<td>'. $user['users'] .' </td>';
									echo '<td>'. $user['visitoripaddy'] .' </td>';
									echo '</tr>';
								}
								
								?>
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
		<p>
			<?php
			
			if (isset($_POST['hatext']))
			{
			
			?>
			<h1 style="padding: 15px;">
				Message Sent
				<span style="border: 2px dotted gray; padding: 10px; margin: 5px; font-size: 70%; font-weight: normal;"><?php echo clean($_POST['hatext']); ?></span>
				<input type="button" value="Send new message?" onclick="document.location = '?p=ha';">
			</h1>
			<?php
			
			}
			else { }
			
			?>
		</p>
	</div>
	<!-- /.row -->
<?php

require_once "bottom.php";

?>