<?php

namespace Cleanse\News\Components;

use Cms\Classes\ComponentBase;

class Guides extends ComponentBase
{
    private $jobs;

    public function componentDetails()
    {
        return [
            'name'        => 'PvPaissa Guides Page',
            'description' => 'Custom guides page component.'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'Role Filter',
                'description' => 'Enter a role slug. Leave empty to show all jobs.',
                'type'        => 'string',
                'default'     => ''
            ]
        ];
    }

    public function onRun()
    {
        $this->prepareVars();
    }

    private function prepareVars()
    {
        $this->jobs = $this->page['jobs'] = $this->loadRole();
    }

    private function loadRole()
    {
        if (!$roleSlug = $this->property('slug'))
            return $this->getJobsList();

        if (!$jobs = $this->getJobs($roleSlug))
            return null;

        return $jobs;
    }

    private function getJobsList()
    {
        return [
            ['name' => 'Paladin', 'abbr' => 'pld', 'role' => 'tank'],
            ['name' => 'Warrior', 'abbr' => 'war', 'role' => 'tank'],
            ['name' => 'Dark Knight', 'abbr' => 'drk', 'role' => 'tank'],

            ['name' => 'Monk', 'abbr' => 'mnk', 'role' => 'melee'],
            ['name' => 'Dragoon', 'abbr' => 'drg', 'role' => 'melee'],
            ['name' => 'Ninja', 'abbr' => 'nin', 'role' => 'melee'],
            ['name' => 'Samurai', 'abbr' => 'sam', 'role' => 'melee'],

            ['name' => 'Bard', 'abbr' => 'brd', 'role' => 'ranged'],
            ['name' => 'Machinist', 'abbr' => 'mch', 'role' => 'ranged'],

            ['name' => 'Black Mage', 'abbr' => 'blm', 'role' => 'ranged'],
            ['name' => 'Summoner', 'abbr' => 'smn', 'role' => 'ranged'],
            ['name' => 'Red Mage', 'abbr' => 'rdm', 'role' => 'ranged'],

            ['name' => 'White Mage', 'abbr' => 'whm', 'role' => 'healer'],
            ['name' => 'Scholar', 'abbr' => 'sch', 'role' => 'healer'],
            ['name' => 'Astrologian', 'abbr' => 'ast', 'role' => 'healer']
        ];
    }

    private function getJobs($role = '')
    {
        $jobs = $this->getJobsList();

        $guideJobs = [];
        foreach ($jobs as $job) {
            if ($job['role'] === $role) {
                $guideJobs[] = $job;
            }
        }

        return $guideJobs;
    }
}
