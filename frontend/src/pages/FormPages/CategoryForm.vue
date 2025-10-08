<template>
  <div class="p-6 bg-white rounded-lg shadow-md max-w-md mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-4 text-center">
      {{ isEdit ? 'Edit Category' : 'Add New Category' }}
    </h2>

    <!-- Loading State -->
    <div v-if="categoryStore.loading" class="text-blue-500 mb-4">
      Loading...
    </div>

    <!-- Error Message -->
    <div
      v-if="categoryStore.error"
      class="bg-red-100 text-red-700 p-2 mb-4 rounded"
    >
      {{ categoryStore.error }}
    </div>

    <!-- Success Message -->
    <div
      v-if="successMessage"
      class="bg-green-100 text-green-700 p-2 mb-4 rounded"
    >
      {{ successMessage }}
    </div>

    <!-- Category Form -->
    <form v-if="!categoryStore.loading" @submit.prevent="handleSubmit" class="space-y-4">
      <div>
        <label for="name" class="block font-medium mb-1">Category Name</label>
        <input
          id="name"
          v-model="form.name"
          type="text"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        />
      </div>

      <div class="flex justify-end space-x-2">
        <!-- Cancel Button -->
        <button
          type="button"
          @click="goBack"
          class="bg-gray-400 hover:bg-gray-500 text-white py-2 px-4 rounded"
        >
          Cancel
        </button>

        <!-- Save/Update Button -->
        <button
          type="submit"
          :disabled="categoryStore.loading"
          class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded"
        >
          <span v-if="categoryStore.loading">
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
import { useCategoryStore } from '../../stores/CategoryStore';

const router = useRouter();
const route = useRoute();
const categoryStore = useCategoryStore();

const isEdit = ref(false);
const successMessage = ref('');

// Form state
const form = ref({
  name: '',
});

// Detect if editing based on route param
onMounted(async function () {
  const id = route.params.id;
  if (id) {
    isEdit.value = true;
    await categoryStore.fetchCategory(id); // fetch category from API
  }
});

// Watch for changes in categoryStore.category and auto-fill form
watch(
  function () { return categoryStore.category; },
  function (newCategory) {
    if (newCategory && isEdit.value) {
      form.value.name = newCategory.name;
    }
  }
);

// Handle form submission
const handleSubmit = async function () {
  if (isEdit.value) {
    await categoryStore.updateCategory(route.params.id, form.value);
    successMessage.value = 'Category updated successfully!';
  } else {
    await categoryStore.createCategory(form.value);
    successMessage.value = 'Category added successfully!';
    form.value.name = ''; // reset form
  }

  // Redirect after 1 second
  setTimeout(function () {
    router.push('/admin/categories');
  }, 1000);
};

// Cancel and go back
const goBack = function () {
  router.push('/admin/categories');
};
</script>
