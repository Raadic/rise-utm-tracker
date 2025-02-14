<?php
/**
 * Help tab content
 *
 * @package RiseUTMTracker
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="rise-utm-wrapper">
    <!-- Common Questions -->
    <div class="rise-utm-card">
        <h2 class="rise-utm-section-title">
            <span class="dashicons dashicons-editor-help"></span>
            <?php _e('Common Questions', 'rise-utm-tracker'); ?>
        </h2>

        <div class="rise-utm-accordion">
            <?php
            $faqs = array(
                array(
                    'question' => __('What parameters are tracked?', 'rise-utm-tracker'),
                    'answer' => __('The plugin tracks UTM parameters (source, medium, campaign), GCLID (Google Click ID), and traffic channel.', 'rise-utm-tracker')
                ),
                array(
                    'question' => __('How do I add tracking to my forms?', 'rise-utm-tracker'),
                    'answer' => __('Go to your form editor and add any of the UTM fields (source, medium, campaign, channel) from the Advanced Fields section.', 'rise-utm-tracker')
                ),
                array(
                    'question' => __('How does channel detection work?', 'rise-utm-tracker'),
                    'answer' => __('The plugin automatically detects traffic sources based on UTM parameters, GCLID, and referrer information.', 'rise-utm-tracker')
                ),
                array(
                    'question' => __('What is GCLID detection?', 'rise-utm-tracker'),
                    'answer' => __('GCLID (Google Click ID) is automatically added to your URLs when someone clicks on your Google Ads. The plugin captures this to identify paid search traffic.', 'rise-utm-tracker')
                )
            );

            foreach ($faqs as $faq) : ?>
                <div class="rise-utm-accordion-item">
                    <div class="rise-utm-accordion-header">
                        <?php echo esc_html($faq['question']); ?>
                        <span class="rise-utm-accordion-icon"></span>
                    </div>
                    <div class="rise-utm-accordion-content">
                        <p><?php echo esc_html($faq['answer']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Need Help? -->
    <div class="rise-utm-card">
        <h2 class="rise-utm-section-title">
            <span class="dashicons dashicons-businessman"></span>
            <?php _e('Need More Help?', 'rise-utm-tracker'); ?>
        </h2>
        <div class="rise-utm-support">
            <p>
                <?php printf(
                    __('For support, please email us at %s', 'rise-utm-tracker'),
                    '<a href="mailto:Support@riseseo.com.au">Support@riseseo.com.au</a>'
                ); ?>
            </p>
        </div>
    </div>
</div>

<style>
/* Accordion Styles */
.rise-utm-accordion-item {
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 10px;
    background: #fff;
}

.rise-utm-accordion-header {
    padding: 15px;
    cursor: pointer;
    position: relative;
    font-weight: 500;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background-color 0.3s ease;
}

.rise-utm-accordion-header:hover {
    background-color: #f8f9fa;
}

.rise-utm-accordion-icon {
    width: 20px;
    height: 20px;
    position: relative;
}

.rise-utm-accordion-icon::before,
.rise-utm-accordion-icon::after {
    content: '';
    position: absolute;
    background-color: #666;
    transition: transform 0.3s ease;
}

.rise-utm-accordion-icon::before {
    width: 2px;
    height: 10px;
    top: 5px;
    left: 9px;
}

.rise-utm-accordion-icon::after {
    width: 10px;
    height: 2px;
    top: 9px;
    left: 5px;
}

.rise-utm-accordion-active .rise-utm-accordion-icon::before {
    transform: rotate(90deg);
}

.rise-utm-accordion-content {
    display: none;
    padding: 15px;
    border-top: 1px solid #ddd;
    background: #f8f9fa;
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

/* Support Section */
.rise-utm-support {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 4px;
}

.rise-utm-support a {
    color: #2271b1;
    text-decoration: none;
}

.rise-utm-support a:hover {
    color: #135e96;
}
</style>