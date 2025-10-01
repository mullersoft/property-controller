import { defineStore } from 'pinia';
import api from '../services/api';

export const useEmployeeStore = defineStore('EmployeeStore', {
  // --- State ---
  state: () => ({
    employees: [], // list of all categories
    employee: null, // single category details
    loading: false, // loading indicator
    error: null, // store error messages
  }),

  // --- Actions ---
  actions: {
    // Fetch all categories
    async fetchEmployees() {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get('/employee');
        this.employees = res.data.employee;
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Fetch a single category by ID
    async fetchEmployee(id) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.get(`/employee/${id}`);
        this.employee = res.data.employee;
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Create a new employee
    async createEmployee(payload) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.post('/employee', payload);
        this.employees.push(res.data.employee); // update local state
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Update a employee
    async updateEmployee(id, payload) {
      this.loading = true;
      this.error = null;
      try {
        const res = await api.put(`/employee/${id}`, payload);
        // update local state
        const index = this.employees.findIndex((c) => c.id === id);
        if (index !== -1) this.employees[index] = res.data;
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Delete a category
    async deleteEmployee(id) {
      this.loading = true;
      this.error = null;
      try {
        await api.delete(`/Employee/${id}`);
        this.employee = this.employee.filter((c) => c.id !== id);
      } catch (err) {
        this.error = err.response?.data?.message || err.message;
      } finally {
        this.loading = false;
      }
    },
  },
});
