<?php

class Cron
{
	public static function execute()
	{
		global $db;
		
		$query = $db->Query('SELECT id FROM site_cron WHERE enabled = "1" ORDER BY prio ASC');
		while($job = $db->FetchAssoc($query))
		{
			if (self::getNextExec($job['id']) <= time())
				self::runJob($job['id']);
		}
	}
	
	public static function runJob($jobId)
	{
		global $db;
		
		$script = $db->Result($db->Query('SELECT scriptfile FROM site_cron WHERE id = "' . $jobId . '" LIMIT 1'), 0);
		
		if(!self::checkScript($script)) {
			Core::systemError('Cron Error', 'Could not execute cron job "' . $script . '": could not locate script file.');
			return;
		}
		
		require_once INCLUDES . '/cron_scripts/' . $script;
		
		$db->Query('UPDATE site_cron SET last_exec = "' . time() . '" WHERE id = "' . $jobId . '" LIMIT 1');
	}
	
	public static function checkScript($script)
	{
		if(file_exists(INCLUDES . '/cron_scripts/' . $script)) {
			return true;
		}
		return false;
	}
	
	public static function getNextExec($jobId)
	{
		global $db;
		
		$query = $db->Query('SELECT last_exec, exec_every FROM site_cron WHERE id = "' . $jobId . '" LIMIT 1');
		
		if($db->NumRows($query) == 1)
		{
			$data = $db->FetchAssoc($query);
			return $data['last_exec'] + $data['exec_every'];		
		}
		return -1;
	}
}
