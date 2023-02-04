<?php

use Ramphor\Popcorn\Rating\Rating;

function show_rating($post = '', $options = [])
{
    if (empty($post)) global $post;
    if (class_exists(Rating::class)) {
        $rating = Rating::getInstance();

        $_options = $rating->defaultOptions();
        $_options['readOnly'] = true;

        $avg_star = $rating->meta($post->ID, 'avg_star');

        if (!is_null($avg_star)) $_options['rating'] = $avg_star;

        $options = $options + $_options;

        $rating->setOptions($options);

        return $rating->render(false);
    }
    return null;
}

function register_rating_js()
{
    if (class_exists(Rating::class)) {
        $rating = Rating::getInstance();
        $rating->registerScripts();
    }
}

function register_rating_ajax()
{
    $ajax     = new \Ramphor\Popcorn\Rating\AjaxRequest();
    add_action('init', array($ajax, 'init'));
}

function get_rating_meta($post_id, $meta_key = '')
{
    if (class_exists(Rating::class)) {
        $rating = Rating::getInstance();
        return $rating->meta($post_id, $meta_key);
    }
    return null;
}
