<?php namespace Cleanse\News\Components;

use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Post as BlogPost;
use RainLab\Blog\Models\Category as BlogCategory;

class Carousel extends ComponentBase
{
    /**
     * A collection of posts to display
     * @var Collection
     */
    public $posts;

    /**
     * Parameter to use for the page number
     * @var string
     */
    public $pageParam;

    /**
     * If the post list should be filtered by a category, the model to use.
     * @var Model
     */
    public $category;

    /**
     * Message to display when there are no messages.
     * @var string
     */
    public $noPostsMessage;

    /**
     * Reference to the page name for linking to posts.
     * @var string
     */
    public $postPage;

    /**
     * Reference to the page name for linking to categories.
     * @var string
     */
    public $categoryPage;

    /**
     * If the post list should be ordered by another attribute.
     * @var string
     */
    public $sortOrder;

    public function componentDetails()
    {
        return [
            'name'        => 'PvPaissa Front Page',
            'description' => 'Custom front page component.'
        ];
    }

    public function defineProperties()
    {
        return [
            'carouselPageNumber' => [
                'title'       => 'Page number',
                'description' => 'This value is used to determine what page the user is on.',
                'type'        => 'string',
                'default'     => '{{ :page }}',
            ],
            'carouselCategoryFilter' => [
                'title'       => 'Category filter',
                'description' => 'Enter a category slug or URL parameter to filter the posts by. Leave empty to show all posts.',
                'type'        => 'string',
                'default'     => ''
            ],
            'carouselPostsPerPage' => [
                'title'             => 'Posts per page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Invalid format of the posts per page value',
                'default'           => '4',
            ],
            'carouselNoPostsMessage' => [
                'title'        => 'No posts message',
                'description'  => 'Message to display in the blog post list in case if there are no posts. This property is used by the default component partial.',
                'type'         => 'string',
                'default'      => 'No posts found',
                'showExternalParam' => false
            ],
            'carouselSortOrder' => [
                'title'       => 'Post order',
                'description' => 'Attribute on which the posts should be ordered',
                'type'        => 'dropdown',
                'default'     => 'published_at desc'
            ],
            'carouselCategoryPage' => [
                'title'       => 'Category page',
                'description' => 'Name of the category page file for the "Posted into" category links. This property is used by the default component partial.',
                'type'        => 'dropdown',
                'default'     => 'article/category',
                'group'       => 'Links',
            ],
            'carouselPostPage' => [
                'title'       => 'Post page',
                'description' => 'Name of the blog post page file for the "Learn more" links. This property is used by the default component partial.',
                'type'        => 'dropdown',
                'default'     => 'article/article',
                'group'       => 'Links',
            ],
        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getPostPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getSortOrderOptions()
    {
        return BlogPost::$allowedSortingOptions;
    }

    public function onRun()
    {
        $this->prepareVars();

        $this->category = $this->page['carouselCategory'] = $this->loadCategory();
        $this->posts = $this->page['carouselPosts'] = $this->listPosts();

        /*
         * If the page number is not valid, redirect
         */
        if ($pageNumberParam = $this->paramName('carouselPageNumber')) {
            $currentPage = $this->property('carouselPageNumber');

            if ($currentPage > ($lastPage = $this->posts->lastPage()) && $currentPage > 1)
                return Redirect::to($this->currentPageUrl([$pageNumberParam => $lastPage]));
        }
    }

    protected function prepareVars()
    {
        $this->pageParam = $this->page['carouselPageParam'] = $this->paramName('carouselPageNumber');
        $this->noPostsMessage = $this->page['carouselNoPostsMessage'] = $this->property('carouselNoPostsMessage');

        /*
         * Page links
         */
        $this->carouselPostPage = $this->page['carouselPostPage'] = $this->property('carouselPostPage');
        $this->carouselCategoryPage = $this->page['carouselCategoryPage'] = $this->property('carouselCategoryPage');
    }

    protected function listPosts()
    {
        $category = $this->category ? $this->category->id : null;

        /*
         * List all the posts, eager load their categories
         */
        $posts = BlogPost::with('categories')->listFrontEnd([
            'page'       => $this->property('carouselPageNumber'),
            'sort'       => $this->property('carouselSortOrder'),
            'perPage'    => $this->property('carouselPostsPerPage'),
            'search'     => trim(input('search')),
            'category'   => $category
        ]);

        /*
         * Add a "url" helper attribute for linking to each post and category
         */
        $posts->each(function($post) {
            $post->setUrl($this->carouselPostPage, $this->controller);

            $post->categories->each(function($category) {
                $category->setUrl($this->carouselCategoryPage, $this->controller);
            });
        });

        return $posts;
    }

    protected function loadCategory()
    {
        if (!$categoryId = $this->property('carouselCategoryFilter'))
            return null;

        if (!$category = BlogCategory::whereSlug($categoryId)->first())
            return null;

        return $category;
    }
}