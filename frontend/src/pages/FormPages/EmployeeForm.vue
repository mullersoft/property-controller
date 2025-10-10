<template>
  <div class="p-6 bg-white rounded-lg shadow-md max-w-md mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-4 text-center">
      {{ isEdit ? 'Edit Employee' : 'Add New Employee' }}
    </h2>

    <!-- Loading State -->
    <div v-if="EmployeeStore.loading" class="text-blue-500 mb-4">
      Loading...
    </div>

    <!-- Error Message -->
    <div
      v-if="EmployeeStore.error"
      class="bg-red-100 text-red-700 p-2 mb-4 rounded"
    >
      {{ EmployeeStore.error }}
    </div>

    <!-- Success Message -->
    <div
      v-if="successMessage && !EmployeeStore.error"
      class="bg-green-100 text-green-700 p-2 mb-4 rounded"
    >
      {{ successMessage }}
    </div>

    <!-- Form -->
    <form
      v-if="!EmployeeStore.loading"
      @submit.prevent="handleSubmit"
      class="space-y-4"
    >
      <div>
        <label for="name" class="block font-medium mb-1">Employee Name</label>
        <input
          id="name"
          v-model="form.name"
          type="text"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        />
      </div>

      <div>
        <label for="department" class="block font-medium mb-1"
          >Department</label
        >
        <input
          id="department"
          v-model="form.department"
          type="text"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        />
      </div>

      <div>
        <label for="email" class="block font-medium mb-1">Email</label>
        <input
          id="email"
          v-model="form.email"
          type="email"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        />
      </div>

      <div>
        <label for="phone" class="block font-medium mb-1">Phone</label>
        <input
          id="phone"
          v-model="form.phone"
          type="text"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        />
      </div>

      <div class="flex justify-end space-x-2">
        <button
          type="button"
          @click="goBack"
          class="bg-gray-400 hover:bg-gray-500 text-white py-2 px-4 rounded"
        >
          Cancel
        </button>

        <button
          type="submit"
          :disabled="EmployeeStore.loading"
          class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded"
        >
          <span v-if="EmployeeStore.loading">
            {{ isEdit ? 'Updating...' : 'Saving...' }}
          </span>
          <span v-else>{{ isEdit ? 'Update' : 'Save' }}</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useEmployeeStore } from '../../stores/EmployeeStore';

const router = useRouter();
const route = useRoute();
const EmployeeStore = useEmployeeStore();

const isEdit = ref(false);
const successMessage = ref('');

const form = ref({
  name: '',
  department: '',
  email: '',
  phone: '',
});

// Detect edit mode
onMounted(async () => {
  const id = route.params.id;
  if (id) {
    isEdit.value = true;
    await EmployeeStore.fetchEmployee(id);
  }
});

// Auto-fill for edit
watch(
  () => EmployeeStore.employee,
  (newEmp) => {
    if (newEmp && isEdit.value) {
      form.value = {
        name: newEmp.name || '',
        department: newEmp.department || '',
        email: newEmp.email || '',
        phone: newEmp.phone || '',
      };
    }
  }
);

// Handle submit
const handleSubmit = async () => {
  successMessage.value = ''; // reset message
  EmployeeStore.error = null; // clear old error

  if (isEdit.value) {
    await EmployeeStore.updateEmployee(route.params.id, form.value);
    if (!EmployeeStore.error) {
      successMessage.value = 'Employee updated successfully!';
      setTimeout(() => router.push('/admin/employees'), 1000);
    }
  } else {
    await EmployeeStore.createEmployee(form.value);
    if (!EmployeeStore.error) {
      successMessage.value = 'Employee added successfully!';
      Object.keys(form.value).forEach((key) => (form.value[key] = ''));
      setTimeout(() => router.push('/admin/employees'), 1000);
    }
  }
};

// Go back
const goBack = () => router.push('/admin/employees');
</script>
