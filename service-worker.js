'use strict';
const CACHE_NAME = 'cache-v1';

const resourceList = [
  '/',
  '/offline',
  '/manifest.json',
  '/HTML-CSS/assignment1.css',
  '/css/main.css',
  '/images/png/SenorContento-1024x1024.png',
  '/images/svg/SenorContento.svg'
];

self.addEventListener('install', event => {
  console.log('Service Worker installing.');
  event.waitUntil(caches.open(CACHE_NAME).then(cache => {
    return cache.addAll(resourceList);
  }));
});

self.addEventListener('activate', event => {
  console.log('Service Worker activating.');
});

self.addEventListener('fetch', event => {
  event.respondWith(caches.match(event.request).then(response => {
    return response || fetch(event.request);
  })
  .catch(function() {
    return caches.match('/offline');
  })
);

function addToCache(cacheName, resourceList) {
  caches.open(cacheName).then(cache => {
    return cache.addAll(resourceList);
  });
}
});