const CACHE_NAME = 'zettelkasten-v2'; // Increment version when you update
const STATIC_CACHE = 'zettelkasten-static-v2';

const staticAssets = [
  'styles.css',
  'jquery-ui-autocomplete.css',
  'icon-192.png',
  'icon-512.png',
  'manifest.json'
];

const phpPages = [
  'index.php',
  'share-handler.php',
  'login.php'
];

// Install service worker and cache static files only
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(STATIC_CACHE)
      .then(cache => cache.addAll(staticAssets))
      .then(() => self.skipWaiting()) // Activate immediately
  );
});

// Network-first strategy: Try network, fallback to cache
self.addEventListener('fetch', event => {
  const url = new URL(event.request.url);
  
  // For PHP files and POST requests: Always use network
  if (event.request.method === 'POST' || url.pathname.endsWith('.php')) {
    event.respondWith(
      fetch(event.request)
        .catch(error => {
          console.log('Network failed, trying cache:', error);
          return caches.match(event.request);
        })
    );
    return;
  }
  
  // For static assets: Cache-first
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        if (response) {
          return response;
        }
        return fetch(event.request).then(response => {
          if (!response || response.status !== 200) {
            return response;
          }
          const responseToCache = response.clone();
          caches.open(STATIC_CACHE).then(cache => {
            cache.put(event.request, responseToCache);
          });
          return response;
        });
      })
  );
});

// Clean up old caches
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME && cacheName !== STATIC_CACHE) {
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => self.clients.claim()) // Take control immediately
  );
});
