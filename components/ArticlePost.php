<?php namespace Cleanse\News\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Post as BlogPost;

class ArticlePost extends ComponentBase
{
    /**
     * @var
     */
    public $post;

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
        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->addCss('/plugins/cleanse/news/assets/css/cleanse-news.css');
        $this->addJs('/plugins/cleanse/news/assets/js/article-navigation.js');

        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->post = $this->page['post'] = $this->loadPost();
    }

    protected function loadPost()
    {
        $slug = $this->property('slug');
        $post = BlogPost::isPublished()->where('slug', $slug)->first();


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
}