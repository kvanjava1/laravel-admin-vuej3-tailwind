<template>
  <Dashboard title="Dashboard" breadcrumb="Not Found">
    <div class="flex flex-col items-center justify-center min-h-[50vh] text-center">
      <h1 class="text-6xl font-bold text-gray-800 mb-6">404</h1>
      <p class="text-xl text-gray-600 mb-6 px-4">Oops! Looks like you’re lost.</p>
      <router-link :to="{ name: 'dashboard.index' }" class="text-blue-500 hover:underline">
        Back to Dashboard
      </router-link>
    </div>
  </Dashboard>
</template>

<script setup lang="ts">
import Dashboard from '@/cms/layouts/Dashboard.vue';
</script>