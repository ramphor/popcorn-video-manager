<?php
namespace Ramphor\Popcorn\Platform\Video;

class Taxonomies
{
    const CAT_TAX = 'genre';

    public function labels()
    {
        return array(
            'name' => __('Genres', 'ramphor_popcorn'),
            'plural_name' => __('Genres', 'ramphor_popcorn'),
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
