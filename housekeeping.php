<?php

define('IN_MAINTENANCE', true);
require_once 'inc/global.php';
require_once ROOT . '/inc/admincore.php';

$p = null;

if(isset($_POST['p']))
	$p = strtolower(clean($_POST['p']));

if($p == null)
{
	$initial = 'main';
	
	if(!HK_LOGGED_IN)
	{
		$initial = 'login';
		$_SESSION['HK_LOGIN_ERROR'] = '<div class="alert alert-info"><p style="text-align:center"><br>You are not logged in</p></div>';
	}

	header('Location: /housekeeping.php?p=' . $initial);
	exit;
}

$tpl = new Tpl();

if(!HK_LOGGED_IN && $p != 'login')
{
	$tpl->AddTemplate('housekeeping/login');
	echo $tpl;
	exit;
}

?>
<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Housekeeping for Retro Hotels</title>
		<link href="cdn/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="cdn/bootstrap/css/sb-admin.css" rel="stylesheet">
		<script type="text/javascript" src="cdn/bootstrap/js/ajax.js"></script>
		<!--<script type="text/javascript" src="cdn/bootstrap/js/iframe.js"></script>-->
		<script type="text/javascript">
		function popClient() {
			window.open('/client.php', 'Retro Hotel BETA', 'width=980, height=600, location=no, status=no, menubar=no, directories=no, toolbar=no, resizable=no, scrollbars=no');
			return false;
		}
		function popSsoClient(sso) {
			window.open('/client.php?forceTicket=' + sso, 'Retro Hotel BETA', 'width=980, height=600, location=no, status=no, menubar=no, directories=no, toolbar=no, resizable=no, scrollbars=no');
			return false;
		}
		//<![CDATA[
		addLoadEvent = function(func){
			if(typeof jQuery != 'undefined')
				jQuery(document).ready(func);
			else if(typeof wpOnload != 'function')
				wpOnload=func;
			else
			{
				var oldonload = wpOnload;
				wpOnload = function() {
					oldonload();
					func();
				}
			}
		};
		var userSettings = {
			'url': '/',
			'uid': '1',
			'time':'1315158571'
		},
		ajaxurl = 'http://www.lukadora.de/home/wp-admin/admin-ajax.php',
		pagenow = 'dashboard',
		typenow = '',
		adminpage = 'index-php',
		thousandsSeparator = '.',
		decimalPoint = ',',
		isRtl = 0;
		//]]>
		</script>
		<!--<script type="text/javascript">
		//<![CDATA[
		(function(){
			var c = document.body.className;
			c = c.replace('/no-js/', 'js');
			document.body.className = c;
		})();
		//]]>
		</script>-->
	</head>
	<body>
		<div id="wrapper">
			<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="./">Housekeeping for Retro Hotels</a> <a class="navbar-brand" href="https://www.orbitrondev.org"><small>(by OrbitronDev)</small></a>
				</div>
				<!-- /.navbar-header -->
				<p class="navbar-text navbar-right">Hello, <a href="<?php echo WWW; ?>/home/<?php echo USER_NAME; ?>"><?php echo USER_NAME; ?></a> | <a href="?p=logout" title="Sign out">Sign out</a></p>
				<!-- /.navbar-link -->
				<div class="navbar-default navbar-static-side" role="navigation">
					<div class="sidebar-collapse">
						<ul class="nav" id="side-menu">
							<li><a href="./"><i class="fa fa-home fa-fw"></i> Home</a></li>
							<li>
								<a href="#"><i class="fa fa-wrench fa-fw"></i> Staffs Panel<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li><a href="../client" target="_new" onClick="popClient()">Game Client</a></li>
									<li><a href="?p=getstaff">Staff List</a></li>
									<li><a href="?p=forum">Staff Forum</a></li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
							<li>
								<a href="#"><i class="fa fa-edit fa-fw"></i> News / Camp<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li><a href="?p=newspublish">Add new News</a></li>
									<li><a href="?p=news">Managing News</a></li>
									<li><a href="?p=campaigns">Hot Campaigns</a></li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
							<li>
								<a href="#"><i class="fa fa-files-o fa-fw"></i> Logs + Others<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li><a href="?p=bans">Bans + appeals</a></li>
									<li><a href="?p=chatlogs">Chatlogs</a></li>
									<li><a href="?p=cfhs">Called for help</a></li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
							<li>
								<a href="#"><i class="fa fa-cog fa-fw"></i> Moderator<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li><a href="?p=roomads">Advertisement</a></li>
									<li><a href="?p=badgedefs">Edit badges <b class="text-danger">(ERROR)</b></a></li>
									<li><a href="?p=presets">Mod Tools</a></li>
									<li><a href="?p=ha">Alert Hotel</a></li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
							<li>
								<a href="#"><i class="fa fa-table fa-fw"></i> Catalog<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li><a href="?p=ot-def">Item definition <b class="text-danger">(LAGGY)</b></a></li>
									<li><a href="?p=ot-pages">Catalog Pages</a></li>
									<li><a href="?p=ot-cata-items">Catalog Items <b class="text-danger">(LAGGY)</b></a></li>
									<li><a href="?p=furnifinder">Furni datas</a></li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
							<li>
								<a href="#"><i class="fa fa-users fa-fw"></i> Users<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li><a href="?p=getuser">Users list</a></li>
									<li><a href="?p=extsignon">Find users</a></li>
									<li><a href="?p=givecredits">Give credits</a></li>
									<li><a href="?p=givepixels">Give pixels</a></li>
									<li><a href="?p=badges">User badges <b class="text-danger">(ERROR)</b></a></li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
							<li>
								<a href="#"><i class="fa fa-flask fa-fw"></i> Site tools<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li><a href="?p=texts">External texts <b class="text-danger">(ERROR)</b></a></li>
									<li><a href="?p=vars">External variables</a></li>
									<li><a href="?p=iptool">IP tool</a></li>
									<li><a href="?p=vouchers">Vouchers</a></li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
							<li>
								<a href="#"><i class="fa fa-wrench fa-fw"></i> Administrator<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li><a href="?p=maint">Maintenance</a></li>
									<li><a href="?p=app">Application <b class="text-danger">(ERROR)</b></a></li>
									<li><a href="?p=rango">Give rank</a></li>
									<li><a href="?p=confsitio">Site settings</a></li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
							<li>
								<a href="#"><i class="fa fa-exclamation-triangle fa-fw"></i> Testing<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li><a href="?p=notListed/getdoc">Get Docs</a></li>
									<li><a href="?p=notListed/content">Administrate content panel</a></li>
									<li><a href="?p=notListed/jobapps">Jobapps</a></li>
								</ul>
								<!-- /.nav-second-level -->
							</li>
						</ul>
						<!-- /#side-menu -->
					</div>
					<!-- /.sidebar-collapse -->
				</div>
				<!-- /.navbar-static-side -->
			</nav>
			<div id="page-wrapper">
<?php

switch($p)
{
	case 'logout':
	
		@session_destroy();
		session_start();
		
		$_SESSION['HK_LOGIN_ERROR'] = '<div class="alert alert-success"><p style="text-align: center"><br>You have successfully signed out!</p></div>';
		
		header('Location: /housekeeping.php?p=login');
		exit;
		
	default:
	
		if(file_exists('pages/'. $p .'.php'))
			$tpl->AddTemplate('housekeeping/' . $p);
		else
			$tpl->AddTemplate('housekeeping/404');
		break;
}

echo $tpl;

?>
			</div>
			<!-- /#page-wrapper -->
		</div>
		<!-- /#wrapper -->
		<script>
		function t(id) {
			var el = document.getElementById(id);
			
			if(el.style.display == 'block' || el.style.display == '') {
				el.style.display = 'none';
			}
			else {
				el.style.display = 'block';
			}
		}
		</script>
		<script src="cdn/bootstrap/js/jquery-1.10.2.js"></script>
		<script src="cdn/bootstrap/js/bootstrap.min.js"></script>
		<script src="cdn/bootstrap/js/jquery.metisMenu.js"></script>
		<script src="cdn/bootstrap/js/sb-admin.js"></script>
	</body>
</html>