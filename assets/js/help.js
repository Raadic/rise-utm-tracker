/**
 * Help Tab JavaScript
 */
(function($) {
    'use strict';

    const RiseHelp = {
        init: function() {
            this.initAccordion();
            this.initCopyButtons();
        },

        initAccordion: function() {
            $('.rise-utm-accordion-item .rise-utm-accordion-header').on('click', function(e) {
                e.preventDefault();
                const $header = $(this);
                const $content = $header.next('.rise-utm-accordion-content');
                const $item = $header.closest('.rise-utm-accordion-item');
                
                // Toggle current item
                $content.slideToggle(300);
                $item.toggleClass('rise-utm-accordion-active');
                $header.find('.rise-utm-accordion-icon').toggleClass('rise-utm-accordion-icon-active');

                // Optional: Close other items
                // $('.rise-utm-accordion-item').not($item).removeClass('rise-utm-accordion-active')
                //     .find('.rise-utm-accordion-content').slideUp(300)
                //     .end()
                //     .find('.rise-utm-accordion-icon').removeClass('rise-utm-accordion-icon-active');
            });
        },

        initCopyButtons: function() {
            $('.rise-utm-copy-button').on('click', function(e) {
                e.preventDefault();
                const $button = $(this);
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
                        alert('Failed to copy text');
                    });
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        RiseHelp.init();
    });

})(jQuery);