<template>
  <aside class="w-64 bg-gray-800 text-white min-h-screen p-4">
    <!-- Logo / Title -->
    <div class="text-2xl font-bold mb-6 text-center">Admin Panel</div>

    <!-- Navigation Menu -->
    <nav class="space-y-2">
      <router-link
        to="/admin/assignments"
        class="block px-4 py-2 rounded hover:bg-gray-700"
        active-class="bg-gray-700"
      >
        Assignments
      </router-link>

      <router-link
        to="/admin/categories"
        class="block px-4 py-2 rounded hover:bg-gray-700"
        active-class="bg-gray-700"
      >
        Categories
      </router-link>

      <router-link
        to="/admin/employees"
        class="block px-4 py-2 rounded hover:bg-gray-700"
        active-class="bg-gray-700"
      >
        Employees
      </router-link>

      <router-link
        to="/admin/properties"
        class="block px-4 py-2 rounded hover:bg-gray-700"
        active-class="bg-gray-700"
      >
        Properties
      </router-link>
    </nav>
  </aside>
</template>

<script setup>
// No specific script needed yet. The component is static and uses <router-link> to navigate.
</script>

<style scoped>
/* Optional: highlight active menu item using Tailwind classes */
</style>
