const CACHE_NAME = 'glomart-cache-v2';
const urlsToCache = [
    '/',
    '/css/tailwind-local.css',
    '/css/fonts.css',
    '/images/brand/logo.svg'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(urlsToCache).catch(err => {
                    console.log('Cache addAll failed:', err);
                    // Cache only the essential files that exist
                    return cache.addAll(['/']);
                });
            })
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => response || fetch(event.request))
            .catch(err => {
                console.log('Fetch failed:', err);
                return fetch(event.request);
            })
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => cacheName !== CACHE_NAME)
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});
