<?php

if(!defined('IN_HK') || !IN_HK)
{
	exit;
}

if(!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_housekeeping_sitemanagement'))
{
	exit;
}

$data = null;

if(isset($_GET['u']) && is_numeric($_GET['u']))
{
	$u = intval($_GET['u']);
	$getData = dbquery("SELECT * FROM site_news WHERE id = '" . $u . "' LIMIT 1");
	
	if(mysql_num_rows($getData) > 0)
	{
		$data = mysql_fetch_assoc($getData);
	}
}

if($data == null)
{
	fMessage('error', 'Woops, that article does not exist.');
	header("Location: index.php?_cmd=news");
	exit;
}

if(isset($_POST['content']))
{
	$title = filter($_POST['title']);
	$teaser = filter($_POST['teaser']);
	$topstory = WWW . '/images/ts/' . filter($_POST['topstory']);
	$content = filter($_POST['content']);
	$category = intval($_POST['category']);
	
	if(strlen($title) < 1 || strlen($teaser) < 1 || strlen($content) < 1)
	{
		fMessage('error', 'Please fill in all fields.');
	}
	else
	{
		dbquery("UPDATE site_news SET title = '" . $title . "', category_id = '" . $category . "', topstory_image = '" . $topstory . "', body = '" . $content . "', snippet = '" . $teaser . "' WHERE id = '" . $data['id'] . "' LIMIT 1");
		fMessage('ok', 'News article updated.');
		
		header("Location: index.php?p=news");
		exit;
	}
}

foreach($data as $key => $value)
{
	switch($key)
	{
		case 'snippet':
		
			$key = 'teaser';
			break;
			
		case 'topstory_image':
			
			$bits = explode('/', $value);
			$value = $bits[count($bits) - 1];
			$key = 'topstory';
			break;
			
		case 'body':
		
			$key = 'content';
			break;
	}

	if(!isset($_POST[$key]))
	{
		$_POST[$key] = $value;
	}
}

require_once "top.php";

?>
<script type="text/javascript">
	function previewTS(el) {
		document.getElementById('ts-preview').innerHTML = '<img src="<?php echo WWW; ?>/images/ts/' + el + '" />';
	}
</script>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Publish News</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Edit
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<form method="post">
						<div class="col-lg-7">
							<div class="form-group">
								<label>Article title</label>
								<input class="form-control" type="text" value="<?php if (isset($_POST['title'])) { echo clean($_POST['title']); } ?>" name="title" size="50" placeholder="Enter article title" />
							</div>
							<div class="form-group">
								<label>Category</label>
								<select class="form-control" name="category">
									<?php
									
									$getOptions = dbquery("SELECT * FROM site_news_categories ORDER BY caption ASC");
									
									while ($option = mysql_fetch_assoc($getOptions))
									{
										echo '<option value="' . intval($option['id']) . '" ' . (($option['id'] == $_POST['category']) ? 'selected' : '') . '>' . clean($option['caption']) . '</option>';
									}
									
									?>
								</select>
							</div>
							<label>SEO-friendly URL</label>
							<div class="form-group input-group">
								<span class="input-group-addon"><?php echo WWW; ?>/[id]-</span>
								<b><input class="form-control"  maxlength="120" id="disabledInput" type="text" placeholder="" disabled="" value="<?php echo $data['id']; ?>-<?php echo clean($data['seo_link']); ?>" /></b>
								<span class="input-group-addon">/</span>
							</div>
							<div class="form-group">
								<label>Frontpage teaser text</label>
								<textarea class="form-control" name="teaser" cols="48" rows="5" style="padding: 5px; font-size: 120%;"><?php if (isset($_POST['teaser'])) { echo clean($_POST['teaser']); } ?></textarea>
							</div>
							<div class="form-group">
								<label>Topstory image</label>
								<select onKeypress="previewTS(this.value);" onChange="previewTS(this.value);" class="form-control" name="topstory">
									<?php
									
									if ($handle = opendir(CWD . '/images/ts'))
									{
										while (false !== ($file = readdir($handle)))
										{		
											if ($file == '.' || $file == '..')
											{
												continue;
											}	
											
											echo '<option value="' . $file . '"';
											
											if (isset($_POST['topstory']) && $_POST['topstory'] == $file)
											{
												echo ' selected';
											}
											
											echo '>' . $file . '</option>';
										}
									}
									
									?>
								</select>
								<div id="ts-preview" style="margin-left: 20px; padding: 10px; float: left;">
									<img src="<?php echo $data['topstory_image']; ?>">
								</div>
							</div>
							<br />
							<br />
							<br />
							<br />
							<br />
							<br />
							<br />
							<br />
							<br />
							<br />
							<div class="form-group">
								<script type="text/javascript" src="js/tiny_mce/tinymce.min.js"></script>
								<script type="text/javascript">
									tinyMCE.init({
										mode : "exact",
										elements : "content",
										theme : "modern",
										theme_advanced_toolbar_location : "top",
										theme_advanced_toolbar_align : "left",
										theme_advanced_resizing : true,
										theme_advanced_statusbar_location : "bottom"
									});
								</script>
								<textarea class="form-control" cols="48" rows="5" id="content" name="content"><?php if (isset($_POST['content'])) { echo clean($_POST['content']); } ?></textarea>
							</div>
						</div>
						<!-- /.col-lg-7 -->
						<div class="col-lg-12">
							<hr />
							<button type="submit" class="btn btn-outline btn-success">Update article</button>
							&nbsp;
							<button type="reset" class="btn btn-default">Reset</button>
							&nbsp;
							<button class="btn btn-outline btn-danger" onClick="window.location = '?p=news'">Cancel</button>
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