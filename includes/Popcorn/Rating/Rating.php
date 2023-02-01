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
        $this->bootstrap();
        // $this->initHooks();
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

    protected function bootstrap()
    {
      
    }

    protected function initFeatures()
    {

        $this->embrati  = Embrati::getInstance();       

        add_action('wp_enqueue_scripts', array($this->embrati, 'registerStyles'));

        add_action('embrati_registered_scripts', array($this, 'registerScripts'));
        add_filter('embrati_enqueue_script', array($this, 'changeEnqueueSCript'));

        $this->embrati->setJsRateCallback('ramphor_set_star_rating');

        $this->ajax     = new \Ramphor\Popcorn\Rating\AjaxRequest();
        
        add_action('init', array($this->ajax, 'init'));
    }
   
    public function registerScripts()
    {
        wp_register_script(
            'ramphor-ratings',
            assetUrl('js/ramphor-rating.js'),
            array('embrati'),
            '1.0.0',
            true
        );

        $globalData = array(
            'set_rate_url' => admin_url('admin-ajax.php?action=ramphor_set_rate'),
        );

        if(is_admin()){
            $current_screen = get_current_screen();
            if ($current_screen->id === Ramphor_Popcorn::POST_TYPE) {
                global $post;         
            }
        }else if(is_singular(Ramphor_Popcorn::POST_TYPE)){
            global $post;
        }

        $globalData['current_nonce'] = wp_create_nonce('set_star_rating_for_' . $post->ID);
        $globalData['post_id'] = $post->ID;

        wp_localize_script('ramphor-ratings', 'ramphor_rating_global', $globalData);
    }

    public function changeEnqueueSCript()
    {
        return 'ramphor-ratings';
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

    public function renderRating($post)
    {
       echo '<div class="ramphor_popcorn-loading"></div>'; // wpcs: XSS Ok

       $this->embrati->create('ramphor_popcorn-rating', $this->options);
    }
}
