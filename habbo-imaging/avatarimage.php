<?php

// Usage: <img src="/habbo-imaging/avatarimage.php?figure=..." />
define('MAINTENANCE_POSSIBLE', false);
define('ALLOW_CACHE', true);

require_once '../inc/global.php';
require      '../inc/class.avatarimage.php';
header('Content-type: image/png');

$avatar = array(
    'figure'         => @$_GET['figure'],
    'size'           => @$_GET['size'],
    'direction'      => @$_GET['direction'],
    'head_direction' => @$_GET['head_direction'],
    'gesture'        => @$_GET['gesture'],
    'action'         => @$_GET['action'],
);

$user = @Core::filterInputString(@$_GET['user']);

if (empty($avatar['figure']) && !empty($user)) {
    $avatar['figure'] = Users::getUserVar(Users::name2id($user), 'look');
}
if (empty($avatar['size'])) {
    $avatar['size'] = "b";
}
if (empty($avatar['direction'])) {
    $avatar['direction'] = "3";
}
if (empty($avatar['head_direction'])) {
    $avatar['head_direction'] = "3";
}
if (empty($avatar['gesture'])) {
    $avatar['gesture'] = "sml";
}

$avatarQueryString = http_build_query($avatar);
$avatarRenderUrl   = 'http://www.habbo.com/habbo-imaging/avatarimage?' . $avatarQueryString;
$avatarFileDir     = 'avatar/' . $avatarQueryString;

$image = new AvatarImage($avatarRenderUrl, $avatarFileDir);

if (defined('ALLOW_CACHE') && ALLOW_CACHE && isset($_GET['cache']) && $_GET['cache']) {
    // Get from cache
    if (file_exists($avatarFileDir)) {
        // Open image from cache
        $image->showImage(true);
    } else {
        // Get Image Data
        $image->grabImage();
        // Save Image Data to file
        $image->saveImage();
        // Open image
        $image->showImage();
    }
} else {
    // Render avatar
    // Get Image Data
    $image->grabImage();
    // Open image
    $image->showImage();
}
