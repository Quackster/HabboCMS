<?php

class HomesManager
{
    /**
     * @param string $linkType
     * @param        $linkId
     *
     * @return bool
     */
    public static function homeExists($linkType = 'user', $linkId)
    {
        global $db;

        $query = $db->prepare("SELECT NULL FROM `homes` WHERE `link_type` = :linkType AND `link_id` = :linkId LIMIT 1");
        $query->execute(array(
            ':linkType' => strtolower($linkType),
            ':linkId'   => intval($linkId),
        ));

        return $query->rowCount() > 0 ? true : false;
    }

    /**
     * @param $linkType
     * @param $linkId
     *
     * @return int
     */
    public static function getHomeId($linkType, $linkId)
    {
        global $db;

        if (!self::homeExists($linkType, $linkId)) {
            return 0;
        }

        $query = $db->prepare("SELECT `home_id` FROM `homes` WHERE `link_type` = :linkType AND `link_id` = :linkId LIMIT 1");
        $query->execute(array(
            ':linkType' => strtolower($linkType),
            ':linkId'   => intval($linkId),
        ));

        $queryData = $query->fetch(PDO::FETCH_ASSOC);
        $homeId = (int)$queryData['home_id'];

        return $homeId;
    }

    /**
     * @param $linkType
     * @param $linkId
     *
     * @return int
     */
    public static function createHome($linkType, $linkId)
    {
        global $db;

        $query = $db->prepare("INSERT INTO homes (home_id, link_type, link_id, allow_display)
                                          VALUES (NULL,    :linkType, :linkId, 1            )");
        $query->execute(array(
            ':linkType' => strtolower($linkType),
            ':linkId'   => intval($linkId),
        ));
        
        $homeId = self::getHomeId($linkType, $linkId);
        $home = self::getHome($homeId);

        $home->addItem('widget', 463, 39, 1, 'ProfileWidget', 'w_skin_defaultskin', 0);
        $home->addItem('stickie', 42, 48, 2,
            'Hi, and welcome to your Hotel Home page. To get started click on edit. Here you will find your Inventory and the Webstore. The Inventory lists all the items that you can place on your page including stickers, backgrounds and widgets. The Webstore is where you can buy new items. Check it regularly for cool new items.',
            'n_skin_noteitskin', 0);
        $home->addItem('stickie', 120, 311, 3, 'Don\'t just leave your page blank, decorate it now!',
            'n_skin_speechbubbleskin', 0);
        $home->addItem('sticker', 593, 11, 4, 's_sticker_arrow_down', '', 0);
        $home->addItem('sticker', 252, 12, 5, 's_paper_clip_1', '', 0);
        $home->addItem('sticker', 341, 353, 6, 's_sticker_spaceduck', '', 0);
        $home->addItem('sticker', 27, 32, 7, 's_needle_3', '', 0);

        return $homeId;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public static function getHomeDataRow($id)
    {
        global $db;

        $query = $db->prepare("SELECT * FROM `homes` WHERE `home_id`=:homeId LIMIT 1");
        $query->execute(array(
            ':homeId' => $id,
        ));
        $queryData = $query->fetch(PDO::FETCH_ASSOC);

        return $queryData;
    }

    /**
     * @param $id
     *
     * @return Home|null
     */
    public static function getHome($id)
    {
        $data = self::getHomeDataRow($id);
        if ($data == null) {
            return null;
        }
        return new Home($data['home_id'], $data['link_type'], $data['link_id']);
    }
}

class Home
{
    public $id = 0;
    public $linkType = '';
    public $linkId = 0;

    /**
     * Home constructor.
     *
     * @param $id
     * @param $linkType
     * @param $linkId
     */
    public function __construct($id, $linkType, $linkId)
    {
        $this->id = $id;
        $this->linkType = $linkType;
        $this->linkId = $linkId;
    }

    /**
     * @param $type
     * @param $x
     * @param $y
     * @param $z
     * @param $data
     * @param $skin
     * @param $ownerId
     */
    public function addItem($type, $x, $y, $z, $data, $skin, $ownerId)
    {
        global $db;

        $query = $db->prepare("INSERT INTO `homes_items`(`home_id`, `type`, `x`, `y`, `z`, `data`, `skin`, `owner_id`)
                                                 VALUES (:homeId,   :type,  :x,  :y,  :z,  :data,  :skin,  :ownerId  )");
        $query->execute(array(
            ':homeId'  => $this->id,
            ':type'    => $type,
            ':x'       => $x,
            ':y'       => $y,
            ':z'       => $z,
            ':data'    => Core::filterInputString($data),
            ':skin'    => $skin,
            ':ownerId' => $ownerId,
        ));
    }

    /**
     * @return array
     */
    public function getItems()
    {
        global $db;

        $list = array();

        $query = $db->prepare("SELECT * FROM `homes_items` WHERE `home_id` = :homeId ORDER BY `type` ASC");
        $query->execute(array(
            ':homeId' => $this->id,
        ));
        $queryData = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($queryData as $item) {
            $list[] = new HomeItem($item['id'], $item['home_id'], $item['type'], $item['data'], $item['skin'],
                $item['x'], $item['y'], $item['z'], $item['owner_id']);
        }
        return $list;
    }
}

class HomeItem
{
    public $id = 0;
    public $homeId = 0;

    public $type = '';
    public $data = '';
    public $skin = '';

    public $x = 0;
    public $y = 0;
    public $z = 0;

    public $ownerId = 0;

    /**
     * HomeItem constructor.
     *
     * @param $id
     * @param $homeId
     * @param $type
     * @param $data
     * @param $skin
     * @param $x
     * @param $y
     * @param $z
     * @param $ownerId
     */
    public function __construct($id, $homeId, $type, $data, $skin, $x, $y, $z, $ownerId)
    {
        $this->id = $id;
        $this->homeId = $homeId;
        $this->type = $type;
        $this->data = $data;
        $this->skin = $skin;
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
        $this->ownerId = $ownerId;
    }

    /**
     * @return Home|null
     */
    public function getHome()
    {
        return HomesManager::getHome($this->homeId);
    }

    /**
     * @return mixed|string
     */
    public function getHtml()
    {
        switch ($this->type) {
            case 'widget':

                $widget = null;

                switch (strtolower($this->data)) {
                    case 'profilewidget':

                        $widget = new Template('widget-profile');
                        $widget->setParam('user_id', $this->getHome()->linkId);
                        break;
                }

                $widget->setParam('id', $this->id);
                $widget->setParam('pos-x', $this->x);
                $widget->setParam('pos-y', $this->y);
                $widget->setParam('pos-z', $this->z);
                $widget->setParam('skin', $this->skin);

                return $widget->getHtml();

            case 'stickie':

                return '<div class="movable stickie ' . $this->skin . '-c" style="left: ' . $this->x . 'px; top: ' . $this->y . 'px; z-index: ' . $this->z . ';" id="stickie-' . $this->id . '">
							<div class="' . $this->skin . '" >
								<div class="stickie-header">
									<h3></h3>
									<div class="clear"></div>
								</div>
								<div class="stickie-body">
									<div class="stickie-content">
										<div class="stickie-markup">' . Core::cleanStringForOutput($this->data) . '</div>
										<div class="stickie-footer"></div>
									</div>
								</div>
							</div>
						</div>';

            case 'sticker':

                return '<div class="movable sticker ' . Core::cleanStringForOutput($this->data) . '" style="left: ' . $this->x . 'px; top: ' . $this->y . 'px; z-index: ' . $this->z . ';" id="sticker-' . $this->id . '"></div>';
        }
    }
}
