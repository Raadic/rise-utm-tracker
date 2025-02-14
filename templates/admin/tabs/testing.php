<?php
/**
 * Testing tab content
 *
 * @package RiseUTMTracker
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="rise-utm-wrapper">
    <!-- Cookie Inspector -->
    <div class="rise-utm-card">
        <h2 class="rise-utm-section-title">
            <span class="dashicons dashicons-visibility"></span>
            <?php _e('Cookie Inspector', 'rise-utm-tracker'); ?>
        </h2>

        <div class="rise-utm-cookie-inspector">
            <div class="rise-utm-cookie-actions">
                <button type="button" class="button" id="refreshCookies">
                    <span class="dashicons dashicons-update"></span>
                    <?php _e('Refresh Cookies', 'rise-utm-tracker'); ?>
                </button>
                <button type="button" class="button" id="clearCookies">
                    <span class="dashicons dashicons-trash"></span>
                    <?php _e('Clear All Tracking Cookies', 'rise-utm-tracker'); ?>
                </button>
            </div>

            <div id="cookieInspector" class="rise-utm-cookie-list">
                <div class="rise-utm-cookie-loading">
                    <?php _e('Loading cookies...', 'rise-utm-tracker'); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Testing Tools -->
    <div class="rise-utm-card">
        <h2 class="rise-utm-section-title">
            <span class="dashicons dashicons-media-text"></span>
            <?php _e('Quick Test Links', 'rise-utm-tracker'); ?>
        </h2>

        <div class="rise-utm-test-links">
            <!-- Google Ads -->
            <div class="rise-utm-test-link">
                <div class="rise-utm-test-label">Google Ads</div>
                <code>?gclid=test123&utm_source=google&utm_medium=cpc</code>
                <div class="rise-utm-test-actions">
                    <button type="button" class="rise-utm-copy-button" 
                            data-copy="?gclid=test123&utm_source=google&utm_medium=cpc">
                        <span class="dashicons dashicons-clipboard"></span>
                        <?php _e('Copy', 'rise-utm-tracker'); ?>
                    </button>
                </div>
            </div>

            <!-- Facebook Ads -->
            <div class="rise-utm-test-link">
                <div class="rise-utm-test-label">Facebook Ads</div>
                <code>?utm_source=facebook&utm_medium=paid-social&utm_campaign=test</code>
                <div class="rise-utm-test-actions">
                    <button type="button" class="rise-utm-copy-button" 
                            data-copy="?utm_source=facebook&utm_medium=paid-social&utm_campaign=test">
                        <span class="dashicons dashicons-clipboard"></span>
                        <?php _e('Copy', 'rise-utm-tracker'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Cookie Inspector */
.rise-utm-cookie-inspector {
    margin-top: 20px;
}

.rise-utm-cookie-actions {
    margin-bottom: 20px;
    display: flex;
    gap: 10px;
}

.rise-utm-cookie-actions .button {
    display: flex;
    align-items: center;
    gap: 5px;
}

.rise-utm-cookie-list {
    background: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
}

.rise-utm-cookie-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.rise-utm-cookie-item:last-child {
    border-bottom: none;
}

.rise-utm-cookie-name {
    font-weight: 500;
    min-width: 150px;
}

.rise-utm-cookie-value {
    flex: 1;
    font-family: monospace;
    margin: 0 10px;
    color: #666;
}

.rise-utm-cookie-action {
    display: flex;
    gap: 5px;
}

.rise-utm-cookie-loading {
    text-align: center;
    color: #666;
    padding: 20px;
}

.rise-utm-no-cookies {
    text-align: center;
    color: #666;
    padding: 20px;
    font-style: italic;
}

/* Test Links */
.rise-utm-test-links {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.rise-utm-test-link {
    background: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.rise-utm-test-label {
    font-weight: 500;
    min-width: 120px;
}

.rise-utm-test-link code {
    flex: 1;
    padding: 8px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 3px;
}

.rise-utm-copy-button {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px;
    background: none;
    border: 1px solid #ddd;
    border-radius: 3px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.rise-utm-copy-button:hover {
    background: #f0f0f1;
}

.rise-utm-copy-button .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
}

/* Section Title */
.rise-utm-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    font-size: 18px;
}

.rise-utm-section-title .dashicons {
    width: 24px;
    height: 24px;
    font-size: 24px;
    color: #c0ff2d;
}

/* Responsive */
@media screen and (max-width: 782px) {
    .rise-utm-test-link {
        flex-direction: column;
        align-items: flex-start;
    }

    .rise-utm-test-label {
        margin-bottom: 5px;
    }

    .rise-utm-test-actions {
        margin-top: 10px;
    }
}
</style>