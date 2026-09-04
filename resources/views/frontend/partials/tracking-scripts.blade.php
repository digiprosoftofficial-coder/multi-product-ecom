@php
    $gaId = \App\Support\Tracking::googleAnalyticsId();
    $gtmId = \App\Support\Tracking::googleTagManagerId();
    $pixelId = \App\Support\Tracking::facebookPixelId();
    $trackingCurrency = \App\Support\Tracking::currency();
    $trackingEnabled = $gaId || $gtmId || $pixelId;
@endphp
@if($gtmId || $gaId)
    <script>
        window.dataLayer = window.dataLayer || [];
    </script>
@endif
@if($gtmId)
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ $gtmId }}');</script>
    <!-- End Google Tag Manager -->
@endif
@if($gaId)
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $gaId }}');
    </script>
@endif
@if($pixelId)
    <!-- Meta Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $pixelId }}');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
             src="https://www.facebook.com/tr?id={{ $pixelId }}&ev=PageView&noscript=1" alt=""/>
    </noscript>
@endif
@if($trackingEnabled)
<script>
window.StorefrontTracking = (function () {
    var defaultCurrency = @json($trackingCurrency);
    var useDataLayer = @json((bool) $gtmId);

    function gaItems(items) {
        return (items || []).map(function (item) {
            return {
                item_id: item.sku || item.id,
                item_name: item.name,
                price: Number(item.price || 0),
                quantity: Number(item.quantity || 1)
            };
        });
    }

    function pixelContents(items) {
        return (items || []).map(function (item) {
            return {
                id: String(item.sku || item.id),
                quantity: Number(item.quantity || 1),
                item_price: Number(item.price || 0)
            };
        });
    }

    function pixelIds(items) {
        return (items || []).map(function (item) {
            return String(item.sku || item.id);
        });
    }

    function once(key) {
        try {
            if (window.localStorage.getItem(key)) {
                return false;
            }
            window.localStorage.setItem(key, '1');
        } catch (e) {}
        return true;
    }

    function pushDataLayer(eventName, ecommerce) {
        if (!useDataLayer) return;
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ ecommerce: null });
        window.dataLayer.push({
            event: eventName,
            ecommerce: ecommerce
        });
    }

    return {
        addToCart: function (item) {
            if (!item) return;
            var items = [item];
            var currency = item.currency || defaultCurrency;
            var value = Number(item.value != null ? item.value : (item.price * item.quantity) || 0);
            var mapped = gaItems(items);

            pushDataLayer('add_to_cart', {
                currency: currency,
                value: value,
                items: mapped
            });

            if (typeof gtag === 'function') {
                gtag('event', 'add_to_cart', {
                    currency: currency,
                    value: value,
                    items: mapped
                });
            }
            if (typeof fbq === 'function') {
                fbq('track', 'AddToCart', {
                    content_ids: pixelIds(items),
                    content_name: item.name,
                    content_type: 'product',
                    contents: pixelContents(items),
                    value: value,
                    currency: currency
                });
            }
        },
        viewContent: function (item) {
            if (!item) return;
            var items = [item];
            var currency = item.currency || defaultCurrency;
            var value = Number(item.value != null ? item.value : item.price || 0);
            var mapped = gaItems(items);

            pushDataLayer('view_item', {
                currency: currency,
                value: value,
                items: mapped
            });

            if (typeof gtag === 'function') {
                gtag('event', 'view_item', {
                    currency: currency,
                    value: value,
                    items: mapped
                });
            }
            if (typeof fbq === 'function') {
                fbq('track', 'ViewContent', {
                    content_ids: pixelIds(items),
                    content_name: item.name,
                    content_type: 'product',
                    contents: pixelContents(items),
                    value: value,
                    currency: currency
                });
            }
        },
        beginCheckout: function (payload) {
            if (!payload) return;
            var currency = payload.currency || defaultCurrency;
            var value = Number(payload.value || 0);
            var items = payload.items || [];
            var mapped = gaItems(items);

            pushDataLayer('begin_checkout', {
                currency: currency,
                value: value,
                items: mapped
            });

            if (typeof gtag === 'function') {
                gtag('event', 'begin_checkout', {
                    currency: currency,
                    value: value,
                    items: mapped
                });
            }
            if (typeof fbq === 'function') {
                fbq('track', 'InitiateCheckout', {
                    content_ids: pixelIds(items),
                    content_type: 'product',
                    contents: pixelContents(items),
                    num_items: items.reduce(function (sum, item) {
                        return sum + Number(item.quantity || 1);
                    }, 0),
                    value: value,
                    currency: currency
                });
            }
        },
        purchase: function (payload) {
            if (!payload || !payload.transaction_id) return;
            if (!once('storefront_purchase_' + payload.transaction_id)) return;

            var currency = payload.currency || defaultCurrency;
            var value = Number(payload.value || 0);
            var items = payload.items || [];
            var mapped = gaItems(items);

            pushDataLayer('purchase', {
                transaction_id: payload.transaction_id,
                currency: currency,
                value: value,
                tax: Number(payload.tax || 0),
                items: mapped
            });

            if (typeof gtag === 'function') {
                gtag('event', 'purchase', {
                    transaction_id: payload.transaction_id,
                    currency: currency,
                    value: value,
                    tax: Number(payload.tax || 0),
                    items: mapped
                });
            }
            if (typeof fbq === 'function') {
                fbq('track', 'Purchase', {
                    content_ids: pixelIds(items),
                    content_type: 'product',
                    contents: pixelContents(items),
                    num_items: items.reduce(function (sum, item) {
                        return sum + Number(item.quantity || 1);
                    }, 0),
                    value: value,
                    currency: currency
                }, { eventID: payload.transaction_id });
            }
        }
    };
})();
</script>
@endif
@if($gtmId)
<script>
(function () {
    function clickText(el) {
        var text = (el.innerText || el.textContent || el.value || el.getAttribute('aria-label') || '').replace(/\s+/g, ' ').trim();
        return text.slice(0, 120);
    }

    function clickClasses(el) {
        if (!el.className) return '';
        if (typeof el.className === 'string') return el.className;
        if (typeof el.className.baseVal === 'string') return el.className.baseVal;
        return '';
    }

    document.addEventListener('click', function (e) {
        var el = e.target && e.target.closest
            ? e.target.closest('a, button, [role="button"], input[type="submit"], input[type="button"]')
            : null;
        if (!el) return;

        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            event: 'element_click',
            click_element: (el.tagName || '').toLowerCase(),
            click_id: el.id || '',
            click_classes: clickClasses(el),
            click_text: clickText(el),
            click_url: el.href || el.getAttribute('href') || '',
            click_page: window.location.pathname
        });
    }, true);
})();
</script>
@endif
