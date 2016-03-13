<?php

if(!defined('IN_HK') || !IN_HK)
{
	exit;
}

if(!HK_LOGGED_IN)
{
	exit;
}

$t = null;

if(isset($_GET['i']) && is_numeric($_GET['i']))
{
	$i = intval($_GET['i']);
	$s = dbquery("SELECT * FROM moderation_forum_threads WHERE id = '". $i ."' LIMIT 1");
	
	if(mysql_num_rows($s) >= 1)
	{
		$t = mysql_fetch_assoc($s);
	}
}

if($t['locked'] == "0" && isset($_POST['msg']))
{	
	$msg = filter($_POST['msg']);
	
	if(strlen($msg) <= 12)
	{
		$error_msg = "Message is too short. Please post something worthwhile.";
	}
	
	dbquery("INSERT INTO moderation_forum_replies (thread_id,poster,date,message) VALUES ('". $t['id'] ."','". HK_USER_NAME ."','". date('j F Y h:i A') ."','". $msg ."')");
	
	if ($t['timestamp'] < 99999999999)
	{
		dbquery("UPDATE moderation_forum_threads SET timestamp = '". time() ."' WHERE id = '". $t['id'] ."' LIMIT 1");
	}
	
	header("Location: ?p=forumthread&i=". $t['id']);
	exit;
}

if(isset($_POST['opt']))
{
	$opt = $_POST['opt'];
	
	switch($opt)
	{
		case 'lock':
		
			$newState = 1;
			$l = 'locked';
			
			if($t['locked'] == "1")
			{
				$newState = 0;
				$l = 'unlocked';
			}
			
			fMessage('ok', 'Thread ' . $l . '.');
			
			dbquery("UPDATE moderation_forum_threads SET locked = '" . $newState . "' WHERE id = '" . $t['id'] . "' LIMIT 1");
			break;
	
		case 'stick':
		
			fMessage('ok', 'Thread stickied.');
		
			dbquery("UPDATE moderation_forum_threads SET timestamp = '99999999999' WHERE id = '" . $t['id'] . "' LIMIT 1");
			break;
	
		case 'bump':
		
			fMessage('ok', 'Thread updated.');
		
			dbquery("UPDATE moderation_forum_threads SET timestamp = '" . time() . "' WHERE id = '" . $t['id'] . "' LIMIT 1");
			break;
	
		case 'del';
		
			fMessage('ok', 'Thread deleted.');
		
			dbquery("DELETE FROM moderation_forum_threads WHERE id = '" . $t['id'] . "' LIMIT 1");
			dbquery("DELETE FROM moderation_forum_replies WHERE thread_id = '" . $t['id'] . "'");
			break;
	}
	
	header("Location: ?p=forum");
	exit;
}

require_once "top.php";

if($t == null)
{

?>
<div id="page-wrapper">
	<br />
	<div class="row">
		<div class="col-lg-12">
			<div class="alert alert-danger" align="center">
				Thread not found! <a href="?p=forum" class="alert-link">Return to discussion board</a>.
			</div>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
<?php

}
elseif($t !== null)
{

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Discussion Board<?php echo ' - "'. clean($t['subject']) .'"'; ?></h1>
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
					<?php
					
					if($t['locked'] >= 1)
					{
						echo '<p><img src="images/locked.gif" alt="Locked" title="Thread locked" style="vertical-align: middle;" /> Locked thread</p>';
					}
					if ($t['timestamp'] >= 99999999999)
					{
						echo '<p><img src="images/sticky.gif" alt="Sticky" title="Sticky topic" style="vertical-align: middle;" /> Sticky thread</p>';
					}
					?>
					<p><?php echo 'Posted by <b>'. $t['poster'] .'</b>'; ?> on <?php echo '<b>'. $t['date'] .'</b>'; ?> (<a href="index.php?p=forum">Return to discussion board</a>)</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
		<?php if ($users->hasFuse(HK_USER_ID, 'fuse_admin')) { ?>
		<div class="col-lg-12">
			<div class="panel panel-danger">
				<div class="panel-heading">
					Admin options
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<form method="post">
						<div class="col-lg-3">
							<select class="form-control" name="opt">
								<?php if ($t['timestamp'] < 99999999999) { ?><option value="stick">Sticky thread</option><?php } ?>
								<option value="bump"><?php if ($t['timestamp'] >= 99999999999) { echo 'Unstick thread'; } else { echo 'Bump thread to top'; } ?></option>	
								<option value="lock"><?php if ($t['locked'] == "1") { echo 'Unlock thread'; } else { echo 'Lock thread'; } ?></option>
								<option value="del">Delete thread</option>
							</select>
						</div>
						<button type="submit" class="btn btn-default">Go</button>
					</form>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
		<?php } ?>
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Main message
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<p class="popular-tags">
						<?php echo nl2br(clean($t['message'])); ?>
					</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Replies
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<?php
					
					$getReplies = dbquery("SELECT * FROM moderation_forum_replies WHERE thread_id = '" . $t['id'] . "'");
					
					
					if (mysql_num_rows($getReplies) >= 1)
					{
						while ($r = mysql_fetch_assoc($getReplies))
						{
							echo '<div class="panel panel-default">
									<div class="panel-heading">
										' . $r['poster'] . ' replied
									</div>
									<!-- /.panel-heading -->
									<div class="panel-body">
										'. nl2br(clean($r['message'])) .'
									</div>
									<!-- /.panel-body -->
									<div class="panel-footer">
										'. $r['date'] .'
									</div>
									<!-- /.panel-footer -->
								</div>
								<!-- /.panel -->
							<hr />';
						}
					}
					else if ($t['locked'] == "0")
					{
						echo '<center><b><i>This topic does not have any replies yet.</i></b></center><hr />';
					}
					
					if ($t['locked'] == "0") {
					
					?>
					<div class="col-lg-8">
						<div id="cn-link" style="<?php if($error_msg == null) { echo "display: block;"; } else { echo "display: none"; } ?>">
							<a style="cursor:pointer" onClick="t('cn-link'); t('cn-form'); return false">Post reply</a>
						</div>
						<?php if($error_msg == null) { echo ""; } else { ?>
						<div class="alert alert-danger">
							<?php echo $error_msg; ?>
						</div>
						<?php } ?>
						<div class="col-lg-6" id="cn-form" style="<?php if($error_msg == null) { echo "display: none;"; } else { echo "display: block"; } ?>">
							<form method="post">
								<div class="form-group">
									<label>Message</label>
									<textarea class="form-control" placeholder="Enter message" name="msg" cols="50" rows="5"></textarea>
								</div>
								<button type="submit" class="btn btn-outline btn-success">Submit</button>
								<button type="reset" class="btn btn-outline btn-default">Reset</button>
								<input value="Cancel" type="button" class="btn btn-outline btn-danger" onClick="t('cn-link'); t('cn-form');" />
							</form>
						</div>
					</div>
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
					}
}

require_once "bottom.php";

?>