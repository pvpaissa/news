<?php

namespace Cleanse\News\Components;

use Cms\Classes\ComponentBase;

class Guides extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'PvPaissa Guides Page',
            'description' => 'Custom guides page component.'
        ];
    }

    public function onRun()
    {
        $this->page['jobs'] = $this->getJobsList();
    }

    private function getJobsList()
    {
        return [
            ['name' => 'Paladin', 'abbr' => 'pld'],
            ['name' => 'Warrior', 'abbr' => 'war'],
            ['name' => 'Dark Knight', 'abbr' => 'drk'],

            ['name' => 'Monk', 'abbr' => 'mnk'],
            ['name' => 'Dragoon', 'abbr' => 'drg'],
            ['name' => 'Ninja', 'abbr' => 'nin'],
            ['name' => 'Samurai', 'abbr' => 'sam'],

            ['name' => 'Bard', 'abbr' => 'brd'],
            ['name' => 'Machinist', 'abbr' => 'mch'],

            ['name' => 'Black Mage', 'abbr' => 'blm'],
            ['name' => 'Summoner', 'abbr' => 'smn'],
            ['name' => 'Red Mage', 'abbr' => 'rdm'],

            ['name' => 'White Mage', 'abbr' => 'whm'],
            ['name' => 'Scholar', 'abbr' => 'sch'],
            ['name' => 'Astrologian', 'abbr' => 'ast']
        ];
    }
}
