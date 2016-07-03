<?php

class Cron
{
    public static function execute()
    {
        global $db;

        $query = $db->prepare('SELECT `id` FROM `site_cron` WHERE `enabled`=1 ORDER BY `prio` ASC');
        $query->execute();

        $jobList = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($jobList as $job) {
            if (self::getNextExec($job['id']) <= time()) {
                self::runJob($job['id']);
            }
        }
    }

    public static function runJob($jobId)
    {
        global $db;

        $query = $db->prepare('SELECT `scriptfile` FROM `site_cron` WHERE `id`=:jobId LIMIT 1');
        $query->execute(array(
            ':jobId' => $jobId,
        ));

        $script = $query->fetch(PDO::FETCH_ASSOC);
        $script = $script['scriptfile'];

        if (!self::checkScript($script)) {
            Core::systemError('Cron Error',
                'Could not execute cron job "' . $script . '": could not locate script file.');
            return;
        }

        require_once ROOT . '/inc/cron_scripts/' . $script;

        $query = $db->prepare('UPDATE `site_cron` SET `last_exec`=:execTime WHERE `id`=:jobId LIMIT 1');
        $query->execute(array(
            ':execTime' => time(),
            ':jobId'    => $jobId,
        ));
    }

    public static function checkScript($script)
    {
        if (file_exists(ROOT . '/inc/cron_scripts/' . $script)) {
            return true;
        }
        return false;
    }

    public static function getNextExec($jobId)
    {
        global $db;

        $query = $db->prepare('SELECT `last_exec`,`exec_every` FROM `site_cron` WHERE `id`=:jobId LIMIT 1');
        $query->execute(array(
            ':jobId' => $jobId,
        ));

        if ($query->rowCount() == 1) {
            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data['last_exec'] + $data['exec_every'];
        }
        return -1;
    }
}
