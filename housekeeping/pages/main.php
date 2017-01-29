<?php

if (!defined('IN_HK') || !IN_HK) {
    exit;
}

if (!HK_LOGGED_IN) {
    header('Location: ' . HK_WWW . '/?p=login');
}

?>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Housekeeping</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-bar-chart-o fa-fw"></i> Welcome
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <p>Willkommen im Housekeeping.</p>
                        <p>Hier kannst du das gesamte Hotel verwalten.</p>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-bar-chart-o fa-fw"></i> Hotel statistics
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <p><i>Activity Statistics</i></p>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: right;">Count</th>
                                        <th>Item</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $usersget = mysql_query("SELECT * FROM users");
                                    $users = mysql_num_rows($usersget);
                                    if ($users <= 0) {
                                        $users = 0;
                                    }
                                    $roomsget = mysql_query("SELECT * FROM rooms");
                                    $rooms = mysql_num_rows($roomsget);
                                    if ($rooms <= 0) {
                                        $rooms = 0;
                                    }
                                    $furniget = mysql_query("SELECT * FROM furniture");
                                    $furni = mysql_num_rows($furniget);
                                    if ($furni <= 0) {
                                        $furni = 0;
                                    }
                                    $newsget = mysql_query("SELECT * FROM site_news");
                                    $newsg = mysql_num_rows($newsget);
                                    if ($newsg <= 0) {
                                        $newsg = 0;
                                    }
                                    $bansget = mysql_query("SELECT * FROM users_bans");
                                    $bans = mysql_num_rows($bansget);
                                    if ($bans <= 0) {
                                        $bans = 0;
                                    }
                                    $tagsget = mysql_query("SELECT * FROM user_tags");
                                    $tags = mysql_num_rows($tagsget);
                                    if ($tags <= 0) {
                                        $tags = 0;
                                    }
                                    $badgeget = mysql_query("SELECT * FROM user_badges");
                                    $badge = mysql_num_rows($badgeget);
                                    if ($badge <= 0) {
                                        $badge = 0;
                                    }

                                    ?>
                                    <style type="text/css">
                                        .no-link, .no-link:hover, .no-link:active {
                                            text-decoration: none;
                                            color: black;
                                        }
                                    </style>
                                    <tr>
                                        <td align="right"><a href="?p=getuser"><?php echo $users; ?></a></td>
                                        <td><a href="?p=getuser">Users</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a class="no-link"><?php echo $rooms; ?></a></td>
                                        <td><a class="no-link">Rooms</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a class="no-link"><?php echo $furni; ?></a></td>
                                        <td><a class="no-link">Furnis</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="?p=newspublish"><?php echo $newsg; ?></a></td>
                                        <td><a href="?p=news">News</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="?p=bans"><span><?php echo $bans; ?></span></a></td>
                                        <td><a href="?p=bans">Bans</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="<?php echo WWW; ?>/tag"><?php echo $tags; ?></a></td>
                                        <td><a href="<?php echo WWW; ?>/tag">Tags</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a class="no-link"><span><?php echo $badge; ?></span></a></td>
                                        <td><a class="no-link">Badges</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <?php

                $sysData = mysql_fetch_assoc(dbquery("SELECT * FROM server_status"));

                if ($sysData['status'] == 0) {
                    $state = "danger";
                } elseif ($sysData['status'] == 1) {
                    $state = "success";
                } else {
                    $state = "danger";
                }

                ?>
                <div class="panel panel-<?php echo $state; ?>">
                    <div class="panel-heading">
                        <i class="fa fa-bar-chart-o fa-fw"></i> Hotel Status
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <ul class="list-group">
                            <?php

                            echo '<li class="list-group-item"><i>Overview status of the Hotel</i></li>';
                            echo '<li class="list-group-item"><p><b>Build:</b> ' . $sysData['server_ver'] . '</p>';

                            switch ($sysData['status']) {
                                case 0:

                                    echo '<p><b>State:</b> The hotel is currently <b style="color: red;">Offline</b>.</p></li>';
                                    break;

                                case 1:

                                    echo '<p><b>State:</b> The hotel is currently <b style="color: darkgreen;">Online</b>.</p>';
                                    echo '<p><b>Users online:</b> ' . $sysData['users_online'] . '</p>';
                                    echo '<p><b>Rooms loaded:</b> ' . $sysData['rooms_loaded'] . '</p></li>';
                                    break;

                                default:

                                    echo '<p><b>State:</b> The hotel is currently <b style="color: red;">Offline</b> (The Server is closed/offline).</p></li>';
                                    break;
                            }

                            unset($sysData);

                            echo '<li class="list-group-item">This information is not for current, to see if it change the informations, refresh the page!</p></li>';

                            ?>
                        </ul>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-8 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->