/**
 * Warn the page must be served over HTTPS
 * The `beforeinstallprompt` event won't fire if the page is served over HTTP.
 * Installability requires a service worker with a fetch event handler, and
 * if the page isn't served over HTTPS, the service worker won't load.
 */
if (window.location.protocol === 'http:') {
  const requireHTTPS = document.getElementById('requireHTTPS');
  const link = requireHTTPS.querySelector('a');
  link.href = window.location.href.replace('http://', 'https://');
  requireHTTPS.classList.remove('hidden');
  window.location.href = 'https:' + window.location.href.substring(5);
}

/* Only register a service worker if it's supported */
if ('serviceWorker' in navigator) {
  console.log('👍', 'navigator.serviceWorker is supported');
  navigator.serviceWorker.register('./service-worker.js');
}