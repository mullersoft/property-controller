<template>
  <aside class="w-64 bg-gray-800 text-white min-h-screen p-4">
    <!-- Logo / Title -->
    <div class="text-xl font-bold mb-8 text-center border-b border-gray-700 pb-4">
      Admin Panel
    </div>

    <!-- Navigation Menu -->
    <nav class="space-y-1">
      <router-link
        to="/admin/assignments"
        class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 transition duration-200"
        active-class="bg-blue-600 text-white"
      >
        <span class="ml-3">Assignments</span>
      </router-link>

      <router-link
        to="/admin/categories"
        class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 transition duration-200"
        active-class="bg-blue-600 text-white"
      >
        <span class="ml-3">Categories</span>
      </router-link>

      <router-link
        to="/admin/employees"
        class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 transition duration-200"
        active-class="bg-blue-600 text-white"
      >
        <span class="ml-3">Employees</span>
      </router-link>

      <router-link
        to="/admin/properties"
        class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 transition duration-200"
        active-class="bg-blue-600 text-white"
      >
        <span class="ml-3">Properties</span>
      </router-link>
    </nav>
  </aside>
</template>

