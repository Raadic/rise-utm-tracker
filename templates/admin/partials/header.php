<?php
/**
 * Admin header partial
 *
 * @package RiseUTMTracker
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="rise-utm-header">
    <div class="rise-utm-header-content">
        <h1><?php _e('UTM/Source Tracker Settings', 'rise-utm-tracker'); ?></h1>
        <p class="rise-utm-header-description">
            <?php _e('Configure your UTM and traffic source tracking settings for Gravity Forms.', 'rise-utm-tracker'); ?>
        </p>
    </div>
    <div class="rise-utm-header-brand">
        <a href="https://RiseSEO.com.au/" target="_blank">
            <img src="<?php echo RISE_UTM_TRACKER_URL; ?>assets/images/rise-logo.png" 
                 alt="Rise" 
                 class="rise-utm-logo">
        </a>
    </div>
</div>

<?php
// Show any admin notices
settings_errors('rise_utm_tracker_messages');
