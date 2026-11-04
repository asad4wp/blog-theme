'use strict';

var CACHE_VERSION = '__CACHE_VERSION__';
var CACHE_NAME = 'puns-' + CACHE_VERSION;
var OFFLINE_URL = '__OFFLINE_URL__';
var PRECACHE_URLS = __PRECACHE_URLS__;

self.addEventListener('install', function (event) {
  event.waitUntil(
    caches.open(CACHE_NAME).then(function (cache) {
      return cache.addAll(PRECACHE_URLS);
    })
  );
  self.skipWaiting();
});

self.addEventListener('activate', function (event) {
  event.waitUntil(
    caches.keys().then(function (keys) {
      return Promise.all(
        keys
          .filter(function (key) {
            return key.indexOf('puns-') === 0 && key !== CACHE_NAME;
          })
          .map(function (key) {
            return caches.delete(key);
          })
      );
    })
  );
  self.clients.claim();
});

self.addEventListener('fetch', function (event) {
  if (event.request.method !== 'GET') {
    return;
  }

  var requestUrl = new URL(event.request.url);

  if (requestUrl.origin !== self.location.origin) {
    return;
  }

  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request)
        .then(function (response) {
          var copy = response.clone();
          caches.open(CACHE_NAME).then(function (cache) {
            cache.put(event.request, copy);
          });
          return response;
        })
        .catch(function () {
          return caches.match(event.request).then(function (cached) {
            if (cached) {
              return cached;
            }

            return caches.match(OFFLINE_URL);
          });
        })
    );
    return;
  }

  event.respondWith(
    caches.match(event.request).then(function (cached) {
      if (cached) {
        return cached;
      }

      return fetch(event.request).then(function (response) {
        if (!response || response.status !== 200) {
          return response;
        }

        if (response.type !== 'basic' && response.type !== 'cors') {
          return response;
        }

        var copy = response.clone();

        caches.open(CACHE_NAME).then(function (cache) {
          cache.put(event.request, copy);
        });

        return response;
      });
    })
  );
});
