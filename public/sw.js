const CACHE_NAME = 'simrs-rme-cache-v2';

// Cache static assets that never change to reduce CPU and bandwidth on low-end servers.
const STATIC_ASSETS = [
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
  'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
  'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css',
  'https://cdn.jsdelivr.net/npm/sweetalert2@11',
  'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js',
  '/manifest.json'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(STATIC_ASSETS);
      })
      .then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', event => {
  const requestUrl = new URL(event.request.url);
  
  // 1. Static Assets: Serve from cache first, fallback to network
  if (STATIC_ASSETS.includes(event.request.url) || requestUrl.pathname.endsWith('.png') || requestUrl.pathname.endsWith('.json')) {
    event.respondWith(
      caches.match(event.request).then(cachedResponse => {
        return cachedResponse || fetch(event.request);
      })
    );
    return;
  }
  
  // 2. Dynamic EMR / Patient CRUD Routes: Always network-only to ensure fresh real-time clinical database logs.
  event.respondWith(
    fetch(event.request).catch(() => {
      // Offline fallback
      return caches.match('/');
    })
  );
});
