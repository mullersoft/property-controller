// D:\qelem meda\inventory sys\frontend\src\stores\CategoryStore.js
import { defineStore } from 'pinia';
import api from '../services/api';

export const useCategoryStore = defineStore('CategoryStore', {
  // --- State ---
  state: () => ({
    categories: [], // list of all categories
    category: null, // single category details
    loading: false, // loading indicator
    error: null, // store error messages
  }),

  // --- Actions ---
  actions: {
    // Fetch all categories
    async fetchCategories() {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get('/categories');
        this.categories = res.data.category;
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Fetch a single category by ID
    async fetchCategory(id) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get(`/category/${id}`);
        this.category = res.data.category;
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Create a new category
    async createCategory(payload) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.post('/category', payload);
        this.categories.push(res.data); // update local state
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Update a category
    async updateCategory(id, payload) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.put(`/category/${id}`, payload);
        // update local state
        const index = this.categories.findIndex((c) => c.id === id);
        if (index !== -1) this.categories[index] = res.data;
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Delete a category
    async deleteCategory(id) {
      this.loading = true;
      this.error = null;
      try {
        await api.delete(`/category/${id}`);
        this.categories = this.categories.filter((c) => c.id !== id);
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },
  },
});
