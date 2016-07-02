<?php
require_once '../global.php';

dbquery("TRUNCATE TABLE site_navi;");
dbquery("INSERT INTO `site_navi` (`id`, `parent_id`, `order_id`, `caption`, `class`, `url`, `visibility`) VALUES
(1, 0, 1, '%habboName%', 'metab', '%www%/me', '2'),
(2, 1, 1, 'Home', '', '/me', '2'),
(5, 0, 2, 'Community', '', '%www%/community', '1'),
(4, 1, 3, 'Profileinstellungen', '', '%www%/profile', '2'),
(6, 0, 3, 'Taler bekommen', '', '%www%/credits', '1'),
(7, 5, 1, 'Community', '', '%www%/community', '1'),
(8, 0, 1, 'Gratis Registrieren!', 'tab-register-now', '%www%/register', '3'),
(10, 6, 3, 'Pixels', '', '%www%/credits/pixels', '1'),
(14, 1, 2, 'Meine Seite', '', '%www%/home/%habboName%', '2'),
(16, 5, 3, '%shortname% Staff', '', '%www%/community/staff', '1'),
(17, 5, 2, 'News', '', '%www%/articles', '1');");
if(isset($_GET['do']))
{
dbquery("DELETE FROM rooms WHERE roomtype = 'private';");
dbquery("TRUNCATE TABLE room_items;");
dbquery("TRUNCATE TABLE room_items_moodlight;");
dbquery("TRUNCATE TABLE room_poll;");
dbquery("TRUNCATE TABLE room_rights;");
dbquery("TRUNCATE TABLE users;");
dbquery("TRUNCATE TABLE user_achievements;");
dbquery("TRUNCATE TABLE user_badges;");
dbquery("TRUNCATE TABLE user_effects;");
dbquery("TRUNCATE TABLE user_favorites;");
dbquery("TRUNCATE TABLE user_ignores;");
dbquery("TRUNCATE TABLE user_info;");
dbquery("TRUNCATE TABLE user_items;");
dbquery("TRUNCATE TABLE user_presents;");
dbquery("TRUNCATE TABLE user_quests;");
dbquery("TRUNCATE TABLE user_roomvisits;");
dbquery("TRUNCATE TABLE user_subscriptions;");
dbquery("TRUNCATE TABLE user_tags;");
dbquery("TRUNCATE TABLE user_vip_wardrobe;");
dbquery("TRUNCATE TABLE user_wardrobe;");
dbquery("TRUNCATE TABLE messenger_friendships;");
dbquery("TRUNCATE TABLE messenger_requests;");
dbquery("TRUNCATE TABLE stream;");
}

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head> 
<meta http-equiv="content-type" content="text/html; charset=utf-8"> 
<title>Fubbo:  </title> 
 
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
if (typeof HabboClient != "undefined") { HabboClient.windowName = "uberClientWnd"; }
</script> 

<link rel="shortcut icon" href="web-gallery/v2/favicon.ico" type="image/vnd.microsoft.icon"> 
<script src=".//web-gallery/static/js/libs2.js" type="text/javascript"></script>
<script src=".//web-gallery/static/js/visual.js" type="text/javascript"></script>
<script src=".//web-gallery/static/js/libs.js" type="text/javascript"></script>
<script src=".//web-gallery/static/js/common.js" type="text/javascript"></script>
<link rel="stylesheet" href="../web-gallery/v2/styles/style.css" type="text/css">
<link rel="stylesheet" href="../web-gallery/v2/styles/buttons.css" type="text/css">
<link rel="stylesheet" href="../web-gallery/v2/styles/boxes.css" type="text/css">
<link rel="stylesheet" href="../web-gallery/v2/styles/tooltips.css" type="text/css">
<link rel="stylesheet" href="../web-gallery/v2/styles/changepassword.css" type="text/css">
<link rel="stylesheet" href="../web-gallery/v2/styles/forcedemaillogin.css" type="text/css">
<link rel="stylesheet" href="../web-gallery/v2/styles/quickregister.css" type="text/css">
<meta name="description" content="Erstelle deinen Pizza, baue Raeume und finde neue Freunde!"> 
<meta name="keywords" content="uber, uberhotel, uber hotel, meth0d, nillus, ragezone, retro, keep it real, private server, free, credits, habbo hotel , virtual, world, social network, free, community, avatar, chat, online, teen, roleplaying, join, social, groups, forums, safe, play, games, online, friends, teens, rares, rare furni, collecting, create, collect, connect, furni, furniture, pets , room design, sharing, expression, badges, hangout, music, celebrity, celebrity visits, celebrities, mmo, mmorpg, massively multiplayer"> 
 
<!--[if IE 8]>
<link rel="stylesheet" href="http://images.habbo.com/habboweb/63_1dc60c6d6ea6e089c6893ab4e0541ee0/160b/web-gallery/v2/styles/ie8.css" type="text/css" />
<![endif]--> 
<!--[if lt IE 8]>
<link rel="stylesheet" href="http://images.habbo.com/habboweb/63_1dc60c6d6ea6e089c6893ab4e0541ee0/160b/web-gallery/v2/styles/ie.css" type="text/css" />
<![endif]--> 
<!--[if lt IE 7]>
<link rel="stylesheet" href="http://images.habbo.com/habboweb/63_1dc60c6d6ea6e089c6893ab4e0541ee0/160b/web-gallery/v2/styles/ie6.css" type="text/css" />
<script src="http://images.habbo.com/habboweb/63_1dc60c6d6ea6e089c6893ab4e0541ee0/160b/web-gallery/static/js/pngfix.js" type="text/javascript"></script>
<script type="text/javascript">
try { document.execCommand('BackgroundImageCache', false, true); } catch(e) {}
</script>
 
<style type="text/css">
body { behavior: url(http://www.habbo.co.uk/js/csshover.htc); }
</style>
<![endif]--> 
<meta name="build" content="54-BUILD 45 - 18.05.2010 16:16 - de"> 
</head><link rel="stylesheet" type="text/css" href="data:text/css,">

<body id="client" class="background-agegate"> 
<div id="overlay"></div> 
<img src="http://images.habbo.com/habboweb/63_1dc60c6d6ea6e089c6893ab4e0541ee0/160b/web-gallery/v2/images/page_loader.gif" style="position:absolute; margin: -1500px;"> 


<div id="change-password-form" style="display: none;">

    <div id="change-password-form-container" class="clearfix">

        <div id="change-password-form-title" class="bottom-border">Wachtwoord vergeten?</div>

        <div id="change-password-form-content" style="display: none;">

            <form method="post" action="..//account/password/identityResetForm" id="forgotten-pw-form">

                <input type="hidden" name="page" value="..//quickregister/start?changePwd=true">

                <span>Vul hier je e-mailadres in:</span>

                <div id="email" class="center bottom-border">

                    <input type="text" id="change-password-email-address" name="emailAddress" value="" class="email-address" maxlength="48">

                    <div id="change-password-error-container" class="error" style="display: none;">Vul alsjeblieft een werkend e-mailadres in</div>

                </div>

            </form>

            <div class="change-password-buttons">

                <a href="#" id="change-password-cancel-link">Annuleren</a>

                <a href="#" id="change-password-submit-button" class="new-button"><b>Verstuur e-mail</b><i></i></a>

            </div>

        </div>

        <div id="change-password-email-sent-notice" style="display: none;">

            <div class="bottom-border">

                <span>Een mail met een link waarmee jij je wachtwoord kunt aanpassen is verstuurd naar jouw mailadres.</span>

                <div id="email-sent-container"></div>

            </div>

            <div class="change-password-buttons">

                <a href="#" id="change-password-change-link">Terug</a>

                <a href="#" id="change-password-success-button" class="new-button"><b>Sluiten</b><i></i></a>

            </div>

        </div>

    </div>

    <div id="change-password-form-container-bottom"></div>

</div>



<script type="text/javascript">

HabboView.add( function() {

     ChangePassword.init();





});

</script> 
<link rel="stylesheet" href="http://images.habbo.com/habboweb/63_1dc60c6d6ea6e089c6893ab4e0541ee0/160b/web-gallery/v2/styles/quickregister.css" type="text/css">
<link rel="stylesheet" href="http://images.habbo.com/habboweb/63_1dc60c6d6ea6e089c6893ab4e0541ee0/160b/web-gallery/v2/styles/forcedemaillogin.css" type="text/css">

 
 
<p class="phishing-warning">Diese Einrichtung wird solange angezeigt bis sie den Ordner "install" l&ouml;schen!</p> 
<div id="stepnumbers"> 
    <div class="step1">Willkommen</div> 
    <div class="step2focus">SQL Anpassungen</div> 
    <div class="step3">Client Einrichtung</div> 
    <div class="stephabbo"></div> 
</div> 
 
<div id="main-container"> 
 
				 
 
    <form id="quickregisterform" method="post" action="..//quickregister/age_gate_submit"> 
 
        <div id="title"> 
            SQL Updates
        </div> 
 
        <div id="date-selector"> 
            <div id="agegate-notice"><span style="font-size:12px; color: #00ccff;">Ihre Datenbank wurde angepasst!</span></div> 
			<?php if(!isset($_GET['do'])) { ?>
			<p><a href="step2.php?do=true">Sie k&ouml;nnen die Datenbank per Klick auf diesen Text komplett leeren</a></p>
			<?php } ?>
			</div> 
 
        <div class="delimiter_smooth"> 
            <div class="flat">&nbsp;</div> 
            <div class="arrow">&nbsp;</div> 
            <div class="flat">&nbsp;</div> 
        </div> 
 
        <div id="inner-container"> 
            <h3>Was wurde angepasst wenn keine Fehler auftreten?</h3>
			<li>- Navigationsleiste verlinkt jetzt richtig</li>
			<?php if(isset($_GET['do'])) { ?>
			<li>- User Tabelle wurde geleert</li>
			<li>- Userdaten wurden entfernt</li>
			<li>- R&auml;me und Items wurden entfernt</li>
			<?php } ?>
        </div> 
    </form> 
 
    <div id="select"> 
        <div class="button"> 
            <a id="proceed" href="step3.php" class="area">Weiter</a> 
            <span class="close"></span> 
        </div> 
   </div> 
</div> 
 
<script language="JavaScript" type="text/javascript"> 
    document.observe("dom:loaded", function() {
        Event.observe($("back-link"), "click", function() {
            Overlay.show(null,'Laden...');
        });        
        Event.observe('proceed', 'click', function(event) {
            Overlay.show(null,'Laden...');
            $("quickregisterform").submit();
        });
        var boyImg = $$(".select_gender.boy img");
        if (boyImg.length > 0) {
            boyImg[0].observe("click", function() {$("radio-button-boy").checked=true;});
        }
        var girlImg = $$(".select_gender.girl img");
        if (girlImg.length > 0) {
            girlImg[0].observe("click", function() {$("radio-button-girl").checked=true;});
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
 
 


<!-- uberCMS: Took 0.082617044448853 to output this page -->

</body></html>