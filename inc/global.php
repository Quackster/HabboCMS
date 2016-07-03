<?php

if (file_exists('./install/index.php')) {
    header('Location: ./install/');
}

define('HOTEL', true);
define('LB', chr(13));
define('ROOT', dirname(__DIR__));
define('USER_IP', $_SERVER['REMOTE_ADDR']);

error_reporting(E_ALL);

session_start();

///////////////////////////////////////////////////////////////////

require 'class.core.php';

Core::startExecTime();

require 'class.cron.php';
require 'class.users.php';
require 'class.tpl.php';
require 'class.i18n.php';

///////////////////////////////////////////////////////////////////

Core::parseConfig();

I18n::load(Core::$config['Site']['language']);

try {
    $db = new PDO('mysql:host=' . Core::$config['MySQL']['hostname'] . ';dbname=' . Core::$config['MySQL']['database'],
        Core::$config['MySQL']['username'], Core::$config['MySQL']['password']);
} catch (PDOException $error) {
    Core::systemError('Database Error', $error->getMessage());
}

Cron::execute();

///////////////////////////////////////////////////////////////////

if (isset($_SESSION['USER_N']) && isset($_SESSION['USER_H'])) {
    $userN = $_SESSION['USER_N'];
    $userH = $_SESSION['SER_H'];

    if (Users::validateUser($userN, $userH)) {
        define('LOGGED_IN', true);
        define('USER_NAME', $userN);
        define('USER_ID', Users::name2id($userN));
        define('USER_HASH', $userH);

        Users::cacheUser(USER_ID);
    } else {
        @session_destroy();
        header('Location: ' . WWW . '/');
        exit;
    }
} else {
    define('LOGGED_IN', false);
    define('USER_NAME', 'Guest');
    define('USER_ID', -1);
    define('USER_HASH', null);
}

define('FORCE_MAINTENANCE', ((Core::getMaintenanceStatus() == 1) ? true : false));

if (FORCE_MAINTENANCE && !defined('IN_MAINTENANCE')) {
    if (!LOGGED_IN || !Users::hasFuse(USER_ID, 'fuse_ignore_maintenance')) {
        header('Location: ' . WWW . '/maintenance');
        exit;
    }
}

if ((!defined('BAN_PAGE') || !BAN_PAGE) && (Users::isIpBanned(USER_IP) || (LOGGED_IN && Users::isUserBanned(USER_NAME)))) {
    header('Location: ' . WWW . '/banned');
    exit;
}

Core::checkCookies();

///////////////////////////////////////////////////////////////////

function shuffle_assoc(&$array)
{
    $keys = array_keys($array);

    shuffle($keys);

    $new = array();
    foreach ($keys as $key) {
        $new[$key] = $array[$key];
    }
    $array = $new;

    return true;
}
