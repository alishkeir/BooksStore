// This file configures the initialization of Sentry on the server.
// The config you add here will be used whenever the server handles a request.
// https://docs.sentry.io/platforms/javascript/guides/nextjs/

import * as Sentry from "@sentry/nextjs";

Sentry.init({
  dsn: "https://94cd1c1ae5fe45268b4b09a8ff7b2ca5@sentry.weborigo.eu/12",

  // Adjust this value in production, or use tracesSampler for greater control
  tracesSampleRate: 0.3,

  // Setting this option to true will print useful information to the console while you're setting up Sentry.
  debug: false,
});
