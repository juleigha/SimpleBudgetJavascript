const CACHEV = "v-2.4";
const DYNCACHE = "budget";
const APPCACHE = [
  "./",
  "/jquery.js",
  "./index.php",
  "./modules/budgetMath.js",
  "./modules/db.js",
  "./modules/screenChangerModule.js"
];
var newresp = {
  "bodyUsed": true,
  "status": "200",
  "statusText": "Ok",
  "url":"TEST"
}
// var obj = {attr:"val"};
// var myBlob = new Blob([JSON.stringify(obj)], {type : 'application/json'});
// return  new Response(myBlob, newresp);
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHEV)
      .then(cache => cache.addAll(APPCACHE))
      .then(self.skipWaiting())
  );
});
self.addEventListener('activate', event => {
  const currentCaches = [CACHEV, DYNCACHE];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return cacheNames.filter(cacheName => !currentCaches.includes(cacheName));
    }).then(cachesToDelete => {
      return Promise.all(cachesToDelete.map(cacheToDelete => {
        console.log(cacheToDelete);
        return caches.delete(cacheToDelete);
      }));
    }).then(() => self.clients.claim())
  );
});
self.addEventListener('fetch', event => {
  var newresp = {
    "bodyUsed": true,
    "status": "200",
    "statusText": "Ok",
    "url":"GETLIST"
  }
if (event.request.url.startsWith(self.location.origin) && event.request.method !== "POST") {
  if (event.request.url.indexOf("drpdwn.txt") > -1
  || event.request.url.indexOf("gateways.txt") > -1){

  }
  else if (event.request.url.indexOf("customCacheGet") > -1){
      event.respondWith(
      caches.match("MASTER_BUDGET").then(cachedResponse => {
        if (cachedResponse === undefined){
          var obj = {noSave:true};
          var myBlob = new Blob([JSON.stringify(obj)], {type : 'application/json'});
          return  new Response(myBlob, newresp);
        }
        return cachedResponse;
      },er=>{

      })

    )
  }
  else if (event.request.url.indexOf("customCachePut") > -1){
    event.respondWith(
    caches.match("MASTER_BUDGET").then(cachedResponse=>{
      return caches.open(DYNCACHE).then(cache => {
          var obj = event.request.url.slice(event.request.url.indexOf("?")+1);
          var myBlob = new Blob([obj], {type : 'application/json'});
          var nResp =  new Response(myBlob, newresp);
          return cache.put("MASTER_BUDGET", nResp.clone()).then(() => {
            return nResp;
          });
      });
    }) );
  }
    else {
    event.respondWith(
      caches.match(event.request).then(cachedResponse => {
        console.log(event.request);
        if ( cachedResponse !== undefined) {
          return cachedResponse;
        }
        return fetch(event.request).then(response => {
          console.log(cachedResponse,response,cachedResponse === response);
          return response;
        });

      })
    );
  }
  }
});
