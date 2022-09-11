<?php

use Ramphor\Popcorn\Admin\Admin;
use Ramphor\Popcorn\Installer;
use Ramphor\Popcorn\PlatformManager;
use Ramphor\Popcorn\Video;

class Ramphor_Popcorn
{
    const POST_TYPE = 'video';

    protected static $instance;

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
    }
}
