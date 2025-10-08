<template>
  <div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Categories</h2>

    <!-- Add button -->
    <div class="mb-4 flex justify-end">
      <router-link
        to="/admin/category/add"
        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
      >
        + Add Category
      </router-link>
    </div>

    <!-- Loading State -->
    <div v-if="categoryStore.loading" class="text-blue-500">Loading...</div>

    <!-- Error State -->
    <div v-else-if="categoryStore.error" class="text-red-500">
      {{ categoryStore.error }}
    </div>

    <!-- Categories Table -->
    <div v-else-if="categoryStore.categories.length">
      <table class="min-w-full border border-gray-300">
        <thead class="bg-gray-200">
          <tr>
            <th class="px-4 py-2 border">ID</th>
            <th class="px-4 py-2 border">Name</th>
            <th class="px-4 py-2 border text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="cat in categoryStore.categories"
            :key="cat.id"
            class="hover:bg-gray-100"
          >
            <td class="px-4 py-2 border">{{ cat.id }}</td>
            <td class="px-4 py-2 border">{{ cat.name }}</td>
            <td class="px-4 py-2 border text-center space-x-2">
              <!-- Edit Button -->
              <router-link
                :to="`/admin/category/edit/${cat.id}`"

                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded"
              >
                Edit
              </router-link>

              <!-- Delete Button -->
              <button
                @click="deleteCategory(cat.id)"
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- No Data -->
    <div v-else class="text-gray-500">No categories found.</div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useCategoryStore } from '../../stores/CategoryStore';

const categoryStore = useCategoryStore();

// Fetch categories when the page loads
onMounted(() => {
  categoryStore.fetchCategories();
});

// Delete handler with confirmation
const deleteCategory = async (id) => {
  if (confirm('Are you sure you want to delete this category?')) {
    await categoryStore.deleteCategory(id);
    // Optionally refetch to refresh list
    await categoryStore.fetchCategories();
  }
};
</script>
