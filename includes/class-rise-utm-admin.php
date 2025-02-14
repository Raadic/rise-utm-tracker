<?php
/**
 * Admin functionality
 *
 * @package RiseUTMTracker
 */

if (!defined('ABSPATH')) {
    exit;
}

class Rise_UTM_Admin {
    /**
     * Constructor
     */
    public function __construct() {
        // Add menu and settings
        add_action('admin_menu', array($this, 'add_menu_page'));
        add_action('admin_init', array($this, 'register_settings'));
        
        // Add assets
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Add plugin action links
        add_filter('plugin_action_links_' . plugin_basename(RISE_UTM_TRACKER_FILE), array($this, 'add_settings_link'));

        // Register AJAX handlers
        $this->register_ajax_handlers();
        add_action('wp_ajax_rise_save_channel_settings', array($this, 'handle_save_channel_settings'));
        add_action('wp_ajax_rise_test_channel_detection', array($this, 'handle_test_channel_detection'));
        add_action('wp_ajax_rise_check_form_tracking', array($this, 'handle_form_tracking_check'));
    }
    
    /**
     * Register AJAX handlers
     */
    private function register_ajax_handlers() {
        // Existing handlers...
        add_action('wp_ajax_rise_get_tracking_cookies', array($this, 'handle_get_tracking_cookies'));
        add_action('wp_ajax_rise_delete_tracking_cookie', array($this, 'handle_delete_tracking_cookie'));
        add_action('wp_ajax_rise_clear_tracking_cookies', array($this, 'handle_clear_tracking_cookies'));
    }

    /**
     * Handle get tracking cookies
     */
    public function handle_get_tracking_cookies() {
        check_ajax_referer('rise_utm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }

        $tracking_cookies = array();
        $tracking_prefixes = array('utm_', 'gclid', 'gad_', 'traffic_');

        foreach ($_COOKIE as $name => $value) {
            foreach ($tracking_prefixes as $prefix) {
                if (strpos($name, $prefix) === 0) {
                    $tracking_cookies[$name] = $value;
                }
            }
        }

        wp_send_json_success($tracking_cookies);
    }

    /**
     * Handle delete tracking cookie
     */
    public function handle_delete_tracking_cookie() {
        check_ajax_referer('rise_utm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }

        $cookie_name = isset($_POST['cookie']) ? sanitize_text_field($_POST['cookie']) : '';
        
        if (empty($cookie_name)) {
            wp_send_json_error('No cookie specified');
        }

        setcookie(
            $cookie_name,
            '',
            time() - 3600,
            COOKIEPATH,
            COOKIE_DOMAIN,
            is_ssl(),
            true
        );

        wp_send_json_success();
    }

    /**
     * Handle clear tracking cookies
     */
    public function handle_clear_tracking_cookies() {
        check_ajax_referer('rise_utm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }

        $tracking_prefixes = array('utm_', 'gclid', 'gad_', 'traffic_');

        foreach ($_COOKIE as $name => $value) {
            foreach ($tracking_prefixes as $prefix) {
                if (strpos($name, $prefix) === 0) {
                    setcookie(
                        $name,
                        '',
                        time() - 3600,
                        COOKIEPATH,
                        COOKIE_DOMAIN,
                        is_ssl(),
                        true
                    );
                }
            }
        }

        wp_send_json_success();
    }

    /**
     * Register all plugin settings
     */
    public function register_settings() {
        // General Settings
        register_setting(
            'rise_utm_tracker_settings',
            'rise_utm_tracker_debug_enabled',
            array(
                'type' => 'boolean',
                'default' => false,
                'sanitize_callback' => 'rest_sanitize_boolean'
            )
        );

        register_setting(
            'rise_utm_tracker_settings',
            'rise_utm_tracker_cookie_lifetime',
            array(
                'type' => 'integer',
                'default' => 30,
                'sanitize_callback' => array($this, 'sanitize_cookie_lifetime')
            )
        );

        // Channel Settings
         register_setting(
            'rise_utm_tracker_channel_settings',
            'rise_utm_tracker_channel_priority',
            array(
                'type' => 'array',
                'default' => array('paid_search', 'paid_social', 'organic_search', 'organic_social', 'referral', 'direct'),
                'sanitize_callback' => array($this, 'sanitize_channel_priority')
            )
        );

        register_setting(
            'rise_utm_tracker_channel_settings',
            'rise_utm_tracker_detect_gclid',
            array(
                'type' => 'boolean',
                'default' => true,
                'sanitize_callback' => 'rest_sanitize_boolean'
            )
        );

        register_setting(
            'rise_utm_tracker_channel_settings',
            'rise_utm_tracker_detect_gad',
            array(
                'type' => 'boolean',
                'default' => true,
                'sanitize_callback' => 'rest_sanitize_boolean'
            )
        );

        register_setting(
            'rise_utm_tracker_channel_settings',
            'rise_utm_tracker_hide_fields',
            array(
                'type' => 'boolean',
                'default' => true,
                'sanitize_callback' => 'rest_sanitize_boolean'
            )
        );
    }

    /**
     * Sanitize channel priority
     */
    public function sanitize_channel_priority($value) {
        if (is_string($value)) {
            $value = explode(',', $value);
        }

        if (!is_array($value)) {
            return array('paid_search', 'paid_social', 'organic_search', 'organic_social', 'referral', 'direct');
        }

        $allowed_channels = array(
            'paid_search',
            'paid_social',
            'organic_search',
            'organic_social',
            'referral',
            'direct'
        );

        return array_filter($value, function($channel) use ($allowed_channels) {
            return in_array($channel, $allowed_channels);
        });
    }

    /**
     * Get default channel priority
     */
    private function get_default_channel_priority() {
        return 'paid_search,paid_social,organic_search,organic_social,referral,direct';
    }

    /**
     * Get allowed channels
     */
    private function get_allowed_channels() {
        return array(
            'paid_search',
            'paid_social',
            'organic_search',
            'organic_social',
            'referral',
            'direct'
        );
    }

    /**
     * Sanitize cookie lifetime
     */
    public function sanitize_cookie_lifetime($value) {
        $value = absint($value);
        return max(1, min($value, 365)); // Between 1 and 365 days
    }

    /**
     * Add menu page
     */
    public function add_menu_page() {
        add_menu_page(
            __('UTM Tracker', 'rise-utm-tracker'),
            __('UTM Tracker', 'rise-utm-tracker'),
            'manage_options',
            'rise-utm-tracker',
            array($this, 'render_settings_page'),
            'dashicons-chart-line',
            85
        );
    }

    /**
     * Add settings link to plugin page
     */
    public function add_settings_link($links) {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            admin_url('admin.php?page=rise-utm-tracker'),
            __('Settings', 'rise-utm-tracker')
        );
        array_unshift($links, $settings_link);
        return $links;
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if ('toplevel_page_rise-utm-tracker' !== $hook) {
            return;
        }

        // Enqueue Manrope font
        wp_enqueue_style(
            'rise-utm-google-font',
            'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap',
            array(),
            RISE_UTM_TRACKER_VERSION
        );
        
          // Help tab script
        wp_enqueue_script(
            'rise-utm-help',
            RISE_UTM_TRACKER_URL . 'assets/js/help.js',
            array('jquery'),
            RISE_UTM_TRACKER_VERSION,
            true
        );

        // Enqueue admin styles
        wp_enqueue_style(
            'rise-utm-admin',
            RISE_UTM_TRACKER_URL . 'assets/css/admin.css',
            array(),
            RISE_UTM_TRACKER_VERSION
        );

        // Enqueue jQuery UI
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-tooltip');

        // Channel Management
        wp_enqueue_script(
            'rise-utm-channel-management',
            RISE_UTM_TRACKER_URL . 'assets/js/channel-management.js',
            array('jquery', 'jquery-ui-sortable', 'jquery-ui-tooltip'),
            RISE_UTM_TRACKER_VERSION,
            true
        );

        // Localize script data
        wp_localize_script('rise-utm-channel-management', 'riseUtmData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('rise_utm_nonce'),
            'strings' => array(
                'saveSuccess' => __('Settings saved successfully', 'rise-utm-tracker'),
                'saveError' => __('Failed to save settings', 'rise-utm-tracker'),
                'invalidPriority' => __('Invalid channel priority order', 'rise-utm-tracker')
            )
        ));
    }

    /**
     * Handle save channel settings
     */
    public function handle_save_channel_settings() {
        check_ajax_referer('rise_utm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Insufficient permissions'));
        }

        $settings = array();

        // Validate and save channel priority
        if (isset($_POST['rise_utm_tracker_channel_priority'])) {
            $priority = sanitize_text_field($_POST['rise_utm_tracker_channel_priority']);
            $settings['channel_priority'] = $this->sanitize_channel_priority($priority);
        }

        // Handle Google Ads detection settings
        $settings['detect_gclid'] = isset($_POST['rise_utm_tracker_detect_gclid']);
        $settings['detect_gad'] = isset($_POST['rise_utm_tracker_detect_gad']);
        
        // Save settings
        foreach ($settings as $key => $value) {
            update_option('rise_utm_tracker_' . $key, $value);
        }

        wp_send_json_success(array('message' => __('Channel settings saved successfully', 'rise-utm-tracker')));
    }

    /**
     * Handle test channel detection
     */
    public function handle_test_channel_detection() {
        check_ajax_referer('rise_utm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Insufficient permissions'));
        }

        $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';
        
        if (empty($url)) {
            wp_send_json_error(array('message' => 'Invalid URL'));
        }

        // Parse URL parameters
        $params = array();
        $parsed_url = parse_url($url);
        
        if (isset($parsed_url['query'])) {
            parse_str($parsed_url['query'], $params);
        }

        // Get tracker instance and detect channel
        $tracker = new Rise_UTM_Tracking();
        $channel = $tracker->detect_channel();
        
        wp_send_json_success(array(
            'channel' => $channel,
            'params' => $params
        ));
    }

    /**
     * Handle form tracking check
     */
    public function handle_form_tracking_check() {
        check_ajax_referer('rise_utm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Insufficient permissions'));
        }

        $form_id = isset($_POST['form_id']) ? absint($_POST['form_id']) : 0;
        
        if (!class_exists('GFAPI')) {
            wp_send_json_error(array('message' => 'Gravity Forms not active'));
        }

        $form = GFAPI::get_form($form_id);
        
        if (!$form) {
            wp_send_json_error(array('message' => 'Form not found'));
        }

        // Check for tracking fields
        $has_tracking = false;
        foreach ($form['fields'] as $field) {
            if (strpos($field->type, 'rise_utm') === 0) {
                $has_tracking = true;
                break;
            }
        }

        wp_send_json_success(array(
            'status' => $has_tracking ? 'success' : 'warning',
            'message' => $has_tracking ? 
                __('UTM tracking is properly configured', 'rise-utm-tracker') :
                __('UTM tracking field not found in form', 'rise-utm-tracker')
        ));
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        require_once RISE_UTM_TRACKER_PATH . 'templates/admin/settings-page.php';
    }
}