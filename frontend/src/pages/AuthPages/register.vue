<template>
  <div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
      <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>

      <!-- Error message -->
      <div v-if="registerStore.error" class="bg-red-100 text-red-700 p-2 mb-4 rounded">
        {{ registerStore.error }}
      </div>

      <!-- Client-side validation errors -->
      <div v-if="clientErrors.length" class="bg-red-100 text-red-700 p-2 mb-4 rounded">
        <ul class="list-disc list-inside">
          <li v-for="error in clientErrors" :key="error">{{ error }}</li>
        </ul>
      </div>

      <!-- Success message -->
      <div
        v-if="registerStore.success"
        class="bg-green-100 text-green-700 p-2 mb-4 rounded"
      >
        User registered successfully!
      </div>

      <form @submit.prevent="handleRegister" class="space-y-4">
        <!-- Name -->
        <div>
          <label class="block mb-1 font-medium">Name</label>
          <input
            v-model="form.name"
            type="text"
            required
            class="w-full border rounded px-3 py-2"
            :class="{ 'border-red-500': clientErrors.includes('Name is required') }"
          />
        </div>

        <!-- Email -->
        <div>
          <label class="block mb-1 font-medium">Email</label>
          <input
            v-model="form.email"
            type="email"
            required
            class="w-full border rounded px-3 py-2"
            :class="{ 'border-red-500': clientErrors.includes('Valid email is required') }"
          />
        </div>

        <!-- Role -->
        <div>
          <label class="block mb-1 font-medium">Role</label>
          <select
            v-model="form.role"
            class="w-full border rounded px-3 py-2"
            :class="{ 'border-red-500': clientErrors.includes('Role is required') }"
          >
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="employee">Employee</option>
            <option value="user">User</option>
          </select>
        </div>

        <!-- Password -->
        <div>
          <label class="block mb-1 font-medium">Password</label>
          <input
            v-model="form.password"
            type="password"
            required
            minlength="8"
            maxlength="20"
            class="w-full border rounded px-3 py-2"
            :class="{ 'border-red-500': clientErrors.includes('Password must be at least 8 characters') || clientErrors.includes('Passwords do not match') }"
          />
        </div>

        <!-- Confirm Password -->
        <div>
          <label class="block mb-1 font-medium">Confirm Password</label>
          <input
            v-model="form.password_confirmation"
            type="password"
            required
            minlength="8"
            maxlength="20"
            class="w-full border rounded px-3 py-2"
            :class="{ 'border-red-500': clientErrors.includes('Passwords do not match') }"
          />
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="registerStore.loading"
          class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition disabled:bg-blue-400 disabled:cursor-not-allowed"
        >
          <span v-if="registerStore.loading">Registering...</span>
          <span v-else>Register</span>
        </button>
      </form>

      <!-- Login Redirect -->
      <p class="mt-4 text-center text-sm text-gray-700">
        Do you have an account?
        <router-link
          to="/login"
          class="text-blue-600 font-semibold hover:underline"
        >
          login here
        </router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";

import { useRegisterStore } from "../../stores/RegisterStore";

const registerStore = useRegisterStore();
const clientErrors = ref([]);
const router = useRouter();


// form data
const form = reactive({
  name: "",
  email: "",
  role: "",
  password: "",
  password_confirmation: "",
});

// Client-side validation
const validateForm = () => {
  clientErrors.value = [];

  if (!form.name.trim()) {
    clientErrors.value.push('Name is required');
  }

  if (!form.email.trim() || !/\S+@\S+\.\S+/.test(form.email)) {
    clientErrors.value.push('Valid email is required');
  }

  if (!form.role) {
    clientErrors.value.push('Role is required');
  }

  if (form.password.length < 8) {
    clientErrors.value.push('Password must be at least 8 characters');
  }

  if (form.password !== form.password_confirmation) {
    clientErrors.value.push('Passwords do not match');
  }

  return clientErrors.value.length === 0;
};

const handleRegister = async () => {
  // Clear previous errors
  registerStore.error = null;
  clientErrors.value = [];

  // Validate before submitting
  if (!validateForm()) {
    return;
  }

  await registerStore.register({ ...form });

  // Redirect immediately if registration was successful
  if (registerStore.success) {
    router.push("/login");
  }
};
</script>
