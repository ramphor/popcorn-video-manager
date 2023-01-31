<?php
namespace Ramphor\Popcorn\Admin;

use Ramphor\Popcorn\Admin\Metabox\EditVideoInfo;
use Ramphor\Popcorn\Admin\Metabox\RatingVideo;
use Ramphor\PostColumns\Columns\ThumbnailColumn;
use Ramphor\PostColumns\ColumnsManager;
use Embrati\Embrati;

use Ramphor_Popcorn;
use AjaxRequest;

class Admin
{
    protected $editVideoInfoMetabox;
    protected $ratingVideoMetabox;
    protected $embrati;
    protected static $assetUrl;
    public $ajax;

    public function __construct()
    {
        $this->bootstrap();
        $this->initHooks();
        $this->initFeatures();
        $this->defineConstants();
    }

    protected function bootstrap()
    {
        $this->editVideoInfoMetabox = EditVideoInfo::getInstance();
        $this->ratingVideoMetabox = RatingVideo::getInstance();
    }

    private function define($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    private function defineConstants()
    {
        $this->define('RAMPHOR_POPCORN_ABSPATH', dirname(__DIR__,3));
    }

    protected function initHooks()
    {
        add_action('admin_init', [$this, 'init']);
        add_action('admin_menu', [$this, 'changeVideoMenuLabels']);
    }

    protected function initFeatures()
    {

        $this->embrati  = Embrati::getInstance();       

        add_action('wp_enqueue_scripts', array($this->embrati, 'registerStyles'));

        add_action('embrati_registered_scripts', array($this, 'registerTestimonialScripts'));
        add_filter('embrati_enqueue_script', array($this, 'changeEnqueueSCript'));

        $this->embrati->setJsRateCallback('ramphor_set_star_rating');

        $this->ajax     = new AjaxRequest();
        add_action('init', array($this->ajax, 'init'));
    }

    protected function assetUrl($path = '')
    {
        if (is_null(static::$assetUrl)) {
            $abspath = constant('ABSPATH');
            $embratiAbspath = constant('RAMPHOR_POPCORN_ABSPATH');
            if (PHP_OS === 'WINNT') {
                $abspath = str_replace('\\', '/', $abspath);
                $embratiAbspath = str_replace('\\', '/', $embratiAbspath);
            }
            static::$assetUrl = str_replace($abspath, site_url('/'), $embratiAbspath);
        }

        return sprintf(
            '%s/assets/%s',
            static::$assetUrl,
            $path
        );
    }

    public function registerTestimonialScripts()
    {
        wp_register_script(
            'ramphor-ratings',
            $this->assetUrl('js/ramphor-rating.js'),
            array('embrati'),
            '1.0.0',
            true
        );

        $globalData = array(
            'set_rate_url' => admin_url('admin-ajax.php?action=ramphor_set_rate'),
        );
        $current_screen = get_current_screen();
        if ($current_screen->id === Ramphor_Popcorn::POST_TYPE) {
            global $post;
            $globalData['current_nonce'] = wp_create_nonce('set_star_rating_for_' . $post->ID);
            $globalData['post_id'] = $post->ID;
        }
        wp_localize_script('ramphor-ratings', 'ramphor_rating_global', $globalData);
    }

    public function changeEnqueueSCript()
    {
        return 'ramphor-ratings';
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
