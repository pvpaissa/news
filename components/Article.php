<?php

namespace Cleanse\News\Components;

use DB;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Post as ArticlePost;

class Article extends ComponentBase
{
    /**
     * @var Article and related article objects.
     */
    public $post;
    public $related;

    /**
     * @var string Reference to the page name for linking to categories.
     */
    public $categoryPage;

    public function componentDetails()
    {
        return [
            'name'        => 'PvPaissa Article Page',
            'description' => 'Custom article page component.'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'Post slug',
                'description' => 'Look up the blog post using the supplied slug value.',
                'default'     => '{{ :slug }}',
                'type'        => 'string'
            ],
            'categoryPage' => [
                'title'       => 'Category page',
                'description' => 'Name of the category page file for the category links. This property is used by the 
                                  default component partial.',
                'type'        => 'dropdown',
                'default'     => 'blog/category',
            ],
            'relatedPerPage' => [
                'title'             => 'Related Posts per page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Invalid format of the posts per page value',
                'default'           => '6',
            ]
        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->post         = $this->page['article'] = $this->loadPost();
        $this->related      = $this->page['related'] = $this->loadRelated();
    }

    protected function loadPost()
    {
        $slug = $this->property('slug');
        $post = ArticlePost::isPublished()->where('slug', $slug)->first();

        /*
         * Add a "url" helper attribute for linking to each category
         */
        if ($post && $post->categories->count()) {
            $post->categories->each(function($category){
                $category->setUrl($this->categoryPage, $this->controller);
            });
        }

        if (!$post || !$post->exists) {
            $this->setStatusCode(404);
            return $this->controller->run('404');
        }

        return $post;
    }

    private function loadRelated()
    {
        $posts = ArticlePost::isPublished()
            ->where('id', '<>', $this->post->id)
            ->whereHas('categories', function($tag) {
                $tag->whereIn('id', $this->post->categories);
            })
            ->with('categories')
            ->orderBy('published_at', 'desc')
            ->take($this->property('relatedPerPage'))
            ->get();

        /*
         * Add a "url" helper attribute for linking to each post
        */
        $posts->each(function($post)
        {
            $post->setUrl($this->postPage, $this->controller);
        });

        return $posts;
    }
}
