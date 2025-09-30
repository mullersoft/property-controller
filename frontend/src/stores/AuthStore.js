import { defineStore } from 'pinia';
import api from '../services/api';
export const useAuthStore = defineStore('AuthStore', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
  }),
  actions: {
    async login(email, password) {
      const res = await api.post("/login", { email, password });
      this.user = res.data.user;
      this.token = res.data.token;
      localStorage.setItem("token", res.data.token);
    },
}
});
