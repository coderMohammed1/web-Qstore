<?php

return [

    /**
     * Server
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Server
     *
     * Note: When server is empty string, it will not be added to the response header.
     */
    'server' => '',

    /**
     * X-Content-Type-Options
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options
     *
     * Available Value: 'nosniff'
     */
    'x-content-type-options' => 'nosniff',

    /**
     * X-DNS-Prefetch-Control
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-DNS-Prefetch-Control
     *
     * Available Value: 'on', 'off'
     */
    'x-dns-prefetch-control' => '',

    /**
     * X-Download-Options
     *
     * @see https://msdn.microsoft.com/en-us/library/jj542450(v=vs.85).aspx
     *
     * Available Value: 'noopen'
     */
    'x-download-options' => 'noopen',

    /**
     * X-Frame-Options
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options
     * @deprecated The X-Frame-Options is no longer recommended for use; please use Content-Security-Policy (CSP) instead.
     *
     * Available Value: 'deny', 'sameorigin', 'allow-from <uri>'
     */
    'x-frame-options' => 'DENY',

    /**
     * X-Permitted-Cross-Domain-Policies
     *
     * @see https://www.adobe.com/devnet-docs/acrobatetk/tools/AppSec/xdomain.html
     *
     * Available Value: 'all', 'none', 'master-only', 'by-content-type', 'by-ftp-filename'
     */
    'x-permitted-cross-domain-policies' => 'none',

    /**
     * X-Powered-By
     *
     * Note: it will not add to response header if the value is empty string.
     *
     * Also, verify that expose_php is turned Off in php.ini.
     * Otherwise, the header will still be included in the response.
     *
     * @see https://github.com/bepsvpt/secure-headers/issues/58#issuecomment-782332442
     */
    'x-powered-by' => 'Q-store',

    /**
     * X-XSS-Protection
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-XSS-Protection
     * @deprecated The X-XSS-Protection is no longer recommended for use; please use Content-Security-Policy (CSP) instead.
     *
     * Available Value: '1', '0', '1; mode=block'
     */
    'x-xss-protection' => '',

    /**
     * Referrer-Policy
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Referrer-Policy
     *
     * Available Value: 'no-referrer', 'no-referrer-when-downgrade', 'origin', 'origin-when-cross-origin',
     *                  'same-origin', 'strict-origin', 'strict-origin-when-cross-origin', 'unsafe-url'
     */
    'referrer-policy' => 'no-referrer',

    /**
     * Cross-Origin-Embedder-Policy
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cross-Origin-Embedder-Policy
     *
     * Available Value: 'unsafe-none', 'require-corp', 'credentialless'
     */
    'cross-origin-embedder-policy' => 'unsafe-none',

    /**
     * Cross-Origin-Opener-Policy
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cross-Origin-Opener-Policy
     *
     * Available Value: 'unsafe-none', 'same-origin-allow-popups', 'same-origin'
     */
    'cross-origin-opener-policy' => 'unsafe-none',

    /**
     * Cross-Origin-Resource-Policy
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cross-Origin-Resource-Policy
     *
     * Available Value: 'same-site', 'same-origin', 'cross-origin'
     */
    'cross-origin-resource-policy' => 'cross-origin',

    /**
     * Clear-Site-Data
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Clear-Site-Data
     */
    'clear-site-data' => [
        'enable' => false,

        'all' => false,

        'cache' => true,

        'clientHints' => true,

        'cookies' => true,

        'storage' => true,

        'executionContexts' => true,
    ],

    /**
     * HTTP Strict Transport Security
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/Security/HTTP_strict_transport_security
     *
     * Note: Please ensure your website had set up ssl/tls before enable hsts.
     */
    'hsts' => [
        'enable' => false,

        'max-age' => 31536000,

        'include-sub-domains' => false,

        'preload' => false,
    ],

    /**
     * Reporting Endpoints
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Reporting-Endpoints
     *
     * Note: The array key is the endpoint name, and the value is the URL.
     */
    'reporting' => [
        // 'csp' => 'https://example.com/csp-reports',
        // 'nel' => 'https://example.com/nel-reports',
    ],

    /**
     * Network Error Logging
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Network_Error_Logging
     * @see https://developer.mozilla.org/en-US/docs/Web/API/Reporting_API
     */
    'nel' => [
        'enable' => false,

        // The name of reporting API, not the endpoint URL.
        'report-to' => '',

        'max-age' => 86400,

        'include-subdomains' => false,

        'success-fraction' => 0.0,

        'failure-fraction' => 1.0,
    ],

    /**
     * Expect-CT
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Expect-CT
     * @deprecated This feature is no longer recommended.
     */
    'expect-ct' => [
        'enable' => false,

        'max-age' => 2147483648,

        'enforce' => false,

        // report uri must be absolute-URI
        'report-uri' => null,
    ],

    /**
     * Permissions Policy
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy
     */
    'permissions-policy' => [
        'enable' => true,

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/accelerometer
        'accelerometer' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/ambient-light-sensor
        'ambient-light-sensor' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/attribution-reporting
        'attribution-reporting' => [
            'none' => false,

            '*' => true,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/autoplay
        'autoplay' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/bluetooth
        'bluetooth' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/browsing-topics
        'browsing-topics' => [
            'none' => false,

            '*' => true,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/camera
        'camera' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/compute-pressure
        'compute-pressure' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/compute-pressure
        'cross-origin-isolated' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/display-capture
        'display-capture' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/document-domain
        'document-domain' => [
            'none' => false,

            '*' => true,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/encrypted-media
        'encrypted-media' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/fullscreen
        'fullscreen' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/gamepad
        'gamepad' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/geolocation
        'geolocation' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/gyroscope
        'gyroscope' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/hid
        'hid' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/identity-credentials-get
        'identity-credentials-get' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/idle-detection
        'idle-detection' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/local-fonts
        'local-fonts' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/magnetometer
        'magnetometer' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/microphone
        'microphone' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/midi
        'midi' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/otp-credentials
        'otp-credentials' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/payment
        'payment' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/picture-in-picture
        'picture-in-picture' => [
            'none' => false,

            '*' => true,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/publickey-credentials-create
        'publickey-credentials-create' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/publickey-credentials-get
        'publickey-credentials-get' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/screen-wake-lock
        'screen-wake-lock' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/serial
        'serial' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/speaker-selection
        'speaker-selection' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/storage-access
        'storage-access' => [
            'none' => false,

            '*' => true,

            'self' => false,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/usb
        'usb' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/web-share
        'web-share' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/window-management
        'window-management' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy/xr-spatial-tracking
        'xr-spatial-tracking' => [
            'none' => false,

            '*' => false,

            'self' => true,

            'origins' => [],
        ],
    ],

      /**
     * Content Security Policy (CSP)
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP
     */
   'csp' => [
    'enable' => true,
    'report-only' => false, // Set to true for debugging

    // Default sources
    'default-src' => [
        "'self'",
        "https://cdn.jsdelivr.net",
        "https://cdnjs.cloudflare.com",
    ],

    // ✅ Allow Bootstrap & FontAwesome scripts
    'script-src' => [
        "'self'",
        "https://cdn.jsdelivr.net",
        "https://cdnjs.cloudflare.com",
        "'unsafe-inline'", // Needed for Bootstrap's navbar toggler
        "'unsafe-eval'",   // Needed for some Bootstrap JS features
    ],

    // ✅ Allow Bootstrap & FontAwesome styles
    'style-src' => [
        "'self'",
        "https://cdn.jsdelivr.net",
        "https://cdnjs.cloudflare.com",
        "'unsafe-inline'", // Needed for Bootstrap's inline styles
    ],

    // ✅ Allow images from trusted sources
    'img-src' => [
        "'self'",
        "data:", // Allows Base64 images
        "https://cdn.jsdelivr.net",
        "https://cdnjs.cloudflare.com",
    ],

    // ✅ Allow fonts from trusted sources
    'font-src' => [
        "'self'",
        "https://cdn.jsdelivr.net",
        "https://cdnjs.cloudflare.com",
    ],

    // ✅ Allow form submissions
    'form-action' => ["'self'"],

    // ✅ Allow API requests & WebSockets
    'connect-src' => [
        "'self'",
        "https://cdn.jsdelivr.net",
        "https://cdnjs.cloudflare.com",
    ],

    // ✅ Allow iframes only from self
    'frame-src' => ["'self'"],

    // ✅ Prevent clickjacking (No embedding in other sites)
    'frame-ancestors' => ["'none'"],

    // ✅ Disable Flash & Java Applets (Legacy security)
    'object-src' => ["'none'"],

    // ✅ Allow web workers
    'worker-src' => ["'self'"],

    // ✅ Allow prefetching from self
    'prefetch-src' => ["'self'"],

    // ✅ Allow manifest loading
    'manifest-src' => ["'self'"],

    // ✅ Allow inline scripts & styles for Bootstrap
    'script-src-attr' => ["'self'", "'unsafe-inline'"],
    'script-src-elem' => ["'self'", "'unsafe-inline'"],
    'style-src-attr' => ["'self'", "'unsafe-inline'"],
    'style-src-elem' => ["'self'", "'unsafe-inline'"],

    // 🔐 Trusted Types (Advanced Security, can be enabled)
    'trusted-types' => [
        'enable' => false,
        'none' => false,
        'allow-duplicates' => false,
        'policies' => [],
    ],

    // 🛡️ Sandbox settings (disable if unnecessary)
    'sandbox' => [
        'enable' => false,
        'allow-downloads' => false,
        'allow-forms' => false,
        'allow-modals' => false,
        'allow-pointer-lock' => false,
        'allow-popups' => false,
        'allow-popups-to-escape-sandbox' => false,
        'allow-presentation' => false,
        'allow-same-origin' => false,
        'allow-scripts' => false,
        'allow-storage-access-by-user-activation' => false,
        'allow-top-navigation' => false,
        'allow-top-navigation-by-user-activation' => false,
        'allow-top-navigation-to-custom-protocols' => false,
    ],
]];