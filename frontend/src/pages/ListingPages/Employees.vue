<template>
  <div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Employees</h2>
   <div class="mb-4 flex justify-end">
      <router-link
        to="/admin/employee/add"
        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
      >
        + Add Employee
      </router-link>
    </div>
    <!-- Loading State -->
    <div v-if="employeeStore.loading" class="text-blue-500">Loading...</div>

    <!-- Error State -->
    <div v-else-if="employeeStore.error" class="text-red-500">
      {{ employeeStore.error }}
    </div>

    <!-- Categories Table -->
    <table
      v-else-if="employeeStore.employees.length"
      class="min-w-full border border-gray-300"
    >
      <thead class="bg-gray-200">
        <tr>
          <th class="px-4 py-2 border">ID</th>
          <th class="px-4 py-2 border">Name</th>
          <th class="px-4 py-2 border">department</th>
          <th class="px-4 py-2 border">email</th>
          <th class="px-4 py-2 border">phone</th>
          <!-- <th class="px-4 py-2 border">Property</th> -->
            <th class="px-4 py-2 border text-center">Actions</th>

        </tr>
      </thead>
      <tbody>
        <tr
          v-for="emp in employeeStore.employees"
          :key="emp.id"
          class="hover:bg-gray-100"
        >
          <td class="px-4 py-2 border">{{ emp.id }}</td>
          <td class="px-4 py-2 border">{{ emp.name }}</td>
          <td class="px-4 py-2 border">{{ emp.department }}</td>
          <td class="px-4 py-2 border">{{ emp.email }}</td>
          <td class="px-4 py-2 border">{{ emp.phone }}</td>
          <!-- <td class="px-4 py-2 border">
           <div v-if="emp.assignment && emp.assignment.length > 0">
  <div v-for="assign in emp.assignment" :key="assign.id">
    {{ assign.property?.name || 'Unnamed Property' }}
  </div>
</div>
<div v-else>No Assignments</div>


          </td> -->
             <td class="px-4 py-2 border text-center space-x-2">
              <!-- Edit Button -->
              <router-link
                :to="`/admin/employee/edit/${emp.id}`"

                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded"
              >
                Edit
              </router-link>

              <!-- Delete Button -->
              <button
                @click="deleteEmployee(emp.id)"
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
              >
                Delete
              </button>
            </td>
        </tr>
      </tbody>
    </table>

    <!-- No Data -->
    <div v-else class="text-gray-500">No categories found.</div>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useEmployeeStore } from '../../stores/EmployeeStore';

const employeeStore = useEmployeeStore();

onMounted(() => {
  employeeStore.fetchEmployees();
});

// Delete handler with confirmation
const deleteEmployee = async (id) => {
  if (confirm('Are you sure you want to delete this employee?')) {
    await employeeStore.deleteEmployee(id);
    // Optionally refetch to refresh list
    await employeeStore.fetchEmployees();
  }
};
</script>
