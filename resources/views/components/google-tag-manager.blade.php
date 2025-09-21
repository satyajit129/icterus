@php
    $settings = \App\Models\Setting::first();
    $gtmId = $settings->gtm_id ?? null;
    $gtmEnabled = $settings->gtm_enabled ?? false;
    $enabledEvents = $settings->gtm_events ? json_decode($settings->gtm_events, true) : [];
    $customDimensions = $settings->gtm_custom_dimensions ? json_decode($settings->gtm_custom_dimensions, true) : [];
    $ecommerceSettings = $settings->gtm_ecommerce_settings ? json_decode($settings->gtm_ecommerce_settings, true) : [];
@endphp

@if ($gtmEnabled && $gtmId)
    <!-- Google Tag Manager -->
    <script>
        // Validate GTM ID format
        function validateGtmId(gtmId) {
            const gtmIdRegex = /^GTM-[A-Z0-9]+$/;
            return gtmIdRegex.test(gtmId);
        }

        // Check if GTM ID is valid
        const gtmId = '{{ $gtmId }}';
        if (!validateGtmId(gtmId)) {
            // Invalid GTM ID format
        } else {
            // GTM ID validated
        }

        // Load GTM script
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '{{ $gtmId }}');
    </script>
    <!-- End Google Tag Manager -->

    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0"
            style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- GTM Helper Functions -->
    <script>
        window.GoogleTagManager = {
            gtmId: '{{ $gtmId }}',
            enabledEvents: @json($enabledEvents),
            customDimensions: @json($customDimensions),
            ecommerceSettings: @json($ecommerceSettings),
            isConnected: false,
            connectionTested: false,

            // Test GTM connection
            testConnection: function() {
                if (this.connectionTested) return this.isConnected;

                try {
                    if (typeof dataLayer !== 'undefined' && Array.isArray(dataLayer)) {
                        this.isConnected = true;
                        this.connectionTested = true;
                        // GTM connection test passed
                        return true;
                    } else {
                        this.isConnected = false;
                        this.connectionTested = true;
                        // GTM connection test failed - dataLayer not found
                        return false;
                    }
                } catch (error) {
                    this.isConnected = false;
                    this.connectionTested = true;
                    // GTM connection test error
                    return false;
                }
            },

            // Validate event before pushing
            validateEvent: function(eventName, eventData) {
                if (!this.testConnection()) {
                    // Cannot push event - GTM not connected
                    return false;
                }

                if (!this.enabledEvents.includes(eventName) && !this.enabledEvents.includes('all')) {
                    // Event not enabled
                    return false;
                }

                return true;
            },

            // Push event to dataLayer
            pushEvent: function(eventName, eventData = {}) {
                if (!this.validateEvent(eventName, eventData)) {
                    return false;
                }

                try {
                    const event = {
                        event: eventName,
                        timestamp: new Date().toISOString(),
                        ...eventData
                    };

                    // Add custom dimensions if configured
                    if (Object.keys(this.customDimensions).length > 0) {
                        event.custom_dimensions = this.customDimensions;
                    }

                    dataLayer.push(event);
                    return true;
                } catch (error) {
                    // Failed to push event
                    return false;
                }
            },

            // E-commerce Events
            trackPageView: function(pageData = {}) {
                const eventData = {
                    page_title: document.title,
                    page_location: window.location.href,
                    page_path: window.location.pathname,
                    ...pageData
                };
                return this.pushEvent('page_view', eventData);
            },

            trackViewItem: function(itemData) {
                const eventData = {
                    currency: 'BDT',
                    value: itemData.value || 0,
                    items: [{
                        item_id: itemData.item_id,
                        item_name: itemData.item_name,
                        item_category: itemData.item_category,
                        item_brand: itemData.item_brand || '{{ $settings->website_name ?? 'Unknown' }}',
                        price: itemData.price || itemData.value || 0,
                        quantity: itemData.quantity || 1
                    }],
                    ...itemData
                };
                return this.pushEvent('view_item', eventData);
            },

            trackAddToCart: function(cartData) {
                const eventData = {
                    currency: 'BDT',
                    value: cartData.value || 0,
                    items: [{
                        item_id: cartData.item_id,
                        item_name: cartData.item_name,
                        item_category: cartData.item_category,
                        item_brand: cartData.item_brand || '{{ $settings->website_name ?? 'Unknown' }}',
                        price: cartData.price || cartData.value || 0,
                        quantity: cartData.quantity || 1
                    }],
                    ...cartData
                };
                return this.pushEvent('add_to_cart', eventData);
            },

            trackBeginCheckout: function(checkoutData) {
                const eventData = {
                    currency: 'BDT',
                    value: checkoutData.value || 0,
                    items: checkoutData.items || [],
                    ...checkoutData
                };
                return this.pushEvent('begin_checkout', eventData);
            },

            trackPurchase: function(purchaseData) {
                const eventData = {
                    transaction_id: purchaseData.transaction_id,
                    currency: 'BDT',
                    value: purchaseData.value || 0,
                    items: purchaseData.items || [],
                    ...purchaseData
                };
                return this.pushEvent('purchase', eventData);
            },

            trackSearch: function(searchData) {
                const eventData = {
                    search_term: searchData.search_term,
                    ...searchData
                };
                return this.pushEvent('search', eventData);
            },

            trackLead: function(leadData) {
                const eventData = {
                    currency: 'BDT',
                    value: leadData.value || 0,
                    ...leadData
                };
                return this.pushEvent('generate_lead', eventData);
            },

            trackContact: function(contactData) {
                const eventData = {
                    ...contactData
                };
                return this.pushEvent('contact', eventData);
            },

            // Custom Events
            trackCustomEvent: function(eventName, eventData = {}) {
                return this.pushEvent(eventName, eventData);
            },

            // Get GTM status and debug info
            getStatus: function() {
                const status = {
                    gtmId: this.gtmId,
                    isEnabled: true,
                    isConnected: this.testConnection(),
                    enabledEvents: this.enabledEvents,
                    customDimensions: this.customDimensions,
                    ecommerceSettings: this.ecommerceSettings,
                    dataLayerAvailable: typeof dataLayer !== 'undefined',
                    dataLayerLength: typeof dataLayer !== 'undefined' ? dataLayer.length : 0,
                    timestamp: new Date().toISOString()
                };

                // GTM Status logged
                return status;
            },

            // Debug helper - test all events
            testAllEvents: function() {
                // Testing all enabled GTM events
                const testData = {
                    item_id: 'test_123',
                    item_name: 'Test Product',
                    item_category: 'Test Category',
                    item_brand: '{{ $settings->website_name ?? 'Test Brand' }}',
                    price: 99.99,
                    value: 99.99,
                    quantity: 1
                };

                this.enabledEvents.forEach(eventName => {
                    if (eventName !== 'page_view') {
                        // Testing GTM event
                        this.pushEvent(eventName, testData);
                    }
                });
            },

            // Manual connection test
            forceConnectionTest: function() {
                // Forcing GTM connection test
                this.connectionTested = false;
                return this.testConnection();
            },

            // Clear dataLayer (for testing)
            clearDataLayer: function() {
                if (typeof dataLayer !== 'undefined') {
                    dataLayer.length = 0;
                    // GTM dataLayer cleared
                }
            }
        };

        // Auto-track page view on load
        document.addEventListener('DOMContentLoaded', function() {
            if (window.GoogleTagManager.enabledEvents.includes('page_view') || window.GoogleTagManager.enabledEvents
                .includes('all')) {
                window.GoogleTagManager.trackPageView();
            }

            // Show GTM status in console
            setTimeout(function() {
                window.GoogleTagManager.getStatus();

                // Add debugging helpers to window for easy access
                window.gtmDebug = {
                    status: () => window.GoogleTagManager.getStatus(),
                    testAll: () => window.GoogleTagManager.testAllEvents(),
                    testConnection: () => window.GoogleTagManager.forceConnectionTest(),
                    push: (event, data) => window.GoogleTagManager.pushEvent(event, data),
                    clear: () => window.GoogleTagManager.clearDataLayer(),
                    viewItem: (data) => window.GoogleTagManager.trackViewItem(data),
                    addToCart: (data) => window.GoogleTagManager.trackAddToCart(data),
                    beginCheckout: (data) => window.GoogleTagManager.trackBeginCheckout(data),
                    purchase: (data) => window.GoogleTagManager.trackPurchase(data),
                    search: (data) => window.GoogleTagManager.trackSearch(data)
                };

                // GTM Debug helpers available
            }, 2000);
        });
    </script>
@else
    <!-- GTM Disabled -->
    <script>
        // Google Tag Manager is disabled

        window.GoogleTagManager = {
            gtmId: null,
            enabledEvents: [],
            customDimensions: {},
            ecommerceSettings: {},
            isConnected: false,
            connectionTested: true,

            pushEvent: function() {
                // GTM disabled - event not tracked
            },
            trackPageView: function() {
                // GTM disabled - page_view not tracked
            },
            trackViewItem: function() {
                // GTM disabled - view_item not tracked
            },
            trackAddToCart: function() {
                // GTM disabled - add_to_cart not tracked
            },
            trackBeginCheckout: function() {
                // GTM disabled - begin_checkout not tracked
            },
            trackPurchase: function() {
                // GTM disabled - purchase not tracked
            },
            trackSearch: function() {
                // GTM disabled - search not tracked
            },
            trackLead: function() {
                // GTM disabled - generate_lead not tracked
            },
            trackContact: function() {
                // GTM disabled - contact not tracked
            },

            getStatus: function() {
                const status = {
                    gtmId: null,
                    isEnabled: false,
                    isConnected: false,
                    enabledEvents: [],
                    customDimensions: {},
                    ecommerceSettings: {},
                    dataLayerAvailable: false,
                    dataLayerLength: 0,
                    timestamp: new Date().toISOString()
                };
                // GTM Status logged
                return status;
            },

            testConnection: function() {
                // GTM disabled - connection test skipped
                return false;
            }
        };
    </script>
@endif
