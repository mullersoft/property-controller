
<template>
  <div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Categories</h2>

    <!-- Loading State -->
    <div v-if="categoryStore.loading" class="text-blue-500">Loading...</div>

    <!-- Error State -->
    <div v-else-if="categoryStore.error" class="text-red-500">
      {{ categoryStore.error }}
    </div>

    <!-- Categories Table -->
    <table
      v-else-if="categoryStore.categories.length"
      class="min-w-full border border-gray-300"
    >
      <thead class="bg-gray-200">
        <tr>
          <th class="px-4 py-2 border">ID</th>
          <th class="px-4 py-2 border">Name</th>
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
        </tr>
      </tbody>
    </table>

    <!-- No Data -->
    <div v-else class="text-gray-500">No categories found.</div>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useCategoryStore } from '../../stores/CategoryStore';

const categoryStore = useCategoryStore();

onMounted(() => {
  categoryStore.fetchCategories();
});
</script>
