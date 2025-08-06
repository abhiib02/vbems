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
// Fetch: Serve from cache or fetch from network
self.addEventListener('fetch', event => {
  const {
    request
  } = event;

  // Only cache GET requests
  if (request.method !== 'GET') return;

  event.respondWith(
    caches.match(request).then(cachedResponse => {
      if (cachedResponse) {
        return cachedResponse;
      }

      // Fetch from network and add to cache
      return fetch(request).then(networkResponse => {
        return caches.open(CACHE_NAME).then(cache => {
          cache.put(request, networkResponse.clone());
          return networkResponse;
        });
      }).catch(() => {
        // Optional: Fallback if offline
        if (request.destination === 'document') {
          return caches.match('/offline.html');
        }
      });
    })
  );
});