<template>
  <div class="p-6">
    <!-- Page Title -->
    <h2 class="text-2xl font-bold mb-4">Properties</h2>
   <!-- Add button -->
    <div class="mb-4 flex justify-end">
      <router-link
        to="/admin/property/add"
        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
      >
        + Add Property
      </router-link>
    </div>
    <!-- =========================
         CATEGORY DROPDOWN FILTER
         ========================= -->
    <div class="mb-4">
      <label for="category" class="mr-2 font-medium">Filter by Category:</label>
      <select id="category" v-model="selectedCategoryId">
        class="border rounded px-2 py-1" >
        <!-- Default option: show all categories -->
        <option :value="null">All Categories</option>

        <!-- Loop through categories from store and create dropdown options -->
        <option
          v-for="cat in categoryStore.categories"
          :key="cat.id"
          :value="cat.id"
        >
          {{ cat.name }}
        </option>
      </select>
    </div>

    <!-- =========================
         LOADING STATE
         ========================= -->
    <div v-if="propertyStore.loading" class="text-blue-500">Loading...</div>

    <!-- =========================
         ERROR STATE
         ========================= -->
    <div v-else-if="propertyStore.error" class="text-red-500">
      {{ propertyStore.error }}
    </div>

    <!-- =========================
         PROPERTIES TABLE
         Only show if there are properties
         ========================= -->
    <table
      v-else-if="propertyStore.properties.length"
      class="min-w-full border border-gray-300"
    >
      <thead class="bg-gray-200">
        <tr>
          <th class="px-4 py-2 border">ID</th>
          <th class="px-4 py-2 border">Category Name</th>
          <th class="px-4 py-2 border">Property Name</th>
          <th class="px-4 py-2 border">Purchase Date</th>
          <th class="px-4 py-2 border">Purchase Cost</th>
          <th class="px-4 py-2 border">Status</th>
          <th class="px-4 py-2 border">Serial Number</th>
          <th class="px-4 py-2 border">Model Number</th>
          <th class="px-4 py-2 border">Manufacturer</th>
          <th class="px-4 py-2 border">Current Value</th>
            <th class="px-4 py-2 border text-center">Actions</th>

        </tr>
      </thead>
      <tbody>
        <!-- Loop through computed filteredProperties -->
        <tr
          v-for="prop in filteredProperties"
          :key="prop.id"
          class="hover:bg-gray-100"
        >
          <td class="px-4 py-2 border">{{ prop.id }}</td>
          <td class="px-4 py-2 border">{{ prop.category.name }}</td>

          <td class="px-4 py-2 border">{{ prop.name }}</td>
          <td class="px-4 py-2 border">{{ prop.purchase_date }}</td>
          <td class="px-4 py-2 border">{{ prop.purchase_cost }}</td>
          <td class="px-4 py-2 border">{{ prop.status }}</td>
          <td class="px-4 py-2 border">{{ prop.serial_number }}</td>
          <td class="px-4 py-2 border">{{ prop.model_number }}</td>
          <td class="px-4 py-2 border">{{ prop.manufacturer }}</td>
          <td class="px-4 py-2 border">{{ prop.current_value }}</td>
          <td class="px-4 py-2 border text-center space-x-2">
              <!-- Edit Button -->
              <router-link
                :to="`/admin/property/edit/${prop.id}`"

                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded"
              >
                Edit
              </router-link>

              <!-- Delete Button -->
              <button
                @click="deleteProperty(prop.id)"
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
              >
                Delete
              </button>
            </td>
        </tr>
      </tbody>
    </table>

    <!-- =========================
         EMPTY STATE
         Shown if no properties exist
         ========================= -->
    <div v-else class="text-gray-500">No Properties found.</div>
  </div>
</template>

<script setup>
/**
 * Import necessary Vue utilities and Pinia stores
 */
import { computed, onMounted, ref } from 'vue';
import { useCategoryStore } from '../../stores/CategoryStore';
import { usePropertyStore } from '../../stores/propertyStore';

/**
 * Initialize Pinia stores
 */
const propertyStore = usePropertyStore(); // manages property state (list, loading, error, etc.)
const categoryStore = useCategoryStore(); // manages category state (for dropdown)

/**
 * Dropdown selection (null means show all categories)
 */
const selectedCategoryId = ref(null);

/**
 * Computed property:
 * Filters the full property list based on selected category.
 * - If no category is selected -> return all properties
 * - If a category is selected -> return only properties matching that category_id
 */
const filteredProperties = computed(() => {
  if (!selectedCategoryId.value) return propertyStore.properties;
  return propertyStore.properties.filter(
    (p) => p.category_id === selectedCategoryId.value
  );
});

/**
 * Lifecycle hook:
 * Runs when the component is mounted.
 * - Fetches all properties
 * - Fetches all categories (needed for dropdown options)
 */
onMounted(() => {
  propertyStore.fetchProperties();
  categoryStore.fetchCategories();
});

// Delete handler with confirmation
const deleteProperty = async (id) => {
  if (confirm('Are you sure you want to delete this property?')) {
    await propertyStore.deleteProperty(id);
    // Optionally refetch to refresh list
    await propertyStore.fetchProperties();
  }
};
</script>
