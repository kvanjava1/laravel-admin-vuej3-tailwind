<template>
  <header class="flex items-center p-6 bg-white shadow">
    <button ref="hamburgerRef" @click="toggleSidebar" class="lg:hidden p-2 hover:bg-gray-200 rounded mr-4">
      <Bars3Icon class="h-6 w-6" />
    </button>
    <div>
      <h2 class="text-xl font-semibold">{{ title }}</h2>
      <p class="text-sm text-gray-600">{{ breadcrumb }}</p>
    </div>
  </header>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Bars3Icon } from '@heroicons/vue/24/outline';

// Define props with TS
defineProps<{
  title?: string;
  breadcrumb?: string;
}>();

// Define emits with TS
const emit = defineEmits<{
  (e: 'toggleSidebar'): void;
}>();

const hamburgerRef = ref<HTMLButtonElement | null>(null);

const toggleSidebar = () => {
  emit('toggleSidebar');
};

defineExpose({ hamburgerRef });
</script>