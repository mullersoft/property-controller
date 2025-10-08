// D:\qelem meda\inventory sys\frontend\src\stores\CategoryStore.js
import { defineStore } from 'pinia';
import api from '../services/api';

export const usePropertyStore = defineStore('PropertyStore', {
  // --- State ---
  state: () => ({
    properties: [],
    property: null,
    loading: false,
    error: null,
  }),

  // --- Actions ---
  actions: {
    // Fetch all categories
    async fetchProperties() {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get('/properties');
        this.properties = res.data.property;
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Fetch a single category by ID
    async fetchProperty(id) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get(`/property/${id}`);
        this.property = res.data.property; // ✅ FIXED: actual object
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Create a new category
    async createProperty(payload) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.post('/property', payload);
        this.properties.push(res.data); // update local state
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Update a category
    async updateProperty(id, payload) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.put(`/property/${id}`, payload);
        const index = this.properties.findIndex((c) => c.id === id);
        if (index !== -1) this.properties[index] = res.data.Property; // ✅ FIXED key name from backend
        this.property = res.data.Property; // ✅ update current property too
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },
    // Delete a category
    async deleteProperty(id) {
      this.loading = true;
      this.error = null;
      try {
        await api.delete(`/property/${id}`);
        this.properties = this.properties.filter((c) => c.id !== id);
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },
  },
});
