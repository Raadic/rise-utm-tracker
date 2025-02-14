<?php
/**
 * Plugin Name: UTM/Source Tracker For Gravity Forms
 * Plugin URI: https://RiseSEO.com.au/
 * Description: Professional UTM and traffic source tracking solution for Gravity Forms by Rise
 * Version: 1.0.0
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Rise
 * Author URI: https://RiseSEO.com.au/
 * Text Domain: rise-utm-tracker
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package RiseUTMTracker
 * @author Rise
 * @copyright 2024 Rise
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
if (!defined('RISE_UTM_TRACKER_VERSION')) {
    define('RISE_UTM_TRACKER_VERSION', '1.0.0');
}

if (!defined('RISE_UTM_TRACKER_FILE')) {
    define('RISE_UTM_TRACKER_FILE', __FILE__);
}

if (!defined('RISE_UTM_TRACKER_PATH')) {
    define('RISE_UTM_TRACKER_PATH', plugin_dir_path(__FILE__));
}

if (!defined('RISE_UTM_TRACKER_URL')) {
    define('RISE_UTM_TRACKER_URL', plugin_dir_url(__FILE__));
}

/**
 * Main plugin class loader
 */
final class Rise_UTM_Tracker_Loader {
    /**
     * Plugin instance
     *
     * @var Rise_UTM_Tracker_Loader
     */
    private static $instance = null;

    /**
     * Main plugin class instance
     *
     * @var Rise_UTM_Tracker
     */
    public $plugin;

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
     * Constructor
     */
    private function __construct() {
        $this->define_constants();
        $this->check_requirements();
        $this->includes();
        $this->init();

        do_action('rise_utm_tracker_loaded');
    }

    /**
     * Define additional constants
     */
    private function define_constants() {
        if (!defined('RISE_UTM_TRACKER_MIN_PHP_VERSION')) {
            define('RISE_UTM_TRACKER_MIN_PHP_VERSION', '7.4');
        }

        if (!defined('RISE_UTM_TRACKER_MIN_WP_VERSION')) {
            define('RISE_UTM_TRACKER_MIN_WP_VERSION', '5.8');
        }
    }

    /**
     * Check plugin requirements
     */
    private function check_requirements() {
        // Check PHP Version
        if (version_compare(PHP_VERSION, RISE_UTM_TRACKER_MIN_PHP_VERSION, '<')) {
            add_action('admin_notices', array($this, 'php_version_notice'));
            return false;
        }

        // Check WordPress Version
        if (version_compare($GLOBALS['wp_version'], RISE_UTM_TRACKER_MIN_WP_VERSION, '<')) {
            add_action('admin_notices', array($this, 'wp_version_notice'));
            return false;
        }

        return true;
    }

    /**
     * PHP version notice
     */
    public function php_version_notice() {
        ?>
        <div class="notice notice-error">
            <p>
                <?php
                printf(
                    /* translators: %1$s: Plugin name, %2$s: Required PHP version, %3$s: Current PHP version */
                    esc_html__('%1$s requires PHP version %2$s or higher. Your current PHP version is %3$s. Please upgrade PHP to run this plugin.', 'rise-utm-tracker'),
                    '<strong>Rise UTM Tracker</strong>',
                    RISE_UTM_TRACKER_MIN_PHP_VERSION,
                    PHP_VERSION
                );
                ?>
            </p>
        </div>
        <?php
    }

    /**
     * WordPress version notice
     */
    public function wp_version_notice() {
        ?>
        <div class="notice notice-error">
            <p>
                <?php
                printf(
                    /* translators: %1$s: Plugin name, %2$s: Required WordPress version */
                    esc_html__('%1$s requires WordPress version %2$s or higher. Please upgrade WordPress to run this plugin.', 'rise-utm-tracker'),
                    '<strong>Rise UTM Tracker</strong>',
                    RISE_UTM_TRACKER_MIN_WP_VERSION
                );
                ?>
            </p>
        </div>
        <?php
    }

    /**
     * Include required files
     */
    private function includes() {
        // Core classes
        require_once RISE_UTM_TRACKER_PATH . 'includes/class-rise-utm-tracker.php';
        require_once RISE_UTM_TRACKER_PATH . 'includes/class-rise-utm-tracking.php';
        require_once RISE_UTM_TRACKER_PATH . 'includes/class-rise-utm-admin.php';
        require_once RISE_UTM_TRACKER_PATH . 'includes/class-rise-utm-gravity-forms.php';
        require_once RISE_UTM_TRACKER_PATH . 'includes/class-rise-utm-gravity-field.php';
    }

    /**
     * Initialize plugin
     */
    private function init() {
        // Initialize main plugin class
        $this->plugin = Rise_UTM_Tracker::get_instance();

        // Register activation and deactivation hooks
        register_activation_hook(RISE_UTM_TRACKER_FILE, array($this->plugin, 'activate'));
        register_deactivation_hook(RISE_UTM_TRACKER_FILE, array($this->plugin, 'deactivate'));
    }
}

/**
 * Start the plugin
 */
function rise_utm_tracker() {
    return Rise_UTM_Tracker_Loader::get_instance();
}

// Initialize plugin
rise_utm_tracker();