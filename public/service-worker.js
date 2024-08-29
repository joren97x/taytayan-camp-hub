self.addEventListener('install', (event) => {
    event.waitUntil(
      caches.open('app-v1').then((cache) => {
        return cache.addAll([
          '/',
          '/css/app.css',
          '/js/app.js',
          '/images/event_logo.png',
          '/images/event_logo.png',
        ]).catch(error => {
            console.error('Failed to cache resources:', error);
        });
      })
    );
  });
  
  self.addEventListener('fetch', (event) => {
    event.respondWith(
      caches.match(event.request).then((response) => {
        return response || fetch(event.request);
      })
    );
  });
  