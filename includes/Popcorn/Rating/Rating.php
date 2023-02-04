<?php

namespace Ramphor\Popcorn\Rating;

use Embrati\Embrati;

use Ramphor_Popcorn;

class Rating
{
    protected static $instance;
    protected $embrati;
    protected $options;
    protected $ajax;

    private function __construct()
    {
        $this->initFeatures();
        $this->options     = $this->defaultOptions();
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function assetUrl($path = '')
    {
        $abspath = constant('ABSPATH');
        $embratiAbspath = dirname(__DIR__, 3);
        if (PHP_OS === 'WINNT') {
            $abspath = str_replace('\\', '/', $abspath);
            $embratiAbspath = str_replace('\\', '/', $embratiAbspath);
        }
        $assetUrl = str_replace($abspath, site_url('/'), $embratiAbspath);

        return sprintf(
            '%s/assets/%s',
            $assetUrl,
            $path
        );
    }

    protected function initFeatures()
    {
        $this->embrati  = Embrati::getInstance();
        if (is_admin()) {
            $this->embrati->registerAdminScripts();
        } else {
            $this->embrati->registerScripts();
        }
        $this->embrati->setJsRateCallback('ramphor_set_star_rating');
        add_action('wp_enqueue_scripts', array($this->embrati, 'registerStyles'));
    }

    public function registerScripts()
    {
        add_action('wp_enqueue_scripts', array($this, '_registerScripts'));
    }

    public function _registerScripts()
    {
        wp_register_script('ramphor-ratings', $this->assetUrl('js/ramphor-rating.js'), null, '1.0.0', true);
        wp_enqueue_script('ramphor-ratings');

        $globalData = array(
            'set_rate_url' => admin_url('admin-ajax.php?action=ramphor_set_rate'),
        );

        if (is_admin()) {
            $current_screen = get_current_screen();
            if ($current_screen->id === Ramphor_Popcorn::POST_TYPE) {
                global $post;
            }
        } else if (is_singular(Ramphor_Popcorn::POST_TYPE)) {
            global $post;
        }

        $globalData['current_nonce'] = wp_create_nonce('set_star_rating_for_' . $post->ID);
        $globalData['post_id'] = $post->ID;

        wp_localize_script('ramphor-ratings', 'ramphor_rating_global', $globalData);
    }

    public function defaultOptions()
    {
        return array(
            'max' => 5,
            'step' => 0.5,
            'starSize' => 20,
            'rating' => 0
        );
    }

    public function addOption($optionName, $optionValue)
    {
        $this->options[$optionName] = $optionValue;
    }

    public function setOptions($options)
    {
        // Parse post layout with default options
        $options = apply_filters("ramphor_popcorn-rating-options", $options);
        foreach ($options as $optionName => $optionValue) {
            $this->addOption($optionName, $optionValue);
        }
    }

    public function getOptions($key = null)
    {
        if (is_null($key)) {
            return (array)$this->options;
        }
        return isset($this->options[$key]) ? $this->options[$key] : null;
    }

    public function render($echo = true)
    {
        if (!$echo) {
            ob_start();
        }
        echo '<div class="ramphor_popcorn-loading"></div>'; // wpcs: XSS Ok

        $this->embrati->create('ramphor_popcorn_rating', $this->options);

        if (!$echo) {
            return ob_get_clean();
        }
    }

    public function meta($post_id, $meta_key = '')
    {
        $data['max'] = 5;
        if (isset($this->options['max'])) $data['max'] = $this->options['max'];
        global $wpdb;
        $result = $wpdb->get_row("SELECT *, SUM(stars) as sum_star, AVG(stars) as avg_star, count(id) as count_id FROM {$wpdb->prefix}popcorn_ratings WHERE post_id = '{$post_id}' GROUP BY post_id");

        if (!is_null($result)) {
            foreach ($result as $key => $value) {
                $data[$key] = $value;
                if ('avg_star' === $key) $data[$key] = floatval($value);
                if ($key === $meta_key) break;
            }
        }
        if ($meta_key) return isset($data[$meta_key]) ? $data[$meta_key] : NULL;
        return $data;
    }
}
