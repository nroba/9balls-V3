const CACHE_NAME = "billiard-cache-v2_02"; 
self.addEventListener("install", (e) => {
  e.waitUntil(
    caches.open("billiard-cache").then((cache) =>
      cache.addAll([
        "./",
        "./index.html",
        "./main.js",
        "./style.css",
        "./manifest.json",
        "./images/ball1.png",
        "./images/ball2.png",
        "./images/ball3.png",
        "./images/ball4.png",
        "./images/ball5.png",
        "./images/ball6.png",
        "./images/ball7.png",
        "./images/ball8.png",
        "./images/ball9.png"
      ])
    )
  );
});

self.addEventListener("fetch", (e) => {
  e.respondWith(
    caches.match(e.request).then((response) => response || fetch(e.request))
  );
});
