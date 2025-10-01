<template>
  <div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
      <h2 class="text-2xl font-bold mb-6 text-center">Login to PMS</h2>

      <!-- Error message -->
      <div v-if="authStore.error" class="bg-red-100 text-red-700 p-2 mb-4 rounded">
        {{ authStore.error }}
      </div>

      <!-- Client-side validation errors -->
      <div v-if="clientErrors.length" class="bg-red-100 text-red-700 p-2 mb-4 rounded">
        <ul class="list-disc list-inside">
          <li v-for="error in clientErrors" :key="error">{{ error }}</li>
        </ul>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <!-- Email -->
        <div>
          <label class="block mb-1 font-medium">Email Address</label>
          <input
            v-model="form.email"
            type="email"
            required
            placeholder="you@example.com"
            class="w-full border rounded px-3 py-2"
            :class="{ 'border-red-500': clientErrors.includes('Valid email is required') }"
          />
        </div>

        <!-- Password -->
        <div>
          <label class="block mb-1 font-medium">Password</label>
          <input
            v-model="form.password"
            type="password"
            required
            placeholder="Enter your password"
            class="w-full border rounded px-3 py-2"
            :class="{ 'border-red-500': clientErrors.includes('Password is required') }"
          />
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="authStore.loading"
          class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition disabled:bg-blue-400 disabled:cursor-not-allowed"
        >
          <span v-if="authStore.loading">Logging in...</span>
          <span v-else>Login</span>
        </button>
      </form>

      <!-- Register Redirect -->
      <p class="mt-4 text-center text-sm text-gray-700">
        Don't have an account?
        <router-link
          to="/register"
          class="text-blue-600 font-semibold hover:underline"
        >
          Register here
        </router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../../stores/AuthStore";

const router = useRouter();
const authStore = useAuthStore();
const clientErrors = ref([]);

// form data
const form = reactive({
  email: "",
  password: "",
});

// Client-side validation
const validateForm = () => {
  clientErrors.value = [];

  if (!form.email.trim() || !/\S+@\S+\.\S+/.test(form.email)) {
    clientErrors.value.push('Valid email is required');
  }

  if (!form.password.trim()) {
    clientErrors.value.push('Password is required');
  }

  return clientErrors.value.length === 0;
};

const handleLogin = async () => {
  // Clear previous errors
  authStore.error = null;
  clientErrors.value = [];

  // Validate before submitting
  if (!validateForm()) {
    return;
  }

  try {
    await authStore.login(form.email, form.password);
    // Redirect on successful login
    router.push("/admin/assignments");
  } catch (error) {
    // Error is already handled in the store, but we can add additional handling here if needed
    console.error("Login failed:", error);
  }
};
</script>
