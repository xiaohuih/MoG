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

    /**
     * Set zone current selected.
     *
     * @return void
     */
    public function setZone($zone)
    {
        session(['__CURRENT_ZONE__' => $zone]);
    }

    /**
     * Get zone current selected.
     *
     * @return number
     */
    public function zone()
    {
        return session('__CURRENT_ZONE__');
    }

}
