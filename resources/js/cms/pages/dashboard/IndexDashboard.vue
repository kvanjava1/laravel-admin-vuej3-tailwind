<template>
  <Dashboard title="Dashboard" breadcrumb="Dashboard / Index">
    <ContentBox title="Example Content Box">
      <p class="mt-4 text-gray-600">Welcome to the dashboard!</p>
    </ContentBox>
  </Dashboard>
</template>

<script setup lang="ts">
import ContentBox from '@/cms/components/ContentBox.vue';
import Dashboard from '@/cms/layouts/Dashboard.vue';
</script>