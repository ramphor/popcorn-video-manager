<?php

use Ramphor\Popcorn\Admin\Admin;
use Ramphor\Popcorn\Installer;
use Ramphor\Popcorn\PlatformManager;
use Ramphor\Popcorn\Video;
use Ramphor\Wallery\Factory\MetaboxFactory;
use Ramphor\Wallery\Wallery;

class Ramphor_Popcorn
{
    const POST_TYPE = 'video';

    protected static $instance;

    /**
     * @var \Ramphor\Wallery\Wallery
     */
    protected $walleryInstance;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->bootstrap();
        $this->loadFeatures();
        $this->initHooks();
    }

    public function bootstrap()
    {
        if (class_exists(Wallery::class)) {
            $walleryFactory  = new MetaboxFactory('Image Heading Text');
            $this->walleryInstance = new Wallery($walleryFactory);

            $this->walleryInstance->setId('popcorn_video_gallary');
        }
    }

    protected function is_request($request)
    {
        switch ($request) {
            case 'admin':
                return is_admin();
            case 'cron':
                return defined('DOING_AJAX') && DOING_AJAX;
        }
    }

    public function loadFeatures()
    {
        new PlatformManager();

        if (is_admin()) {
            new Admin();
        }
    }

    public function initHooks()
    {
        register_activation_hook(POPCORN_PLUGIN_FILE, [Installer::class, 'active']);

        add_action('the_post', [Video::class, 'createFromPost']);

        add_action('add_meta_boxes', [$this, 'wallery_register_metabox']);
    }

    function wallery_register_metabox()
    {
        add_meta_box(
            'metabox_id',
            __('Video Gallery', 'ramphor_popcorn'),
            array( $this->walleryInstance, 'render' ),
            static::POST_TYPE
        );
    }
}
