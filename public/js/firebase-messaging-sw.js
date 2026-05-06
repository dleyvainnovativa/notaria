// Import the Firebase app and messaging services
importScripts("https://www.gstatic.com/firebasejs/12.8.0/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/12.8.0/firebase-messaging-compat.js");

  

// Initialize the Firebase app in the service worker.
// IMPORTANT: These keys are publicly visible and are meant to be. 
// There is no security risk in having them here.
const firebaseConfig = {
  apiKey: "AIzaSyBzG0iMMrg1vvQAg6syRXO_VDOx2UnjAn8",
  authDomain: "notaria-38a21.firebaseapp.com",
  projectId: "notaria-38a21",
  storageBucket: "notaria-38a21.firebasestorage.app",
  messagingSenderId: "836562439296",
  appId: "1:836562439296:web:83fc124611568e1e3b6a65",
  measurementId: "G-WQ1S71G8DW"
};


firebase.initializeApp(firebaseConfig);

// Retrieve an instance of Firebase Messaging so that it can handle background messages.
const messaging = firebase.messaging();

/**
 * Handle background messages. When a notification is received while the app
 * is in the background or closed, this is the code that will run.
 */
messaging.onBackgroundMessage(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  
  // Customize the notification here
  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/img/icon/favicon-96x96.png' // Or your preferred icon path
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});
