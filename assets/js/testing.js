/**
 * Testing Tab JavaScript
 */
(function($) {
    'use strict';

    const RiseTesting = {
        init: function() {
            this.initCookieInspector();
            this.initCopyButtons();
        },

        initCookieInspector: function() {
            this.loadCookies();

            $('#refreshCookies').on('click', () => {
                this.loadCookies();
            });

            $('#clearCookies').on('click', () => {
                if (confirm(riseUtmData.strings.confirmClearCookies)) {
                    this.clearAllCookies();
                }
            });

            $(document).on('click', '.rise-utm-delete-cookie', (e) => {
                const cookieName = $(e.currentTarget).data('cookie');
                if (confirm(riseUtmData.strings.confirmDeleteCookie.replace('{cookie}', cookieName))) {
                    this.deleteCookie(cookieName);
                }
            });
        },

        loadCookies: function() {
            const $inspector = $('#cookieInspector');
            $inspector.html('<div class="rise-utm-cookie-loading">Loading cookies...</div>');

            $.ajax({
                url: riseUtmData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'rise_get_tracking_cookies',
                    nonce: riseUtmData.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.displayCookies(response.data);
                    } else {
                        this.showError('Failed to load cookies');
                    }
                },
                error: () => {
                    this.showError('Connection error');
                }
            });
        },

        displayCookies: function(cookies) {
            const $inspector = $('#cookieInspector');
            
            if (Object.keys(cookies).length === 0) {
                $inspector.html('<div class="rise-utm-no-cookies">No tracking cookies found</div>');
                return;
            }

            let html = '';
            for (const [name, value] of Object.entries(cookies)) {
                html += `
                    <div class="rise-utm-cookie-item">
                        <div class="rise-utm-cookie-name">${this.formatCookieName(name)}</div>
                        <div class="rise-utm-cookie-value">${value}</div>
                        <div class="rise-utm-cookie-action">
                            <button type="button" class="rise-utm-delete-cookie button-link" data-cookie="${name}">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </div>
                    </div>
                `;
            }

            $inspector.html(html);
        },

        formatCookieName: function(name) {
            return name
                .split('_')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        },

        clearAllCookies: function() {
            $.ajax({
                url: riseUtmData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'rise_clear_tracking_cookies',
                    nonce: riseUtmData.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.showSuccess('All tracking cookies cleared');
                        this.loadCookies();
                    } else {
                        this.showError('Failed to clear cookies');
                    }
                },
                error: () => {
                    this.showError('Connection error');
                }
            });
        },

        deleteCookie: function(cookieName) {
            $.ajax({
                url: riseUtmData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'rise_delete_tracking_cookie',
                    cookie: cookieName,
                    nonce: riseUtmData.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.showSuccess(`Cookie ${this.formatCookieName(cookieName)} deleted`);
                        this.loadCookies();
                    } else {
                        this.showError('Failed to delete cookie');
                    }
                },
                error: () => {
                    this.showError('Connection error');
                }
            });
        },

        initCopyButtons: function() {
            $('.rise-utm-copy-button').on('click', (e) => {
                const $button = $(e.currentTarget);
                const textToCopy = $button.data('copy');
                
                navigator.clipboard.writeText(textToCopy)
                    .then(() => {
                        const $originalText = $button.text();
                        $button.text('Copied!');
                        setTimeout(() => {
                            $button.text($originalText);
                        }, 2000);
                    })
                    .catch(() => {
                        this.showError('Failed to copy text');
                    });
            });
        },

        showSuccess: function(message) {
            this.showNotification(message, 'success');
        },

        showError: function(message) {
            this.showNotification(message, 'error');
        },

        showNotification: function(message, type = 'success') {
            const $notification = $(`
                <div class="rise-utm-notification ${type}">
                    <p>${message}</p>
                </div>
            `);

            $('.rise-utm-notification').remove();
            $('.wrap').prepend($notification);

            setTimeout(() => {
                $notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        RiseTesting.init();
    });

})(jQuery);