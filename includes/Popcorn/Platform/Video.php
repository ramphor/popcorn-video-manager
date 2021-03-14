<?php
namespace Ramphor\Popcorn\Platform;

class Video
{
    protected static $instance;

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct()
    {
        $this->registerPostType();
    }

    public function registerPostType()
    {
        $postType = new Video\PostType();
        $taxonomies = new Video\Taxonomies();

        add_action('init', array($postType, 'register'));
        add_action('init', array($taxonomies, 'register'));
    }
}
