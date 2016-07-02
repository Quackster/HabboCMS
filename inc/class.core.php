<?php

class Core
{
	public static $config;
	public $execStart;
	
	public function __construct()
	{
		$this->execStart = microtime(true);
	}	
	
	public static function checkBetaKey($keyCode)
	{
		global $db;

		$getKeys = $db->prepare('SELECT null FROM betakeys WHERE keyc=:keyCode AND qty > 0 LIMIT 1');
        $getKeys->bindValue(':keyCode', FilterInputString(($keyCode)));
        $getKeys->execute();

        if($getKeys->rowCount() > 0) {
            return true;
        }
        return false;
	}
	
	public static function eatBetaKey($keyCode)
	{
		global $db;

        $eatKey = $db->prepare('UPDATE betakeys SET qty = qty - 1 WHERE keyc=:keyCode LIMIT 1');
        $eatKey->bindValue(':keyCode', FilterInputString(($keyCode)));
        $eatKey->execute();
    }
	
	public static function checkCookies()
	{
		global $db;
		
		if(LOGGED_IN) {
			return;
		}
		
		if(isset($_COOKIE['rememberme']) && $_COOKIE['rememberme'] == 'true' && isset($_COOKIE['rememberme_token']) && isset($_COOKIE['rememberme_name']))
		{
			$name = filter($_COOKIE['rememberme_name']);
			$token = filter($_COOKIE['rememberme_token']);

            $find = $db->prepare('SELECT id, username FROM users WHERE username=:username AND password=:token LIMIT 1');
            $find->bindValue(':username', $name);
            $find->bindValue(':token', $token);
            $find->execute();

			if($find->rowCount() > 0)
			{
                $data = $find->fetchAll();
				
				$_SESSION['UBER_USER_N'] = $data['username'];
				$_SESSION['UBER_USER_H'] = $token;
				$_SESSION['set_cookies'] = true; // renew cookies
				
				header('Location: ' . WWW . '/security_check');
				exit;
			}
		}
	}
	
	public static function FormatDate()
	{
		return date('j F Y, h:i:s A');
	}
	
	public static function Hash($input = '')
	{
		return sha1($input . self::$config['Site']['hash_secret']);
	}
	
	public static function GenerateTicket($seed = '')
	{
		$ticket = 'ST-';
		$ticket .= sha1($seed . 'Uber' . rand(118,283));
		$ticket .= '-' . rand(100, 255);
		$ticket .= '-uber-fe' . rand(0, 5);
		
		return $ticket;
	}
	
	public static function FilterInputString($strInput = '')
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
	
	public static function cleanStringForOutput($strInput = '', $ignoreHtml = false, $nl2br = false)
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
        <body style="background: #000;">
            <div style="height:50px;"></div>
            <div style="width:500px; height:200px; margin-left:auto; margin-right:auto; color:#fff; background: url('/images/error.png') no-repeat;">
                <div style="margin-left:170px;">
                    <h1><?php echo $title; ?></h1>
                    <br />
                    <br />
                    <h3><?php echo $text; ?></h3>
                </div>
            </div>
        <?php
		exit;		
	}
	
	public static function parseConfig()
	{
		$configPath = ROOT . '/inc/inc.config.php';
		
		if(file_exists($configPath)) {
            $config = require $configPath;
		} else {
            self::systemError('Configuration Error', 'The configuration file could not be located at ' . $configPath);
            exit;
        }
		
		if(!isset($config)) {
			self::systemError('Configuration Error', 'The configuration file was located, but is in an invalid format. Data is missing or in the wrong format.');
            exit;
		}
		
		self::$config = $config;
		
		define('WWW', self::$config['Site']['www']);
	}
	
	public static function getSystemStatusString($statsFig)
	{
		switch(self::getSystemStatus())
		{
			case 2:
			case 0:
				
				return 'Sorry, das Hotel ist offline.';
				
			case 1:
				
				if(!$statsFig) {
					return self::GetUsersOnline() . ' User online';
				} else {
					return '<span class="stats-fig">' . self::GetUsersOnline() . '</span> user(s) online!';
				}
				
			default:
				
				return 'Unknown';
		}
	}
	
	public static function getSystemStatus()
	{
		global $db;

        $valueQuery = $db->prepare('SELECT status FROM server_status LIMIT 1');
        $valueQuery->execute();

        $valueData = $valueQuery->fetchAll();
        $value = (int) $valueData[0]['status'];

        return $value;
	}
	
	public static function getUsersOnline()
	{
		global $db;

        $valueQuery = $db->prepare('SELECT users_online FROM server_status LIMIT 1');
        $valueQuery->execute();

        $valueData = $valueQuery->fetchAll();
        $value = (int) $valueData[0]['users_online'];

        return $value;
	}
	
	public static function getMaintenanceStatus()
	{
		global $db;

        $valueQuery = $db->prepare('SELECT maintenance FROM site_config LIMIT 1');
        $valueQuery->execute();

        $valueData = $valueQuery->fetchAll();
        $value = $valueData[0]['maintenance']; // TODO: Does it return bool, int or what?

        return $value;
	}
	
	public static function getVar($data)
	{
		global $db;

        $getValueQuery = $db->prepare('SELECT sval FROM external_variables WHERE skey=:keydata LIMIT 1');
        $getValueQuery->bindValue(':keydata', $data);
        $getValueQuery->execute();

        $valueData = $getValueQuery->fetchAll();
        $value = $valueData[0]['sval'];

        return $value;
    }
	
	public static function setVar($data, $row)
	{
		global $db;

        $setValueQuery = $db->prepare('UPDATE external_variables SET sval=:info WHERE skey=:row LIMIT 1');
        $setValueQuery->bindValue(':info', $data);
        $setValueQuery->bindValue(':row', $row);
        $setValueQuery->execute();
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

        $setValueQuery = $db->prepare('INSERT INTO bans (id, bantype, value, reason, expire, added_by, added_date, appeal_state) VALUES (NULL, :bantype, :value, :reason, :expireTime, :addedBy, :addedDate, :blockAppeal)');
        $setValueQuery->bindValue(':bantype', $type);
        $setValueQuery->bindValue(':value', $value);
        $setValueQuery->bindValue(':reason', $reason);
        $setValueQuery->bindValue(':expireTime', $expireTime);
        $setValueQuery->bindValue(':addedBy', $addedBy);
        $setValueQuery->bindValue(':addedDate', date('d/m/Y H:i'));
        $setValueQuery->bindValue(':blockAppeal', ($blockAppeal) ? '0' : '1');
        $setValueQuery->execute();
	}
}
