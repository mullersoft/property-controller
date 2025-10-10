<template>
  <div class="p-6 bg-white rounded-lg shadow-md max-w-md mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-4 text-center">
      {{ isEdit ? 'Edit Assignment' : 'Add New Assignment' }}
    </h2>

    <div v-if="AssignmentStore.loading" class="text-blue-500 mb-4">Loading...</div>

    <form v-if="!AssignmentStore.loading" @submit.prevent="handleSubmit" class="space-y-4">
      <!-- Employee Dropdown -->
      <div>
        <label class="block font-medium mb-1">Employee</label>
        <select
          v-model="form.employee_id"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        >
          <option disabled value="">-- Select Employee --</option>
          <option v-for="emp in EmployeeStore.employees" :key="emp.id" :value="emp.id">
            {{ emp.name }}
          </option>
        </select>
      </div>

      <!-- Multiple Property Selection -->
      <div>
        <label class="block font-medium mb-1">Properties</label>
        <Multiselect
          v-model="form.property_ids"
          :options="propertyStore.properties"
          :multiple="true"
          track-by="id"
          label="name"
          placeholder="Select one or more properties"
          searchable
          :close-on-select="false"
        />
      </div>

      <!-- Assigned Date -->
      <div>
        <label class="block font-medium mb-1">Assigned Date</label>
        <input
          type="date"
          v-model="form.assigned_date"
          :max="form.return_date"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        />
      </div>

      <!-- Return Date -->
      <div>
        <label class="block font-medium mb-1">Return Date</label>
        <input
          type="date"
          v-model="form.return_date"
          :min="form.assigned_date"
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        />
      </div>
      <div v-if="formErrors.return_date" class="text-red-500 text-sm">
        {{ formErrors.return_date }}
      </div>

      <!-- Buttons -->
      <div class="flex justify-end space-x-2">
        <button type="button" @click="goBack" class="bg-gray-400 hover:bg-gray-500 text-white py-2 px-4 rounded">
          Cancel
        </button>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
          {{ isEdit ? 'Update' : 'Save' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue';
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.min.css';
import { useRoute, useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
import { useAssignmentStore } from '../../stores/AssignmentStore';
import { useEmployeeStore } from '../../stores/EmployeeStore';
import { usePropertyStore } from '../../stores/propertyStore';

const router = useRouter();
const route = useRoute();
const toast = useToast();

const AssignmentStore = useAssignmentStore();
const EmployeeStore = useEmployeeStore();
const propertyStore = usePropertyStore();

const isEdit = ref(false);
const formErrors = ref({});

const form = ref({
  employee_id: '',
  property_ids: [],
  assigned_date: '',
  return_date: '',
});

onMounted(async () => {
  await EmployeeStore.fetchEmployees();
  await propertyStore.fetchProperties();

  const id = route.params.id;
  if (id) {
    isEdit.value = true;
    await AssignmentStore.fetchAssignment(id);
  }
});

watch(
  () => AssignmentStore.assignment,
  (newAssign) => {
    if (newAssign && isEdit.value) {
      form.value = {
        employee_id: newAssign.employee_id,
        property_ids: [newAssign.property_id],
        assigned_date: newAssign.assigned_date,
        return_date: newAssign.return_date,
      };
    }
  }
);

const validateForm = () => {
  formErrors.value = {};
  if (!form.value.employee_id) {
    formErrors.value.employee_id = 'Employee is required';
    return false;
  }
  if (!form.value.property_ids.length) {
    formErrors.value.property_ids = 'Select at least one property';
    return false;
  }
  if (new Date(form.value.return_date) <= new Date(form.value.assigned_date)) {
    formErrors.value.return_date = 'Return date must be after assigned date';
    return false;
  }
  return true;
};

const handleSubmit = async () => {
  if (!validateForm()) {
    toast.error('Please fix the errors before submitting');
    return;
  }

  try {
    // Convert property objects to IDs
    const payload = {
      ...form.value,
      property_ids: form.value.property_ids.map((p) =>
        typeof p === 'object' ? p.id : p
      ),
    };

    if (isEdit.value) {
      await AssignmentStore.updateAssignment(route.params.id, payload);
      toast.success('Assignment updated successfully');
    } else {
      await AssignmentStore.createAssignment(payload);
      toast.success('Assignment(s) created successfully');
      // Reset form
      form.value = { employee_id: '', property_ids: [], assigned_date: '', return_date: '' };
    }

    router.push('/admin/assignments');
  } catch (error) {
    const msg = error.response?.data?.message || 'Error submitting form';
    toast.error(msg);
    console.error('Submission Error:', error.response?.data || error);
  }
};

const goBack = () => router.push('/admin/assignments');
</script>
