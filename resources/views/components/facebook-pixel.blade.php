@php
    $settings = \App\Models\Setting::first();
    $pixelId = $settings->facebook_pixel_id ?? null;
    $pixelEnabled = $settings->facebook_pixel_enabled ?? false;
    $enabledEvents = $settings->facebook_pixel_events ? json_decode($settings->facebook_pixel_events, true) : [];
@endphp

@if ($pixelEnabled && $pixelId)
    <!-- Facebook Pixel Code -->
    <script>
        // Validate Pixel ID format
        function validatePixelId(pixelId) {
            const pixelIdRegex = /^\d{15,16}$/;
            return pixelIdRegex.test(pixelId);
        }

        // Check if pixel ID is valid
        const pixelId = '{{ $pixelId }}';
        if (!validatePixelId(pixelId)) {
            // Invalid Pixel ID format
        } else {
            // Facebook Pixel ID validated
        }

        // Load Facebook Pixel script
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');

        // Initialize pixel with error handling and connection testing
        try {
            fbq('init', pixelId);
            // Facebook Pixel initialized successfully

            // Test pixel connection
            fbq('track', 'PageView');
            // Facebook Pixel PageView event sent

            // Additional connection test
            setTimeout(function() {
                if (typeof fbq !== 'undefined' && fbq.callMethod) {
                    // Facebook Pixel is active and ready
                } else {
                    // Facebook Pixel failed to initialize properly
                }
            }, 1000);

        } catch (error) {
            // Facebook Pixel initialization failed
        }
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id={{ $pixelId }}&ev=PageView&noscript=1" /></noscript>
    <!-- End Facebook Pixel Code -->

    <!-- Facebook Pixel Helper Functions -->
    <script>
        window.FacebookPixel = {
            pixelId: '{{ $pixelId }}',
            enabledEvents: @json($enabledEvents),
            isConnected: false,
            connectionTested: false,

            // Test pixel connection
            testConnection: function() {
                if (this.connectionTested) return this.isConnected;

                try {
                    if (typeof fbq !== 'undefined' && fbq.callMethod) {
                        this.isConnected = true;
                        this.connectionTested = true;
                        // Facebook Pixel connection test passed
                        return true;
                    } else {
                        this.isConnected = false;
                        this.connectionTested = true;
                        // Facebook Pixel connection test failed
                        return false;
                    }
                } catch (error) {
                    this.isConnected = false;
                    this.connectionTested = true;
                    // Facebook Pixel connection test error
                    return false;
                }
            },

            // Validate event before tracking
            validateEvent: function(eventName, parameters) {
                if (!this.testConnection()) {
                    // Cannot track event - Pixel not connected
                    return false;
                }

                if (!this.enabledEvents.includes(eventName) && !this.enabledEvents.includes('all')) {
                    // Event not enabled
                    return false;
                }

                return true;
            },

            // Track custom events with validation
            track: function(eventName, parameters = {}) {
                if (!this.validateEvent(eventName, parameters)) {
                    return false;
                }

                try {
                    fbq('track', eventName, parameters);
                    return true;
                } catch (error) {
                    // Failed to send event
                    return false;
                }
            },

            // Track PageView
            trackPageView: function() {
                this.track('PageView');
            },

            // Track ViewContent
            trackViewContent: function(contentData) {
                this.track('ViewContent', contentData);
            },

            // Track AddToCart
            trackAddToCart: function(cartData) {
                this.track('AddToCart', cartData);
            },

            // Track InitiateCheckout
            trackInitiateCheckout: function(checkoutData) {
                this.track('InitiateCheckout', checkoutData);
            },

            // Track Purchase
            trackPurchase: function(purchaseData) {
                this.track('Purchase', purchaseData);
            },

            // Track Lead
            trackLead: function(leadData) {
                this.track('Lead', leadData);
            },

            // Track CompleteRegistration
            trackCompleteRegistration: function(registrationData) {
                this.track('CompleteRegistration', registrationData);
            },

            // Track Search
            trackSearch: function(searchData) {
                this.track('Search', searchData);
            },

            // Track Contact
            trackContact: function(contactData) {
                this.track('Contact', contactData);
            },

            // Get pixel status and debug info
            getStatus: function() {
                const status = {
                    pixelId: this.pixelId,
                    isEnabled: true,
                    isConnected: this.testConnection(),
                    enabledEvents: this.enabledEvents,
                    fbqAvailable: typeof fbq !== 'undefined',
                    timestamp: new Date().toISOString()
                };

                // Facebook Pixel Status logged
                return status;
            },

            // Debug helper - test all events
            testAllEvents: function() {
                // Testing all enabled events
                const testData = {
                    content_ids: ['test_123'],
                    content_type: 'product',
                    content_name: 'Test Product',
                    content_category: 'Test Category',
                    value: 99.99,
                    currency: 'BDT'
                };

                this.enabledEvents.forEach(eventName => {
                    if (eventName !== 'PageView') {
                        // Testing event
                        this.track(eventName, testData);
                    }
                });
            },

            // Manual connection test
            forceConnectionTest: function() {
                // Forcing connection test
                this.connectionTested = false;
                return this.testConnection();
            }
        };

        // Auto-track page view on load
        document.addEventListener('DOMContentLoaded', function() {
            if (window.FacebookPixel.enabledEvents.includes('PageView') || window.FacebookPixel.enabledEvents
                .includes('all')) {
                window.FacebookPixel.trackPageView();
            }

            // Show pixel status in console
            setTimeout(function() {
                window.FacebookPixel.getStatus();

                // Add debugging helpers to window for easy access
                window.pixelDebug = {
                    status: () => window.FacebookPixel.getStatus(),
                    testAll: () => window.FacebookPixel.testAllEvents(),
                    testConnection: () => window.FacebookPixel.forceConnectionTest(),
                    track: (event, data) => window.FacebookPixel.track(event, data)
                };

                // Debug helpers available
            }, 2000);
        });
    </script>
@else
    <!-- Facebook Pixel Disabled -->
    <script>
        // Facebook Pixel is disabled

        window.FacebookPixel = {
            pixelId: null,
            enabledEvents: [],
            isConnected: false,
            connectionTested: true,

            track: function() {
                // Facebook Pixel disabled - event not tracked
            },
            trackPageView: function() {
                // Facebook Pixel disabled - PageView not tracked
            },
            trackViewContent: function() {
                // Facebook Pixel disabled - ViewContent not tracked
            },
            trackAddToCart: function() {
                // Facebook Pixel disabled - AddToCart not tracked
            },
            trackInitiateCheckout: function() {
                // Facebook Pixel disabled - InitiateCheckout not tracked
            },
            trackPurchase: function() {
                // Facebook Pixel disabled - Purchase not tracked
            },
            trackLead: function() {
                // Facebook Pixel disabled - Lead not tracked
            },
            trackCompleteRegistration: function() {
                // Facebook Pixel disabled - CompleteRegistration not tracked
            },
            trackSearch: function() {
                // Facebook Pixel disabled - Search not tracked
            },
            trackContact: function() {
                // Facebook Pixel disabled - Contact not tracked
            },

            getStatus: function() {
                const status = {
                    pixelId: null,
                    isEnabled: false,
                    isConnected: false,
                    enabledEvents: [],
                    fbqAvailable: false,
                    timestamp: new Date().toISOString()
                };
                // Facebook Pixel Status logged
                return status;
            },

            testConnection: function() {
                // Facebook Pixel disabled - connection test skipped
                return false;
            }
        };
    </script>
@endif
