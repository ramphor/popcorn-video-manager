<?php
namespace Ramphor\Popcorn\Platform\Video;

class PostType
{
    const POST_TYPE_NAME = 'video';

    public function labels()
    {
        return array(
            'name' => __('Videos', 'rampho_popcorn'),
        );
    }

    public function register()
    {
        register_post_type(static::POST_TYPE_NAME, array(
            'public' => true,
            'labels' => $this->labels(),
            'supports' => array('title', 'editor', 'thumbnail'),
            'show_in_rest' => false,
        ));
    }
}
