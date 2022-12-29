<?php
namespace Ramphor\Popcorn;

use Ramphor\Popcorn\Platform\Tag;
use Ramphor\Popcorn\Platform\VideoPlatform;

class PlatformManager
{
    public function __construct()
    {
        $this->setupVideoPlatform();
        $this->registerPopcornTag();
    }

    public function setupVideoPlatform()
    {
        VideoPlatform::getInstance();
    }

    public function registerPopcornTag()
    {
        add_action('init', array(new Tag(), 'register'), 50);
    }
}
