'use strict';
const CACHE_NAME = 'cache-v1';

const resourceList = [
  '/offline/index.php',
  '/manifest.json',
  '/service-worker.js',

  '/css/assignment1.css',
  '/css/main.css',

  '/images/png/SenorContento-1024x1024.png',
  '/images/svg/SenorContento.svg',

  '/css/material-components-web-dark-custom.css',
  '/js/material-components-web.js'
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
    return caches.match('/offline/index.php');
  })
);

function addToCache(cacheName, resourceList) {
  caches.open(cacheName).then(cache => {
    return cache.addAll(resourceList);
  });
}
});