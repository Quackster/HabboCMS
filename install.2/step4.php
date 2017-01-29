<!DOCTYPE html>

<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>Hotel: </title>

        <script type="text/javascript">
            var andSoItBegins = (new Date()).getTime();
            var ad_keywords = "";
            document.habboLoggedIn = false;
            var habboName = "null";
            var habboReqPath = "../";
            var habboStaticFilePath = "http://fubbo.net/images.fubbo.net/habboweb/63_1dc60c6d6ea6e089c6893ab4e0541ee0/160b/web-gallery";
            var habboImagerUrl = "http://www.habbo.co.uk/habbo-imaging/";
            var habboPartner = "";
            var habboDefaultClientPopupUrl = "..//client";
            window.name = "habboMain";
            if (typeof HabboClient != "undefined") {
                HabboClient.windowName = "uberClientWnd";
            }
        </script>

        <link rel="shortcut icon" href="../web-gallery/v2/favicon.ico" type="image/vnd.microsoft.icon">
        <script src="../web-gallery/static/js/libs2.js" type="text/javascript"></script>
        <script src="../web-gallery/static/js/visual.js" type="text/javascript"></script>
        <script src="../web-gallery/static/js/libs.js" type="text/javascript"></script>
        <script src="../web-gallery/static/js/common.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../web-gallery/v2/styles/style.css" type="text/css">
        <link rel="stylesheet" href="../web-gallery/v2/styles/buttons.css" type="text/css">
        <link rel="stylesheet" href="../web-gallery/v2/styles/boxes.css" type="text/css">
        <link rel="stylesheet" href="../web-gallery/v2/styles/tooltips.css" type="text/css">
        <link rel="stylesheet" href="../web-gallery/v2/styles/changepassword.css" type="text/css">
        <link rel="stylesheet" href="../web-gallery/v2/styles/forcedemaillogin.css" type="text/css">
        <link rel="stylesheet" href="../web-gallery/v2/styles/quickregister.css" type="text/css">
        <meta name="description" content="Erstelle deinen Pizza, baue Raeume und finde neue Freunde!">
        <meta name="keywords"
              content="hotel, ragezone, retro, keep it real, private server, free, credits, habbo hotel , virtual, world, social network, free, community, avatar, chat, online, teen, roleplaying, join, social, groups, forums, safe, play, games, online, friends, teens, rares, rare furni, collecting, create, collect, connect, furni, furniture, pets , room design, sharing, expression, badges, hangout, music, celebrity, celebrity visits, celebrities, mmo, mmorpg, massively multiplayer">

        <!--[if IE 8]>
        <link rel="stylesheet" href="../web-gallery/v2/styles/ie8.css" type="text/css" />
        <![endif]-->
        <!--[if lt IE 8]>
        <link rel="stylesheet" href="../web-gallery/v2/styles/ie.css" type="text/css" />
        <![endif]-->
        <!--[if lt IE 7]>
        <link rel="stylesheet" href="../web-gallery/v2/styles/ie6.css" type="text/css" />
        <script src="../web-gallery/static/js/pngfix.js" type="text/javascript"></script>
        <script type="text/javascript">
            try {
                document.execCommand('BackgroundImageCache', false, true);
            } catch (e) {
            }
        </script>
        <style type="text/css">
            body {
                behavior: url(http://www.habbo.co.uk/js/csshover.htc);
            }
        </style>
        <![endif]-->
        <meta name="build" content="54-BUILD 45 - 18.05.2010 16:16 - de">
    </head>
    <body id="client" class="background-agegate" style="background: black;">
        <div id="overlay"></div>
        <img src="../web-gallery/v2/images/page_loader.gif" style="position:absolute; margin: -1500px;" />
        <p class="phishing-warning">Diese Einrichtung wird solange angezeigt bis sie den Ordner "install"
                                    l&ouml;schen!</p>
        <div id="stepnumbers">
            <div class="step1">Willkommen</div>
            <div class="step2">SQL Anpassungen</div>
            <div class="step3">Client Einrichtung</div>
            <div class="stephabbo"></div>
        </div>
        <div id="main-container">
            <form id="quickregisterform" method="post" action="../quickregister/age_gate_submit">
                <div id="title">
                    Ordner muss gel&ouml;scht werden!
                </div>
                <div id="date-selector">
                    <div id="agegate-notice"><span style="font-size:12px; color: #00ccff;">Der Ordner 'install' wurde noch nicht gel&ouml;scht!</span>
                    </div>
                    <p>Bevor sie ShorsideCMS verwenden k&ouml;nnen muss der Ordner 'install' in ihrem Webordner gel&ouml;scht
                       werden. Ansonsten kann jeder mit dem Link zu diesem Ordner ihr Hotel zur&uuml;cksetzen. L&ouml;schen
                       sie den Ordner.</p>
                </div>
                <div class="delimiter_smooth">
                    <div class="flat">&nbsp;</div>
                    <div class="arrow">&nbsp;</div>
                    <div class="flat">&nbsp;</div>
                </div>
            </form>
            <div id="select">
                <a id="back-link" href="../index.php">Nicht registrieren und beenden!</a>
                <div class="button">
                    <a id="proceed" href="../quickregister/start" class="area">User Registrieren</a>
                    <span class="close"></span>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            document.observe("dom:loaded", function () {
                Event.observe($("back-link"), "click", function () {
                    Overlay.show(null, 'Laden...');
                });
                Event.observe('proceed', 'click', function (event) {
                    Overlay.show(null, 'Laden...');
                    $("quickregisterform").submit();
                });
                var boyImg = $$(".select_gender.boy img");
                if (boyImg.length > 0) {
                    boyImg[0].observe("click", function () {
                        $("radio-button-boy").checked = true;
                    });
                }
                var girlImg = $$(".select_gender.girl img");
                if (girlImg.length > 0) {
                    girlImg[0].observe("click", function () {
                        $("radio-button-girl").checked = true;
                    });
                }
                var dateSelector = $$("#date-selector select");
                if (dateSelector.length > 0) {
                    dateSelector[0].focus();
                }
                new Ajax.Request("/quickregister/start_loaded");
            });
        </script>
        <script type="text/javascript">
            HabboView.run();
        </script>
    </body>
</html>
