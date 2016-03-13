            <div id="overlay"></div>
            <div id="change-password-form" style="display: none;">
                <div id="change-password-form-container" class="clearfix">
                    <div id="change-password-form-title" class="bottom-border">Passwort vergessen?</div>
                    <div id="change-password-form-content" style="display: none;">
                        <form method="POST" action="%www%/account/password/identityResetForm" id="forgotten-pw-form">
                            <input type="hidden" name="page" value="/?changePwd=true" />
                            <span>Gib bitte die E-mail Adresse deines Orbitrons Accounts ein:</span>
                            <div id="email" class="center bottom-border">
                                <input type="text" id="change-password-email-address" name="emailAddress" value="" class="email-address" maxlength="48" />
                                <div id="change-password-error-container" class="error" style="display: none;">
                                    Bitte gebe eine korrekte E-Mail Adresse ein
                                </div>
                            </div>
                        </form>
                        <div class="change-password-buttons">
                            <a href="#" id="change-password-cancel-link">Abbrechen</a>
                            <a href="#" id="change-password-submit-button" class="new-button"><b>Email senden</b><i></i></a>
                        </div>
                    </div>
                    <div id="change-password-email-sent-notice" style="display: none;">
                        <div class="bottom-border">
                            <span>Hey! Wir haben dir gerade eine E-mail geschickt. Darin findest du einen Link, mit dem du dein Passwort zur&uuml;cksetzen kannst. Denk daran, auch in deinem Spam Ordner nachzusehen!</span>
                            <div id="email-sent-container"></div>
                        </div>
                        <div class="change-password-buttons">
                            <a href="#" id="change-password-change-link">Zur&uuml;ck</a>
                            <a href="#" id="change-password-success-button" class="new-button"><b>Schliessen</b><i></i></a>
                        </div>
                    </div>
                </div>
                <div id="change-password-form-container-bottom"></div>
            </div>
            <script type="text/javascript">
                HabboView.add(function(){
                    ChangePassword.init();
                });
            </script>
            <style type="text/css">
                #footer .footer-links a {
                    color: white;
                    font-size:14px;
                }

                #footer #compact-tags-container span, #footer #compact-tags-container a {
                    color: #333;
                }

                #loginerrorfield {
                    width: 100%;
                    height: 100%;
                    background-color: #C00;
                    color: white;
                }
                #loginerrorfieldwrapper {
                    height: 35px;
                }
            </style>
            <div id="site-header">
                <form id="loginformitem" name="loginformitem" action="%www%/account/submit" method="POST">
                    %login_result%
                    <div style="clear: both;"></div>
                    <div id="site-header-content">
                        <div id="habbo-logo" style="background: url('%www%/web-gallery/v2/images/habbologo_whiteR.gif') no-repeat;">
                        </div>
                        <div id="login-form">
                            <div id="login-form-email">
                                <label for="login-username" class="login-text">Email</label>
                                <input tabindex="3" type="text" class="login-field" name="credentials.username" id="login-username" value="" maxlength="48" />
                                <input tabindex="6" type="checkbox" name="_login_remember_me" id="login-remember-me" value="true" />
                                <label for="login-remember-me">Angemeldet bleiben</label>
                                <div id="landing-remember-me-notification" class="bottom-bubble" style="display: none;">
                                    <div class="bottom-bubble-t">
                                        <div>
                                        </div>
                                    </div>
                                    <div class="bottom-bubble-c">
                                        Wenn du diese Option aktivierst, bleibst Du im HoBBo Hotel eingeloggt bis Du auf "ausloggen" klickst.
                                    </div>
                                    <div class="bottom-bubble-b">
                                        <div></div>
                                    </div>
                                </div>
                            </div>
                            <div id="login-form-password">
                                <label for="login-password" class="login-text">Passwort</label>
                                <input tabindex="4" type="password" class="login-field" name="credentials.password" id="login-password" maxlength="32" />
                                <div id="login-forgot-password">
                                    <a href="#" id="forgot-password"><span>Passwort vergessen?</span></a>
                                </div>
                            </div>
                            <div id="login-form-submit">
                                <input type="submit" value="Login" class="login-top-button" id="login-submit-button" />
                                <a href="#" tabindex="6" id="login-submit-new-button"><span>Login</span></a>
                            </div>
                        </div>
                        <div id="rpx-login">
                            <div>
                                <div id="fb-root">
                                </div>
                                <script type="text/javascript">
                                    window.fbAsyncInit = function(){
                                        Cookie.erase("fbsr_163085011898");
                                        FB.init({appId: '163085011898', status: true, cookie: true, xfbml: true, oauth: true});
                                        $(document).fire("fbevents:scriptLoaded");
                                    };

                                    window.assistedLogin = function(FBobject, optresponse){
                                        Cookie.erase("fbsr_163085011898");
                                        FBobject.init({appId: '163085011898', status: true, cookie: true, xfbml: true, oauth: true});
                                        permissions = 'user_birthday,email';
                                        defaultAction = function(response){
                                            if (response.authResponse){
                                                fbConnectUrl = "/facebook?connect=true";
                                                Cookie.erase("fbhb_val_163085011898");
                                                Cookie.set("fbhb_val_163085011898", response.authResponse.accessToken);
                                                Cookie.erase("fbhb_expr_163085011898");
                                                Cookie.set("fbhb_expr_163085011898", response.authResponse.expiresIn);
                                                window.location.replace(fbConnectUrl);
                                            }
                                        };
                                        if (typeof optresponse == 'undefined') FBobject.login(defaultAction, {scope:permissions});
                                        else FBobject.login(optresponse, {scope:permissions});
                                    };
                                    (function(){
                                        var e = document.createElement('script');
                                        e.async = true;
                                        e.src = document.location.protocol + '//connect.facebook.net/de_DE/all.js';
                                        document.getElementById('fb-root').appendChild(e);
                                    }());
                                </script>
                                <a class="fb_button fb_button_large" onclick="assistedLogin(FB); return false;" style="padding-bottom:2px;">
                                    <span class="fb_button_text" style="color:white; cursor:pointer;">Facebook Login</span>
                                </a>
                                <hr>
                            </div>
                            <div>
                                <div id="rpx-signin">
                                    <a class="rpxnow" onclick="return false;" href="https://login.habbo.com/openid/v2/signin?token_url=http%3A%2F%2Fwww.habbo.de/rpx">Alternative Anmeldemöglichkeiten</a>
                                </div>
                            </div>
                        </div>
                        <noscript>
                            <div id="alert-javascript-container">
                                <div id="alert-javascript-title">JavaScript wird nicht unterstützt</div>
                                <div id="alert-javascript-text">Javascript ist in deinem Browser deaktiviert. Bitte aktiviere Javascript oder benutze einen Javascript fähigen Browser um Habbo zu spielen.</div>
                            </div>
                        </noscript>
                        <div id="alert-cookies-container" style="display:none">
                            <div id="alert-cookies-title">Fehlende Cookie  Unterstützung</div>
                            <div id="alert-cookies-text">Cookies sind in ihrem Browser deaktiviert. Bitte aktiviere Cookies um Habbo zu benutzen.</div>
                        </div>
                        <script type="text/javascript">
                            document.cookie = "habbotestcookie=supported";
                            var cookiesEnabled = document.cookie.indexOf("habbotestcookie") != -1;
                            if (cookiesEnabled){
                                var date = new Date();
                                date.setTime(date.getTime()-24*60*60*1000);
                                document.cookie="habbotestcookie=supported; expires="+date.toGMTString();
                            }
                            else{
                                $('alert-cookies-container').show();
                            }
                        </script>
                        <script type="text/javascript">
                            HabboView.add(function() {
                                LandingPage.init();
                                if (!LandingPage.focusForced) {
                                    LandingPage.fieldFocus('login-username');
                                }
                            });
                        </script>
                    </div>
                </form>
            </div>
            <div id="fp-container">
                <div id="content">
                    <div id="column1" class="column">
                        <div class="habblet-container">
                            <div style="width: 890px; margin: 0 auto">
                                <div id="geotargeting" style="font-size: 24px;">Treffe neue und alte Freunde im %fullName%</div>
                            </div>
                        </div>
                        <div id="frontpage-image-container">
                            <div id="join-now-button-container">
                                <div id="join-now-button-wrapper-fb">
                                    <div class="join-now-alternative">
                                        &nbsp;
                                    </div>
                                    <div class="join-now-button">
                                        <a class="join-now-link" href="#" onclick="assistedLogin(FB); return false;">
                                            <span class="join-now-text-big">Play Buzz</span>
                                            <span class="join-now-text-small">with<span class="fbword">Facebook</span></span>
                                        </a>
                                        <span class="close"></span>
                                    </div>
                                </div>
                                <div id="join-now-button-wrapper">
                                    <div class="join-now-alternative">
                                        <a href="%www%/quickregister/start" class="newusers" id="newusers" onclick="startRegistration(this); return false;"><b>Neu bei Habbo?</b>,<span style="color: #8f8f8f;">hier klicken</span></a>
                                    </div>
                                    <div class="join-now-button">
                                        <a class="join-now-link" id="register-link" href="%www%/quickregister/start" onclick="startRegistration(this); return false;">
                                            <span class="join-now-text-big">Kostenlos</span>
                                            <span class="join-now-text-small">Registrieren</span>
                                        </a>
                                        <span class="close"></span>
                                    </div>
                                    <div class="join-now-alternative">
                                        <a class="fbicon" id="fbicon" href="#" onclick="assistedLogin(FB); return false;">Habbo mit Facebook starten</a>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                function startRegistration(elem) {
                                    targetUrl = elem.href;
                                    if (typeof targetUrl == "undefined") {
                                        targetUrl = "%www%/quickregister/start";
                                    }
                                    window.location.href = targetUrl;
                                }
                            </script>
                            <div id="people-inside">
                                <b><span><span class="stats-fig">%users_online%</span> User sind online</span></b>
                                <i></i>
                            </div>
                            <a href="/quickregister/start" id="frontpage-image" style="background-image: url('%www%/swf/c_images/Frontpage_images/hotel_view_low_HW2010_001.png'); background-position:0% 25%; " onclick="startRegistration(this); return false;"></a>
                        </div>
                        <script type="text/javascript">
                            document.observe("dom:loaded", function() {
                                LandingPage.checkLoginButtonSetTimer();
                            });
                        </script>
                    </div>
                    <script type="text/javascript">
                        if(!$(document.body).hasClassName('process-template')) {
                            Rounder.init();
                        }
                    </script>
                </div>
                <!--[if lt IE 7]>
                <script type="text/javascript">
                    Pngfix.doPngImageFix();
                </script>
                <![endif]-->
            </div>
        </div>
        <div id="footer" class="new_and_improved">
            <p class="footer-links">
                <a href="community/staff">Staffs</a> |
                <a href="http://www.lukadora.de/?page_id=258">&Uuml;ber Ultimate</a> |
                <a href="%www%/papers/termsAndConditions" target="_new">Nutzungsbedingungen</a> |
                <a href="%www%/papers/privacy" target="_new">Datenschutzerkl&auml;rung</a>|
                <a href="http://www.lukadora.de/?page_id=14" target="_new">Forum</a>
            </p>
            %copy%
            <?php

            global $db;

            $getStreams = $db->prepare('SELECT * FROM users WHERE rank < 3 ORDER BY credits DESC LIMIT 20');
            $getStreams->execute();

            if($getStreams->rowCount() > 0) {
                echo '<div id="compact-tags-container">
                                <span class="tags-habbos-like" style="color:#888;">Die reichsten User..</span>
                                    <ul class="tag-list" style="color:#777;">';
            }

            $getStreams = $getStreams->fetchAll();
            foreach($getStreams as $a) {
                ?>
                <li><a style="cursor:pointer" onclick="alert('Username: <?php echo $a['username']; ?>\r\nTaler: <?php echo $a['credits']; ?>');"><?php echo $a['username']; ?></a></li>
                <?php
            }
            echo '</ul></div>';

            ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    if (typeof HabboView != "undefined") {
        HabboView.run();
    }
</script>
<script src="%www%/web-gallery/js/rpx.js" type="text/javascript"></script>
<script type="text/javascript">
    RPXNOW.overlay = false;
    RPXNOW.language_preference = 'de';
    var flags =  'show_provider_list';
    RPXNOW.flags = flags.split(',');
</script>
<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-448325-18']);
    _gaq.push (['_gat._anonymizeIp']);
    _gaq.push(['_trackPageview']);
    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
</script>
<!-- Start Quantcast tag -->
<script type="text/javascript">
    _qoptions={
        qacct:"p-b5UDx6EsiRfMI"
    };
</script>
<script type="text/javascript" src="%www%/web-gallery/js/quant.js"></script>
<noscript>
    <img src="%www%/web-gallery/v2/images/p-b5UDx6EsiRfMI.gif" style="display: none;" border="0" height="1" width="1" alt="Quantcast"/>
</noscript>
<!-- End Quantcast tag -->

<!-- HL-23485 -->
<!-- SZM VERSION="1.5" -->
<!-- rewritten tag for Prototype.js and modern browsers (we don't support legacy browsers) -->
<script type="text/javascript">
    Event.observe(window,'load',function() {
        var img = document.createElement('img');
        var code = location.pathname.split('/')[1]; if (code == '') code = 'frontpage';
        var IVW = 'http://habbo.ivwbox.de/cgi-bin/ivw/CP/' + code;
        img.src = IVW+'?r='+escape(document.referrer)+'&d='+Math.floor(Math.random()*100000);
        document.body.appendChild(img);
    });
</script>
<!-- /SZM -->

<!-- HL-30554 -->
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 1057383532;
    var google_conversion_language = "en";
    var google_conversion_format = "3";
    var google_conversion_color = "ffffff";
    var google_conversion_label = "-Y5SCLif5AIQ7MiZ-AM";
    var google_conversion_value = 0;
    /* ]]> */
</script>
<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1057383532/?label=-Y5SCLif5AIQ7MiZ-AM&amp;guid=ON&amp;script=0"/>
    </div>
</noscript>
