<?php
/**
 * Settings tab content
 *
 * @package RiseUTMTracker
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="rise-utm-card">
    <form method="post" action="options.php" id="rise-utm-settings-form">
        <?php
        settings_fields('rise_utm_tracker_settings');
        do_settings_sections('rise_utm_tracker_settings');
        ?>
        
        <table class="form-table">
            <!-- Debug Settings -->
            <tr valign="top">
                <th scope="row">
                    <?php _e('Debug Tools', 'rise-utm-tracker'); ?>
                </th>
                <td>
                    <fieldset>
                        <label class="rise-utm-toggle">
                            <input type="checkbox" 
                                   name="rise_utm_tracker_debug_enabled" 
                                   id="rise-utm-debug-toggle"
                                   value="1" 
                                   <?php checked(get_option('rise_utm_tracker_debug_enabled'), 1); ?>>
                            <span class="rise-utm-toggle-slider"></span>
                            <?php _e('Enable debug console', 'rise-utm-tracker'); ?>
                        </label>
                        <p class="description">
                            <?php _e('Shows tracking information in browser console (F12)', 'rise-utm-tracker'); ?>
                        </p>
                    </fieldset>
                </td>
            </tr>

            <!-- Cookie Settings -->
            <tr valign="top">
                <th scope="row">
                    <?php _e('Cookie Settings', 'rise-utm-tracker'); ?>
                </th>
                <td>
                    <fieldset>
                        <label>
                            <input type="number" 
                                   name="rise_utm_tracker_cookie_lifetime" 
                                   value="<?php echo esc_attr(get_option('rise_utm_tracker_cookie_lifetime', 30)); ?>"
                                   min="1" 
                                   max="365"
                                   class="small-text">
                            <?php _e('days', 'rise-utm-tracker'); ?>
                        </label>
                        <p class="description">
                            <?php _e('How long to remember UTM parameters (1-365 days)', 'rise-utm-tracker'); ?>
                        </p>
                    </fieldset>
                </td>
            </tr>

            <!-- Form Integration -->
            <tr valign="top">
                <th scope="row">
                    <?php _e('Form Integration', 'rise-utm-tracker'); ?>
                </th>
                <td>
                    <fieldset>
                        <label>
                            <input type="checkbox" 
                                   name="rise_utm_tracker_hide_fields" 
                                   value="1" 
                                   <?php checked(get_option('rise_utm_tracker_hide_fields', 1)); ?>>
                            <?php _e('Hide tracking fields on forms', 'rise-utm-tracker'); ?>
                        </label>
                        <p class="description">
                            <?php _e('Automatically hide UTM tracking fields on your forms', 'rise-utm-tracker'); ?>
                        </p>
                    </fieldset>
                </td>
            </tr>
        </table>

        <?php submit_button(__('Save Settings', 'rise-utm-tracker')); ?>
    </form>
</div>