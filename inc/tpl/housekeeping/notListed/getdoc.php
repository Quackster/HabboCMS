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
			<h1 class="page-header">Get docs (.pdf)</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<?php
			
			if (!isset($_GET['doc']))
			{
				die("No doc!");
			}
			
			$file = 'docs/' . $_GET['doc'];
			
			if (!file_exists($file))
			{
				die("Could not find file");
			}
			
			header("Content-type: application/force-download");
			header("Content-Transfer-Encoding: Binary");
			header("Content-length: ". filesize($file));
			header("Content-disposition: attachment; filename = ". basename($file));
			readfile($file);
			
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