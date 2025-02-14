<?php
/**
 * Main plugin class
 *
 * @package RiseUTMTracker
 */

if (!defined('ABSPATH')) {
    exit;
}

class Rise_UTM_Tracker {
    /**
     * Plugin instance
     *
     * @var Rise_UTM_Tracker
     */
    private static $instance;

    /**
     * Admin class instance
     *
     * @var Rise_UTM_Admin
     */
    public $admin;

    /**
     * Tracking class instance
     *
     * @var Rise_UTM_Tracking
     */
    public $tracking;

    /**
     * Gravity Forms class instance
     *
     * @var Rise_UTM_Gravity_Forms
     */
    public $gravity_forms;

    /**
     * Constructor
     */
    public function __construct() {
        $this->define_constants();
        $this->load_dependencies();
        $this->init_hooks();
    }

    /**
     * Get plugin instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Define plugin constants
     */
    private function define_constants() {
        if (!defined('RISE_UTM_TRACKER_VERSION')) {
            define('RISE_UTM_TRACKER_VERSION', '1.0.0');
        }
        
        if (!defined('RISE_UTM_TRACKER_PATH')) {
            define('RISE_UTM_TRACKER_PATH', plugin_dir_path(dirname(__FILE__)));
        }
        
        if (!defined('RISE_UTM_TRACKER_URL')) {
            define('RISE_UTM_TRACKER_URL', plugin_dir_url(dirname(__FILE__)));
        }
    }

    /**
     * Load dependencies
     */
    private function load_dependencies() {
        // Load core files
        require_once RISE_UTM_TRACKER_PATH . 'includes/class-rise-utm-tracking.php';
        require_once RISE_UTM_TRACKER_PATH . 'includes/class-rise-utm-admin.php';
        require_once RISE_UTM_TRACKER_PATH . 'includes/class-rise-utm-gravity-field.php';
        require_once RISE_UTM_TRACKER_PATH . 'includes/class-rise-utm-gravity-forms.php';

        // Initialize components
        $this->init_components();
    }

    /**
     * Initialize components
     */
    private function init_components() {
        // Initialize tracking
        $this->tracking = new Rise_UTM_Tracking();

        // Initialize admin if in admin area
        if (is_admin()) {
            $this->admin = new Rise_UTM_Admin();
        }

        // Initialize Gravity Forms integration if GF is active
        if ($this->is_gravity_forms_active()) {
            $this->gravity_forms = new Rise_UTM_Gravity_Forms();
        }
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Plugin activation/deactivation
        register_activation_hook(RISE_UTM_TRACKER_PATH . 'rise-utm-tracker.php', array($this, 'activate'));
        register_deactivation_hook(RISE_UTM_TRACKER_PATH . 'rise-utm-tracker.php', array($this, 'deactivate'));

        // Initialization hooks
        add_action('plugins_loaded', array($this, 'init'));
        add_action('init', array($this, 'load_textdomain'));

        // Admin notices
        add_action('admin_notices', array($this, 'admin_notices'));
    }

    /**
     * Plugin activation
     */
    public function activate() {
        // Check requirements
        if (!$this->check_requirements()) {
            deactivate_plugins(plugin_basename(RISE_UTM_TRACKER_PATH . 'rise-utm-tracker.php'));
            wp_die(
                esc_html__('Rise UTM Tracker requires Gravity Forms to be installed and activated.', 'rise-utm-tracker'),
                'Plugin Activation Error',
                array('back_link' => true)
            );
        }

        // Set default options
        $this->set_default_options();

        // Flush rewrite rules
        flush_rewrite_rules();

        // Set activation flag
        update_option('rise_utm_tracker_activated', true);
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Clean up if needed
        // delete_option('rise_utm_tracker_settings');
    }

    /**
     * Plugin initialization
     */
    public function init() {
        // Check if this is a new installation
        if (get_option('rise_utm_tracker_activated')) {
            $this->handle_first_activation();
        }
    }

    /**
     * Handle first activation
     */
    private function handle_first_activation() {
        // Remove activation flag
        delete_option('rise_utm_tracker_activated');

        // Add welcome notice flag
        set_transient('rise_utm_tracker_show_welcome', true, 5);
    }

    /**
     * Set default options
     */
    private function set_default_options() {
        // General settings
        add_option('rise_utm_tracker_debug_enabled', false);
        add_option('rise_utm_tracker_cookie_lifetime', 30);

        // Channel settings
        add_option('rise_utm_tracker_paid_search_terms', array('cpc', 'ppc', 'paid', 'sem'));
        add_option('rise_utm_tracker_social_sources', array('facebook', 'instagram', 'linkedin', 'twitter', 'tiktok'));
        add_option('rise_utm_tracker_search_engines', array(
            'google' => 'Google',
            'bing' => 'Bing',
            'yahoo' => 'Yahoo',
            'duckduckgo' => 'DuckDuckGo'
        ));
    }

    /**
     * Load textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'rise-utm-tracker',
            false,
            dirname(plugin_basename(RISE_UTM_TRACKER_PATH . 'rise-utm-tracker.php')) . '/languages/'
        );
    }

    /**
     * Check if Gravity Forms is active
     */
    public function is_gravity_forms_active() {
        return class_exists('GFForms');
    }

    /**
     * Check plugin requirements
     */
    private function check_requirements() {
        return $this->is_gravity_forms_active();
    }

    /**
     * Admin notices
     */
    public function admin_notices() {
        // Welcome notice
        if (get_transient('rise_utm_tracker_show_welcome')) {
            ?>
            <div class="notice notice-success is-dismissible">
                <p>
                    <?php
                    echo sprintf(
                        /* translators: %1$s: Plugin name, %2$s: Settings page link */
                        esc_html__('Thank you for installing %1$s! Please visit the %2$s to configure your tracking settings.', 'rise-utm-tracker'),
                        '<strong>Rise UTM Tracker</strong>',
                        '<a href="' . admin_url('admin.php?page=rise-utm-tracker') . '">' . __('settings page', 'rise-utm-tracker') . '</a>'
                    );
                    ?>
                </p>
            </div>
            <?php
            delete_transient('rise_utm_tracker_show_welcome');
        }

        // Check Gravity Forms requirement
        if (!$this->is_gravity_forms_active()) {
            ?>
            <div class="notice notice-error">
                <p>
                    <?php
                    echo sprintf(
                        /* translators: %s: Plugin name */
                        esc_html__('%s requires Gravity Forms to be installed and activated.', 'rise-utm-tracker'),
                        '<strong>Rise UTM Tracker</strong>'
                    );
                    ?>
                </p>
            </div>
            <?php
        }
    }

    /**
     * Get plugin settings
     */
    public function get_settings($key = null) {
        $settings = array(
            'debug_enabled' => get_option('rise_utm_tracker_debug_enabled', false),
            'cookie_lifetime' => get_option('rise_utm_tracker_cookie_lifetime', 30),
            'paid_search_terms' => get_option('rise_utm_tracker_paid_search_terms', array()),
            'social_sources' => get_option('rise_utm_tracker_social_sources', array()),
            'search_engines' => get_option('rise_utm_tracker_search_engines', array()),
        );

        if ($key && isset($settings[$key])) {
            return $settings[$key];
        }

        return $settings;
    }
}