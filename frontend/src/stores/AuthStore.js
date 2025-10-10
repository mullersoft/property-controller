import { defineStore } from 'pinia';
import api from '../services/api';

export const useAuthStore = defineStore('AuthStore', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    loading: false,
    error: null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
  },

  actions: {
    async login(email, password) {
      this.loading = true;
      this.error = null;

      try {
        const response = await api.post('/login', { email, password });

        // Update state
        this.user = response.data.user;
        this.token = response.data.token;

        // Persist token
        localStorage.setItem('token', response.data.token);

        // Set Axios default header
        api.defaults.headers.common[
          'Authorization'
        ] = `Bearer ${response.data.token}`;

        return response.data;
      } catch (err) {
        this.error =
          err.response?.data?.message ||
          'Login failed. Please check your credentials.';
        throw err;
      } finally {
        this.loading = false;
      }
    },

    async logout() {
      this.loading = true;
      try {
        // Optional: call backend logout endpoint
        await api.post('/logout');
      } catch (err) {
        console.error('Logout API failed', err);
      } finally {
        // Clear state
        this.user = null;
        this.token = null;
        this.error = null;

        // Clear storage & axios header
        localStorage.removeItem('token');
        delete api.defaults.headers.common['Authorization'];
        this.loading = false;
      }
    },

    initialize() {
      // Call this on app mount to restore token header if it exists
      if (this.token) {
        api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      }
    },
  },
});
