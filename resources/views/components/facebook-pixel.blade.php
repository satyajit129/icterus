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
            console.error('❌ Facebook Pixel Error: Invalid Pixel ID format. Expected 15-16 digits, got:', pixelId);
            console.error('Please check your Pixel ID in Settings > Facebook Pixel Settings');
        } else {
            console.log('✅ Facebook Pixel ID validated:', pixelId);
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
            console.log('✅ Facebook Pixel initialized successfully with ID:', pixelId);

            // Test pixel connection
            fbq('track', 'PageView');
            console.log('✅ Facebook Pixel PageView event sent');

            // Additional connection test
            setTimeout(function() {
                if (typeof fbq !== 'undefined' && fbq.callMethod) {
                    console.log('✅ Facebook Pixel is active and ready');
                    console.log('📊 Pixel Status: Connected and tracking');
                    console.log('🎯 Available events:', @json($enabledEvents));
                } else {
                    console.error('❌ Facebook Pixel failed to initialize properly');
                }
            }, 1000);

        } catch (error) {
            console.error('❌ Facebook Pixel initialization failed:', error);
            console.error('Please check your internet connection and Pixel ID');
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
                        console.log('✅ Facebook Pixel connection test passed');
                        return true;
                    } else {
                        this.isConnected = false;
                        this.connectionTested = true;
                        console.error('❌ Facebook Pixel connection test failed');
                        return false;
                    }
                } catch (error) {
                    this.isConnected = false;
                    this.connectionTested = true;
                    console.error('❌ Facebook Pixel connection test error:', error);
                    return false;
                }
            },

            // Validate event before tracking
            validateEvent: function(eventName, parameters) {
                if (!this.testConnection()) {
                    console.error('❌ Cannot track event - Pixel not connected');
                    return false;
                }

                if (!this.enabledEvents.includes(eventName) && !this.enabledEvents.includes('all')) {
                    console.warn('⚠️ Event not enabled:', eventName);
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
                    console.log('🎯 Facebook Pixel Event:', eventName, parameters);
                    fbq('track', eventName, parameters);
                    console.log('✅ Event sent successfully:', eventName);
                    return true;
                } catch (error) {
                    console.error('❌ Failed to send event:', eventName, error);
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

                console.log('📊 Facebook Pixel Status:', status);
                return status;
            },

            // Debug helper - test all events
            testAllEvents: function() {
                console.log('🧪 Testing all enabled events...');
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
                        console.log(`Testing event: ${eventName}`);
                        this.track(eventName, testData);
                    }
                });
            },

            // Manual connection test
            forceConnectionTest: function() {
                console.log('🔍 Forcing connection test...');
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

                console.log('🛠️ Debug helpers available:');
                console.log('- pixelDebug.status() - Get pixel status');
                console.log('- pixelDebug.testAll() - Test all events');
                console.log('- pixelDebug.testConnection() - Test connection');
                console.log('- pixelDebug.track(event, data) - Track custom event');
            }, 2000);
        });
    </script>
@else
    <!-- Facebook Pixel Disabled -->
    <script>
        console.log('⚠️ Facebook Pixel is disabled');
        console.log('To enable: Go to Settings > Facebook Pixel Settings');

        window.FacebookPixel = {
            pixelId: null,
            enabledEvents: [],
            isConnected: false,
            connectionTested: true,

            track: function() {
                console.log('⚠️ Facebook Pixel disabled - event not tracked');
            },
            trackPageView: function() {
                console.log('⚠️ Facebook Pixel disabled - PageView not tracked');
            },
            trackViewContent: function() {
                console.log('⚠️ Facebook Pixel disabled - ViewContent not tracked');
            },
            trackAddToCart: function() {
                console.log('⚠️ Facebook Pixel disabled - AddToCart not tracked');
            },
            trackInitiateCheckout: function() {
                console.log('⚠️ Facebook Pixel disabled - InitiateCheckout not tracked');
            },
            trackPurchase: function() {
                console.log('⚠️ Facebook Pixel disabled - Purchase not tracked');
            },
            trackLead: function() {
                console.log('⚠️ Facebook Pixel disabled - Lead not tracked');
            },
            trackCompleteRegistration: function() {
                console.log('⚠️ Facebook Pixel disabled - CompleteRegistration not tracked');
            },
            trackSearch: function() {
                console.log('⚠️ Facebook Pixel disabled - Search not tracked');
            },
            trackContact: function() {
                console.log('⚠️ Facebook Pixel disabled - Contact not tracked');
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
                console.log('📊 Facebook Pixel Status:', status);
                return status;
            },

            testConnection: function() {
                console.log('⚠️ Facebook Pixel disabled - connection test skipped');
                return false;
            }
        };
    </script>
@endif
