<?php

namespace App;

/**
 * Class Game.
 */
class Game
{
    /**
     * Left sider-bar zone select.
     *
     * @return array
     */
    public function zoneSelect()
    {
        return new Widgets\ZoneSelect;
    }
}
