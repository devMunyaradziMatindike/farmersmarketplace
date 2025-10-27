<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
        
        <!-- SEO Meta Tags -->
        <title>{{ config('app.name', 'Musika Wedu') }} - Zimbabwe's Premier Agricultural Marketplace</title>
        <meta name="description" content="Musika Wedu is Zimbabwe's leading agricultural marketplace connecting farmers, suppliers, and buyers. Buy and sell crops, livestock, equipment, and agricultural products across Zimbabwe.">
        <meta name="keywords" content="agricultural marketplace Zimbabwe, farmers Zimbabwe, crops Zimbabwe, livestock Zimbabwe, agricultural equipment Zimbabwe, Musika Wedu, farming Zimbabwe, agricultural products Zimbabwe">
        <meta name="author" content="Nesso Systems (Pvt) Ltd">
        <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
        <meta name="googlebot" content="index, follow">
        <meta name="bingbot" content="index, follow">
        
        <!-- Open Graph Meta Tags (Facebook, WhatsApp, etc.) -->
        <meta property="og:type" content="website">
        <meta property="og:title" content="Musika Wedu - Zimbabwe's Premier Agricultural Marketplace">
        <meta property="og:description" content="Connect with farmers across Zimbabwe. Buy and sell crops, livestock, equipment, and agricultural products on Zimbabwe's leading agricultural marketplace.">
        <meta property="og:url" content="https://musikawedu.co.zw">
        <meta property="og:site_name" content="Musika Wedu">
        <meta property="og:image" content="https://musikawedu.co.zw/images/musika-wedu-og-image.jpg">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:image:alt" content="Musika Wedu Agricultural Marketplace">
        <meta property="og:locale" content="en_ZW">
        
        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Musika Wedu - Zimbabwe's Premier Agricultural Marketplace">
        <meta name="twitter:description" content="Connect with farmers across Zimbabwe. Buy and sell crops, livestock, equipment, and agricultural products.">
        <meta name="twitter:image" content="https://musikawedu.co.zw/images/musika-wedu-twitter-card.jpg">
        <meta name="twitter:image:alt" content="Musika Wedu Agricultural Marketplace">
        
        <!-- Additional SEO Meta Tags -->
        <meta name="geo.region" content="ZW">
        <meta name="geo.country" content="Zimbabwe">
        <meta name="geo.placename" content="Zimbabwe">
        <meta name="language" content="English">
        <meta name="revisit-after" content="7 days">
        <meta name="distribution" content="global">
        <meta name="rating" content="general">
        
        <!-- Mobile & PWA Meta Tags -->
        <meta name="theme-color" content="#22c55e">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="Musika Wedu">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="format-detection" content="telephone=no">
        
        <!-- Canonical URL -->
        <link rel="canonical" href="https://musikawedu.co.zw">
        
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        
        <!-- Preconnect for Performance -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="preconnect" href="https://maps.googleapis.com">
        <link rel="dns-prefetch" href="https://fonts.bunny.net">
        <link rel="dns-prefetch" href="https://maps.googleapis.com">
        
        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Google Maps API -->
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places" async defer></script>
        
        <!-- Google Analytics & Search Console -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GOOGLE_ANALYTICS_ID', 'GA_MEASUREMENT_ID') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ env('GOOGLE_ANALYTICS_ID', 'GA_MEASUREMENT_ID') }}');
        </script>
        
        <!-- Structured Data (JSON-LD) -->
        <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "Organization",
            "name": "Musika Wedu",
            "alternateName": "Musika Wedu Agricultural Marketplace",
            "url": "https://musikawedu.co.zw",
            "logo": "https://musikawedu.co.zw/images/musika-wedu-logo.png",
            "description": "Zimbabwe's premier agricultural marketplace connecting farmers, suppliers, and buyers",
            "foundingDate": "2025",
            "founder": {
                "@@type": "Organization",
                "name": "Nesso Systems (Pvt) Ltd"
            },
            "address": {
                "@@type": "PostalAddress",
                "streetAddress": "Farm 42 Coburn Estate",
                "addressLocality": "Chegutu",
                "addressCountry": "ZW"
            },
            "contactPoint": {
                "@@type": "ContactPoint",
                "telephone": "+263-78-233-9300",
                "contactType": "customer service",
                "email": "info@nessosystems.co.zw"
            },
            "sameAs": [
                "https://www.facebook.com/musikawedu",
                "https://www.twitter.com/musikawedu",
                "https://www.linkedin.com/company/musika-wedu"
            ],
            "areaServed": {
                "@@type": "Country",
                "name": "Zimbabwe"
            },
            "serviceType": "Agricultural Marketplace",
            "hasOfferCatalog": {
                "@@type": "OfferCatalog",
                "name": "Agricultural Products",
                "itemListElement": [
                    {
                        "@@type": "Offer",
                        "itemOffered": {
                            "@@type": "Product",
                            "name": "Crops and Grains"
                        }
                    },
                    {
                        "@@type": "Offer",
                        "itemOffered": {
                            "@@type": "Product",
                            "name": "Livestock"
                        }
                    },
                    {
                        "@@type": "Offer",
                        "itemOffered": {
                            "@@type": "Product",
                            "name": "Agricultural Equipment"
                        }
                    }
                ]
            }
        }
        </script>
        
        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
