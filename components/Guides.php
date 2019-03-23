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
            ['name' => 'Paladin', 'abbr' => 'pld', 'role' => 'tank', 'url' => '/article/paladin-feast-guide'],
            ['name' => 'Warrior', 'abbr' => 'war', 'role' => 'tank', 'url' => '/article/warrior-feast-guide'],
            ['name' => 'Dark Knight', 'abbr' => 'drk', 'role' => 'tank'],
            ['name' => 'Gunbreaker', 'abbr' => 'gun', 'role' => 'tank'],

            ['name' => 'Monk', 'abbr' => 'mnk', 'role' => 'melee'],
            ['name' => 'Dragoon', 'abbr' => 'drg', 'role' => 'melee', 'url' => '/article/dragoon-feast-guide'],
            ['name' => 'Ninja', 'abbr' => 'nin', 'role' => 'melee'],
            ['name' => 'Samurai', 'abbr' => 'sam', 'role' => 'melee', 'url' => '/article/samurai-feast-guide'],

            ['name' => 'Bard', 'abbr' => 'brd', 'role' => 'ranged', 'url' => '/article/bard-feast-guide'],
            ['name' => 'Machinist', 'abbr' => 'mch', 'role' => 'ranged', 'url' => '/article/machinist-feast-guide'],
            ['name' => 'Dancer', 'abbr' => 'dnc', 'role' => 'ranged'],

            ['name' => 'Black Mage', 'abbr' => 'blm', 'role' => 'ranged', 'url' => '/article/black-mage-feast-guide'],
            ['name' => 'Summoner', 'abbr' => 'smn', 'role' => 'ranged', 'url' => '/article/summoner-feast-guide'],
            ['name' => 'Red Mage', 'abbr' => 'rdm', 'role' => 'ranged', 'url' => '/article/red-mage-feast-guide'],

            ['name' => 'White Mage', 'abbr' => 'whm', 'role' => 'healer', 'url' => '/article/white-mage-feast-guide'],
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
