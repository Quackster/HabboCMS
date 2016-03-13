<?php

class Users
{
	/**************************************************************************************************/
	
	private static $userCache = Array();

	/**************************************************************************************************/

	private static $blockedNames = Array('admin', 'administrator', 'mod', 'moderator', 'guest', 'undefined');
	private static $blockedNameParts = Array('moderate', 'staff', 'manage', 'system', 'admin', 'uber');
	
	/**************************************************************************************************/
	
	public static function isValidEmail($email = '')
	{
		return preg_match('/^[a-z0-9_\.-]+@([a-z0-9]+([\-]+[a-z0-9]+)*\.)+[a-z]{2,7}$/i', $email);
	}
	
	public static function isValidName($nm = '')
	{
		if(preg_match('/^[a-z0-9]+$/i', $nm) && strlen($nm) >= 1 && strlen($nm) <= 32)
			return true;
		return false;
	}
	
	public static function isNameTaken($nm = '')
	{
		global $db;
		return (($db->NumRows($db->Query('SELECT null FROM users WHERE username = "' . $nm . '" LIMIT 1')) > 0) ? true : false);
	}
	
	public static function idExists($id = 0)
	{
		global $db;
		return (($db->NumRows($db->Query('SELECT null FROM users WHERE id = "' . $id . '" LIMIT 1')) > 0) ? true : false);
	}
	
	public static function isNameBlocked($nm = '')
	{	
		foreach(self::$blockedNames as $bl) {
			if(strtolower($nm) == strtolower($bl)) {
				return true;
			}
		}
		
		foreach(self::$blockedNameParts as $bl) {
			if(strpos(strtolower($nm), strtolower($bl)) !== false) {
				return true;
			}
		}
		
		return false;
	}	
	
	/**************************************************************************************************/
	
	public static function add($username = '', $passwordHash = '', $email = 'default@localhost', $rank = 1, $figure = '', $sex = 'M')
	{
		global $db;
		$db->Query('INSERT INTO users (username, password, mail, auth_ticket, rank, look, gender, motto, credits, activity_points, last_online, account_created)
					VALUES ("' . $username . '", "' . $passwordHash . '", "' . $email . '", "", "' . $rank . '", "' . $figure . '", "' . $sex . '", "", "500", "1000", "", "' . date('d-M-Y') . '")');		
		$id = intval($db->Result($db->Query('SELECT id FROM users WHERE username = "' . $username . '" ORDER BY id DESC LIMIT 1'), 0));
		$db->Query('INSERT INTO user_info (user_id, bans, cautions, reg_timestamp, login_timestamp, cfhs, cfhs_abusive)
					VALUES ("' . $id . '", "0", "0", "' . time(). '", "' . time() . '", "0", "0")');
		return $id;
	}
	
	public static function delete($id)
	{
		global $db;
		$db->Query('DELETE FROM messenger_friendships WHERE user_one_id = "' . $id . '" OR user_two_id = "' . $id . '"');
		$db->Query('DELETE FROM messenger_requests WHERE to_id = "' . $id . '" OR from_id = "' . $id . '"');
		$db->Query('DELETE FROM users WHERE id = "' . $id . '" LIMIT 1');
		$db->Query('DELETE FROM user_subscriptions WHERE user_id = "' . $id . '"');
		$db->Query('DELETE FROM user_info WHERE user_id = "' . $id . '" LIMIT 1');
		$db->Query('DELETE FROM user_items WHERE user_id = "' . $id . '"');
	}
	
	/**************************************************************************************************/
	
	public static function validateUser($username, $password)
	{
		global $db;
		return $db->NumRows($db->Query('SELECT null FROM users WHERE username = "' . $username . '" AND password = "' . $password . '" LIMIT 1'));
	}
	
	public static function validateUserByEmail($email, $password)
	{
		global $db;
		if($rows = $db->NumRows($db->Query('SELECT null FROM users WHERE mail = "' . $email . '" AND password = "' . $password . '" LIMIT 1'))) {
			return $db->NumRows($db->Query('SELECT null FROM users WHERE mail = "' . $email . '"'));
		} else {
			return $rows;
		}
	}
	
	public static function validateLogin($user_mail, $password)
	{
		if($user = self::validateUser($user_mail, $password)) {
			return array(1, 0, 1);
		} elseif($emails = self::validateUserByEmail($user_mail, $password)) {
			return array(1, 1, $emails);
		} else {
			return array(0, null, null);
		}
	}
	
	/**************************************************************************************************/
	
	public static function name2id($username = '')
	{
		global $db;
		return @intval($db->Result($db->Query('SELECT id FROM users WHERE username = "' . $username . '" LIMIT 1'), 0));
	}
	
	public static function id2name($id = -1)
	{
		global $db;
		if(isset(self::$userCache[$id]['username'])) {
			return self::$userCache[$id]['username'];
		}
	
		$name = $db->Result($db->Query('SELECT username FROM users WHERE id = "' . $id . '" LIMIT 1'), 0);
		self::$userCache[$id]['username'] = $name;
		return $name;
	}	
	
	public static function email2id($email = '')
	{
		global $db;
		return @intval($db->Result($db->Query('SELECT id FROM users WHERE mail = "' . $email . '" LIMIT 1'), 0));
	}
	
	/**************************************************************************************************/
	
	public static function cacheUser($id)
	{
		global $db;
		$data = $db->FetchAssoc($db->Query('SELECT * FROM users WHERE id = "' . $id . '" LIMIT 1'));
		
		foreach($data as $key => $value) {
			self::$userCache[$id][$key] = $value;
		}
	}
	
	public static function getUserVar($id, $var, $allowCache = true)
	{
		global $db;
		if($allowCache && isset(self::$userCache[$id][$var])) {
			return self::$userCache[$id][$var];
		}
		
		$val = @$db->Result($db->Query('SELECT ' . $var . ' FROM users WHERE id = "' . $id . '" LIMIT 1'), 0);
		self::$userCache[$id][$var] = $val;
		return $val;
	}
	
	public static function formatUsername($id, $link = true, $styles = true)
	{
		global $db;
		$datas = $db->Query('SELECT id, rank, username FROM users WHERE id = "' . $id . '" LIMIT 1');
		
		if($db->NumRows($datas) == 0) {
			return '<s>Unknown User</s>';
		}
		
		$data = $db->FetchAssoc($datas);
		
		$prefix = '';
		$name = $data['username'];
		$suffix = '';
		
		if($link) {
			$prefix .= '<a href="/user/' . clean($data['username']) . '">';
			$suffix .= '</a>';
		}
		
		if($styles) {
			$rank = self::getRank($id);
			
			$rankData = $db->Query('SELECT prefix, suffix FROM ranks WHERE id = "' . $rank . '" LIMIT 1');
			
			if($db->NumRows($rankData) == 1)
			{
				$rankData = $db->FetchAssoc($rankData);
				
				$prefix .= $rankData['prefix'];
				$suffix .= $rankData['suffix'];
			}
		}
		
		return clean($prefix . $name . $suffix, true);
	}
	
	/**************************************************************************************************/

	public static function getRank($id)
	{
		return self::GetUserVar($id, 'rank');
	}
	
	public static function getRankVar($rankId, $var)
	{
		global $db;
		return $db->Result($db->Query('SELECT ' . $var . ' FROM ranks WHERE id = "' . intval($rankId) . '" LIMIT 1'), 0);
	}
	
	public static function getRankName($rankId)
	{
		return self::GetRankVar($rankId, 'name');
	}
	
	public static function hasFuse($id, $fuse)
	{
		global $db;
		if($db->NumRows($db->Query('SELECT null FROM fuserights WHERE rank <= "' . self::getRank($id) . '" AND fuse = "' . $fuse . '" LIMIT 1')) == 1) {
			return true;
		}
		return false;
	}
	
	/**************************************************************************************************/
	
	public static function getFriendCount($id, $onlineOnly = false)
	{
		global $db;
		$i = 0;
		$q = $db->Query('SELECT user_two_id FROM messenger_friendships WHERE user_one_id = "' . $id . '"');
		
		while($friend = $db->FetchAssoc($q)) {
			if($onlineOnly) {
				$isOnline = $db->Result($db->Query('SELECT online FROM users WHERE id = "' . $friend['user_two_id'] . '" LIMIT 1'), 0);
				
				if($isOnline == 1) {
					$i++;
				}
			} else {
				$i++;
			}
		}
		return $i;
	}
	
	/**************************************************************************************************/

	public static function getBadgeSlot($id, $slot)
	{
		global $db;
		$badge = '';
		$q = $db->Query('SELECT * FROM user_badges WHERE user_id = "' . $id . '" AND badge_slot = "' . $slot . '" LIMIT 1');
		if($db->NumRows($q) == 0) {
			$badge = 'WHY';
		} else {
			while($a = $db->FetchAssoc($q)) {
				$badge = $a['badge_id'];
			}
		}
		
		return $badge;
	}
	
	public static function checkSSO($id)
	{
        global $db;

		if(strlen(self::getUserVar($id, 'auth_ticket')) <= 3) {
			$db->Query('UPDATE users SET auth_ticket = "' . Core::GenerateTicket(self::GetUserVar($id, 'username')) . '" WHERE id = "' . $id . '" LIMIT 1');
		}
	}
	
	/**************************************************************************************************/
	
	public static function getCredits($id)
	{
		return self::getUserVar($id, 'credits');
	}
	
	public static function setCredits($id, $newAmount)
	{
		global $db;
		
		$db->Query('UPDATE users SET credits = "' . $newAmount . '" WHERE id = "' . $id . '" LIMIT 1');
		Core::mus('updateCredits:' . $id);
	}
	
	public static function giveCredits($id, $amount)
	{
		self::setCredits($id, (self::getCredits($id) + $amount));
		Core::mus('updateCredits:' . $id);
	}
	
	public static function takeCredits($id, $amount)
	{
		self::setCredits($id, (self::getCredits($id) - $amount));
		Core::mus('updateCredits:' . $id);
	}	
	
	public static function renderHabboImage($id, $size = 'b', $dir = 2, $head_dir = 3, $action = 'wlk', $gesture = 'sml')
	{
		$look = self::getUserVar($id, 'look');
		
		return 'http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=' . $look . '&size=' . $size . '&action=' . $action . ',&gesture=' . $gesture . '&direction=' . $dir . '&head_direction=' . $head_dir;
	}
	
	public static function getClubDays($id)
	{
		global $db;
		
		$sql = $db->Query('SELECT timestamp_activated, timestamp_expire FROM user_subscriptions WHERE subscription_id = "habbo_club" AND user_id = "' . $id . '" LIMIT 1');
		
		if($db->NumRows($sql) == 0) {
			return 0;
		}
		
		$data = $db->FetchAssoc($sql);
		$diff = $data['timestamp_expire'] - time();
		
		if($diff <= 0) {
			return 0;
		}
		
		return ceil($diff / 86400);
	}
	
	public static function hasClub($id)
	{
		return self::getClubDays($id) > 0 ? true : false;
	}
	
	/**************************************************************************************************/
	
	public static function isUserBanned($name)
	{
		if(self::GetBan('user', $name, true) != null) {
			return true;
		}
		return false;
	}
	
	public static function isIpBanned($ip)
	{
		if(self::GetBan('ip', $ip, true) != null) {
			return true;
		}
		return false;
	}
	
	public static function is_Online($userId)
	{
		global $db;
		
		$result = $db->Query('SELECT online FROM users WHERE id = "' . $userId . '" LIMIT 1');
		$row = $db->FetchAssoc($result);
		return $row['online'];
	}
	
	public static function getBan($type, $value, $mustNotBeExpired = false)
	{
		global $db;
		
		$q = 'SELECT * FROM bans WHERE bantype = "' . $type . '" AND value = "' . $value . '"';
		if($mustNotBeExpired) {
			$q .= ' AND expire > ' . time();
		}
		$q .= ' LIMIT 1';
		
		$get = $db->Query($q);
		
		if($db->NumRows($get) > 0) {
			return $db->FetchAssoc($get);
		}
		return null;
	}	
	
	/**************************************************************************************************/
	
	public static function getUserTags($userId)
	{
		global $db;
		
		$tagsArray = Array();
		$data = $db->Query('SELECT id, tag FROM user_tags WHERE user_id = "' . $userId . '"');
		
		while($tag = $db->FetchAssoc($data)) {
			$tagsArray[$tag['id']] = $tag['tag'];
		}
		
		return $tagsArray;
	}
	
	public static function friendShipExist($useroneid, $usertwoid)
	{
		global $db;
		
		$q = $db->Query('SELECT user_two_id FROM messenger_friendships WHERE user_one_id = "' . $useroneid . '" AND user_two_id = "' . $usertwoid . '"');
		if($db->NumRows($q) > 0) {
			return true;
		}
		return false;
	}
}
