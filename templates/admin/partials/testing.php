<?php
/**
 * Testing partial
 *
 * @package RiseUTMTracker
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="rise-utm-card">
    <h2><?php _e('Test Your Tracking Setup', 'rise-utm-tracker'); ?></h2>

    <div class="rise-utm-test-section">
        <h3><?php _e('Quick Test', 'rise-utm-tracker'); ?></h3>
        <p><?php _e('Click the button below to open a new tab with test parameters:', 'rise-utm-tracker'); ?></p>
        <button id="rise-utm-test-tracking" class="button button-primary">
            <span class="dashicons dashicons-chart-line"></span>
            <?php _e('Test Tracking Parameters', 'rise-utm-tracker'); ?>
        </button>
    </div>

    <div class="rise-utm-test-section">
        <h3><?php _e('Current Tracking Status', 'rise-utm-tracker'); ?></h3>
        <div class="rise-utm-status-grid">
            <div class="rise-utm-status-item">
                <span class="rise-utm-status-label"><?php _e('Debug Mode:', 'rise-utm-tracker'); ?></span>
                <span class="rise-utm-status-value <?php echo get_option('rise_utm_tracker_debug_enabled') ? 'active' : 'inactive'; ?>">
                    <?php echo get_option('rise_utm_tracker_debug_enabled') ? __('Enabled', 'rise-utm-tracker') : __('Disabled', 'rise-utm-tracker'); ?>
                </span>
            </div>
            <div class="rise-utm-status-item">
                <span class="rise-utm-status-label"><?php _e('Cookie Lifetime:', 'rise-utm-tracker'); ?></span>
                <span class="rise-utm-status-value">
                    <?php echo sprintf(__('%d days', 'rise-utm-tracker'), Rise_UTM_Tracking::COOKIE_LIFETIME); ?>
                </span>
            </div>
        </div>
    </div>

    <div class="rise-utm-test-section">
        <h3><?php _e('Test Cases', 'rise-utm-tracker'); ?></h3>
        <div class="rise-utm-test-cases">
            <div class="rise-utm-test-case">
                <h4><?php _e('Google Ads Traffic', 'rise-utm-tracker'); ?></h4>
                <div class="rise-utm-code-block">
                    <code>?gclid=test123&utm_source=google&utm_medium=cpc</code>
                    <button class="rise-utm-copy-button" data-copy="?gclid=test123&utm_source=google&utm_medium=cpc">
                        <span class="dashicons dashicons-clipboard"></span>
                        <?php _e('Copy', 'rise-utm-tracker'); ?>
                    </button>
                </div>
            </div>
            
            <div class="rise-utm-test-case">
                <h4><?php _e('Facebook Ads Traffic', 'rise-utm-tracker'); ?></h4>
                <div class="rise-utm-code-block">
                    <code>?utm_source=facebook&utm_medium=paid&utm_campaign=test</code>
                    <button class="rise-utm-copy-button" data-copy="?utm_source=facebook&utm_medium=paid&utm_campaign=test">
                        <span class="dashicons dashicons-clipboard"></span>
                        <?php _e('Copy', 'rise-utm-tracker'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
