<?php

namespace Ramphor\Popcorn\Rating;

class AjaxRequest
{
    public function init()
    {
        add_action('wp_ajax_ramphor_set_rate', array($this, 'setRating'));
        add_action('wp_ajax_nopriv_ramphor_set_rate', array($this, 'setRating'));
    }

    public function setRating()
    {
        $requestPayload = json_decode(file_get_contents('php://input'), true);
        if (empty($requestPayload)) {
            wp_send_json_error(__('The argument is invalid', 'ramphor_testimonials'));
        }
        if (empty($requestPayload['post_id']) || is_null(get_post($requestPayload['post_id']))) {
            wp_send_json_error(__('The post ID is invalid', 'ramphor_testimonials'));
        }
        if (
            empty($requestPayload['nonce']) ||
            !wp_verify_nonce($requestPayload['nonce'], sprintf('set_star_rating_for_%s', $requestPayload['post_id']))
        ) {
            wp_send_json_error(__('The request is not accept by security rules', 'ramphor_testimonials'));
        }
        // Don't do anything when the rating value is empty
        if (empty($requestPayload['rating'])) {
            wp_send_json_success();
        }

        global $wpdb;
        $user_id = empty(get_current_user_id()) ? 0 : get_current_user_id();
        $user_ip = $this->get_real_ip_address();

        $data = array('post_id' => "{$requestPayload['post_id']}", 'stars' => "{$requestPayload['rating']}", 'user_id' => "{$user_id}", 'comment' => '111', 'user_ip' => "{$user_ip}");

        if (0 == $user_id) {
            $results = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}popcorn_ratings WHERE user_ip = '{$user_ip}' AND NOW() < DATE_ADD(created_at, INTERVAL 1 DAY) ORDER BY created_at DESC LIMIT 1");
            if (0 == count((array)$results)) {
                $wpdb->insert("{$wpdb->prefix}popcorn_ratings", $data);
            }
        } else {
            $results = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}popcorn_ratings WHERE user_id = '{$user_id}' LIMIT 1");
            if (0 == count((array)$results)) {
                $wpdb->insert("{$wpdb->prefix}popcorn_ratings", $data);
            } else {
                $wpdb->update("{$wpdb->prefix}popcorn_ratings", $data, array('id' => $results->id));
            }
        }
        wp_send_json_success($requestPayload['rating']);
    }

    private function get_real_ip_address()
    {
        $ip_headers = array(
            'HTTP_CF_IPCOUNTRY',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        );

        foreach ($ip_headers as $ip_header) {
            if (!empty($_SERVER[$ip_header])) {
                return $_SERVER[$ip_header];
            }
        }
        return '127.0.0.1';
    }
}
