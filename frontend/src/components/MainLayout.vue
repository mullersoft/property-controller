<template>
  <div class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
      <div class="flex justify-between items-center px-6 py-4">
        <!-- Logo/Brand -->
        <div class="flex items-center">
          <h1 class="text-xl font-bold text-gray-800">
            Property Management System
          </h1>
        </div>

        <!-- User Menu -->
        <div class="flex items-center space-x-4">
          <span class="text-gray-700">Welcome, {{ user?.name || 'User' }}</span>
          <button
            @click="handleLogout"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200"
          >
            Logout
          </button>
        </div>
      </div>
    </header>

    <div class="flex flex-1">
      <!-- Sidebar -->
      <SideBar />

      <!-- Main Content -->
      <main class="flex-1 bg-gray-50">
        <slot />
        <!-- This will render the child components -->
      </main>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4">
      <div class="container mx-auto px-6 text-center">
        <p>&copy; 2024 Property Management System. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/AuthStore';
import SideBar from './SideBare.vue';

const router = useRouter();
const authStore = useAuthStore();

const user = computed(() => authStore.user);

const handleLogout = () => {
  authStore.logout();
  router.push('/login');
};
</script>


