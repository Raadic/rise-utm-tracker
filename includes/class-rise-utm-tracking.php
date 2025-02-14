<?php
/**
 * Tracking functionality
 *
 * @package RiseUTMTracker
 */

if (!defined('ABSPATH')) {
    exit;
}

class Rise_UTM_Tracking {
    /**
     * Cookie lifetime in days
     */
    const COOKIE_LIFETIME = 30;

    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'track_parameters'), 1);
        
        // Add debug info in footer if enabled
        if (get_option('rise_utm_tracker_debug_enabled', false)) {
            add_action('wp_footer', array($this, 'debug_info'));
        }
    }

    /**
     * Get tracking parameters
     */
    public function get_tracking_params() {
        $params = array(
            'utm_source' => '',
            'utm_medium' => '',
            'utm_campaign' => '',
            'gclid' => '',
            'gad_source' => ''
        );
        
        foreach ($params as $param => $value) {
            // Check URL parameters first for new visits
            if (isset($_GET[$param])) {
                $params[$param] = sanitize_text_field($_GET[$param]);
            }
            // Otherwise use existing cookie values
            elseif (isset($_COOKIE[$param])) {
                $params[$param] = $_COOKIE[$param];
            }
        }

        return $params;
    }

    /**
     * Track parameters and set cookies
     */
    public function track_parameters() {
        $params = $this->get_tracking_params();
        
        // Only set cookies for parameters that have values
        foreach ($params as $param => $value) {
            if (!empty($value)) {
                $this->set_cookie($param, $value);
            }
        }
        
        // Detect and store channel for new visits
        if (empty($_COOKIE['traffic_channel']) || isset($_GET['utm_source']) || isset($_GET['gclid'])) {
            $channel = $this->detect_channel();
            if (!empty($channel)) {
                $this->set_cookie('traffic_channel', $channel);
            }
        }
    }

    /**
     * Detect traffic channel
     */
    public function detect_channel() {
        // Get existing channel if any
        $existing_channel = isset($_COOKIE['traffic_channel']) ? $_COOKIE['traffic_channel'] : '';
        
        // Get parameters and referrer
        $params = $this->get_tracking_params();
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        
        // If we have UTM parameters or gclid/gad, these take precedence
        if (!empty($params['gclid']) || !empty($params['gad_source'])) {
            return 'Paid Search - Google Ads';
        }

        if (!empty($params['utm_source'])) {
            // Handle UTM-tagged traffic
            if (!empty($params['utm_medium'])) {
                if (in_array($params['utm_medium'], array('cpc', 'ppc', 'paid'))) {
                    return 'Paid - ' . ucfirst($params['utm_source']);
                }
            }

            // Check for social platforms
            $social_platforms = array('facebook', 'instagram', 'linkedin', 'twitter');
            if (in_array(strtolower($params['utm_source']), $social_platforms)) {
                return !empty($params['utm_medium']) && in_array($params['utm_medium'], array('cpc', 'ppc', 'paid')) 
                    ? 'Paid Social' 
                    : 'Social';
            }

            // Return the UTM source if nothing else matches
            return ucfirst($params['utm_source']);
        }

        // No UTM parameters - check referrer
        if (!empty($referer)) {
            $parsed_url = parse_url($referer);
            $referrer_host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
            $current_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

            // If referrer is from same domain
            if ($referrer_host === $current_host) {
                // Return existing channel if we have one, otherwise treat as Direct
                return $existing_channel ?: 'Direct';
            }

            // Check for organic search
            $search_engines = array(
                'google.' => 'Organic Search - Google',
                'bing.' => 'Organic Search - Bing',
                'yahoo.' => 'Organic Search - Yahoo',
                'duckduckgo.' => 'Organic Search - DuckDuckGo'
            );

            foreach ($search_engines as $domain => $label) {
                if (strpos($referrer_host, $domain) !== false) {
                    return $label;
                }
            }

            // Check for social referrers
            $social_domains = array(
                'facebook.com' => 'Social - Facebook',
                'instagram.com' => 'Social - Instagram',
                'linkedin.com' => 'Social - LinkedIn',
                'twitter.com' => 'Social - Twitter'
            );

            foreach ($social_domains as $domain => $label) {
                if (strpos($referrer_host, $domain) !== false) {
                    return $label;
                }
            }

            // If we have a referrer but it's not from any known source, it's a referral
            return 'Referral - ' . $referrer_host;
        }

        // If we have no referrer and no parameters, it's direct traffic
        return 'Direct';
    }

    /**
     * Set cookie with proper parameters
     */
    private function set_cookie($name, $value) {
        setcookie(
            $name,
            $value,
            time() + (DAY_IN_SECONDS * self::COOKIE_LIFETIME),
            COOKIEPATH,
            COOKIE_DOMAIN,
            is_ssl(),
            true
        );
        $_COOKIE[$name] = $value;
    }

    /**
     * Clear tracking cookies
     */
    public function clear_tracking_cookies() {
        $tracking_params = array(
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'gclid',
            'gad_source',
            'traffic_channel'
        );

        foreach ($tracking_params as $param) {
            if (isset($_COOKIE[$param])) {
                setcookie(
                    $param,
                    '',
                    time() - 3600,
                    COOKIEPATH,
                    COOKIE_DOMAIN,
                    is_ssl(),
                    true
                );
                unset($_COOKIE[$param]);
            }
        }
    }

    /**
     * Add debug information to console
     */
    public function debug_info() {
        if (!get_option('rise_utm_tracker_debug_enabled')) {
            return;
        }

        $debug_data = array(
            'tracking_parameters' => $this->get_tracking_params(),
            'channel' => isset($_COOKIE['traffic_channel']) ? $_COOKIE['traffic_channel'] : 'Not set',
            'referrer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Not set',
            'current_url' => $_SERVER['REQUEST_URI'],
            'current_host' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'Not set'
        );

        echo '<script>
            console.group("Rise UTM Tracker - Debug Info");
            console.log("Current URL:", ' . json_encode($debug_data['current_url']) . ');
            console.log("Current Host:", ' . json_encode($debug_data['current_host']) . ');
            console.log("Referrer:", ' . json_encode($debug_data['referrer']) . ');
            
            console.group("Tracking Parameters");';
        
        foreach ($debug_data['tracking_parameters'] as $param => $value) {
            echo 'console.log("' . esc_js($param) . ':", "' . esc_js($value) . '");';
        }
        
        echo 'console.groupEnd();
            console.log("Detected Channel:", ' . json_encode($debug_data['channel']) . ');
            console.groupEnd();
        </script>';
    }
}