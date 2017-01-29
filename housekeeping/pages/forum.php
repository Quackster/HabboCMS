<?php

if (!defined('IN_HK') || !IN_HK)
{
	exit;
}

if (!HK_LOGGED_IN)
{
	exit;
}

if (isset($_POST['msg']))
{
	$subject = $_POST['subj'];
	$body = $_POST['msg'];
	
	if(strlen($subject) < 1)
	{
		$subject = 'No subject';
	}
	
	if(strlen($body) < 20)
	{
		$error_msg = "Message is too short. Please type something constructive.";
	}
	
	if($error_msg == null)
	{
		dbquery("INSERT INTO moderation_forum_threads (subject,message,poster,date,timestamp) VALUES ('". filter($subject) ."','". filter($body) ."','". HK_USER_NAME ."','". date('j F Y h:i A') ."','". time() ."')");
		
		fMessage('ok', 'Thread created');
		
		header("Location: index.php?p=forum");
		exit;
	}
}

require_once "top.php";

?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Discussion Board</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					Help
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<p>Use the staff discussion board to give us feedback, report private bugs, or discuss other staff or moderation related topics. Please keep spam to a minimum and only post relevant topics. Threads that have had no posts for over a week will be removed automatically. If you would like a thread removed sooner for whatever reason, please ask an administrator.</p>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Thread
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<?php
					
					$getTopics = dbquery("SELECT * FROM moderation_forum_threads ORDER BY timestamp DESC");
					
					if(mysql_num_rows($getTopics) >= 1)
					{
						while($topic = mysql_fetch_assoc($getTopics))
						{
							echo '<div class="panel panel-default">
									<div class="panel-heading">';
							
							if($topic['locked'] >= 1)
							{
								echo '<img src="images/locked.gif" alt="Locked" title="Thread locked" style="vertical-align: middle;" />&nbsp;';
							}		
							
							if($topic['timestamp'] >= 99999999999)
							{
								echo '<img src="images/sticky.gif" alt="Sticky" title="Sticky topic" style="vertical-align: middle;" />&nbsp;';
							}
							
							echo '<b><a href="?p=forumthread&i='. $topic['id'] .'">'. clean($topic['subject']) .'&nbsp;';
							
							$rCount = mysql_result(dbquery("SELECT COUNT(*) FROM moderation_forum_replies WHERE thread_id = '". $topic['id'] ."'"), 0);
							
							if($topic['locked'] == "0" || $rCount > 0)
							{
								echo '('. $rCount .' replies)';
							}
							
							echo '</a></b>
									</div>
									<!-- /.panel-heading -->
									<div class="panel-body">
									<p>'. substr($topic['message'], 0, 120);
							
							if (strlen($topic['message']) > 120)
							{
								echo '...';
							}
							
							echo '</p>
								</div>
								<!-- /.panel-body -->
								<div class="panel-footer">
									Posted on <b>'. $topic['date'] .'</b> by <b>'. $topic['poster'] .'</b>
								</div>
								<!-- /.panel-footer -->
							</div>
							<!-- /.panel panel-default -->
							<hr />';
						}
					}
					else
					{
						echo '<center><b><i>No topics have been posted yet.</b></i></center><hr />';
					}
					
					?>
					<div class="col-lg-8">
						<div id="cn-link" style="<?php if($error_msg == null) { echo "display: block;"; } else { echo "display: none"; } ?>">
							<a style="cursor:pointer" onClick="t('cn-link'); t('cn-form'); return false">Create new thread</a>
						</div>
						<?php if($error_msg == null) { echo ""; } else { ?>
						<div class="alert alert-danger">
							<?php echo $error_msg; ?>
						</div>
						<?php } ?>
						<div class="col-lg-6" id="cn-form" style="<?php if($error_msg == null) { echo "display: none;"; } else { echo "display: block"; } ?>">
							<form method="post">
								<div class="form-group">
									<label>Subject</label>
									<input maxlength="120" type="text" name="subj"  class="form-control" placeholder="Enter subject">
								</div>
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

require_once "bottom.php";

?>