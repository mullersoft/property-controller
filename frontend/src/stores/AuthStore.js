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
        this.user = response.data.user;
        this.token = response.data.token;
        localStorage.setItem('token', response.data.token);

        // Set default authorization header for future requests
        api.defaults.headers.common[
          'Authorization'
        ] = `Bearer ${response.data.token}`;

        return response.data;
      } catch (err) {
        this.error =
          err.response?.data?.message ||
          'Login failed. Please check your credentials and try again.';
        throw err;
      } finally {
        this.loading = false;
      }
    },

    logout() {
      this.user = null;
      this.token = null;
      this.error = null;
      localStorage.removeItem('token');
      delete api.defaults.headers.common['Authorization'];
    },

    // Optional: Add method to check authentication status
    checkAuth() {
      if (this.token) {
        api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      }
    },
  },
});
