<?php
/**
 * Instructions partial
 *
 * @package RiseUTMTracker
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="rise-utm-card">
    <h2><?php _e('How to Use UTM/Source Tracker', 'rise-utm-tracker'); ?></h2>
    
    <div class="rise-utm-instruction-section">
        <h3><?php _e('1. Adding to Forms', 'rise-utm-tracker'); ?></h3>
        <ol>
            <li><?php _e('Edit your Gravity Form', 'rise-utm-tracker'); ?></li>
            <li><?php _e('Look for "Channel Tracking" under Advanced Fields', 'rise-utm-tracker'); ?></li>
            <li><?php _e('Drag the Channel Tracking field onto your form', 'rise-utm-tracker'); ?></li>
            <li><?php _e('Save your form', 'rise-utm-tracker'); ?></li>
        </ol>
    </div>

    <div class="rise-utm-instruction-section">
        <h3><?php _e('2. URL Parameters', 'rise-utm-tracker'); ?></h3>
        <p><?php _e('The plugin tracks the following parameters:', 'rise-utm-tracker'); ?></p>
        <ul>
            <li><code>utm_source</code> - <?php _e('Traffic source (e.g., google, facebook)', 'rise-utm-tracker'); ?></li>
            <li><code>utm_medium</code> - <?php _e('Marketing medium (e.g., cpc, email)', 'rise-utm-tracker'); ?></li>
            <li><code>utm_campaign</code> - <?php _e('Campaign name', 'rise-utm-tracker'); ?></li>
            <li><code>gclid</code> - <?php _e('Google Click Identifier', 'rise-utm-tracker'); ?></li>
            <li><code>gad_source</code> - <?php _e('Google Ads Source', 'rise-utm-tracker'); ?></li>
        </ul>
    </div>

    <div class="rise-utm-instruction-section">
        <h3><?php _e('3. Example URL', 'rise-utm-tracker'); ?></h3>
        <div class="rise-utm-code-block">
            <code>https://your-site.com/?utm_source=facebook&utm_medium=cpc&utm_campaign=spring_sale</code>
            <button class="rise-utm-copy-button" data-copy="https://your-site.com/?utm_source=facebook&utm_medium=cpc&utm_campaign=spring_sale">
                <span class="dashicons dashicons-clipboard"></span>
                <?php _e('Copy', 'rise-utm-tracker'); ?>
            </button>
        </div>
    </div>

    <div class="rise-utm-instruction-section">
        <h3><?php _e('4. Channel Detection', 'rise-utm-tracker'); ?></h3>
        <p><?php _e('The plugin automatically detects:', 'rise-utm-tracker'); ?></p>
        <ul>
            <li><?php _e('Paid Search Traffic (Google Ads, Bing Ads)', 'rise-utm-tracker'); ?></li>
            <li><?php _e('Organic Search Traffic', 'rise-utm-tracker'); ?></li>
            <li><?php _e('Direct Traffic', 'rise-utm-tracker'); ?></li>
            <li><?php _e('Referral Traffic', 'rise-utm-tracker'); ?></li>
        </ul>
    </div>
</div>
