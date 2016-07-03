<?php

class Core
{
    public static $config;
    public static $execStart;

    public static function startExecTime()
    {
        self::$execStart = microtime(true);
    }

    public static function checkBetaKey($keyCode)
    {
        global $db;

        $getKeys = $db->prepare('SELECT NULL FROM `betakeys` WHERE `keyc`=:keyCode AND `qty` > 0 LIMIT 1');
        $getKeys->execute(array(
            ':keyCode' => Core::filterInputString(($keyCode)),
        ));

        if ($getKeys->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public static function eatBetaKey($keyCode)
    {
        global $db;

        $eatKey = $db->prepare('UPDATE `betakeys` SET `qty` = `qty` - 1 WHERE `keyc`=:keyCode LIMIT 1');
        $eatKey->execute(array(
            ':keyCode' => Core::filterInputString(($keyCode)),
        ));
    }

    public static function checkCookies()
    {
        global $db;

        if (LOGGED_IN) {
            return;
        }

        if (isset($_COOKIE['rememberme']) && $_COOKIE['rememberme'] == 'true' && isset($_COOKIE['rememberme_token']) && isset($_COOKIE['rememberme_name'])) {
            $name = Core::filterInputString($_COOKIE['rememberme_name']);
            $token = Core::filterInputString($_COOKIE['rememberme_token']);

            $find = $db->prepare('SELECT `id`,`username` FROM `users` WHERE `username`=:username AND `password`=:token LIMIT 1');
            $find->execute(array(
                ':username' => $name,
                ':token'    => $token,
            ));

            if ($find->rowCount() > 0) {
                $data = $find->fetch(PDO::FETCH_ASSOC);
                var_dump($data);
                $_SESSION['UBER_USER_N'] = $data['username'];
                $_SESSION['UBER_USER_H'] = $token;
                $_SESSION['set_cookies'] = true; // renew cookies

                header('Location: ' . WWW . '/security_check');
                exit;
            }
        }
    }

    public static function formatDate()
    {
        return date('j F Y, h:i:s A');
    }

    public static function hash($input = '')
    {
        return sha1($input . self::$config['Site']['hash_secret']);
    }

    public static function generateTicket($seed = '')
    {
        $ticket = 'ST-';
        $ticket .= sha1($seed . 'Hotel' . rand(118, 283));
        $ticket .= '-' . rand(100, 255);
        $ticket .= '-hotel-fe' . rand(0, 5);

        return $ticket;
    }

    public static function filterInputString($strInput)
    {
        return stripslashes(trim($strInput));
    }

    public static function filterSpecialChars($strInput, $allowLB = false)
    {
        $strInput = str_replace(chr(1), ' ', $strInput);
        $strInput = str_replace(chr(2), ' ', $strInput);
        $strInput = str_replace(chr(3), ' ', $strInput);
        $strInput = str_replace(chr(9), ' ', $strInput);

        if (!$allowLB) {
            $strInput = str_replace(chr(13), ' ', $strInput);
        }

        return $strInput;
    }

    public static function cleanStringForOutput($strInput, $ignoreHtml = false, $nl2br = false)
    {
        $strInput = stripslashes(trim($strInput));

        if (!$ignoreHtml) {
            $strInput = htmlentities($strInput);
        }
        if ($nl2br) {
            $strInput = nl2br($strInput);
        }

        return $strInput;
    }

    public static function checkAll()
    {
        return '<p class="copyright">OrbitronDev Habbo Retro, Copyright &copy; 2010 - 2016 OrbitronDev.org - powered by OrbitronDev. All Rights reserved.</p>';
    }

    public static function systemError($title, $text)
    {
        ?>
        <style type="text/css" scoped>
            * {
                font-family: Consolas, "Liberation Mono", Menlo, Courier, monospace;
                color: white;
            }

            body {
                background: #000;
            }

            .error-content {
                padding-top: 100px;
                margin: 0 auto;
                text-align: center;
            }
        </style>
        <div class="error-content">
            <h1><img src="/web-gallery/error.png" height="23" width="23"/> <?php echo $title; ?></h1>
            <h3><?php echo $text; ?></h3>
        </div>
        <?php
        exit;
    }

    public static function parseConfig()
    {
        $configPath = ROOT . '/inc/inc.config.php';

        if (file_exists($configPath)) {
            $config = require $configPath;
        } else {
            self::systemError('Configuration Error', 'The configuration file could not be located at ' . $configPath);
            exit;
        }

        if (!isset($config)) {
            self::systemError('Configuration Error',
                'The configuration file was located, but is in an invalid format. Data is missing or in the wrong format.');
            exit;
        }

        self::$config = $config;

        define('WWW', self::$config['Site']['www']);
    }

    public static function getSystemStatusString($statsFig)
    {
        switch (self::getSystemStatus()) {
            case 2:
            case 0:

                return 'Sorry, das Hotel ist offline.';

            case 1:

                if (!$statsFig) {
                    return self::getUsersOnline() . ' User online';
                } else {
                    return '<span class="stats-fig">' . self::getUsersOnline() . '</span> user(s) online!';
                }

            default:

                return 'Unknown';
        }
    }

    public static function getSystemStatus()
    {
        global $db;

        $valueQuery = $db->prepare('SELECT `status` FROM `server_status` LIMIT 1');
        $valueQuery->execute();

        $valueData = $valueQuery->fetchAll(PDO::FETCH_ASSOC);
        $value = (int)$valueData[0]['status'];

        return $value;
    }

    public static function getUsersOnline()
    {
        global $db;

        $valueQuery = $db->prepare('SELECT `users_online` FROM `server_status` LIMIT 1');
        $valueQuery->execute();

        $valueData = $valueQuery->fetchAll(PDO::FETCH_ASSOC);
        $value = (int)$valueData[0]['users_online'];

        return $value;
    }

    public static function getMaintenanceStatus()
    {
        global $db;

        $valueQuery = $db->prepare('SELECT `maintenance` FROM `site_config` LIMIT 1');
        $valueQuery->execute();

        $valueData = $valueQuery->fetchAll(PDO::FETCH_ASSOC);
        $value = $valueData[0]['maintenance']; // TODO: Does it return "bool", "int" or what?

        return $value;
    }

    public static function getVar($data)
    {
        global $db;

        $getValueQuery = $db->prepare('SELECT `sval` FROM `external_variables` WHERE `skey`=:keydata LIMIT 1');
        $getValueQuery->execute(array(
            ':keydata' => $data,
        ));

        $valueData = $getValueQuery->fetchAll(PDO::FETCH_ASSOC);
        $value = $valueData[0]['sval'];

        return $value;
    }

    public static function setVar($data, $row)
    {
        global $db;

        $setValueQuery = $db->prepare('UPDATE `external_variables` SET `sval`=:info WHERE `skey`=:row LIMIT 1');
        $setValueQuery->execute(array(
            ':info' => $data,
            ':row'  => $row,
        ));
    }

    public static function mus($header, $data = '')
    {
        if (self::$config['MUS']['enabled'] == false || self::getSystemStatus() == 0) {
            return;
        }

        $musData = $header . chr(1) . $data;

        $sock = @socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp'));
        @socket_connect($sock, self::$config['MUS']['ip'], intval(self::$config['MUS']['port']));
        @socket_send($sock, $musData, strlen($musData), MSG_DONTROUTE);
        @socket_close($sock);
    }

    public static function addBan($type, $value, $reason, $expireTime, $addedBy, $blockAppeal)
    {
        global $db;

        $setValueQuery = $db->prepare('INSERT INTO `bans`(`id`,`bantype`,`value`,`reason`,`expire`,`added_by`,`added_date`,`appeal_state`) VALUES (NULL, :bantype, :value, :reason, :expireTime, :addedBy, :addedDate, :blockAppeal)');
        $setValueQuery->execute(array(
            ':bantype'     => $type,
            ':value'       => $value,
            ':reason'      => $reason,
            ':expireTime'  => $expireTime,
            ':addedBy'     => $addedBy,
            ':addedDate'   => date('d/m/Y H:i'),
            ':blockAppeal' => ($blockAppeal) ? '0' : '1',
        ));
    }
}
