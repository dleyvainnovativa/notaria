// This script's only job is to correctly register the service worker with the right scope.

// Get the base path of your application from the <base> tag in your HTML head.
// This makes the script portable whether you are in a subdirectory or at the root.
// const basePath = document.querySelector('base')?.getAttribute('href') || '/';
const basePath = document.querySelector('meta[name="app-url"]').getAttribute('content') || "/";

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register(`${basePath}firebase-messaging-sw.js`, { scope: basePath })
        .then((registration) => {
            // console.log('Service Worker registration successful with scope: ', registration.scope);
        })
        .catch((err) => {
            console.error('Service Worker registration failed: ', err);
        });
} else {
    console.warn('Service workers are not supported by this browser.');
}
