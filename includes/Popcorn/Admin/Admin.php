<?php
namespace Ramphor\Popcorn\Admin;

use Ramphor\PostColumns\Columns\ThumbnailColumn;
use Ramphor\PostColumns\ColumnsManager;
use Ramphor_Popcorn;

class Admin
{
    public function __construct()
    {
        $this->initHooks();
    }

    protected function initHooks()
    {
        add_action('admin_init', [$this, 'init']);
    }

    public function init()
    {
        $postColumnManager = ColumnsManager::create(Ramphor_Popcorn::POST_TYPE);
        $thumbnailColumn = new ThumbnailColumn([
            'position' => 1,
            'id' => 'video_thumbnail',
            'title' => __('Thumbnail')
        ]);
        $postColumnManager->addColumn($thumbnailColumn);
    }
}
