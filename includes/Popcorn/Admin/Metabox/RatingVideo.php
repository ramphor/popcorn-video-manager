<?php
namespace Ramphor\Popcorn\Admin\Metabox;

use Ramphor\Popcorn\Rating\Rating;
use Embrati\Embrati;
use Ramphor_Popcorn;

class RatingVideo
{
    protected static $instance;

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function registerMetabox()
    {
        $embrati  = Embrati::getInstance(); 
        $embrati->registerAdminScripts();     

        $rating = Rating::getInstance();
        add_meta_box(
            'ramphor-rating',
            __('Rating', 'ramphor_popcorn'),
            array($rating, 'renderRating'),
            Ramphor_Popcorn::POST_TYPE,
            'side'
        );
    }
}
