<template>
  <div class="p-6">
    <!-- Page Title -->
    <h2 class="text-2xl font-bold mb-4">
      Units for {{ property ? property.name : 'Loading...' }}
    </h2>

    <!-- Top Actions: Add Button + Refresh -->
    <div class="flex justify-between items-center mb-4">
      <!-- Only show Add button if property is loaded -->
<router-link
  v-if="property"
  :to="{ name: 'AddPropertyItem', params: { id: property.id } }"
  class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded shadow transition-all duration-200"
>
  + Add New Unit
</router-link>




      <button
        @click="fetchPropertyData"
        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow transition-all duration-200"
      >
        Refresh
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-blue-500 font-medium">
      Loading property items...
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="text-red-500">{{ error }}</div>

    <!-- Items Table -->
    <table
      v-else-if="items.length"
      class="min-w-full border border-gray-300 mt-2"
    >
      <thead class="bg-gray-200">
        <tr>
          <th class="border px-3 py-2 text-left">#</th>
          <th class="border px-3 py-2 text-left">Serial Number</th>
          <th class="border px-3 py-2 text-left">Status</th>
          <th class="border px-3 py-2 text-left">Assigned To</th>
          <th class="border px-3 py-2 text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(item, index) in items"
          :key="item.id"
          class="hover:bg-gray-100 transition-all duration-150"
        >
          <td class="border px-3 py-2">{{ index + 1 }}</td>
          <td class="border px-3 py-2">{{ item.serial_number }}</td>
          <td class="border px-3 py-2">
            <span
              :class="{
                'text-green-600 font-semibold': item.status === 'available',
                'text-yellow-600 font-semibold': item.status === 'assigned',
                'text-gray-500': item.status === 'maintenance',
              }"
            >
              {{ item.status }}
            </span>
          </td>
          <td class="border px-3 py-2">
            {{ item.employee ? item.employee.name : '-' }}
          </td>
          <td class="border px-3 py-2 text-center space-x-2">
            <!-- Edit Button -->
            <router-link
              v-if="property"
              :to="`/admin/property/${property.id}/edit-item/${item.id}`"
              class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded"
            >
              Edit
            </router-link>

            <!-- Delete Button -->
            <button
              @click="deleteItem(item.id)"
              class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Empty State -->
    <div v-else class="text-gray-500 mt-6">
      No units added yet for this property.
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { useToast } from 'vue-toastification';
import { usePropertyItemStore } from '../../stores/propertyItemStore';

const route = useRoute();
const toast = useToast();
const propertyItemStore = usePropertyItemStore();

const property = ref(null);
const items = ref([]);
const loading = ref(false);
const error = ref('');

const fetchPropertyData = async () => {
  try {
    loading.value = true;
    error.value = '';

    const propId = route.params.id;

    // Fetch all items from store
    await propertyItemStore.fetchPropertyItems();

    // Filter items for this property
    items.value = propertyItemStore.propertyItems.filter(
      (i) => i.property_id === Number(propId)
    );

    // Set property name (assume first item has the property data)
    property.value = items.value[0]?.property || {
      id: propId,
      name: 'Unknown',
    };
  } catch (err) {
    error.value = 'Failed to load property items.';
  } finally {
    loading.value = false;
  }
};

const deleteItem = async (id) => {
  if (confirm('Are you sure you want to delete this item?')) {
    try {
      await propertyItemStore.deletePropertyItem(id); // you need to add delete action in the store
      toast.success('✅ Item deleted successfully!');
      fetchPropertyData();
    } catch {
      toast.error('❌ Failed to delete item.');
    }
  }
};

onMounted(() => {
  fetchPropertyData();
});
</script>

<style scoped>
table {
  border-radius: 6px;
  overflow: hidden;
}
</style>
