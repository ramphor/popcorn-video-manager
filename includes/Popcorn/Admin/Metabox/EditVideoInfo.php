<?php
namespace Ramphor\Popcorn\Admin\Metabox;

use Ramphor\Popcorn\Video;

class EditVideoInfo
{
    protected static $instance;

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

    public function registerMetabox($post)
    {
        $video = Video::createFromPost($post);
        ?>
        <p>
            <label for=""><?php echo __('Embed code', 'ramphor_popcorn'); ?></label>
            <textarea name="" id="ramphor-popcorn-video-embded" class="widefat"></textarea>
        </p>
        <p>
            <label for=""><?php echo __('Preview URL', 'ramphor_popcorn'); ?></label>
            <input type="text" class="widefat" name="" value="" />
        </p>
        <p>
            <label for=""><?php echo __('Duration', 'ramphor_popcorn'); ?></label>
            <input type="text" class="widefat" name="" value="" />
        </p>
        <?php
    }

    public function saveInfo($post)
    {
    }
}
