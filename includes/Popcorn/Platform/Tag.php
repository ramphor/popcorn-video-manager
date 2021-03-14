<?php
namespace Ramphor\Popcorn\Platform;

use Ramphor\Popcorn\Platform\Video\PostType;

class Tag
{
    const TAG_TAX = 'popcorn_tag';

    public function labels()
    {
        return array(
            'name' => __('Tags', 'ramphor_popcorn'),
        );
    }

    public function register()
    {
        register_taxonomy(static::TAG_TAX, array(PostType::POST_TYPE_NAME), array(
            'public' => true,
            'labels' => $this->labels(),
            'show_admin_column' => true,
        ));
    }
}
