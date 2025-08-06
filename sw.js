const CACHE_NAME = 'pwa-assets-v1';

const ASSETS_TO_CACHE = [
  '/', // index.html root
  '/index.html',
  '/fonts/remixicon.woff2',
  '/css/style.css',
  '/css/bootstrap5.min.css',
  '/css/calander.css',
  '/css/dashboard.css',
  '/css/dialog.css',
  '/css/easepick.css',
  '/css/remixicons.css',
  '/js/main.js',
  '/js/ezToast.js',
   // Add your actual font paths
];

// Install: Cache files
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      return cache.addAll(ASSETS_TO_CACHE);
    })
  );
  self.skipWaiting(); // Force SW activation after install
});

// Activate: Clear old caches
self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(keys => {
      return Promise.all(
        keys.map(key => {
          if (!cacheWhitelist.includes(key)) {
            return caches.delete(key); // Remove outdated caches
          }
        })
      );
    })
  );
  self.clients.claim();
});


self.addEventListener('fetch', event => {
  // Only handle GET requests
  if (event.request.method !== 'GET') return;

  event.respondWith(
    caches.match(event.request).then(cachedResponse => {
      if (cachedResponse) {
        return cachedResponse; // ✅ Serve from cache
      }

      // ❌ If not found in cache, block the request (do NOT go to network)
      // Optional: Serve fallback (like offline.html) for documents
      if (event.request.destination === 'document') {
        return caches.match('/offline.html');
      }

      // For other files (e.g., images, fonts), just return a 404 response
      return new Response('Not cached and no fallback available.', {
        status: 404,
        statusText: 'Not Found'
      });
    })
  );
});