<?php
namespace Ramphor\Popcorn;

use Ramphor\Popcorn\Platform\Video;
use Ramphor\Popcorn\Platform\Tag;

class PlatformManager
{
    public function __construct()
    {
        $this->setupVideoPlatform();
        $this->registerPopcornTag();
    }

    public function setupVideoPlatform()
    {
        Video::getInstance();
    }

    public function registerPopcornTag()
    {
        add_action('init', array(new Tag(), 'register'), 50);
    }
}
