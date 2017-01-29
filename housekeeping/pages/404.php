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
			<h1 class="page-header"><center>Page not found</center></h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<center>
			<p>This page does not exist or was deleted</p>
			<p>Report this problem <a href="?p=forum">Housekeeping Forum</a>.</p>
		</center>
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
<?php

require_once "bottom.php";

?>