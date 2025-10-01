<template>
  <div class="bg-gray-300 p-8 rounded-2xl shadow-lg max-w-sm mx-auto my-24">
    <!-- Title -->
    <h2 class="text-3xl font-bold text-center text-gray-900 mb-6">
      Login to PMS
    </h2>

    <!-- Login Form -->
    <form @submit.prevent="handleLogin" class="space-y-5">
      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
          Email Address
        </label>
        <input
          type="email"
          id="email"
          v-model="email"
          placeholder="you@example.com"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-400 focus:outline-none"
        />
      </div>

      <!-- Password -->
      <div>
        <label
          for="password"
          class="block text-sm font-medium text-gray-700 mb-1"
        >
          Password
        </label>
        <input
          type="password"
          id="password"
          v-model="password"
          placeholder="Enter your password"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-400 focus:outline-none"
        />
      </div>

      <!-- Submit Button -->
      <button
        type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold transition duration-200"
      >
        Login
      </button>
    </form>

  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/AuthStore';

const email = ref('');
const password = ref('');
const router = useRouter()
const authStore = useAuthStore();
const handleLogin = async () => {
  try{
    await authStore.login(email.value, password.value);
          router.push("/admin/assignments");

  } catch(error){
    console.error('Login failed:', error);
    alert('Login failed. Please check your credentials and try again.');
  }

};
</script>
