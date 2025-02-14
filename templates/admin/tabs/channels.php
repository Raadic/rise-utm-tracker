<?php
/**
 * Channel setup tab content
 *
 * @package RiseUTMTracker
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get settings
$detect_gclid = get_option('rise_utm_tracker_detect_gclid', true);
$detect_gad = get_option('rise_utm_tracker_detect_gad', true);
?>

<div class="rise-utm-card">
    <form method="post" action="" id="rise-utm-channel-form">
        <?php wp_nonce_field('rise_utm_nonce', 'rise_utm_nonce'); ?>

        <!-- Google Ads Detection -->
        <div class="rise-utm-section">
            <h2><?php _e('Google Ads Detection', 'rise-utm-tracker'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <?php _e('GCLID Detection', 'rise-utm-tracker'); ?>
                        <span class="rise-utm-help">
                            <span class="dashicons dashicons-editor-help" 
                                  title="<?php esc_attr_e('Automatically detect Google Ads traffic using the GCLID parameter', 'rise-utm-tracker'); ?>">
                            </span>
                        </span>
                    </th>
                    <td>
                        <label class="rise-utm-toggle">
                            <input type="checkbox" 
                                   name="rise_utm_tracker_detect_gclid" 
                                   value="1" 
                                   <?php checked($detect_gclid); ?>>
                            <span class="rise-utm-toggle-slider"></span>
                            <?php _e('Auto-detect Google Ads via GCLID parameter', 'rise-utm-tracker'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php _e('GAD Source Detection', 'rise-utm-tracker'); ?>
                        <span class="rise-utm-help">
                            <span class="dashicons dashicons-editor-help" 
                                  title="<?php esc_attr_e('Automatically detect Google Ads traffic using the gad_source parameter', 'rise-utm-tracker'); ?>">
                            </span>
                        </span>
                    </th>
                    <td>
                        <label class="rise-utm-toggle">
                            <input type="checkbox" 
                                   name="rise_utm_tracker_detect_gad" 
                                   value="1" 
                                   <?php checked($detect_gad); ?>>
                            <span class="rise-utm-toggle-slider"></span>
                            <?php _e('Auto-detect Google Ads via gad_source parameter', 'rise-utm-tracker'); ?>
                        </label>
                    </td>
                </tr>
            </table>
        </div>

        <?php submit_button(__('Save Settings', 'rise-utm-tracker')); ?>
    </form>
</div>

<style>
/* Toggle Switch */
.rise-utm-toggle {
    position: relative;
    display: inline-flex;
    align-items: center;
    cursor: pointer;
}

.rise-utm-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
    position: absolute;
}

.rise-utm-toggle-slider {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 24px;
    background-color: #ccc;
    border-radius: 34px;
    margin-right: 10px;
    transition: .4s;
}

.rise-utm-toggle-slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    border-radius: 50%;
    transition: .4s;
}

.rise-utm-toggle input:checked + .rise-utm-toggle-slider {
    background-color: #c0ff2d;
}

.rise-utm-toggle input:checked + .rise-utm-toggle-slider:before {
    transform: translateX(16px);
}

/* Help Icons */
.rise-utm-help {
    display: inline-block;
    margin-left: 5px;
    vertical-align: middle;
}

.rise-utm-help .dashicons {
    width: 16px;
    height: 16px;
    font-size: 16px;
    color: #666;
}

/* Sections */
.rise-utm-section {
    margin-bottom: 30px;
}

/* Form Table */
.form-table th {
    padding: 20px 10px 20px 0;
    width: 200px;
}

.form-table td {
    padding: 15px 10px;
}

/* Notifications */
.rise-utm-notification {
    padding: 12px 15px;
    margin: 5px 0 15px;
    border-left: 4px solid #c0ff2d;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.rise-utm-notification.error {
    border-left-color: #dc3232;
}

.rise-utm-notification p {
    margin: 0.5em 0;
    padding: 2px;
}
</style>