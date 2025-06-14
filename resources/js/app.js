// resuorces/js/app.js
// import './bootstrap';
// Importing required packages
import Echo from 'laravel-echo';
import Pusher from 'pusher-js'; // or Reverb if that's what you're using

window.Pusher = Pusher;
window.Pusher.logToConsole = true;

// Initialize Echo 
// window.Echo = new Echo({
//     broadcaster: 'reverb',
//     key: import.meta.env.VITE_REVERB_APP_KEY,
//     wsHost: import.meta.env.VITE_REVERB_HOST ,
//     wsPort: import.meta.env.VITE_REVERB_PORT,
//     forceTLS: false,
//     disableStats: true,
//     enabledTransports: ['ws', 'wss'], 
// });

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ,
    wssPort: import.meta.env.VITE_REVERB_PORT ,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws','wss'],
});

// Confirm Echo initialization
console.log('Echo initialized:', window.Echo);
