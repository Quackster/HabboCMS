<?php

class RoomManager
{
	static function createRoom($name, $owner, $model)
	{
		global $db;
		
		$db->Query('INSERT INTO rooms (roomtype, caption, owner, state, model_name) VALUES ("private", "' . filter($name) . '", "' . $owner . '", "open", "' . $model . '")');
		return intval($db->Result($db->Query('SELECT id FROM rooms WHERE owner = "' . $owner . '" ORDER BY id DESC LIMIT 1'), 0));
	}
	
	static function paintRoom($roomId, $wallpaper, $floor)
	{
		global $db;
		
		$db->Query('UPDATE rooms SET wallpaper = "' . $wallpaper . '", floor = "' . $floor . '" WHERE id = "' . $roomId . '" LIMIT 1');
		return $db->AffectedRows() > 0 ? true : false;
	}
	
	static function getRoomVar($roomId, $var)
	{
		global $db;
		
		return $db->Result($db->Query('SELECT ' . $var . ' FROM rooms WHERE id = "' . $roomId . '" LIMIT 1'), 0);
	}
}
