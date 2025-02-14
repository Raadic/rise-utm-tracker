<?php
/**
 * Footer partial
 *
 * @package RiseUTMTracker
 */

if (!defined('ABSPATH')) {
    exit;
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
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                <line x1="12" y1="17" x2="12" y2="17"></line>
            </svg>
            <?php _e('Need help? Contact support', 'rise-utm-tracker'); ?>
        </a>
    </div>
</div>
