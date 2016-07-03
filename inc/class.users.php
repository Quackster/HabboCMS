<?php

class Users
{
    /**************************************************************************************************/

    private static $userCache = array();

    /**************************************************************************************************/

    private static $blockedNames = array('admin', 'administrator', 'mod', 'moderator', 'guest', 'undefined');
    private static $blockedNameParts = array('moderate', 'staff', 'manage', 'system', 'admin');

    /**************************************************************************************************/

    /**
     * @param $email
     *
     * @return int
     */
    public static function isValidEmail($email)
    {
        return preg_match('/^[a-z0-9_\.-]+@([a-z0-9]+([\-]+[a-z0-9]+)*\.)+[a-z]{2,7}$/i', $email);
    }

    /**
     * @param $username
     *
     * @return bool
     *
     */
    public static function isValidName($username)
    {
        if (preg_match('/^[a-z0-9]+$/i', $username) && strlen($username) >= 1 && strlen($username) <= 32) {
            return true;
        }
        return false;
    }

    /**
     * @param $username
     *
     * @return bool
     */
    public static function isNameTaken($username)
    {
        global $db;

        $query = $db->prepare('SELECT NULL FROM `users` WHERE `username`=:username LIMIT 1');
        $query->execute(array(
            ':username' => $username,
        ));

        $isTaken = $query->rowCount() > 0 ? true : false;
        return $isTaken;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public static function idExists($id)
    {
        global $db;

        $query = $db->prepare('SELECT NULL FROM `users` WHERE `id`=:id LIMIT 1');
        $query->execute(array(
            ':id' => $id,
        ));

        $idExists = $query->rowCount() > 0 ? true : false;

        return $idExists;
    }

    /**
     * @param string $username
     *
     * @return bool
     */
    public static function isNameBlocked($username = '')
    {
        foreach (self::$blockedNames as $bl) {
            if (strtolower($username) == strtolower($bl)) {
                return true;
            }
        }

        foreach (self::$blockedNameParts as $bl) {
            if (strpos(strtolower($username), strtolower($bl)) !== false) {
                return true;
            }
        }

        return false;
    }

    /**************************************************************************************************/

    /**
     * @param        $username
     * @param        $passwordHash
     * @param        $email
     * @param int    $rank
     * @param string $figure
     * @param string $sex
     *
     * @return mixed
     */
    public static function add($username, $passwordHash, $email, $rank = 1, $figure = '', $sex = 'M')
    {
        global $db;

        $query = $db->prepare('INSERT INTO `users`(`username`, `password`, `mail`, `auth_ticket`, `rank`, `look`,  `gender`, `motto`, `credits`, `activity_points`, `last_online`, `account_created`)
                                           VALUES (:username,  :password,  :email, NULL,          :rank,  :figure, :sex,     NULL,    500,       1000,              NULL,          :accountCreated  )');
        $query->execute(array(
            ':username'       => $username,
            ':password'       => $passwordHash,
            ':email'          => $email,
            ':rank'           => $rank,
            ':figure'         => $figure,
            ':sex'            => $sex,
            ':accountCreated' => date('d-M-Y'),
        ));

        $query = $db->prepare('SELECT `id` FROM `users` WHERE `username`=:username ORDER BY id DESC LIMIT 1');
        $query->execute(array(
            ':username' => $username,
        ));
        $id = $query->fetch(PDO::FETCH_ASSOC);
        $id = $id['id'];

        $query = $db->prepare('INSERT INTO `user_info`(`user_id`, `bans`, `cautions`, `reg_timestamp`,        `login_timestamp`, `cfhs`, `cfhs_abusive`)
					                           VALUES (:id,       0,      0,          :registationTimestamp,  :loginTimestamp,   0,      0             )');
        $query->execute(array(
            ':id'                   => $id,
            ':registationTimestamp' => time(),
            ':loginTimestamp'       => time(),
        ));

        return $id;
    }

    /**
     * @param $userId
     */
    public static function delete($userId)
    {
        global $db;

        $query = $db->prepare('DELETE FROM `messenger_friendships` WHERE `user_one_id`=:id OR `user_two_id`=:id');
        $query->execute(array(':id' => $userId));
        $query = $db->prepare('DELETE FROM `messenger_requests` WHERE `to_id`=:id OR from_id=:id');
        $query->execute(array(':id' => $userId));
        $query = $db->prepare('DELETE FROM `users` WHERE `id`=:id LIMIT 1');
        $query->execute(array(':id' => $userId));
        $query = $db->prepare('DELETE FROM `user_subscriptions` WHERE `user_id`=:id');
        $query->execute(array(':id' => $userId));
        $query = $db->prepare('DELETE FROM `user_info` WHERE `user_id`=:id LIMIT 1');
        $query->execute(array(':id' => $userId));
        $query = $db->prepare('DELETE FROM `user_items` WHERE `user_id`=:id');
        $query->execute(array(':id' => $userId));
    }

    /**************************************************************************************************/

    /**
     * @param $username
     * @param $password
     *
     * @return int
     */
    public static function validateUser($username, $password)
    {
        global $db;

        $query = $db->prepare('SELECT NULL FROM `users` WHERE `username`=:username AND `password`=:password LIMIT 1');
        $query->execute(array(
            ':username' => $username,
            ':password' => $password,
        ));

        return $query->rowCount();
    }

    /**
     * @param $email
     * @param $password
     *
     * @return int
     */
    public static function validateUserByEmail($email, $password)
    {
        global $db;

        $query = $db->prepare('SELECT NULL FROM `users` WHERE `mail`=:email AND `password`=:password LIMIT 1');
        $query->execute(array(
            ':email'    => $email,
            ':password' => $password,
        ));

        $rowCount = $query->rowCount();

        if ($rowCount) {
            $query = $db->prepare('SELECT NULL FROM `users` WHERE `mail`=:email');
            $query->execute(array(
                ':email' => $email,
            ));
            return $query->rowCount();
        } else {
            return $rowCount;
        }
    }

    /**
     * @param $user_mail
     * @param $password
     *
     * @return array
     */
    public static function validateLogin($user_mail, $password)
    {
        if ($user = self::validateUser($user_mail, $password)) {
            return array(1, 0, 1);
        } elseif ($emails = self::validateUserByEmail($user_mail, $password)) {
            return array(1, 1, $emails);
        } else {
            return array(0, null, null);
        }
    }

    /**************************************************************************************************/

    /**
     * @param $username
     *
     * @return int
     */
    public static function name2id($username)
    {
        global $db;

        $query = $db->prepare('SELECT `id` FROM `users` WHERE `username`=:username LIMIT 1');
        $query->execute(array(
            ':username' => $username,
        ));

        $queryData = $query->fetch(PDO::FETCH_ASSOC);
        $id = (int)$queryData['id'];

        return $id;
    }

    /**
     * @param $id
     *
     * @return string
     */
    public static function id2name($id)
    {
        global $db;
        if (isset(self::$userCache[$id]['username'])) {
            return self::$userCache[$id]['username'];
        }

        $query = $db->prepare('SELECT `username` FROM `users` WHERE `id`=:userId LIMIT 1');
        $query->execute(array(
            ':userId' => $id,
        ));
        $queryData = $query->fetch(PDO::FETCH_ASSOC);
        $name = (string)$queryData['username'];

        self::$userCache[$id]['username'] = $name;
        return $name;
    }

    /**
     * @param $email
     *
     * @return int
     */
    public static function email2id($email)
    {
        global $db;

        $query = $db->prepare('SELECT `id` FROM `users` WHERE `mail`=:email LIMIT 1');
        $query->execute(array(
            ':email' => $email,
        ));

        $queryData = $query->fetch(PDO::FETCH_ASSOC);
        $id = (int)$queryData['id'];

        return $id;
    }

    /**************************************************************************************************/

    /**
     * @param $id
     */
    public static function cacheUser($id)
    {
        global $db;

        $query = $db->prepare('SELECT * FROM `users` WHERE `id`=:userId LIMIT 1');
        $query->execute(array(
            ':userId' => $id,
        ));

        $queryData = $query->fetch(PDO::FETCH_ASSOC);

        foreach ($queryData as $key => $value) {
            self::$userCache[$id][$key] = $value;
        }
    }

    /**
     * @param      $id
     * @param      $var
     * @param bool $allowCache
     *
     * @return mixed
     */
    public static function getUserVar($id, $var, $allowCache = true)
    {
        global $db;
        if ($allowCache && isset(self::$userCache[$id][$var])) {
            return self::$userCache[$id][$var];
        }

        $query = $db->prepare('SELECT :var FROM `users` WHERE `id`=:userId LIMIT 1');
        $query->execute(array(
            ':var'    => $var,
            ':userId' => $id,
        ));

        $queryData = $query->fetch(PDO::FETCH_ASSOC);
        $val = $queryData[$var];

        self::$userCache[$id][$var] = $val;

        return $val;
    }

    /**
     * @param      $id
     * @param bool $link
     * @param bool $styles
     *
     * @return string
     */
    public static function formatUsername($id, $link = true, $styles = true)
    {
        global $db;

        $query = $db->prepare('SELECT `id`,`rank`,`username` FROM `users` WHERE `id`=:userId LIMIT 1');
        $query->execute(array(
            ':userId' => $id,
        ));

        if ($query->rowCount() == 0) {
            return '<s>Unknown User</s>';
        }

        $queryData = $query->fetch(PDO::FETCH_ASSOC);

        $prefix = '';
        $name = $queryData['username'];
        $suffix = '';

        if ($link) {
            $prefix .= '<a href="/user/' . Core::cleanStringForOutput($name) . '">';
            $suffix .= '</a>';
        }

        if ($styles) {
            $rank = self::getRank($id);

            $queryRank = $db->prepare('SELECT `prefix`,`suffix` FROM `ranks` WHERE `id`=:rank LIMIT 1');
            $queryRank->execute(array(
                ':rank' => $rank,
            ));

            if ($queryRank->rowCount() == 1) {
                $rankData = $queryRank->fetch(PDO::FETCH_ASSOC);

                $prefix .= $rankData['prefix'];
                $suffix .= $rankData['suffix'];
            }
        }

        return Core::cleanStringForOutput($prefix . $name . $suffix, true);
    }

    /**************************************************************************************************/

    /**
     * @param $id
     *
     * @return mixed
     */
    public static function getRank($id)
    {
        return self::getUserVar($id, 'rank');
    }

    /**
     * @param $rankId
     * @param $var
     *
     * @return mixed
     */
    public static function getRankVar($rankId, $var)
    {
        global $db;

        $query = $db->prepare('SELECT :var FROM `ranks` WHERE `id`=:rankId LIMIT 1');
        $query->execute(array(
            ':var'    => $var,
            ':rankId' => (int)$rankId,
        ));

        $queryData = $query->fetch(PDO::FETCH_ASSOC);
        $val = $queryData[$var];

        return $val;
    }

    /**
     * @param $rankId
     *
     * @return mixed
     */
    public static function getRankName($rankId)
    {
        return self::getRankVar($rankId, 'name');
    }

    /**
     * @param $id
     * @param $fuse
     *
     * @return bool
     */
    public static function hasFuse($id, $fuse)
    {
        global $db;

        $query = $db->prepare('SELECT NULL FROM `fuserights` WHERE `rank` <= :rankId AND `fuse` = :fuse LIMIT 1');
        $query->execute(array(
            ':rankId' => self::getRank($id),
            ':fuse'   => $fuse,
        ));

        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }

    /**************************************************************************************************/

    /**
     * @param      $id
     * @param bool $onlineOnly
     *
     * @return int
     */
    public static function getFriendCount($id, $onlineOnly = false)
    {
        global $db;
        $friendsCount = 0;

        $query = $db->prepare('SELECT `user_two_id` FROM `messenger_friendships` WHERE `user_one_id`=:userId');
        $query->execute(array(
            ':userId' => $id,
        ));

        $friends = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($friends as $friend) {
            if ($onlineOnly) {
                $queryIsOnline = $db->prepare('SELECT `online` FROM `users` WHERE `id`=:friendUserId LIMIT 1');
                $queryIsOnline->execute(array(
                    ':friendUserId' => $friend['user_two_id'],
                ));
                $queryData = $queryIsOnline->fetch(PDO::FETCH_ASSOC);
                $isOnline = $queryData['online'] ? true : false;

                if ($isOnline) {
                    $friendsCount++;
                }
            } else {
                $friendsCount++;
            }
        }

        return $friendsCount;
    }

    /**************************************************************************************************/

    /**
     * @param $id
     * @param $slot
     *
     * @return string
     */
    public static function getBadgeSlot($id, $slot)
    {
        global $db;

        $query = $db->prepare('SELECT * FROM `user_badges` WHERE `user_id`=:userId AND `badge_slot`=:slot LIMIT 1');
        $query->execute(array(
            ':userId' => $id,
            ':slot'   => $slot,
        ));

        if ($query->rowCount() == 0) {
            $badge = 'WHY';
        } else {
            $queryData = $query->fetch(PDO::FETCH_ASSOC);
            $badge = $queryData['badge_id'];
        }

        return $badge;
    }

    /**
     * @param $id
     */
    public static function checkSSO($id)
    {
        global $db;

        if (strlen(self::getUserVar($id, 'auth_ticket')) <= 3) {
            $query = $db->prepare('UPDATE `users` SET `auth_ticket`=:ticketId WHERE `id`=:userId LIMIT 1');
            $query->execute(array(
                ':ticketId' => Core::generateTicket(self::getUserVar($id, 'username')),
                ':userId'   => $id,
            ));
        }
    }

    /**************************************************************************************************/

    /**
     * @param $id
     *
     * @return float
     */
    public static function getCredits($id)
    {
        return (float)self::getUserVar($id, 'credits');
    }

    /**
     * @param $id
     * @param $newAmount
     */
    public static function setCredits($id, $newAmount)
    {
        global $db;

        $query = $db->prepare('UPDATE `users` SET `credits`=:newAmount WHERE `id`=:userId LIMIT 1');
        $query->execute(array(
            ':newAmount' => $newAmount,
            ':userId'    => $id,
        ));
        Core::mus('updateCredits:' . $id);
    }

    /**
     * @param $id
     * @param $amount
     */
    public static function giveCredits($id, $amount)
    {
        self::setCredits($id, (self::getCredits($id) + $amount));
        Core::mus('updateCredits:' . $id);
    }

    /**
     * @param $id
     * @param $amount
     */
    public static function takeCredits($id, $amount)
    {
        self::setCredits($id, (self::getCredits($id) - $amount));
        Core::mus('updateCredits:' . $id);
    }

    /**
     * @param        $id
     * @param string $size
     * @param int    $dir
     * @param int    $head_dir
     * @param string $action
     * @param string $gesture
     *
     * @return string
     */
    public static function renderHabboImage(
        $id,
        $size = 'b',
        $dir = 2,
        $head_dir = 3,
        $action = 'wlk',
        $gesture = 'sml'
    ) {
        $look = self::getUserVar($id, 'look');

        return 'http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=' . $look . '&size=' . $size . '&action=' . $action . ',&gesture=' . $gesture . '&direction=' . $dir . '&head_direction=' . $head_dir;
    }

    /**
     * @param $id
     *
     * @return float|int
     */
    public static function getClubDays($id)
    {
        global $db;

        $query = $db->prepare("SELECT `timestamp_activated`,`timestamp_expire` FROM `user_subscriptions` WHERE `subscription_id`='habbo_club' AND `user_id`=:userId LIMIT 1");
        $query->execute(array(
            ':userId' => $id,
        ));

        if ($query->rowCount() == 0) {
            return 0;
        }

        $queryData = $query->fetch(PDO::FETCH_ASSOC);
        $diff = $queryData['timestamp_expire'] - time();

        if ($diff <= 0) {
            return 0;
        }

        return ceil($diff / 86400);
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public static function hasClub($id)
    {
        return self::getClubDays($id) > 0 ? true : false;
    }

    /**************************************************************************************************/

    /**
     * @param $name
     *
     * @return bool
     */
    public static function isUserBanned($name)
    {
        if (self::getBan('user', $name, true) != null) {
            return true;
        }
        return false;
    }

    /**
     * @param $ip
     *
     * @return bool
     */
    public static function isIpBanned($ip)
    {
        if (self::getBan('ip', $ip, true) != null) {
            return true;
        }
        return false;
    }

    /**
     * @param $userId
     *
     * @return bool
     */
    public static function is_Online($userId)
    {
        global $db;

        $query = $db->prepare("SELECT `online` FROM `users` WHERE `id`=:userId LIMIT 1");
        $query->execute(array(
            ':userId' => $userId,
        ));

        $queryData = $query->fetch(PDO::FETCH_ASSOC);

        return $queryData['online'] ? true : false;
    }

    /**
     * @param      $type
     * @param      $value
     * @param bool $mustNotBeExpired
     *
     * @return mixed|null
     */
    public static function getBan($type, $value, $mustNotBeExpired = false)
    {
        global $db;

        $queryString = "SELECT * FROM `bans` WHERE `bantype`=:banType AND `value`=:value";
        if ($mustNotBeExpired) {
            $queryString .= " AND `expire` > :time";
        }
        $queryString .= " LIMIT 1";

        $query = $db->prepare($queryString);
        $query->execute(array(
            ':banType' => $type,
            ':value'   => $value,
            ':time'    => time(),
        ));

        if ($query->rowCount() > 0) {
            return $query->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }

    /**************************************************************************************************/

    /**
     * @param $userId
     *
     * @return array
     */
    public static function getUserTags($userId)
    {
        global $db;

        $tagsArray = array();

        $query = $db->prepare("SELECT `id`,`tag` FROM `user_tags` WHERE `user_id`=:userId");
        $query->execute(array(
            ':userId' => $userId,
        ));
        $queryData = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($queryData as $tag) {
            $tagsArray[$tag['id']] = $tag['tag'];
        }

        return $tagsArray;
    }

    /**
     * @param $userOneId
     * @param $userTwoId
     *
     * @return bool
     */
    public static function friendShipExist($userOneId, $userTwoId)
    {
        global $db;

        $query = $db->prepare("SELECT `user_two_id` FROM `messenger_friendships` WHERE `user_one_id`=:userOneId AND `user_two_id`=:userTwoId");
        $query->execute(array(
            ':userOneId' => $userOneId,
            ':userTwoId' => $userTwoId,
        ));

        if ($query->rowCount() > 0) {
            return true;
        }
        return false;
    }
}
