/**
 * Rise UTM Tracker Admin JavaScript
 * Version: 1.0.0
 */

(function($) {
    'use strict';

    // Main admin object
    const RiseUTMAdmin = {
        /**
         * Initialize admin functionality
         */
        init: function() {
            this.bindEvents();
            this.initTooltips();
            this.initTabs();
            this.initCopyButtons();
            this.checkDebugStatus();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Settings form submission
            $('#rise-utm-settings-form').on('submit', this.handleSettingsSubmit.bind(this));
            
            // Debug toggle
            $('#rise-utm-debug-toggle').on('change', this.handleDebugToggle.bind(this));
            
            // Test tracking button
            $('#rise-utm-test-tracking').on('click', this.handleTestTracking.bind(this));
        },

        /**
         * Initialize tooltips
         */
        initTooltips: function() {
            $('.rise-utm-help-tip').each(function() {
                $(this).tooltip({
                    content: $(this).attr('title'),
                    position: { my: 'left+10 center', at: 'right center' },
                    tooltipClass: 'rise-utm-tooltip'
                });
            });
        },

        /**
         * Initialize tabs
         */
        initTabs: function() {
            const $tabs = $('.rise-utm-tabs');
            const $tabPanels = $('.rise-utm-tab-panel');

            $tabs.on('click', 'button', function(e) {
                e.preventDefault();
                const targetId = $(this).data('tab');

                // Update tabs
                $tabs.find('button').removeClass('active');
                $(this).addClass('active');

                // Update panels
                $tabPanels.removeClass('active');
                $(`#${targetId}`).addClass('active');

                // Save last active tab
                localStorage.setItem('riseUtmLastTab', targetId);
            });

            // Restore last active tab
            const lastTab = localStorage.getItem('riseUtmLastTab');
            if (lastTab) {
                $tabs.find(`[data-tab="${lastTab}"]`).trigger('click');
            }
        },

        /**
         * Initialize copy buttons
         */
        initCopyButtons: function() {
            $('.rise-utm-copy-button').on('click', function(e) {
                e.preventDefault();
                const textToCopy = $(this).data('copy');
                
                navigator.clipboard.writeText(textToCopy).then(() => {
                    // Show success message
                    const $button = $(this);
                    const originalText = $button.text();
                    
                    $button.text('Copied!').addClass('copied');
                    
                    setTimeout(() => {
                        $button.text(originalText).removeClass('copied');
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy text:', err);
                });
            });
        },

        /**
         * Handle settings form submission
         */
        handleSettingsSubmit: function(e) {
            const $form = $(e.target);
            const $submitButton = $form.find('[type="submit"]');

            // Save button loading state
            $submitButton.prop('disabled', true)
                .addClass('updating-message')
                .data('originalText', $submitButton.text())
                .text('Saving...');

            // After save completion (using jQuery's ajaxComplete)
            $(document).ajaxComplete(function() {
                $submitButton.prop('disabled', false)
                    .removeClass('updating-message')
                    .text('Settings Saved!');

                setTimeout(() => {
                    $submitButton.text($submitButton.data('originalText'));
                }, 2000);
            });
        },

        /**
         * Handle debug toggle
         */
        handleDebugToggle: function(e) {
            const isEnabled = $(e.target).prop('checked');
            
            // Show confirmation for enabling debug
            if (isEnabled) {
                this.showDebugNotice();
            }

            // Update debug status in local storage
            localStorage.setItem('riseUtmDebug', isEnabled);
        },

        /**
         * Show debug notice
         */
        showDebugNotice: function() {
            const notice = $('<div>')
                .addClass('notice notice-warning is-dismissible')
                .html(`
                    <p>
                        <strong>Debug Mode Enabled</strong><br>
                        Tracking information will be logged to the browser console. 
                        Remember to disable debug mode in production.
                    </p>
                `);

            $('.rise-utm-wrapper').prepend(notice);

            // Make notice dismissible
            notice.find('.notice-dismiss').on('click', function() {
                notice.fadeOut(300, function() { $(this).remove(); });
            });
        },

        /**
         * Check debug status
         */
        checkDebugStatus: function() {
            const isDebugEnabled = $('#rise-utm-debug-toggle').prop('checked');
            
            if (isDebugEnabled) {
                console.info('Rise UTM Tracker Debug Mode: Enabled');
                console.info('Version:', riseUtmData.version);
                console.group('Active Tracking Parameters');
                console.log('UTM Parameters:', this.getActiveUtmParams());
                console.log('Traffic Channel:', this.getTrafficChannel());
                console.groupEnd();
            }
        },

        /**
         * Get active UTM parameters
         */
        getActiveUtmParams: function() {
            const params = {};
            const cookies = document.cookie.split(';');
            
            cookies.forEach(cookie => {
                const [name, value] = cookie.split('=').map(c => c.trim());
                if (name.startsWith('utm_') || name === 'gclid' || name === 'gad_source') {
                    params[name] = decodeURIComponent(value);
                }
            });

            return params;
        },

        /**
         * Get traffic channel
         */
        getTrafficChannel: function() {
            const cookies = document.cookie.split(';');
            let channel = 'Not set';

            cookies.forEach(cookie => {
                const [name, value] = cookie.split('=').map(c => c.trim());
                if (name === 'traffic_channel') {
                    channel = decodeURIComponent(value);
                }
            });

            return channel;
        },

        /**
         * Handle test tracking
         */
        handleTestTracking: function(e) {
            e.preventDefault();
            
            // Generate test URL with parameters
            const baseUrl = window.location.origin;
            const testParams = {
                utm_source: 'test',
                utm_medium: 'test',
                utm_campaign: 'debug',
                gclid: 'test_' + Date.now()
            };

            const testUrl = new URL(baseUrl);
            Object.entries(testParams).forEach(([key, value]) => {
                testUrl.searchParams.append(key, value);
            });

            // Open test URL in new tab
            window.open(testUrl.toString(), '_blank');
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        RiseUTMAdmin.init();
    });

})(jQuery);
