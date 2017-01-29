<?php

if (!defined('IN_HK') || !IN_HK) {
    exit;
}

if (HK_LOGGED_IN) {
    exit;
}

if (isset($_POST['usr']) && isset($_POST['pwd'])) {
    $username = Core::filterInputString($_POST['usr']);
    $password = Core::hash($_POST['pwd']);

    if (Users::validateUser($username, $password)) {
        $hkId = Users::name2id($username);

        if (Users::getUserVar($hkId, 'rank') > 4) {
            session_destroy();
            session_start();

            $_SESSION['USER_N'] = Users::getUserVar($hkId, 'username');
            $_SESSION['USER_H'] = $password;
            $_SESSION['HK_USER_N'] = $_SESSION['USER_N'];
            $_SESSION['HK_USER_H'] = $_SESSION['USER_H'];
            
            header("Location: " . HK_WWW . "/?=main");

            exit;
        } else {
            $_SESSION['HK_LOGIN_ERROR'] = "<div class=\"alert alert-danger\"><p style=\"text-align: center;\">" . Users::getUserVar($hkId, 'rank') . " - Du hast keine Rechte um auf das Housekeeping zuzugreifen!</p></div>";
        }
    } else {
        $_SESSION['HK_LOGIN_ERROR'] = '<div class="alert alert-danger"><p style=\"text-align: center;\">Fehlerhafte Angaben</p></div>';
    }
}

?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="http://localhost/web-gallery/v2/favicon.ico" type="image/vnd.microsoft.icon">

        <title>Login | Housekeeping</title>

        <!-- Bootstrap Core CSS -->
        <link href="./bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="./bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="./dist/css/sb-admin-2.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="./bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- jQuery -->
        <script src="./bower_components/jquery/dist/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="./bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="./bower_components/metisMenu/dist/metisMenu.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="./dist/js/sb-admin-2.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <?= isset($_SESSION['HK_LOGIN_ERROR']) ? '<br />' . $_SESSION['HK_LOGIN_ERROR'] : '' ?>
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Please Sign In</h3>
                        </div>
                        <div class="panel-body">
                            <form method="post" role="form">
                                <fieldset>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Username" name="usr" type="text" autofocus value="<?= LOGGED_IN ? USER_NAME : '' ?>" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Password" name="pwd" type="password" value="" />
                                    </div>
                                    <input type="submit" class="btn btn-lg btn-success btn-block" value="Login">
                                    <a href="/" class="btn btn-lg btn-default btn-block">Back to site</a>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>