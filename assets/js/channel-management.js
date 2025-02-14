/**
 * Channel Management JavaScript
 */
(function($) {
    'use strict';

    const RiseChannelManager = {
        init: function() {
            this.initPriorityList();
            this.initFormSubmit();
        },

        initPriorityList: function() {
            const $list = $('#channelPriority');
            if ($list.length) {
                $list.sortable({
                    handle: '.dashicons-move',
                    placeholder: 'rise-utm-priority-item is-dragging',
                    update: (event, ui) => {
                        this.updatePriorityInput();
                    }
                });
            }
        },

        initFormSubmit: function() {
            $('#rise-utm-channel-form').on('submit', (e) => {
                e.preventDefault();
                this.saveChannelSettings($(e.currentTarget));
            });
        },

        updatePriorityInput: function() {
            const priorityOrder = $('#channelPriority').sortable('toArray', {
                attribute: 'data-channel'
            });
            $('#channelPriorityInput').val(priorityOrder.join(','));
        },

        saveChannelSettings: function($form) {
            const $submitButton = $form.find('[type="submit"]');
            const originalText = $submitButton.val();

            // Update priority input before saving
            this.updatePriorityInput();

            // Show loading state
            $submitButton.prop('disabled', true)
                .val('Saving...')
                .addClass('updating-message');

            // Get form data
            const formData = new FormData($form[0]);
            formData.append('action', 'rise_save_channel_settings');
            formData.append('nonce', riseUtmData.nonce);

            // Make AJAX request
            $.ajax({
                url: riseUtmData.ajaxUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    if (response.success) {
                        this.showNotification('Settings saved successfully', 'success');
                    } else {
                        this.showNotification(response.data?.message || 'Failed to save settings', 'error');
                    }
                },
                error: () => {
                    this.showNotification('Failed to save settings', 'error');
                },
                complete: () => {
                    $submitButton.prop('disabled', false)
                        .val(originalText)
                        .removeClass('updating-message');
                }
            });
        },

        showNotification: function(message, type = 'success') {
            const $notification = $(`
                <div class="rise-utm-notification ${type}">
                    <p>${message}</p>
                </div>
            `);

            // Remove any existing notifications
            $('.rise-utm-notification').remove();

            // Add new notification
            $('.wrap').prepend($notification);

            // Auto-remove after delay
            setTimeout(() => {
                $notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        RiseChannelManager.init();
    });

})(jQuery);