<?php
/**
 * Channel Management AJAX Handlers
 *
 * @package RiseUTMTracker
 */

if (!defined('ABSPATH')) {
    exit;
}

class Rise_UTM_Channel_Handler {
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_ajax_rise_save_channel_priority', array($this, 'save_channel_priority'));
        add_action('wp_ajax_rise_test_channel_detection', array($this, 'test_channel_detection'));
        add_action('wp_ajax_rise_save_channel_settings', array($this, 'save_channel_settings'));
    }

    /**
     * Save channel priority order
     */
    public function save_channel_priority() {
        check_ajax_referer('rise_utm_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }

        $priority = isset($_POST['priority']) ? (array) $_POST['priority'] : array();
        
        // Sanitize array
        $priority = array_map('sanitize_text_field', $priority);
        
        // Save priority
        update_option('rise_utm_tracker_channel_priority', $priority);
        
        wp_send_json_success();
    }

    /**
     * Test channel detection
     */
    public function test_channel_detection() {
        check_ajax_referer('rise_utm_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }

        $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';
        
        if (empty($url)) {
            wp_send_json_error(array('message' => 'Invalid URL'));
        }

        // Parse URL
        $params = array();
        $parsed_url = parse_url($url);
        
        if (isset($parsed_url['query'])) {
            parse_str($parsed_url['query'], $params);
        }

        // Detect channel
        $tracker = new Rise_UTM_Tracking();
        $channel = $tracker->detect_channel_from_params($params);

        wp_send_json_success(array(
            'channel' => $channel,
            'params' => $params
        ));
    }

    /**
     * Save channel settings
     */
    public function save_channel_settings() {
        check_ajax_referer('rise_utm_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }

        // Save paid search terms
        if (isset($_POST['rise_utm_tracker_paid_search_terms'])) {
            $terms = sanitize_text_field($_POST['rise_utm_tracker_paid_search_terms']);
            $terms = array_map('trim', explode(',', $terms));
            update_option('rise_utm_tracker_paid_search_terms', $terms);
        }

        // Save social sources
        if (isset($_POST['rise_utm_tracker_social_sources'])) {
            $sources = sanitize_text_field($_POST['rise_utm_tracker_social_sources']);
            $sources = array_map('trim', explode(',', $sources));
            update_option('rise_utm_tracker_social_sources', $sources);
        }

        // Save search engines
        if (isset($_POST['rise_utm_tracker_search_engines'])) {
            $engines = array();
            $domains = isset($_POST['rise_utm_tracker_search_domains']) ? 
                      (array) $_POST['rise_utm_tracker_search_domains'] : array();
            
            foreach ($_POST['rise_utm_tracker_search_engines'] as $index => $engine) {
                if (!empty($engine) && isset($domains[$index])) {
                    $engines[sanitize_text_field($engine)] = sanitize_text_field($domains[$index]);
                }
            }
            
            update_option('rise_utm_tracker_search_engines', $engines);
        }

        wp_send_json_success();
    }
}