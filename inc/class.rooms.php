<?php

class RoomManager
{
    /**
     * @param $name
     * @param $owner
     * @param $model
     *
     * @return int
     */
    static function createRoom($name, $owner, $model)
    {
        global $db;

        $query = $db->prepare("INSERT INTO `rooms`(`roomtype`, `caption`, `owner`, `state`, `model_name`)
                                           VALUES ('private',  :caption,  :owner,  'open',  :model      )");
        $query->execute(array(
            ':caption' => Core::filterInputString($name),
            ':owner'   => $owner,
            ':model'   => $model,
        ));

        $query = $db->prepare("SELECT `id` FROM `rooms` WHERE `owner`=:owner ORDER BY `id` DESC LIMIT 1");
        $query->execute(array(
            ':owner' => $owner,
        ));
        $queryData = $query->fetch(PDO::FETCH_ASSOC);
        $roomId = (int)$queryData['id'];

        return $roomId;
    }

    /**
     * @param $roomId
     * @param $wallpaper
     * @param $floor
     *
     * @return bool
     */
    static function paintRoom($roomId, $wallpaper, $floor)
    {
        global $db;

        $query = $db->prepare("UPDATE `rooms` SET `wallpaper`=:wallpaper,`floor`=:floor WHERE `id`=:roomId LIMIT 1");
        $query->execute(array(
            ':wallpaper' => $wallpaper,
            ':floor'     => $floor,
            ':roomId'    => $roomId,
        ));

        return $query->rowCount() > 0 ? true : false;
    }

    /**
     * @param $roomId
     * @param $var
     *
     * @return mixed
     */
    static function getRoomVar($roomId, $var)
    {
        global $db;

        $query = $db->prepare("SELECT :var FROM `rooms` WHERE `id`= :roomId LIMIT 1");
        $query->execute(array(
            ':var'    => $var,
            ':roomId' => $roomId,
        ));
        $queryData = $query->fetch(PDO::FETCH_ASSOC);
        $val = $queryData[$var];

        return $val;
    }
}
