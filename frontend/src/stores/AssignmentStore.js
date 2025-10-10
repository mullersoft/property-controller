import { defineStore } from 'pinia';
import api from '../services/api';

export const useAssignmentStore = defineStore('AssignmentStore', {
  state: () => ({
    assignments: [],
    assignment: null,
    loading: false,
    error: null,
  }),

  actions: {
    async fetchAssignments() {
      this.loading = true;
      try {
        const { data } = await api.get('/assignments');
        this.assignments = data.assignments;
      } finally {
        this.loading = false;
      }
    },

    async fetchAssignment(id) {
      if (!id) return;
      this.loading = true;
      try {
        const { data } = await api.get(`/assignment/${id}`);
        this.assignment = data.assignment;
      } finally {
        this.loading = false;
      }
    },

    async createAssignment(payload) {
      this.loading = true;
      try {
        const { data } = await api.post('/assignment', payload);
        return data;
      } catch (err) {
        console.error(err.response?.data);
        throw err;
      } finally {
        this.loading = false;
      }
    },

    async updateAssignment(id, payload) {
      this.loading = true;
      try {
        const { data } = await api.put(`/assignment/${id}`, payload);
        return data;
      } catch (err) {
        console.error(err.response?.data);
        throw err;
      } finally {
        this.loading = false;
      }
    },
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
