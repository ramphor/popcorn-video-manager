<?php
namespace Ramphor\Popcorn\Admin;

use Ramphor\Popcorn\Admin\Metabox\EditVideoInfo;
use Ramphor\Popcorn\Admin\Metabox\RatingVideo;
use Ramphor\PostColumns\Columns\ThumbnailColumn;
use Ramphor\PostColumns\ColumnsManager;
use Ramphor_Popcorn;

class Admin
{
    protected $editVideoInfoMetabox;
    protected $ratingVideoMetabox;

    public function __construct()
    {
        $this->bootstrap();
        $this->initHooks();
    }

    protected function bootstrap()
    {
        $this->editVideoInfoMetabox = EditVideoInfo::getInstance();
        $this->ratingVideoMetabox = RatingVideo::getInstance();
    }

    protected function initHooks()
    {
        add_action('admin_init', [$this, 'init']);
        add_action('admin_menu', [$this, 'changeVideoMenuLabels']);
    }

    public function registerMetaboxes()
    {
        add_meta_box(
            'ramohor-video-info',
            __('Video Informations', 'ramphor_popcorn'),
            [$this->editVideoInfoMetabox, 'registerMetabox'],
            Ramphor_Popcorn::POST_TYPE,
            'advanced'
        );
    }

    public function changeVideoMenuLabels()
    {
        global $menu;

        foreach ($menu as $index => $m) {
            if (array_search('edit.php?post_type=video', $m) !== false) {
                $menu[ $index ][0] = sprintf(__('%s Manager', 'ramphor_popcorn'), $menu[ $index ][0]);
            }
        }
    }

    public function init()
    {
        $postColumnManager = ColumnsManager::create(Ramphor_Popcorn::POST_TYPE);
        $thumbnailColumn   = new ThumbnailColumn([
            'position' => 1,
            'id' => 'video_thumbnail',
            'title' => __('Thumbnail')
        ]);
        $postColumnManager->addColumn($thumbnailColumn);

        add_action('add_meta_boxes', [$this, 'registerMetaboxes']);
        add_action('add_meta_boxes', [$this->ratingVideoMetabox, 'registerMetabox']);
        add_action('save_post', [$this->editVideoInfoMetabox, 'saveInfo'], 10, 2);
    }
}
