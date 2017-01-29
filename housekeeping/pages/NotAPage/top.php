<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="http://localhost/web-gallery/v2/favicon.ico" type="image/vnd.microsoft.icon">

        <title>Housekeeping for Retro Hotels</title>

        <!-- Bootstrap Core CSS -->
        <link href="./bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="./bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

        <!-- Timeline CSS -->
        <link href="./dist/css/timeline.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="./dist/css/sb-admin-2.css" rel="stylesheet">

        <!-- Morris Charts CSS -->
        <link href="./bower_components/morrisjs/morris.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="./bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div id="wrapper">

            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="./">Housekeeping for Retro Hotels</a> <a class="navbar-brand"
                                                                                           href="https://www.orbitrondev.org">
                        <small>(by OrbitronDev)</small>
                    </a>
                </div>
                <!-- /.navbar-header -->

                <p class="nav navbar-text navbar-right">Hello,
                    <a href="<?= WWW; ?>/home/<?= USER_NAME; ?>"><?= USER_NAME; ?></a> |
                    <a href="?p=logout" title="Sign out">Sign out</a>
                </p>
                <!-- /.navbar-link -->

                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
                            <li>
                                <a href="./"><i class="fa fa-home fa-fw"></i> Home</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-wrench fa-fw"></i> Staffs Panel<span
                                        class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="../client" target="_new" onClick="popClient()">Game Client</a>
                                    </li>
                                    <li>
                                        <a href="?p=getstaff">Staff List</a>
                                    </li>
                                    <li>
                                        <a href="?p=forum">Staff Forum</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-edit fa-fw"></i> News / Camp<span
                                        class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="?p=newspublish">Add new News</a>
                                    </li>
                                    <li>
                                        <a href="?p=news">Managing News</a>
                                    </li>
                                    <li>
                                        <a href="?p=campaigns">Hot Campaigns</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-files-o fa-fw"></i> Logs + Others<span
                                        class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="?p=bans">Bans + appeals</a>
                                    </li>
                                    <li>
                                        <a href="?p=chatlogs">Chatlogs</a>
                                    </li>
                                    <li>
                                        <a href="?p=cfhs">Called for help</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-cog fa-fw"></i> Moderator<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="?p=roomads">Advertisement</a>
                                    </li>
                                    <li>
                                        <a href="?p=badgedefs">Edit badges <b class="text-danger">(ERROR)</b></a>
                                    </li>
                                    <li>
                                        <a href="?p=presets">Mod Tools</a>
                                    </li>
                                    <li>
                                        <a href="?p=ha">Alert Hotel</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-table fa-fw"></i> Catalog<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="?p=ot-def">Item definition <b class="text-danger">(LAGGY)</b></a>
                                    </li>
                                    <li>
                                        <a href="?p=ot-pages">Catalog Pages</a>
                                    </li>
                                    <li>
                                        <a href="?p=ot-cata-items">Catalog Items <b class="text-danger">(LAGGY)</b></a>
                                    </li>
                                    <li>
                                        <a href="?p=furnifinder">Furni datas</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-users fa-fw"></i> Users<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="?p=getuser">Users list</a>
                                    </li>
                                    <li>
                                        <a href="?p=extsignon">Find users</a>
                                    </li>
                                    <li>
                                        <a href="?p=givecredits">Give credits</a>
                                    </li>
                                    <li>
                                        <a href="?p=givepixels">Give pixels</a>
                                    </li>
                                    <li>
                                        <a href="?p=badges">User badges <b class="text-danger">(ERROR)</b></a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-flask fa-fw"></i> Site tools<span
                                        class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="?p=texts">External texts <b class="text-danger">(ERROR)</b></a>
                                    </li>
                                    <li>
                                        <a href="?p=vars">External variables</a>
                                    </li>
                                    <li>
                                        <a href="?p=iptool">IP tool</a>
                                    </li>
                                    <li>
                                        <a href="?p=vouchers">Vouchers</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-wrench fa-fw"></i> Administrator<span
                                        class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="?p=maint">Maintenance</a>
                                    </li>
                                    <li>
                                        <a href="?p=app">Application <b class="text-danger">(ERROR)</b></a>
                                    </li>
                                    <li>
                                        <a href="?p=rango">Give rank</a>
                                    </li>
                                    <li>
                                        <a href="?p=confsitio">Site settings</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-exclamation-triangle fa-fw"></i> Testing<span
                                        class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="?p=notListed/getdoc">Get Docs</a>
                                    </li>
                                    <li>
                                        <a href="?p=notListed/content">Administrate content panel</a>
                                    </li>
                                    <li>
                                        <a href="?p=notListed/jobapps">Jobapps</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                        </ul>
                        <!-- /#side-menu -->
                    </div>
                    <!-- /.sidebar-collapse -->
                </div>
                <!-- /.navbar-static-side -->
            </nav>