// Import the root Vue component
import App from './App.vue';

// Import Pinia for state management
import { createPinia } from 'pinia';

// Import Vue's createApp function to initialize the app
import { createApp } from 'vue';

// Import the router configuration
import router from './router/routes';

// Import Vue Toastification for notifications
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css'; // Toast CSS
import './index.css'; // Global app styles

// Import the AuthStore to manage authentication state
import { useAuthStore } from './stores/AuthStore';

// -------------------------
// Create the Vue app instance with the root component
const app = createApp(App);

// -------------------------
// Install and configure Toast plugin for notifications
app.use(Toast, {
  position: 'top-right', // Where to show notifications
  timeout: 4000, // Auto close after 4 seconds
  closeOnClick: true, // Close on click
  pauseOnHover: true, // Pause timeout when hovering
  draggable: true, // Allow dragging to dismiss
  showCloseButtonOnHover: false, // Show close button only when hovering
});

// -------------------------
// Create Pinia instance for state management
const pinia = createPinia();
app.use(pinia);

// -------------------------
// Register Vue Router for page navigation
app.use(router);

// -------------------------
// Initialize authentication state from localStorage
// This ensures Axios Authorization header is set if token exists
const authStore = useAuthStore();
authStore.initialize(); // Restore token for API requests

// -------------------------
// Mount the Vue app to the HTML element with id="app"
app.mount('#app');
