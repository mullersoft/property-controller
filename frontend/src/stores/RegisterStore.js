import { defineStore } from 'pinia';
import api from '../services/api'; // your axios instance

export const useRegisterStore = defineStore('RegisterStore', {
  state: () => ({
    user: null,
    loading: false,
    error: null,
    success: false,
  }),

  actions: {
    async register(payload) {
      this.loading = true;
      this.error = null;
      this.success = false;

      try {
        const response = await api.post('/register', payload); // backend route
        this.user = response.data.user;
        this.success = true;
      } catch (err) {
        this.error =
          err.response?.data?.message ||
          'Registration failed. Please try again.';
      } finally {
        this.loading = false;
      }
    },
  },
});
