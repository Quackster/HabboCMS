<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_housekeeping_moderation'))
{
	exit;
}

if (isset($_POST['v-code']))
{
	$vCode = filter($_POST['v-code']);
	$vValue = filter($_POST['v-value']);
	
	if (strlen($vCode) <= 0)
	{
		fMessage('error', 'Please enter a voucher code.');
	}
	else if (!is_numeric($vValue) || intval($vValue) <= 0 || intval($vValue) > 5000)
	{
		fMessage('error', 'Invalid credit value. Must be numeric and a value between 1 - 5000.');
	}
	else
	{
		dbquery("INSERT INTO credit_vouchers (code,value) VALUES ('" . $vCode . "','" . intval($vValue) . "')");
		fMessage('ok', 'Voucher is now live and redeemable.');
	}
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Credit vouchers</h1>
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
					<p>Credit vouchers can be exchanged for credits on the website and in the ingame catalogue.</p>
					<hr />
					<p style="font-size: 125%; color: darkred;">
						<b>NOTE:</b> Staff are *NOT* to abuse this system. Vouchers may be used as a method of refunds,
						rewards, or prizes, but not to be handed out without VALID reason. Amounts must be kept reasonable.
						<u>Abuse of this system WILL be punished.</u>
					</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Redeemable vouchers
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="dataTables-example">
							<thead>
								<tr>
									<td>Voucher code</td>
									<td>Value</td>
								</tr>
							</thead>
							<tbody>
								<?php
								
								$get = dbquery("SELECT code,value FROM credit_vouchers ORDER BY code ASC");
								
								while ($user = mysql_fetch_assoc($get))
								{
									echo '<tr>';
									echo '<td>'. $user['code'] .'</td>';
									echo '<td>'. $user['value'] .' Credits</td>';
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
			<div class="panel panel-default">
				<div class="panel-heading">
					Add new voucher
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<form method="post">
						<div class="col-lg-4">
							<div class="form-group">
								<label>Code</label>
								<input class="form-control" type="text" name="v-code">
							</div>
							<div class="form-group">
								<label>Credit value</label>
								<input class="form-control" type="text" name="v-value">
							</div>
						</div>
						<!-- /.col-lg-4 -->
						<div class="col-lg-12">
							<div class="form-group">
								<hr/>
								<button type="submit" class="btn btn-outline btn-success">Add</button>
							</div>
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

?>