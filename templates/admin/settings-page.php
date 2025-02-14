<?php
/**
 * Main settings page template
 *
 * @package RiseUTMTracker
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get current tab
$current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'settings';
?>

<div class="wrap rise-utm-wrapper">
    <div class="rise-utm-header">
        <div class="rise-utm-header-content">
            <h1><?php _e('UTM/Source Tracker Settings', 'rise-utm-tracker'); ?></h1>
            <p class="rise-utm-header-description">
                <?php _e('Configure your UTM and traffic source tracking settings for Gravity Forms.', 'rise-utm-tracker'); ?>
            </p>
        </div>
        <div class="rise-utm-header-brand">
            <a href="https://RiseSEO.com.au/" target="_blank" class="rise-utm-logo-link">
                <img src="<?php echo RISE_UTM_TRACKER_URL; ?>assets/images/rise-logo.png" 
                     alt="Rise" 
                     class="rise-utm-logo">
            </a>
        </div>
    </div>

    <nav class="rise-utm-tabs nav-tab-wrapper">
        <a href="?page=rise-utm-tracker&tab=settings" 
           class="nav-tab <?php echo $current_tab === 'settings' ? 'nav-tab-active' : ''; ?>">
            <span class="dashicons dashicons-admin-settings"></span>
            <?php _e('Settings', 'rise-utm-tracker'); ?>
        </a>
        <a href="?page=rise-utm-tracker&tab=channels" 
           class="nav-tab <?php echo $current_tab === 'channels' ? 'nav-tab-active' : ''; ?>">
            <span class="dashicons dashicons-chart-bar"></span>
            <?php _e('Channel Setup', 'rise-utm-tracker'); ?>
        </a>
        <a href="?page=rise-utm-tracker&tab=testing" 
           class="nav-tab <?php echo $current_tab === 'testing' ? 'nav-tab-active' : ''; ?>">
            <span class="dashicons dashicons-chart-line"></span>
            <?php _e('Testing', 'rise-utm-tracker'); ?>
        </a>
        <a href="?page=rise-utm-tracker&tab=help" 
           class="nav-tab <?php echo $current_tab === 'help' ? 'nav-tab-active' : ''; ?>">
            <span class="dashicons dashicons-editor-help"></span>
            <?php _e('Help', 'rise-utm-tracker'); ?>
        </a>
    </nav>

    <?php
    // Load tab content
    switch ($current_tab) {
        case 'channels':
            require_once RISE_UTM_TRACKER_PATH . 'templates/admin/tabs/channels.php';
            break;
        case 'testing':
            require_once RISE_UTM_TRACKER_PATH . 'templates/admin/tabs/testing.php';
            break;
        case 'help':
            require_once RISE_UTM_TRACKER_PATH . 'templates/admin/tabs/help.php';
            break;
        default:
            require_once RISE_UTM_TRACKER_PATH . 'templates/admin/tabs/settings.php';
            break;
    }
    ?>

    <div class="rise-utm-footer">
        <div class="rise-utm-footer-info">
            <?php printf(
                __('UTM/Source Tracker v%s | By %s', 'rise-utm-tracker'),
                RISE_UTM_TRACKER_VERSION,
                '<a href="https://RiseSEO.com.au/" target="_blank">Rise</a>'
            ); ?>
        </div>
        <div class="rise-utm-footer-support">
            <a href="mailto:Support@riseseo.com.au" class="rise-support-link">
                <span class="dashicons dashicons-editor-help"></span>
                <?php _e('Need help? Contact support', 'rise-utm-tracker'); ?>
            </a>
        </div>
    </div>
</div>