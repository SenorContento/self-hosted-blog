/* https://www.w3schools.com/js/js_strict.asp */
'use strict'; // Helps force writing better code and helps with better security practices

/*
 * I just used the unix 'date' command to get the cache version.
 *   I am not a big fan of perpetually increasing the version number,
 *   so I am just setting a date instead.
 *
 * Now, the next trick is automating the detection of changed files in
 *   the resource list, or having the date change regardless upon push to git.
 *   I may set the cache name to be a variable read by the "post-receive" git hook
 *   and then have the date set automatically.
 *
 * I am only performing the next trick if it sounds like a reasonable option.
 *   I will have updated this comment to reflect the change if I go through with it.
*/
const CACHE_NAME = 'Sat Jan 19 14:20:22 EST 2019';

/* Without having a running hook, the browser could stop the service worker.
 *
 * Adding waitUntil(...);
 * https://stackoverflow.com/questions/37902441/what-does-event-waituntil-do-in-service-worker-and-why-is-it-needed
 *
 * To see it in action, look under eventLister("install");
*/

const resourceList = [
  '/errors/offline/index.php',
  '/manifest.json',
  '/service-worker.js',

  '/css/main.css',

  '/images/png/SenorContento-1024x1024.png',
  '/images/svg/SenorContento.svg',

  '/css/material-components-web-dark-custom.css',
  '/js/material-components-web.js'
];

self.addEventListener('install', event => {
  console.log('Service Worker installing.');
  self.skipWaiting(); // This forces new service worker to activate once browser installs it.

  event.waitUntil(caches.open(CACHE_NAME).then(cache => {
    return cache.addAll(resourceList);
  }));
});

self.addEventListener('activate', event => {
  console.log('Service Worker activating.');
  // TODO: Once I add the ability for users to manually cache pages, I will have to exclude the cache from auto-clear.
  caches.keys().then(function(names) {
    for(let name of names) {
      if(!(name === CACHE_NAME)) {
        caches.delete(name);
      }
    }
  });
});

/* https://craig552uk.github.com/2016/01/29/service-worker-messaging.html */
// Send in console: navigator.serviceWorker.controller.postMessage("Console says 'hello'");
self.addEventListener('message', event => {
  // Eventually add a message channel so the service worker can respond back to the client
  console.log("SW Received Message: " + event.data.text());
});

self.addEventListener('fetch', event => {
  event.respondWith(caches.match(event.request).then(response => {
    return response || fetch(event.request);
  }).catch(function() {
    return caches.match('/errors/offline/index.php');
  }));

  function addToCache(cacheName, resourceList) {
    caches.open(cacheName).then(cache => {
      return cache.addAll(resourceList);
    });
  }
});

/* No Idea Yet */
self.addEventListener('sync', event => {
  console.log("Sync: " + event); //Produces SyncEvent.
});

/* https://developer.mozilla.org/en-US/docs/Web/API/PushMessageData */
self.addEventListener('push', event => {
  console.log("Push: " + event.data); //Produces PushMessageData.
});