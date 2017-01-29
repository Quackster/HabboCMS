<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_admin'))
{
	exit;
}

if (isset($_POST['hatext']))
{
	fMessage('ok', 'Message sent:<br />"' . clean($_POST['hatext']) . '"');
	$core->Mus('ha', clean($_POST['hatext']));
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Hotel Alert</h1>
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
					<p>Notify the entire hotel with an alert. Use with care. <i>Always double-check to avoid typos or errors.</i></p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Send message
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<?php if (isset($_POST['hatext'])) { ?>
					<div class="col-lg-4">
						<label>Message Sent</label>
						<div style="border: 1px solid grey; border-radius: 5px; padding: 5px">
							<?php echo clean($_POST['hatext']); ?>
						</div>
					</div>
					<!-- /.col-lg-4 -->
					<br />
					<div class="col-lg-12">
						<hr />
						<button class="btn btn-outline btn-default" onClick="document.location = '?p=ha';">Send new message?</button>
					</div>
					<!-- /.col-lg-4 -->
					<?php } else { ?>
					<form method="post">
						<div class="col-lg-7">
							<div class="form-group">
								<textarea class="form-control" name="hatext" rows="5"></textarea>
							</div>
						</div>
						<!-- /.col-lg-7 -->
						<div class="col-lg-12">
							<div class="form-group">
								<hr />
								<button type="submit" class="btn btn-outline btn-success">Send</button>
							</div>
						</div>
						<!-- /.col-lg-7 -->
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
}

require_once "bottom.php";

?>