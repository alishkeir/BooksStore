module.exports = {
  images: {
    domains: [
      'source.unsplash.com',
      'alomgyar-be.skvad.live',
      'pam.skvad.live',
      'alomgyar.hu',
      'olcsokonyvek.hu',
      'nagyker.alomgyar.hu',
      'pamadmin.skvad.live',
      'pamadmin.hu',
      'dibook.hu',
      'staging.pamadmin.hu',
      'dev.pamadmin.hu',
      'api.pamadmin.hu',
      'book24.hu',
      'localhost',
    ],
    minimumCacheTTL: 50,
  },

  async rewrites() {
    return [
      {
        source: '/sync/arukereso',
        destination: 'https://pamadmin.hu/storage/sync/arukereso.xml',
      },
      {
        source: '/sync/google_merchant',
        destination: 'https://pamadmin.hu/storage/sync/google_merchant.xml',
      },
    ];
  },

  async redirects() {
    return [
      {
        source: '/profil',
        destination: '/profil/szemelyes-adataim',
        permanent: true,
      },
      {
        source: '/konyvek/:slug',
        destination: '/konyv/:slug',
        permanent: true,
      },
    ];
  },

  async headers() {
    return [
      {
        source: '/:all*(js|css|woff|svg|jpg|png|webp)',
        locale: false,
        headers: [
          {
            key: 'Cache-Control',
            value: 'public, max-age=86400, must-revalidate',
          },
        ],
      },
    ];
  },

  webpack(config) {
    config.module.rules.push({
      test: /\.svg$/,
      use: ['@svgr/webpack'],
    });

    return config;
  },
};

// Injected content via Sentry wizard below

const { withSentryConfig } = require('@sentry/nextjs');

module.exports = withSentryConfig(
  module.exports,
  {
    // For all available options, see:
    // https://github.com/getsentry/sentry-webpack-plugin#options

    // Suppresses source map uploading logs during build
    silent: true,

    org: 'weborigo',
    project: 'alomgyar-frontend',
    url: 'https://sentry.weborigo.eu/',
  },
  {
    // For all available options, see:
    // https://docs.sentry.io/platforms/javascript/guides/nextjs/manual-setup/

    // Upload a larger set of source maps for prettier stack traces (increases build time)
    widenClientFileUpload: false,

    // Transpiles SDK to be compatible with IE11 (increases bundle size)
    transpileClientSDK: false,

    // Routes browser requests to Sentry through a Next.js rewrite to circumvent ad-blockers (increases server load)
    //tunnelRoute: "/monitoring",

    // Hides source maps from generated client bundles
    hideSourceMaps: true,

    // Automatically tree-shake Sentry logger statements to reduce bundle size
    disableLogger: true,
  },
);
