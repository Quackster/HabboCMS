<?php

define('IN_MAINTENANCE', true);

require_once "../inc/global.php";
require_once "../inc/admincore.php";

$p = null;

if (isset($_POST['p'])) {
    $p = strtolower(Core::cleanStringForOutput($_POST['p']));
}

if ($p == null && isset($_GET['p'])) {
    $p = strtolower(Core::cleanStringForOutput($_GET['p']));
}

if ($p == null) {
    $initial = 'main';

    if (!HK_LOGGED_IN) {
        $initial = 'login';
        $_SESSION['HK_LOGIN_ERROR'] = "<div class=\"alert alert-info\"><p style=\"text-align: center;\">You are not logged in</p></div>";
    }
    header("Location: " . HK_WWW . "/?p=" . $initial);
    exit;
}

if (HK_LOGGED_IN && $p == 'login') {
    header("Location: " . HK_WWW . "/?p=main");
    exit;
} elseif (!HK_LOGGED_IN && $p != 'login') {
    $_SESSION['HK_LOGIN_ERROR'] = "<div class=\"alert alert-info\"><p style=\"text-align: center;\">You are not logged in</p></div>";
    header("Location: " . HK_WWW . "/?p=login");
    exit;
}

switch ($p) {
    case 'logout':

        session_destroy();
        session_start();

        $_SESSION['HK_LOGIN_ERROR'] = "<div class=\"alert alert-success\"><p style=\"text-align: center;\">You have successfully signed out!</p></div>";

        header("Location: /?p=login");
        exit;

    case 'getPageContent':

        if (file_exists('pages/' . $p . '.php') && HK_LOGGED_IN) {
            require_once 'pages/' . $p . '.php';
        } else {
            require_once 'pages/404.php';
        }
        break;

    case 'login':

        include 'pages/login.php';
        break;

    default:

        include 'pages/NotAPage/top.php';
        if (file_exists('pages/' . $p . '.php') && HK_LOGGED_IN) {
            include 'pages/' . $p . '.php';
        } else {
            include 'pages/404.php';
        }
        include 'pages/NotAPage/bottom.php';

        break;
}

//unset($_SESSION['HK_LOGIN_ERROR']);