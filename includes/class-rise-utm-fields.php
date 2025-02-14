<?php
/**
 * Rise UTM Fields for Gravity Forms
 *
 * @package RiseUTMTracker
 */

if (!defined('ABSPATH')) {
    exit;
}

if (class_exists('GF_Field')) {
    /**
     * Base UTM Field class
     */
    abstract class Rise_UTM_Field_Base extends GF_Field {
        /**
         * Whether to hide the field on the front end
         */
        protected $is_hidden = true;

        /**
         * Cookie name for this field
         */
        protected $cookie_name = '';

        /**
         * Constructor
         */
        public function __construct($data = array()) {
            parent::__construct($data);
            
            // Make field hidden by default
            $this->visibility = 'visible';
            $this->is_hidden = get_option('rise_utm_tracker_hide_fields', true);
        }

        /**
         * Get form editor field settings
         */
        public function get_form_editor_field_settings() {
            return array(
                'label_setting',
                'visibility_setting',
                'conditional_logic_field_setting',
                'css_class_setting'
            );
        }

        /**
         * Get field input
         */
        public function get_field_input($form, $value = '', $entry = null) {
            $id = (int) $this->id;
            $form_id = $form['id'];
            $is_entry_detail = $this->is_entry_detail();
            $is_form_editor = $this->is_form_editor();

            // Get value from cookie
            $cookie_value = isset($_COOKIE[$this->cookie_name]) ? $_COOKIE[$this->cookie_name] : '';
            
            // Create hidden input
            $input = sprintf(
                '<input type="hidden" name="input_%d" id="input_%d_%d" value="%s" />',
                $id,
                $form_id,
                $id,
                esc_attr($cookie_value)
            );

            // If not hidden and not in admin, show the value
            if (!$this->is_hidden && !$is_entry_detail && !$is_form_editor) {
                $input .= sprintf(
                    '<div class="rise-utm-field-display">
                        <span class="rise-utm-field-label">%s:</span>
                        <span class="rise-utm-field-value">%s</span>
                    </div>',
                    esc_html($this->label),
                    esc_html($cookie_value ?: '(not set)')
                );
            }

            return $input;
        }

        /**
         * Get value for entry detail
         */
        public function get_value_entry_detail($value, $currency = '', $use_text = false, $format = 'html', $media = 'screen') {
            if (empty($value)) {
                return '';
            }

            return esc_html($value);
        }

        /**
         * Get value for save
         */
        public function get_value_save_entry($value, $form, $input_name, $entry_id, $entry) {
            return isset($_COOKIE[$this->cookie_name]) ? $_COOKIE[$this->cookie_name] : '';
        }
    }

    /**
     * UTM Source Field
     */
    class Rise_UTM_Source_Field extends Rise_UTM_Field_Base {
        public $type = 'rise_utm_source';
        protected $cookie_name = 'utm_source';

        public function __construct($data = array()) {
            parent::__construct($data);
            $this->label = esc_html__('UTM Source', 'rise-utm-tracker');
        }

        public function get_form_editor_field_title() {
            return esc_attr__('UTM Source', 'rise-utm-tracker');
        }

        public function get_form_editor_button() {
            return array(
                'group' => 'advanced_fields',
                'text'  => $this->get_form_editor_field_title()
            );
        }
    }

    /**
     * UTM Medium Field
     */
    class Rise_UTM_Medium_Field extends Rise_UTM_Field_Base {
        public $type = 'rise_utm_medium';
        protected $cookie_name = 'utm_medium';

        public function __construct($data = array()) {
            parent::__construct($data);
            $this->label = esc_html__('UTM Medium', 'rise-utm-tracker');
        }

        public function get_form_editor_field_title() {
            return esc_attr__('UTM Medium', 'rise-utm-tracker');
        }

        public function get_form_editor_button() {
            return array(
                'group' => 'advanced_fields',
                'text'  => $this->get_form_editor_field_title()
            );
        }
    }

    /**
     * UTM Campaign Field
     */
    class Rise_UTM_Campaign_Field extends Rise_UTM_Field_Base {
        public $type = 'rise_utm_campaign';
        protected $cookie_name = 'utm_campaign';

        public function __construct($data = array()) {
            parent::__construct($data);
            $this->label = esc_html__('UTM Campaign', 'rise-utm-tracker');
        }

        public function get_form_editor_field_title() {
            return esc_attr__('UTM Campaign', 'rise-utm-tracker');
        }

        public function get_form_editor_button() {
            return array(
                'group' => 'advanced_fields',
                'text'  => $this->get_form_editor_field_title()
            );
        }
    }

    /**
     * Traffic Channel Field
     */
    class Rise_Traffic_Channel_Field extends Rise_UTM_Field_Base {
        public $type = 'rise_traffic_channel';
        protected $cookie_name = 'traffic_channel';

        public function __construct($data = array()) {
            parent::__construct($data);
            $this->label = esc_html__('Traffic Channel', 'rise-utm-tracker');
        }

        public function get_form_editor_field_title() {
            return esc_attr__('Traffic Channel', 'rise-utm-tracker');
        }

        public function get_form_editor_button() {
            return array(
                'group' => 'advanced_fields',
                'text'  => $this->get_form_editor_field_title()
            );
        }
    }

    /**
     * GCLID Field
     */
    class Rise_GCLID_Field extends Rise_UTM_Field_Base {
        public $type = 'rise_gclid';
        protected $cookie_name = 'gclid';

        public function __construct($data = array()) {
            parent::__construct($data);
            $this->label = esc_html__('GCLID', 'rise-utm-tracker');
        }

        public function get_form_editor_field_title() {
            return esc_attr__('GCLID', 'rise-utm-tracker');
        }

        public function get_form_editor_button() {
            return array(
                'group' => 'advanced_fields',
                'text'  => $this->get_form_editor_field_title()
            );
        }
    }

    // Register all fields
    GF_Fields::register(new Rise_UTM_Source_Field());
    GF_Fields::register(new Rise_UTM_Medium_Field());
    GF_Fields::register(new Rise_UTM_Campaign_Field());
    GF_Fields::register(new Rise_Traffic_Channel_Field());
    GF_Fields::register(new Rise_GCLID_Field());
}