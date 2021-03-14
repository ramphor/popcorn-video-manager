<?php

use Ramphor\Popcorn\PlatformManager;

class Ramphor_Popcorn
{
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
    }

    public function initHooks()
    {
    }
}
