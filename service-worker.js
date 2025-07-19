// /service-worker.js
const CACHE_NAME = "billiard-cache";
const FILES_TO_CACHE = [
  "./",
  "./index.php",
  "./pocketmode/main.js",
  "./pocketmode/pocketmode.css",
  "./manifest.json",
  "./images/icon-192.png",
  "./images/icon-512.png",
  "./images/ball1.png",
  "./images/ball2.png",
  "./images/ball3.png",
  "./images/ball4.png",
  "./images/ball5.png",
  "./images/ball6.png",
  "./images/ball7.png",
  "./images/ball8.png",
  "./images/ball9.png"
];

self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(FILES_TO_CACHE).catch(err => {
        console.error("キャッシュ失敗:", err);
      });
    })
  );
});

self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((keyList) => {
      return Promise.all(
        keyList.map((key) => {
          if (key !== CACHE_NAME) {
            return caches.delete(key);
          }
        })
      );
    })
  );
});

self.addEventListener("fetch", (event) => {
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    })
  );
});
