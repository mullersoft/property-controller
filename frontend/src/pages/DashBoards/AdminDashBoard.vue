<template>
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <SideBar />

    <!-- Main content -->
    <div class="flex-1 bg-gray-100 p-6">
      <router-view />
      <!-- Renders the currently selected page -->
    </div>
  </div>
</template>

<script setup>
import SideBar from '../../components/SideBar.vue';
</script>

<style scoped>
/* Optional: add global admin dashboard styles here */
</style>
