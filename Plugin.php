<?php

namespace Cleanse\News;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'PvPaissa News',
            'description' => 'Add custom news Component.',
            'author'      => 'Paul Lovato',
            'icon'        => 'icon-newspaper-o'
        ];
    }

    public function registerComponents()
    {
        return [
            'Cleanse\News\Components\Index'     => 'cleanseNewsIndex',
            'Cleanse\News\Components\Article'   => 'cleanseNewsArticle',
            'Cleanse\News\Components\Articles'   => 'cleanseNewsArticles', //maybe create multiple layouts
            'Cleanse\News\Components\Guides'   => 'cleanseNewsGuides', //??^Maybe make this a hard coded page?

            //Rename Rework
            'Cleanse\News\Components\Carousel' => 'newsPvPaissaCarousel',
            'Cleanse\News\Components\Summarized' => 'newsFrontOneSummary',
            'Cleanse\News\Components\FrontPageNews' => 'newsFrontOne',
            'Cleanse\News\Components\ArticlePost' => 'pvpaissaArticle',
            'Cleanse\News\Components\ArticlePosts' => 'pvpaissaArticles'
        ];
    }

    public function registerMarkupTags()
    {
        return [
            'filters' => [
                'timediff' => [$this, 'makeTimeDiff']
            ]
        ];
    }

    public function makeTimeDiff($datetime)
    {
        $time = time() - strtotime($datetime);

        $units = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($units as $unit => $val) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return ($val == 'second')? 'a few seconds ago' :
                (($numberOfUnits>1) ? $numberOfUnits : 'a')
                .' '.$val.(($numberOfUnits>1) ? 's' : '').' ago';
        }

        return '';
    }
}
