<?php
/**
 * Gravity Forms integration
 *
 * @package RiseUTMTracker
 */

if (!defined('ABSPATH')) {
    exit;
}

class Rise_UTM_Gravity_Forms {
    /**
     * Field type identifier
     */
    const FIELD_TYPE = 'channel_tracking';

    /**
     * Constructor
     */
    public function __construct() {
        // Add custom field button
        add_filter('gform_add_field_buttons', array($this, 'add_field_button'));
        
        // Add title to field
        add_filter('gform_field_type_title', array($this, 'field_type_title'), 10, 2);
        
        // Add field settings
        add_action('gform_editor_js', array($this, 'editor_script'));
        
        // Add field classes
        add_filter('gform_field_css_class', array($this, 'field_css_class'), 10, 3);
        
        // Populate field values
        add_filter('gform_field_value', array($this, 'populate_field_values'), 10, 3);

        // Hide fields in form
        add_filter('gform_field_content', array($this, 'hide_tracking_fields'), 10, 5);
    }

    /**
     * Add field button to form editor
     */
    public function add_field_button($field_groups) {
        foreach ($field_groups as &$group) {
            if ($group['name'] === 'advanced_fields') {
                $group['fields'][] = array(
                    'class' => 'button',
                    'value' => __('Channel Tracking', 'rise-utm-tracker'),
                    'onclick' => "StartAddField('" . self::FIELD_TYPE . "');"
                );
                break;
            }
        }
        return $field_groups;
    }

    /**
     * Set field title in editor
     */
    public function field_type_title($title, $field_type) {
        if ($field_type === self::FIELD_TYPE) {
            return __('Channel Tracking', 'rise-utm-tracker');
        }
        return $title;
    }

    /**
     * Add editor script
     */
    public function editor_script() {
        ?>
        <script type="text/javascript">
            // Register field type
            fieldSettings['<?php echo self::FIELD_TYPE; ?>'] = '.label_setting, .description_setting, .admin_label_setting, .visibility_setting';

            // Bind to the load field settings event
            jQuery(document).bind('gform_load_field_settings', function(event, field, form) {
                if (field.type == '<?php echo self::FIELD_TYPE; ?>') {
                    // Any custom field settings initialization
                }
            });

            // Function to set default values when field is added
            function SetDefaultValues_<?php echo self::FIELD_TYPE; ?>(field) {
                field.label = '<?php _e('Channel Tracking', 'rise-utm-tracker'); ?>';
                
                // Create sub-fields
                field.inputs = [
                    {
                        id: field.id + '.1',
                        label: '<?php _e('Source', 'rise-utm-tracker'); ?>',
                        name: 'source'
                    },
                    {
                        id: field.id + '.2',
                        label: '<?php _e('Medium', 'rise-utm-tracker'); ?>',
                        name: 'medium'
                    },
                    {
                        id: field.id + '.3',
                        label: '<?php _e('Campaign', 'rise-utm-tracker'); ?>',
                        name: 'campaign'
                    },
                    {
                        id: field.id + '.4',
                        label: '<?php _e('GCLID', 'rise-utm-tracker'); ?>',
                        name: 'gclid'
                    },
                    {
                        id: field.id + '.5',
                        label: '<?php _e('GAD Source', 'rise-utm-tracker'); ?>',
                        name: 'gad_source'
                    },
                    {
                        id: field.id + '.6',
                        label: '<?php _e('Channel', 'rise-utm-tracker'); ?>',
                        name: 'channel'
                    }
                ];

                // Set field type specific settings
                field.visibility = 'hidden';
                field.enableColumns = false;
                field.inputs_label = '<?php _e('Tracking Fields', 'rise-utm-tracker'); ?>';
                
                return field;
            }
        </script>
        <?php
    }

    /**
     * Add custom CSS class to field
     */
    public function field_css_class($classes, $field, $form) {
        if ($field->type === self::FIELD_TYPE) {
            $classes .= ' rise-utm-tracking-field';
        }
        return $classes;
    }

    /**
     * Populate field values
     */
    public function populate_field_values($value, $field, $name) {
        // Map field names to parameters
        $param_mapping = array(
            'source' => 'utm_source',
            'medium' => 'utm_medium',
            'campaign' => 'utm_campaign',
            'gclid' => 'gclid',
            'gad_source' => 'gad_source',
            'channel' => 'traffic_channel'
        );
        
        // Check if the field name matches our mapping
        if (isset($param_mapping[$name])) {
            return isset($_COOKIE[$param_mapping[$name]]) ? 
                   $_COOKIE[$param_mapping[$name]] : '';
        }
        
        return $value;
    }

    /**
     * Hide tracking fields in form
     */
    public function hide_tracking_fields($content, $field, $value, $entry_id, $form_id) {
        if ($field->type === self::FIELD_TYPE) {
            // Add hidden class and data attributes for debugging
            $content = sprintf(
                '<div class="rise-utm-tracking-fields" style="display: none;" data-tracking-id="%d">%s</div>',
                $field->id,
                $content
            );
        }
        return $content;
    }

    /**
     * Get tracking field data
     */
    public static function get_tracking_data($form_id, $entry = null) {
        $tracking_data = array();
        
        if ($entry) {
            $form = GFAPI::get_form($form_id);
            foreach ($form['fields'] as $field) {
                if ($field->type === self::FIELD_TYPE) {
                    foreach ($field->inputs as $input) {
                        $tracking_data[$input['name']] = rgar($entry, $input['id']);
                    }
                    break;
                }
            }
        }
        
        return $tracking_data;
    }

    /**
     * Get entry tracking source
     */
    public static function get_entry_source($entry, $form) {
        $tracking_data = self::get_tracking_data($form['id'], $entry);
        return isset($tracking_data['source']) ? $tracking_data['source'] : '';
    }

    /**
     * Get entry tracking medium
     */
    public static function get_entry_medium($entry, $form) {
        $tracking_data = self::get_tracking_data($form['id'], $entry);
        return isset($tracking_data['medium']) ? $tracking_data['medium'] : '';
    }

    /**
     * Get entry tracking campaign
     */
    public static function get_entry_campaign($entry, $form) {
        $tracking_data = self::get_tracking_data($form['id'], $entry);
        return isset($tracking_data['campaign']) ? $tracking_data['campaign'] : '';
    }

    /**
     * Get entry tracking channel
     */
    public static function get_entry_channel($entry, $form) {
        $tracking_data = self::get_tracking_data($form['id'], $entry);
        return isset($tracking_data['channel']) ? $tracking_data['channel'] : '';
    }

    /**
     * Add merge tags
     */
    public function add_merge_tags($merge_tags, $form_id, $fields, $element_id) {
        if (!$fields) return $merge_tags;

        foreach ($fields as $field) {
            if ($field->type === self::FIELD_TYPE) {
                $merge_tags[] = array(
                    'label' => __('UTM Source', 'rise-utm-tracker'),
                    'tag' => "{tracking:source}"
                );
                $merge_tags[] = array(
                    'label' => __('UTM Medium', 'rise-utm-tracker'),
                    'tag' => "{tracking:medium}"
                );
                $merge_tags[] = array(
                    'label' => __('UTM Campaign', 'rise-utm-tracker'),
                    'tag' => "{tracking:campaign}"
                );
                $merge_tags[] = array(
                    'label' => __('Traffic Channel', 'rise-utm-tracker'),
                    'tag' => "{tracking:channel}"
                );
                break;
            }
        }

        return $merge_tags;
    }
}
