<?php

if(!defined('IN_HK') || !IN_HK)
{
	exit;
}

if(!HK_LOGGED_IN || !$users->hasFuse(USER_ID, 'fuse_admin'))
{
	exit;
}

function getExtension($str)
{
	$i = strrpos($str, '.');
	
	if (!$i)
	{
		return '';
	}
	
	$l = strlen($str) - $i;
	$ext = substr($str, $i + 1, $l);
	
	return $ext;
}

if(isset($_POST['name']))
{
	$image = $_FILES['img']['name'];
	
	if($image && isset($_POST['name']) && isset($_POST['clickurl']))
	{
		$filename = strtolower($_POST['name']);
		$clickurl = $_POST['clickurl'];
		
		if(strlen($filename) >= 1 && strlen($clickurl) >= 1)
		{
			$ext = getExtension(strtolower($_FILES['img']['name']));
			
			$fileLoc = CWD . '/ads/' . $filename . '.' . $ext;
			$wwwLoc = WWW . '/ads/' . $filename . '.' . $ext;
			
			if($ext == "gif")
			{
				if(copy($_FILES['img']['tmp_name'], $fileLoc))
				{
					dbquery("INSERT INTO room_ads (id,ad_image,ad_image_orig,ad_link,views,views_limit,enabled) VALUES (NULL,'" . $wwwLoc . "','" . $filename . '.' . $ext . "','" . filter($clickurl) . "',0,0,1)");
					fMessage('ok', 'Okay, interstitial ad uploaded.');
				}
				else
				{
					fMessage('error', 'Could not process file: ' . $fileLoc);
				}
			}
			else
			{
				fMessage('error', 'Invalid file type: ' . $ext);
			}
		}
		else
		{
			fMessage('error', 'Please enter a file name and URL.');
		}
	}
	else
	{
		fMessage('error', 'File upload error (unknown).');
	}
}

if (isset($_GET['delId']))
{
	dbquery("DELETE FROM room_ads WHERE ad_image_orig = '". filter($_GET['delId']) ."'");
	
	if(@unlink(CWD .'/ads/'. filter($_GET['delId'])))
	{
		fMessage('ok', 'Deleted interstitial ad.');
	}
	
	header("Location: ?p=roomads");
	exit;
}

if(isset($_GET['switchId']))
{
	$get = dbquery("SELECT enabled FROM room_ads WHERE ad_image_orig = '" . filter($_GET['switchId']) . "' LIMIT 1");
	
	if(mysql_num_rows($get) >= 1)
	{
		$enabled = mysql_result($get, 0);
		
		$set = "0";
		
		if($enabled == "0")
		{
			$set = "1";
		}

		dbquery("UPDATE room_ads SET enabled = '" . $set . "' WHERE ad_image_orig = '" . filter($_GET['switchId']) . "' LIMIT 1");
	}
	
	header("Location: ?p=roomads");
	exit;	
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Interstitials</h1>
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
					<p>Interstitials are advertisements shown when users navigate between rooms, while the new room loads.</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Ad gallery
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="dataTables-example">
							<thead>
								<tr>
									<td>Thumbnail <small>(click to enlarge)</small></td>
									<td>Filename</td>
									<td>Click URL</td>
									<td>Views</td>
									<td>Enable/disable</td>
									<td>Delete</td>
								</tr>
							</thead>
							<?php
							
							$handle = null;
							
							if($handle = opendir(CWD .'/ads'))
							{
								echo '<tbody>';
								
								while(false !== ($file = readdir($handle)))
								{
									$file = strtolower($file);
								
									if($file == '.' || $file == '..' || getExtension($file) != "gif")
									{
										continue;
									}
									
									$hasDbEntry = false;
									$dbData = null;
									$dbGet = dbquery("SELECT * FROM room_ads WHERE ad_image_orig = '". $file ."' LIMIT 1");
									
									if(mysql_num_rows($dbGet) >= 1)
									{
										$hasDbEntry = true;
										$dbData = mysql_fetch_assoc($dbGet);
									}
									
									echo '<tr>
											<td><a href="/ads/'. $file .'"><center><img src="'. WWW .'/ads/'. $file .'" height="100" width="100"></center></a></td>
											<td><a href="/ads/'. $file .'"><b>'. $file .'</b></a></td>
											<td>';
									
									if($hasDbEntry)
									{
										echo '<a href="'. $dbData['ad_link'] .'">'. clean($dbData['ad_link']) .'</a>';
									}
									else
									{
										echo 'N/A';
									}
									
									echo '</td>
											<td>';
									
									if ($hasDbEntry)
									{
										echo $dbData['views'];
									}
									else
									{
										echo 'N/A';
									}
									
									echo '</td>
											<td>';
									
									if($hasDbEntry)
									{
										if($dbData['enabled'] == "0")
										{
											echo 'Currently <b style="color: darkred !important;">disabled</b><br /><br /><button type="submit" onClick="document.location = \'?p=roomads&switchId='. $file .'\';" class="btn btn-outline btn-success">Enable</button>';
										}
										else
										{
											echo 'Currently <b style="color: darkgreen !important;">enabled</b><br /><br /><button type="submit" onClick="document.location = \'?p=roomads&switchId='. $file .'\';" class="btn btn-outline btn-danger">Disable</button>';
										}
									}
									else
									{
										echo '<strong style="color: darkred;">DB entry is missing!<br />Please reupload.</strong>';
									}
									
									echo '</td>
											<td><button type="submit" onClick="document.location = \'?p=roomads&delId='. $file .'\';" class="btn btn-outline btn-danger">Delete</button></td>
										</tr>';
								}
								
								echo '</tbody>';
								
								closedir($handle);
							}
							
							?>
						</table>
					</div>
					<!-- /.table-responsive -->
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<div class="panel panel-default">
				<div class="panel-heading">
					Upload an interstitial ad (GIF only!)
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<form method="post" enctype="multipart/form-data">
						<div class="col-lg-7">
							<div class="form-group">
								<label id="ban-value-heading">File</label>
								<input class="form-control" type="file" name="img">
							</div>
							<div class="form-group">
								<label id="ban-value-heading">Filename (alphanumeric characters only, no extension)</label>
								<input class="form-control" type="text" name="name">
							</div>
							<div class="form-group">
								<label id="ban-value-heading">Click URL</label>
								<input class="form-control" type="text" name="clickurl">
							</div>
						</div>
						<!-- /.col-lg-7 -->
						<div class="col-lg-12">
							<hr />
							<div class="form-group">
								<button type="submit" class="btn btn-outline btn-success">Submit</button>
							</div>
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