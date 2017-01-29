<?php
$pagename= "Seitenkonfiguration";

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_sysadmin'))
{
	exit;
}

if (isset($_POST['sitio']))
{
	$sitename = filter($_POST['sitename']);
	$sitelogo = filter($_POST['sitelogo']);
	$ip = filter($_POST['ip']);
	$port = filter($_POST['port']);
	$swfbase = filter($_POST['swfbase']);
	$swfflash = filter($_POST['clienturl']);
	$variables = filter($_POST['variables']);
	$text = filter($_POST['text']);
	$productdata = filter($_POST['productdata']);
	$furnidata = filter($_POST['furnidata']);
	$msg = "Einstellungen gespeichert";
	$core->SetVar($ip, 'gameip');
	$core->SetVar($port, 'gameport');
	$core->SetVar($sitelogo, 'logoname');
	$core->SetVar($swfbase, 'swf_path');
	$core->SetVar($swfflash, 'habbo.swf');
	$core->SetVar($variables, 'variables');
	$core->SetVar($text, 'texts');
	$core->SetVar($productdata, 'productdata');
	$core->SetVar($furnidata, 'furnidata');
	$core->SetVar($sitename, 'sitename');
}

function get_client_ip()
{
	$ipaddress = '';

	if($_SERVER['HTTP_CLIENT_IP'])
	{
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif($_SERVER['HTTP_X_FORWARDED_FOR'])
	{
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	elseif($_SERVER['HTTP_X_FORWARDED'])
	{
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	}
	elseif($_SERVER['HTTP_FORWARDED_FOR'])
	{
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	}
	elseif($_SERVER['HTTP_FORWARDED'])
	{
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	}
	elseif($_SERVER['REMOTE_ADDR'])
	{
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	}
	else
	{
		$ipaddress = 'UNKNOWN';
	}
	
	return $ipaddress;
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Hotel</h1>
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
					<p>Mit diesem Tool kannst du den Clienten bearbeiten und deine Seite umbennen sowie das Logo austauschen.</p>
					<hr />
					<p>Deine &Ouml;ffentliche IP-Adresse</p>
					<p><?php echo get_client_ip(); ?></p>
					<img src="http://www.tracemyip.com/tracker/1108/4684NR-IPIB/324444479/25/njsUrl/" alt="Your IP adress" border="0">
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Settings
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<?php
					
					if(isset($msg))
					{
						echo '<div class="alert alert-success alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								'. $msg .'
							</div>';
					}
					
					?>
					<form method="post" action="?p=confsitio&do=save">
						<div class="panel panel-default">
							<div class="panel-heading">
								<b>Seiten Einstellungen</b>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="dataTables-example">
										<thead>
										</thead>
										<tbody>
											<tr>
												<td>Hotel Name</td>
												<td><input class="form-control" type="text" name="sitename" value="<?php echo $core->GetVar('sitename'); ?>" /></td>
											</tr>
											<tr>
												<td>Logo Name</td>
												<td><input class="form-control" type="text" name="sitelogo" value="<?php echo $core->GetVar('logoname'); ?>" /></td>
											</tr>
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
								<b>Client Einstellungen</b>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="dataTables-example">
										<thead>
										</thead>
										<tbody>
											<tr>
												<td>Client Ip</td>
												<td><input class="form-control" type="text" size='20' name="ip" value="<?php echo $core->GetVar('gameip'); ?>"></td> 
											</tr>
											<tr>
												<td>Client Port</td>
												<td><input class="form-control" type="text" size='7' name="port" value="<?php echo $core->GetVar('gameport'); ?>"></td>
											</tr>
											<tr>
												<td>SWF Base</td>
												<td><input class="form-control" type="text" size='60' name="swfbase" value="<?php echo $core->GetVar('swf_path'); ?>"></td>
											</tr>
											<tr>
												<td>SWF clienturl &nbsp;&nbsp; (habbo.swf)</td>
												<td><input class="form-control" type="text" size='60' name="clienturl" value="<?php echo $core->GetVar('habbo.swf'); ?>"></td>
											</tr>
											<tr>
												<td>External Variables</td>
												<td><input class="form-control" type="text" size='60' name="variables" value="<?php echo $core->GetVar('variables'); ?>"></td>
											</tr>
											<tr>
												<td>External Text</td>
												<td><input class="form-control" type="text" name="text" size='60' value="<?php echo $core->GetVar('texts'); ?>"></td>
											</tr>
											<tr>
												<td>Productdata</td>
												<td><input class="form-control" type="text" size='60' name="productdata" value="<?php echo $core->GetVar('productdata'); ?>"></td>
											</tr>
											<tr>
												<td>Furnidata</td>
												<td><input class="form-control" type="text" size='60' name="furnidata" value="<?php echo $core->GetVar('furnidata'); ?>"></td>
											</tr>
										</tbody>
									</table>
								</div>
								<!-- /.table-responsive -->
							</div>
							<!-- /.panel-body -->
						</div>
						<!-- /.panel -->
						<div class="form-group">
							<hr />
							<button type="submit" name="sitio" class="btn btn-outline btn-success">Speichern</button>
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