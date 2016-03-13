<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN || !$users->GetuserVar(USER_ID, 'rank') > 5)
{
	exit;
}

if (isset($_POST['content']))
{
	$title = filter($_POST['title']);
	$teaser = filter($_POST['teaser']);
	$topstory = WWW . '/images/ts/' . filter($_POST['topstory']);
	$content = filter($_POST['content']);
	$seoUrl = filter($_POST['url']);
	$category = intval($_POST['category']);
	
	if (strlen($seoUrl) < 1 || strlen($title) < 1 || strlen($teaser) < 1 || strlen($content) < 1)
	{
		fMessage('error', 'Please fill in all fields.');
	}
	else
	{
		dbquery("INSERT INTO site_news (title,category_id,seo_link,topstory_image,body,snippet,datestr,timestamp) VALUES ('" . $title . "','" . $category . "','" . $seoUrl . "','" . $topstory . "','" . $content . "','" . $teaser . "','" . date('d-M-Y') . "', '" . time() . "')");
		fMessage('ok', 'News article published.');
		
		header("Location: ?p=news");
		exit;
	}
}

require_once "top.php";

?>
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
			<script type="text/javascript">
				function previewTS(el) {
					document.getElementById('ts-preview').innerHTML = '<img src="<?php echo WWW; ?>/images/ts/' + el + '" />';
				}
				
				function suggestSEO(el) {
					var suggested = el;
					
					suggested = suggested.toLowerCase();
					suggested = suggested.replace(/^\s+/, ''); 
					suggested = suggested.replace(/\s+$/, '');
					suggested = suggested.replace(/[^a-z 0-9]+/g, '');
					
					while (suggested.indexOf(' ') > -1) {
						suggested = suggested.replace(' ', '-');
					}
					
					document.getElementById('url').value = suggested;
				}
			</script>
			<div class="panel panel-info">
				<div class="panel-heading">
					Publish
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<form method="post">
						<div class="col-lg-7">
							<div class="form-group">
								<label>Article title</label>
								<input class="form-control" type="text" value="<?php if (isset($_POST['title'])) { echo clean($_POST['title']); } ?>" name="title" size="50" onKeyup="suggestSEO(this.value);" placeholder="Enter article title" />
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
							<label>SEO-friendly URL</label>&nbsp;(<small>This will be automatically suggested for you when you type a title. Required for us to be friendly to search engines.</small>)
							<div class="form-group input-group">
								<span class="input-group-addon"><?php echo WWW; ?>/[id]-</span>
								<input type="text" class="form-control" name="url" id="url" value="<?php if (isset($_POST['url'])) { echo clean($_POST['url']); } ?>" maxlength="120">
								<span class="input-group-addon">/</span>
							</div>
							<div class="form-group">
								<label>Frontpage teaser text</label>
								<textarea class="form-control" name="teaser" cols="48" rows="5"><?php if (isset($_POST['teaser'])) { echo clean($_POST['teaser']); } ?></textarea>
							</div>
							<div class="form-group">
								<label>Topstory image</label>
								<select class="form-control" onKeypress="previewTS(this.value);" onChange="previewTS(this.value);" name="topstory">
									<?php
									
									if($handle = opendir(CWD . '/images/ts'))
									{
										while(false !== ($file = readdir($handle)))
										{
											if($file == '.' || $file == '..')
											{
												continue;
											}	
											
											echo '<option value="' . $file . '"';
											
											if(isset($_POST['topstory']) && $_POST['topstory'] == $file)
											{
												echo ' selected';
											}
											
											echo '>' . $file . '</option>';
										}
									}

									?>
								</select>
								<div id="ts-preview" style="margin-left: 20px; padding: 10px; float: left;">
									<small>(Select an Topstory image from the list to preview it here)</small>
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
								<textarea id="content" name="content"><?php if (isset($_POST['content'])) { echo clean($_POST['content']); } ?></textarea>
							</div>
						</div>
						<!-- /.col-lg-7 -->
						<div class="col-lg-12">
							<hr />
							<button type="submit" class="btn btn-outline btn-success">Submit</button>
							&nbsp;
							<button type="reset" class="btn btn-outline btn-default">Reset</button>
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