<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN)
{
	exit;
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><img src="images/uberdown.png" /> Uberdown Emergency reporting</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-danger">
				<div class="panel-heading">
					WARNING
				</div>
				<div class="panel-body">
					<p class="text-danger"><b>WARNING:</b> This is only for CRITICAL issues such as reporting downtime. Abuse of this system <u>WILL</u> lead to <u>REMOVAL</u>.</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-info">
				<div class="panel-heading">
					Info
				</div>
				<div class="panel-body">
					<p>
						In the event of a serious technical problem with the hotel, a system called uberdown comes into play. Selected individuals (including the
						Moderators and staff at Uber) have access to a web-based form, where they can report critical disruptions to the service. While you are
						working a shift as Moderator it is your responsibility to report major problems promptly.
					</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-warning">
				<div class="panel-heading">
					<b>Report form</b>
				</div>
				<div class="panel-body">
					<p>
						<ol style="margin-left: 40px;">
							<li>First check the STATUS TOOL in Housekeeping - <?php echo WWW; ?>/manage/.</li>
							<li>If you see any RED ERROR, there is most likely a problem. This may not always be accurate.</li>
							<li>If you are certain there is a problem, use the form below to report it.</li>
							<li><i style="color: red;">Allow up to 15 minutes for us to resolve the problem before reporting it. If Roy is aware of the problem, then no need to report it.<br /><b>Always be short and descriptive, and never spam this system. One report will suffice.</b></i></li>
						</ol>
					</p>
					<hr />
					<form method="post">
						<?php
						
						if (isset($_POST['UBERDOWN']))
						{
							dbquery("INSERT INTO uberdown (username,shit) VALUES ('" . USER_NAME . "','" . filter($_POST['UBERDOWN']) . "')");
						
						?>
						<h1 style="text-align: center; color: blue;">UBERDOWN REPORT WAS SUBMITTED! Thank you.</h1>
						<?php
						
						}
						else
						{
						
						?>
						<textarea class="form-control" rows="5" name="UBERDOWN" onclick="if(this.value=='Describe the problem here...'){this.value='';}">Describe the problem here...</textarea>
						<input type="submit" value="Submit Uberdown report">
						<input type="button" onclick="window.location = '?p=main';" value="Cancel report">
						<div style="clear: both;"></div>
						<?php
						
						}
						
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
<?

require_once "bottom.php";

?>