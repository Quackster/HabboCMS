<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->GetuserVar(USER_ID, 'rank') > 5)
{
	exit;
}

if (isset($_GET['doDel']) && is_numeric($_GET['doDel']))
{
	dbquery("DELETE FROM site_news WHERE id = '" . intval($_GET['doDel']) . "' LIMIT 1"); 
	
	if (mysql_affected_rows() >= 1)
	{
		fMessage('ok', 'Article deleted.');
	}
	
	header("Location: ?p=news&deleteOK");
	exit;
}

if (isset($_GET['doBump']) && is_numeric($_GET['doBump']))
{
	dbquery("UPDATE site_news SET datestr = '". date('d-M-Y') ."', timestamp = '". time() ."' WHERE id = '". intval($_GET['doBump']) ."' LIMIT 1"); 
	
	if (mysql_affected_rows() >= 1)
	{
		fMessage('ok', 'Article date bumped.');
	}
	
	header("Location: ?p=news&bumpOK");
	exit;
}

require_once "top.php";

?>			
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Manage News</h1>
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
					<p>You can use this overview to manage news articles. Topstories will be <span style="background-color: #CEE3F6; padding: 2px;">highlighted</span>.</p>
					<p><a href="index.php?p=newspublish"><b>Write new article</b></a></p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Table
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="dataTables-example">
							<thead>
								<tr>
									<td>ID</td>
									<td>Title</td>
									<td>Topstory snippet</td>
									<td>Category</td>
									<td>Date</td>
									<td>Controls</td>
								</tr>
							</thead>
							<tbody>
								<?php

								$getNews = dbquery("SELECT * FROM site_news ORDER BY timestamp DESC");

								while($n = mysql_fetch_assoc($getNews))
								{
									$highlight = '#fff';
									
									if ($i <= 3)
									{
										$highlight = '#CEE3F6';
									}
									else if ($i <= 5)
									{
										$highlight = '#EFFBFB';
									}
									echo '<tr style="background-color: '. $highlight .';">
											<td>'. $n['id'] .'</td>
											<td>'. clean($n['title']) .'</td>
											<td>'. clean($n['snippet']) .'</td>
											<td>'. clean(mysql_result(dbquery("SELECT caption FROM site_news_categories WHERE id = '". $n['category_id'] ."' LIMIT 1"), 0)) . '</td>
											<td>'. $n['datestr'] .'</td>
											<td>
												<button class="btn btn-outline btn-default" onClick="document.location = \''. WWW .'/articles/'. $n['id'] .'-'. $n['seo_link'] .'\';">View</button>&nbsp;&nbsp;
												<button class="btn btn-outline btn-danger" onClick="document.location = \'index.php?p=news&doDel='. $n['id'] .'\';">Delete</button>&nbsp;
												<button class="btn btn-outline btn-default" onClick="document.location = \'index.php?p=newsedit&u='. $n['id'] .'\';">Edit</button>&nbsp;
												<button class="btn btn-outline btn-default" onClick="document.location = \'index.php?p=news&doBump='. $n['id'] .'\';">Update/bump date</button>&nbsp;
											</td>
										</tr>';
									
									$i++;
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
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
<?php

require_once "bottom.php";

?>