<template>
  <div class="p-6 bg-white rounded-lg shadow-md max-w-md mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-4 text-center">
      {{ isEdit ? 'Edit Property Item' : 'Add New Property Item' }}
    </h2>

    <!-- Loading -->
    <div v-if="propertyItemStore.loading" class="text-blue-500 mb-3">
      Loading...
    </div>

    <!-- Error (only show when editing) -->
    <div
      v-if="isEdit && propertyItemStore.error"
      class="bg-red-100 text-red-700 p-2 mb-4 rounded"
    >
      {{ propertyItemStore.error }}
    </div>

    <!-- Success -->
    <div
      v-if="successMessage"
      class="bg-green-100 text-green-700 p-2 mb-4 rounded"
    >
      {{ successMessage }}
    </div>

    <!-- Property Item Form -->
    <form
      v-if="!propertyItemStore.loading"
      @submit.prevent="handleSubmit"
      class="space-y-4"
    >
      <!-- Parent Property (readonly context) -->
      <div>
        <label class="block font-medium mb-1">Parent Property</label>
        <select
          v-model="form.property_id"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        >
          <option disabled value="">-- Select Property --</option>
          <option v-for="p in properties" :key="p.id" :value="p.id">
            {{ p.name }}
          </option>
        </select>
      </div>

      <!-- Serial Number -->
      <div>
        <label for="serial_number" class="block font-medium mb-1">Serial Number</label>
        <input
          id="serial_number"
          v-model="form.serial_number"
          type="text"
          required
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
        />
      </div>

      <!-- Status -->
      <div>
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
        </select>
      </div>

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
          :disabled="propertyItemStore.loading"
          class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded"
        >
          {{ isEdit ? 'Update' : 'Save' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import { usePropertyItemStore } from '../../stores/propertyItemStore'
import { usePropertyStore } from '../../stores/propertyStore'

const router = useRouter()
const route = useRoute()
const toast = useToast()

const propertyItemStore = usePropertyItemStore()
const propertyStore = usePropertyStore()

const properties = ref([])
const isEdit = ref(false)
const successMessage = ref('')

const form = ref({
  property_id: '',
  property_name: '',
  serial_number: '',
  status: '',
  model_number: '',
  manufacturer: '',
})

onMounted(async () => {
  // Load all properties
  await propertyStore.fetchProperties()
  properties.value = propertyStore.properties

  // Pre-fill property id if present
  const propertyId = route.params.id
  if (propertyId) {
    form.value.property_id = propertyId
    const property = properties.value.find(p => p.id === Number(propertyId))
    form.value.property_name = property ? property.name : ''
  }

  // Editing existing item
  const itemId = route.params.itemId
  if (itemId) {
    isEdit.value = true
    try {
      await propertyItemStore.fetchPropertyItem(itemId)
    } catch (err) {
      console.error(err)
      toast.error('Failed to fetch property item.')
    }
  }
})

// Autofill data when editing
watch(
  () => propertyItemStore.propertyItem,
  newItem => {
    if (newItem && isEdit.value) {
      form.value = {
        property_id: newItem.property_id,
        property_name: newItem.property?.name || '',
        serial_number: newItem.serial_number || '',
        status: newItem.status || '',
        model_number: newItem.model_number || '',
        manufacturer: newItem.manufacturer || '',
      }
    }
  }
)

// Submit form
const handleSubmit = async () => {
  try {
    const payload = {
      property_id: form.value.property_id,
      serial_number: form.value.serial_number,
      status: form.value.status,
      model_number: form.value.model_number,
      manufacturer: form.value.manufacturer,
    }

    propertyItemStore.loading = true

    if (isEdit.value) {
      await propertyItemStore.updatePropertyItem(route.params.itemId, payload)
      toast.success('✅ Property item updated successfully!')
    } else {
      await propertyItemStore.createPropertyItem(payload)
      toast.success('✅ Property item added successfully!')
    }

    // Reset form and navigate
    Object.keys(form.value).forEach(k => (form.value[k] = ''))
    router.push(`/admin/property/${payload.property_id}/items`)
  } catch (err) {
    toast.error(propertyItemStore.error || 'Error saving property item.')
  } finally {
    propertyItemStore.loading = false
  }
}

// Cancel navigation
const goBack = () => {
  router.push(`/admin/property/${form.value.property_id}/items`)
}
</script>

<style scoped>
input:invalid {
  border-color: #ef4444;
}
</style>
