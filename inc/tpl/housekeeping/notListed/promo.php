<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_housekeeping_sitemanagement'))
{
	exit;
}

if (isset($_GET['doDel']) && is_numeric($_GET['doDel']))
{
	dbquery("DELETE FROM site_promo WHERE id = '" . intval($_GET['doDel']) . "' LIMIT 1"); 
	
	if (mysql_affected_rows() >= 1)
	{
		fMessage('ok', 'Article deleted.');
	}
	
	header("Location: ?p=promo&deleteOK");
	exit;
}

if (isset($_GET['doBump']) && is_numeric($_GET['doBump']))
{
	dbquery("UPDATE site_promo SET datestr = '" . date('d-M-Y') . "', timestamp = '" . time() . "' WHERE id = '" . intval($_GET['doBump']) . "' LIMIT 1"); 
	
	if (mysql_affected_rows() >= 1)
	{
		fMessage('ok', 'Article date bumped.');
	}
	
	header("Location: ?p=promo&bumpOK");
	exit;
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Administrate promotions</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
	
<p>
<img src="images/promo.gif" style="float: right;">
	Here you can manage promotions of your hotel


	</p>

<br />
<br><br><br><br>
<p>
	<a href="?p=promopublish" class="button-primary">
		<b>
			Write promotion
		</b>
	</a>
</p>

<br />

<table class="widefat post fixed" >
<thead>
<tr>
	<th scope="col" id="title" class="manage-column column-title" style="width:25px;">ID</th>
	<th scope="col" id="title" class="manage-column column-title" style="">Title</th>
	<th scope="col" id="title" class="manage-column column-title" style="">Topstory snippet</th>
	<th scope="col" id="title" class="manage-column column-title" style="">Category</th>
	<th scope="col" id="title" class="manage-column column-title" style="">Date</th>
	<th scope="col" id="title" class="manage-column column-title" style="">Controls</th>
</tr>
</thead>
<tbody>
<?php

$getNews = dbquery("SELECT * FROM site_promo ORDER BY timestamp DESC");
$i = 1;

while ($n = mysql_fetch_assoc($getNews))
{
	$highlight = '#fff';

$oddeven++;
		if(IsEven($oddeven)){ $even = "author-self status-publish iedit"; } else { $even = "alternate author-self status-draft iedit"; }
			echo "<tr class=\"".$even."\">";
			
	
	echo '
	<td>' . $n['id'] . '</td>
	<td>' . clean($n['title']) . '</td>
	<td>' . clean($n['snippet']) . '</td>
	<td>' . clean(mysql_result(dbquery("SELECT caption FROM site_promo_categories WHERE id = '" . $n['category_id'] . "' LIMIT 1"), 0)) . '</td>
	<td>' . $n['datestr'] . '</td>
	<td>
		<input class="button-secondary" type="button" value="Ver" onclick="document.location = \'' . WWW . '/promo/' . $n['id'] . '-' . $n['seo_link'] . '\';">&nbsp;
		<input class="button-secondary" type="button" value="Deletar" onclick="document.location = \'index.php?_cmd=promo&doDel=' . $n['id'] . '\';">&nbsp;
		<input class="button-secondary" type="button" value="Editar" onclick="document.location = \'index.php?_cmd=promoedit&u=' . $n['id'] . '\';">
		<input class="button-secondary" type="button" value="Mudar a data" onclick="document.location = \'index.php?_cmd=promo&doBump=' . $n['id'] . '\';">&nbsp;
	</td>
	</tr>';
	
	$i++;
}

?>
</tbody>
</table>


		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
<?php

require_once "bottom.php";

?>