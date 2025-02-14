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
         * Cookie name for this field
         */
        protected $cookie_name = '';

        /**
         * Constructor
         */
        public function __construct($data = array()) {
            parent::__construct($data);
            
            // Always make the field hidden on the front end
            $this->visibility = 'hidden';
            
            // Allow field to still save data
            $this->displayOnly = false;
        }

        /**
         * Get form editor field settings
         */
        public function get_form_editor_field_settings() {
            return array(
                'label_setting',
                'conditional_logic_field_setting',
                'css_class_setting',
                'admin_label_setting'
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

            // Get cookie value
            $cookie_value = isset($_COOKIE[$this->cookie_name]) ? $_COOKIE[$this->cookie_name] : '';
            
            // In form editor or entry detail, show a placeholder
            if ($is_form_editor || $is_entry_detail) {
                return sprintf(
                    '<div class="rise-utm-field-admin">
                        <span class="rise-utm-field-label">%s</span>
                        <span class="rise-utm-field-value">%s</span>
                    </div>',
                    esc_html($this->label),
                    $is_entry_detail ? esc_html($cookie_value) : '[' . esc_html__('Hidden Field', 'rise-utm-tracker') . ']'
                );
            }

            // On front end, just return the hidden input
            return sprintf(
                '<input type="hidden" name="input_%d" id="input_%d_%d" value="%s" />',
                $id,
                $form_id,
                $id,
                esc_attr($cookie_value)
            );
        }

        /**
         * Get value for entry detail
         */
        public function get_value_entry_detail($value, $currency = '', $use_text = false, $format = 'html', $media = 'screen') {
            return !empty($value) ? esc_html($value) : '';
        }

        /**
         * Get value for entry list
         */
        public function get_value_entry_list($value, $entry, $field_id, $columns, $form) {
            return !empty($value) ? esc_html($value) : '';
        }

        /**
         * Get value for save
         */
        public function get_value_save_entry($value, $form, $input_name, $entry_id, $entry) {
            return isset($_COOKIE[$this->cookie_name]) ? $_COOKIE[$this->cookie_name] : '';
        }
    }

    // Field class definitions remain the same...
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