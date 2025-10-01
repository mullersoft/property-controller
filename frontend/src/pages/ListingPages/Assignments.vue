<template>
  <div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Assignments</h2>

    <!-- Loading State -->
    <div v-if="assignmentStore.loading" class="text-blue-500">Loading...</div>

    <!-- Error State -->
    <div v-else-if="assignmentStore.error" class="text-red-500">
      {{ assignmentStore.error }}
    </div>

    <!-- Categories Table -->
    <table
      v-else-if="assignmentStore.assignments.length"
      class="min-w-full border border-gray-300"
    >
      <thead class="bg-gray-200">
        <tr>
          <th class="px-4 py-2 border">ID</th>
          <th class="px-4 py-2 border">Employee</th>
          <th class="px-4 py-2 border">Property</th>
          <th class="px-4 py-2 border">assigned_date</th>
          <th class="px-4 py-2 border">return_date</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="assign in assignmentStore.assignments"
          :key="assign.id"
          class="hover:bg-gray-100"
        >
          <td class="px-4 py-2 border">{{ assign.id }}</td>
          <td class="px-4 py-2 border">{{ assign.employee.name }}</td>
          <td class="px-4 py-2 border">{{ assign.property.name }}</td>
          <td class="px-4 py-2 border">{{ assign.assigned_date }}</td>
          <td class="px-4 py-2 border">{{ assign.return_date }}</td>
        </tr>
      </tbody>
    </table>

    <!-- No Data -->
    <div v-else class="text-gray-500">No assignments found.</div>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useAssignmentStore } from '../../stores/AssignmentStore';

const assignmentStore = useAssignmentStore();

onMounted(() => {
  assignmentStore.fetchAssignments();
});
</script>
