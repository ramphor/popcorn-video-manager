<?php
namespace Ramphor\Popcorn;

use Ramphor\Popcorn\Platform\Common;
use WP_Post;

class Video
{
    protected $post;

    protected $videoId;

    protected $tile;

    protected $thumbnailId;

    protected $embedCode;

    protected $previewVideoUrl;

    protected $duration;

    protected $galleryId;

    protected $categories;

    protected $tags;

    private static $metaMaps = [
        'embedCode' => Common::EMBED_META_KEY,
        'previewVideoUrl' => Common::VIDEO_PREVIEW_URL_META_KEY,
        'duration' => Common::DURATION_META_KEY,
        'galleryId' => Common::VIDEO_GALLARY_ID_META_KEY,
    ];

    public function __construct($post = null)
    {
        if (!is_null($post)) {
            $this->post = $post;
        }
    }

    /**
     * @param   \WP_Post|int  $post The post or post ID
     *
     * @return static
     */
    public static function createFromPost($post)
    {
        if (is_int($post)) {
            $post = get_post($post);
        }
        if (!is_a($post, WP_Post::class)) {
            return new Video();
        }

        $video = new static($post);
        $video->videoId = $post->ID;
        $video->setTitle($post->post_title);

        $video->setThumbailId(get_post_thumbnail_id($post));

        foreach (static::$metaMaps as $key => $metaKey) {
            $video->$key = get_post_meta($video->videoId, $metaKey, true);
        }

        return $video;
    }

    public function __get($name)
    {
        if (is_a($this->post, WP_Post::class) && property_exists($this->post, $name)) {
            return $this->post->$name;
        }
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setThumbailId($thumbnailId)
    {
        $this->thumbnailId = $thumbnailId;
    }

    public function getThumbailId()
    {
        return $this->thumbnailId;
    }

    public function setEmbedCode($embedCode)
    {
        $this->embedCode = $embedCode;
    }

    public function getEmbedCode()
    {
        return $this->embedCode;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setPreviewVideoUrl($previewVideoUrl)
    {
        $this->previewVideo = $previewVideoUrl;
    }

    public function getPreviewVideoUrl()
    {
        return $this->previewVideoUrl;
    }

    public function setGalleryId($galleryId)
    {
        $this->galleryId = $galleryId;
    }

    public function getGalleryId()
    {
        return $this->galleryId;
    }

    public function getCategories()
    {
        if (is_null($this->categories)) {
        }
        return $this->categories;
    }

    public function getTags()
    {
        if (is_null($this->tags)) {
        }
        return $this->tags;
    }
}
