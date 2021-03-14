<?php
namespace Ramphor\Popcorn\Platform\Video;

class Taxonomies
{
    const CAT_TAX = 'video_cat';

    public function labels()
    {
        return array(
            'name' => __('Categories', 'ramphor_popcorn'),
            'plural_name' => __('Categories', 'ramphor_popcorn'),
        );
    }

    public function register()
    {
        register_taxonomy(static::CAT_TAX, PostType::POST_TYPE_NAME, array(
            'public' => true,
            'labels' => $this->labels(),
            'show_admin_column' => true,
        ));
    }
}
