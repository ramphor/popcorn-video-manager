<?php
namespace Ramphor\Popcorn\Admin\Metabox;

use Ramphor\Popcorn\Platform\Common;
use Ramphor\Popcorn\Video;
use Ramphor_Popcorn;

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
            <textarea name="<?php echo Common::EMBED_META_KEY; ?>" id="ramphor-popcorn-video-embded" class="widefat"><?php echo $video->getEmbedCode(); ?></textarea>
        </p>
        <p>
            <label for=""><?php echo __('Preview URL', 'ramphor_popcorn'); ?></label>
            <input type="text" class="widefat" name="<?php echo Common::VIDEO_PREVIEW_URL_META_KEY; ?>" value="<?php echo $video->getPreviewVideoUrl(); ?>" />
        </p>
        <p>
            <label for=""><?php echo __('Duration', 'ramphor_popcorn'); ?></label>
            <input type="text" class="widefat" name="<?php echo Common::DURATION_META_KEY; ?>" value="<?php echo $video->getDuration(); ?>" />
        </p>
        <?php
    }

    public function saveInfo($postID, $post)
    {
        if ($post->post_type !== Ramphor_Popcorn::POST_TYPE) {
            return;
        }
        $videoMetaKeys = [
            Common::EMBED_META_KEY,
            Common::DURATION_META_KEY,
            Common::VIDEO_PREVIEW_URL_META_KEY
        ];
        foreach($videoMetaKeys as $metaKey) {
            if (empty($_POST[$metaKey])) {
                delete_post_meta($postID, $metaKey);
            } else {
                update_post_meta($postID, $metaKey, $_POST[$metaKey]);
            }
        }
    }
}
