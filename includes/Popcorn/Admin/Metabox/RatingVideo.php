<?php
namespace Ramphor\Popcorn\Admin\Metabox;

use Embrati\Embrati;
use Ramphor_Popcorn;

class RatingVideo
{

    protected static $instance;
    protected $embrati;


    protected function __construct()
    {
    }

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function registerMetabox()
    {
        $this->embrati = Embrati::getInstance();
        $this->embrati->registerAdminScripts();
        add_meta_box(
            'ramphor-rating',
            __('Rating', 'ramphor_popcorn'),
            array($this, 'renderRating'),
            Ramphor_Popcorn::POST_TYPE,
            'side'
        );
    }

    public function renderRating($post)
    {
        echo '<div class="ramphor_popcorn-loading"></div>'; // wpcs: XSS Ok
        $rating = 0;
        $options = apply_filters('ramphor_popcorn-rating-options', array(
            'max' => 5,            
            'step' => 0.5,
            'rating' => $rating,
            'starSize' => 20
        ));
        $this->embrati->create('ramphor_popcorn-rating', $options);
    }
}
