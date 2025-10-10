<template>
  <div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold text-gray-700">Assignments</h2>
      <router-link
        to="/admin/assignment/add"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition"
      >
        + Add Assignment
      </router-link>
    </div>

    <!-- Search Input -->
    <div class="mb-4">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search by Employee or Property"
        class="w-full md:w-1/3 border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
    </div>

    <!-- Loading State -->
    <div v-if="AssignmentStore.loading" class="text-blue-500 text-center py-8">
      Loading assignments...
    </div>

    <!-- Assignment Table -->
    <div v-else class="overflow-x-auto bg-white rounded-lg shadow-lg">
      <table class="min-w-full border-collapse">
        <thead class="bg-gray-200">
          <tr>
            <th class="text-left py-3 px-4 font-semibold text-gray-600">#</th>
            <th class="text-left py-3 px-4 font-semibold text-gray-600">Employee</th>
            <th class="text-left py-3 px-4 font-semibold text-gray-600">Assigned Properties</th>
            <th class="text-left py-3 px-4 font-semibold text-gray-600">Assigned Date</th>
            <th class="text-left py-3 px-4 font-semibold text-gray-600">Return Date</th>
            <th class="text-center py-3 px-4 font-semibold text-gray-600">Actions</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="(assignment, index) in filteredAssignments"
            :key="assignment.id"
            class="hover:bg-gray-100 transition"
          >
            <td class="py-3 px-4">{{ index + 1 }}</td>
            <td class="py-3 px-4">{{ assignment.employee_name }}</td>
            <td class="py-3 px-4">
              <span v-if="assignment.property_names.length">
                {{ assignment.property_names.join(', ') }}
              </span>
              <span v-else class="text-gray-400 italic">No properties</span>
            </td>
            <td class="py-3 px-4">{{ assignment.assigned_date }}</td>
            <td class="py-3 px-4">{{ assignment.return_date || 'Not returned' }}</td>
            <td class="py-3 px-4 text-center">
              <div class="flex justify-center space-x-2">
                <!-- Edit Button -->
                <button
                  @click="editAssignment(assignment.id)"
                  class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded"
                >
                  Edit
                </button>

                <!-- Delete Button -->
                <button
                  @click="deleteAssignment(assignment.id)"
                  class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
                >
                  Delete
                </button>

                <!-- Return Button -->
                <button
                  v-if="!assignment.return_date"
                  @click="returnProperty(assignment.id)"
                  class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded"
                >
                  Return
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Empty State -->
      <div v-if="!filteredAssignments.length" class="text-center py-10 text-gray-500">
        No assignments found.
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
import api from '../../services/api';
import { useAssignmentStore } from '../../stores/AssignmentStore';
import { useEmployeeStore } from '../../stores/EmployeeStore';
import { usePropertyStore } from '../../stores/propertyStore';

const router = useRouter();
const toast = useToast();

const AssignmentStore = useAssignmentStore();
const EmployeeStore = useEmployeeStore();
const PropertyStore = usePropertyStore();

// Search query reactive state
const searchQuery = ref('');

// Fetch all data on mount
onMounted(async () => {
  await Promise.all([
    AssignmentStore.fetchAssignments(),
    EmployeeStore.fetchEmployees(),
    PropertyStore.fetchProperties(),
  ]);
});

// Format assignments with employee & property names for display
const formattedAssignments = computed(() => {
  if (!AssignmentStore.assignments?.length) return [];

  return AssignmentStore.assignments.map((a) => {
    const employee = EmployeeStore.employees.find((e) => e.id === a.employee_id);
    const propertyIds = Array.isArray(a.property_ids)
      ? a.property_ids
      : [a.property_id];

    const propertyNames = propertyIds
      .map((id) => PropertyStore.properties.find((p) => p.id === id)?.name)
      .filter(Boolean);

    return {
      ...a,
      employee_name: employee ? employee.name : 'Unknown',
      property_names: propertyNames,
    };
  });
});

// ✅ Filtered assignments based on search query
const filteredAssignments = computed(() => {
  if (!searchQuery.value) return formattedAssignments.value;

  const query = searchQuery.value.toLowerCase();

  return formattedAssignments.value.filter((assignment) => {
    const employeeMatch = assignment.employee_name.toLowerCase().includes(query);
    const propertyMatch = assignment.property_names.some((name) =>
      name.toLowerCase().includes(query)
    );
    return employeeMatch || propertyMatch;
  });
});

// Navigate to edit page
const editAssignment = (id) => {
  router.push(`/admin/assignment/edit/${id}`);
};

// Delete assignment
const deleteAssignment = async (id) => {
  if (confirm('Are you sure you want to delete this assignment?')) {
    try {
      await AssignmentStore.deleteAssignment(id);
      toast.success('✅ Assignment deleted successfully!');
    } catch (err) {
      toast.error('❌ Failed to delete assignment');
    }
  }
};

// Return property functionality
const returnProperty = async (assignmentId) => {
  try {
    await api.post(`/assignment/return/${assignmentId}`);
    toast.success('✅ Property returned successfully!');
    await AssignmentStore.fetchAssignments();
  } catch (err) {
    toast.error(err.response?.data?.message || '❌ Failed to return property');
  }
};
</script>

<style scoped>
table {
  width: 100%;
  border-spacing: 0;
}

th,
td {
  border-bottom: 1px solid #e5e7eb;
}
</style>
