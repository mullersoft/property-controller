import { defineStore } from 'pinia';
import api from '../services/api';

export const useAssignmentStore = defineStore('AssignmentStore', {
  // --- State ---
  state: () => ({
    assignments: [],
    assignment: null,
    loading: false,
    error: null,
  }),

  // --- Actions ---
  actions: {
    // Fetch all categories
    async fetchAssignments() {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get('/assignment');
        this.assignments = res.data.assignment;
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Fetch a single category by ID
    async fetchAssignment(id) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get(`/assignment/${id}`);
        this.category = res.data.assignment;
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Create a new assignment
    async createAssignment(payload) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.post('/assignment', payload);
        this.assignments.push(res.data.assignment); // update local state
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Update a category
    async updateAssignment(id, payload) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.put(`/assignment/${id}`, payload);
        // update local state
        const index = this.assignments.findIndex((c) => c.id === id);
        if (index !== -1) this.assignments[index] = res.data;
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Delete a category
    async deleteAssignment(id) {
      this.loading = true;
      this.error = null;
      try {
        await api.delete(`/assignment/${id}`);
        this.assignments = this.assignments.filter((c) => c.id !== id);
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },
  },
});
