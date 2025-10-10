<template>
  <div class="p-6 bg-white rounded-lg shadow-md max-w-md mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-4 text-center">
      {{ isEdit ? 'Edit Property' : 'Add New Property' }}
    </h2>

    <!-- Loading State -->
    <div v-if="propertyStore.loading" class="text-blue-500 mb-4">
      Loading...
    </div>

    <!-- Error Message -->
    <div
      v-if="propertyStore.error"
      class="bg-red-100 text-red-700 p-2 mb-4 rounded"
    >
      {{ propertyStore.error }}
    </div>

    <!-- Success Message -->
    <div
      v-if="successMessage"
      class="bg-green-100 text-green-700 p-2 mb-4 rounded"
    >
      {{ successMessage }}
    </div>

    <!-- Property Form -->
    <form
      v-if="!propertyStore.loading"
      @submit.prevent="handleSubmit"
      class="space-y-4"
    >
      <!-- Property Name -->
      <div>
        <label for="name" class="block font-medium mb-1">Property Name</label>
        <input
          id="name"
          v-model="form.name"
          type="text"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        />
      </div>
      <!-- Quantity -->
<!-- <div>
  <label for="quantity" class="block font-medium mb-1">Quantity</label>
  <input
    id="quantity"
    v-model="form.quantity"
    type="number"
    min="1"
    required
    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
  />
</div> -->


      <!-- Category -->
      <div>
        <label class="block font-medium mb-1">Category</label>
        <select
          v-model="form.category_id"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        >
          <option disabled value="">-- Select a category --</option>
          <option
            v-for="cat in categoryStore.categories"
            :key="cat.id"
            :value="cat.id"
          >
            {{ cat.name }}
          </option>
        </select>
      </div>

      <!-- Purchase Date -->
      <div>
        <label for="purchase_date" class="block font-medium mb-1">Purchase Date</label>
        <input
          id="purchase_date"
          v-model="form.purchase_date"
          type="date"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        />
      </div>

      <!-- Purchase Cost -->
      <div>
        <label for="purchase_cost" class="block font-medium mb-1">Purchase Cost</label>
        <input
          id="purchase_cost"
          v-model="form.purchase_cost"
          type="number"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        />
      </div>

      <!-- Status -->
      <!-- <div>
        <label for="status" class="block font-medium mb-1">Status</label>
        <select
          id="status"
          v-model="form.status"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        >
          <option disabled value="">-- Select Status --</option>
          <option value="available">Available</option>
          <option value="assigned">Assigned</option>
          <option value="maintenance">Maintenance</option>
          <option value="retired">Retired</option>
        </select>
      </div> -->

      <!-- Serial Number -->
      <!-- <div>
        <label for="serial_number" class="block font-medium mb-1">Serial Number</label>
        <input
          id="serial_number"
          v-model="form.serial_number"
          type="text"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        />
      </div> -->

      <!-- Model Number -->
      <div>
        <label for="model_number" class="block font-medium mb-1">Model Number</label>
        <input
          id="model_number"
          v-model="form.model_number"
          type="text"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        />
      </div>

      <!-- Manufacturer -->
      <div>
        <label for="manufacturer" class="block font-medium mb-1">Manufacturer</label>
        <input
          id="manufacturer"
          v-model="form.manufacturer"
          type="text"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        />
      </div>

      <!-- Current Value -->
      <!-- <div>
        <label for="current_value" class="block font-medium mb-1">Current Value</label>
        <input
          id="current_value"
          v-model="form.current_value"
          type="number"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        />
      </div> -->

      <!-- Buttons -->
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
          :disabled="propertyStore.loading"
          class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded"
        >
          <span v-if="propertyStore.loading">
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
import { usePropertyStore } from '../../stores/propertyStore';

const router = useRouter();
const route = useRoute();
const propertyStore = usePropertyStore();
const categoryStore = useCategoryStore(); // template

const isEdit = ref(false);
const successMessage = ref('');

// Form fields
const form = ref({
  name: '',
    // quantity: '', // <-- include this!

  category_id: '',
  purchase_date: '',
  purchase_cost: '',
  // status: '',
  // serial_number: '',
  model_number: '',
  manufacturer: '',
  // current_value: '',
});

// Detect edit mode
onMounted(async () => {
  await categoryStore.fetchCategories();

  const id = route.params.id;
  if (id) {
    isEdit.value = true;
    await propertyStore.fetchProperty(id);
  }
});

// Watch for store updates and autofill form
watch(
  () => propertyStore.property,
  (newProperty) => {
    if (newProperty && isEdit.value) {
      form.value = {
        name: newProperty.name || '',
        category_id: newProperty.category_id || '',
        purchase_date: newProperty.purchase_date || '',
        purchase_cost: newProperty.purchase_cost || '',
        // status: newProperty.status || '',
        // serial_number: newProperty.serial_number || '',
        model_number: newProperty.model_number || '',
        manufacturer: newProperty.manufacturer || '',
        // current_value: newProperty.current_value || '',
      };
    }
  }
);

const handleSubmit = async () => {
  // Clear previous messages
  propertyStore.error = '';
  successMessage.value = '';

  try {
    // Validate required fields manually (optional)
    if (!form.value.name || !form.value.category_id || !form.value.purchase_date || !form.value.purchase_cost || !form.value.model_number || !form.value.manufacturer) {
      propertyStore.error = 'Please fill in all required fields.';
      return; // Stop submission
    }

    if (isEdit.value) {
      await propertyStore.updateProperty(route.params.id, form.value);
      successMessage.value = 'Property updated successfully!';
    } else {
      await propertyStore.createProperty(form.value);
      successMessage.value = 'Property added successfully!';
      // Clear form after success
      Object.keys(form.value).forEach((key) => (form.value[key] = ''));
    }

    // Only redirect after a short delay (optional)
    setTimeout(() => {
      // router.push('/admin/properties');
    }, 1500);

  } catch (err) {
    // If backend returns an error, show it and DO NOT redirect
    if (err.response && err.response.data && err.response.data.message) {
      propertyStore.error = err.response.data.message;
    } else {
      propertyStore.error = 'An unexpected error occurred.';
    }
    successMessage.value = ''; // Clear success message on error
  }
};



// Cancel navigation
const goBack = () => {
  router.push('/admin/properties');
};
</script>
