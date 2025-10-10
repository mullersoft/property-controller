import { defineStore } from 'pinia';
import api from '../services/api';

export const usePropertyItemStore = defineStore('PropertyItemStore', {
  state: () => ({
    propertyItems: [],
    propertyItem: null,
    loading: false,
    error: null,
  }),

  actions: {
    async fetchPropertyItems() {
      try {
        this.loading = true;
        const { data } = await api.get('/property-items');
        this.propertyItems = data.items;
        this.error = null;
      } catch (err) {
        console.error(err); // optional logging
        // this.error = 'Failed to fetch property items.'; // <-- comment out or remove for Add page
      } finally {
        this.loading = false;
      }
    },
    async fetchPropertyItem(id) {
      if (!id) return;
      try {
        this.loading = true;
        const { data } = await api.get(`/property-items/${id}`);
        this.propertyItem = data.item;
        this.error = null; // clear previous error
      } catch {
        this.error = 'Item not found.';
      } finally {
        this.loading = false;
      }
    },

    async createPropertyItem(payload) {
      this.loading = true;
      this.error = null;
      try {
        const { data } = await api.post('/property-items', payload);
        return data;
      } catch (err) {
        console.error(err);
        this.error = 'Failed to create property item.';
        throw err; 
      } finally {
        this.loading = false;
      }
    },

    async updatePropertyItem(id, payload) {
      this.loading = true;
      this.error = null;
      try {
        const { data } = await api.put(`/property-items/${id}`, payload);
        return data;
      } catch (err) {
        console.error(err);
        this.error = 'Failed to update property item.';
        throw err;
      } finally {
        this.loading = false;
      }
    },

    async deletePropertyItem(id) {
      try {
        this.loading = true;
        await api.delete(`/property-items/${id}`);
      } catch {
        this.error = 'Failed to delete property item.';
      } finally {
        this.loading = false;
      }
    },

    async checkSerialExists(serial) {
      try {
        const { data } = await api.get(
          `/property-items/check-serial/${serial}`
        );
        return data.exists;
      } catch {
        return false;
      }
    },
  },
});
