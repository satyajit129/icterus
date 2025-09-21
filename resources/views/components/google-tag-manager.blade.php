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
            console.error('❌ GTM Error: Invalid GTM ID format. Expected GTM-XXXXXXX, got:', gtmId);
            console.error('Please check your GTM ID in Settings > Google Tag Manager Settings');
        } else {
            console.log('✅ GTM ID validated:', gtmId);
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
                        console.log('✅ GTM connection test passed');
                        return true;
                    } else {
                        this.isConnected = false;
                        this.connectionTested = true;
                        console.error('❌ GTM connection test failed - dataLayer not found');
                        return false;
                    }
                } catch (error) {
                    this.isConnected = false;
                    this.connectionTested = true;
                    console.error('❌ GTM connection test error:', error);
                    return false;
                }
            },

            // Validate event before pushing
            validateEvent: function(eventName, eventData) {
                if (!this.testConnection()) {
                    console.error('❌ Cannot push event - GTM not connected');
                    return false;
                }

                if (!this.enabledEvents.includes(eventName) && !this.enabledEvents.includes('all')) {
                    console.warn('⚠️ Event not enabled:', eventName);
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

                    console.log('🎯 GTM Event:', eventName, event);
                    dataLayer.push(event);
                    console.log('✅ Event pushed successfully:', eventName);
                    return true;
                } catch (error) {
                    console.error('❌ Failed to push event:', eventName, error);
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

                console.log('📊 GTM Status:', status);
                return status;
            },

            // Debug helper - test all events
            testAllEvents: function() {
                console.log('🧪 Testing all enabled GTM events...');
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
                        console.log(`Testing GTM event: ${eventName}`);
                        this.pushEvent(eventName, testData);
                    }
                });
            },

            // Manual connection test
            forceConnectionTest: function() {
                console.log('🔍 Forcing GTM connection test...');
                this.connectionTested = false;
                return this.testConnection();
            },

            // Clear dataLayer (for testing)
            clearDataLayer: function() {
                if (typeof dataLayer !== 'undefined') {
                    dataLayer.length = 0;
                    console.log('🧹 GTM dataLayer cleared');
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

                console.log('🛠️ GTM Debug helpers available:');
                console.log('- gtmDebug.status() - Get GTM status');
                console.log('- gtmDebug.testAll() - Test all events');
                console.log('- gtmDebug.testConnection() - Test connection');
                console.log('- gtmDebug.push(event, data) - Push custom event');
                console.log('- gtmDebug.clear() - Clear dataLayer');
                console.log('- gtmDebug.viewItem(data) - Track view item');
                console.log('- gtmDebug.addToCart(data) - Track add to cart');
                console.log('- gtmDebug.beginCheckout(data) - Track begin checkout');
                console.log('- gtmDebug.purchase(data) - Track purchase');
                console.log('- gtmDebug.search(data) - Track search');
            }, 2000);
        });
    </script>
@else
    <!-- GTM Disabled -->
    <script>
        console.log('⚠️ Google Tag Manager is disabled');
        console.log('To enable: Go to Settings > Google Tag Manager Settings');

        window.GoogleTagManager = {
            gtmId: null,
            enabledEvents: [],
            customDimensions: {},
            ecommerceSettings: {},
            isConnected: false,
            connectionTested: true,

            pushEvent: function() {
                console.log('⚠️ GTM disabled - event not tracked');
            },
            trackPageView: function() {
                console.log('⚠️ GTM disabled - page_view not tracked');
            },
            trackViewItem: function() {
                console.log('⚠️ GTM disabled - view_item not tracked');
            },
            trackAddToCart: function() {
                console.log('⚠️ GTM disabled - add_to_cart not tracked');
            },
            trackBeginCheckout: function() {
                console.log('⚠️ GTM disabled - begin_checkout not tracked');
            },
            trackPurchase: function() {
                console.log('⚠️ GTM disabled - purchase not tracked');
            },
            trackSearch: function() {
                console.log('⚠️ GTM disabled - search not tracked');
            },
            trackLead: function() {
                console.log('⚠️ GTM disabled - generate_lead not tracked');
            },
            trackContact: function() {
                console.log('⚠️ GTM disabled - contact not tracked');
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
                console.log('📊 GTM Status:', status);
                return status;
            },

            testConnection: function() {
                console.log('⚠️ GTM disabled - connection test skipped');
                return false;
            }
        };
    </script>
@endif
