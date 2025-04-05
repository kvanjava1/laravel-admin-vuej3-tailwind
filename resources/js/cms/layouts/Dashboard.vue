<template>
  <div class="flex min-h-screen bg-gray-100 text-gray-800 font-sans" @click="handleOutsideClick">
    <Sidebar :is-open="isSidebarOpen" @update:is-open="isSidebarOpen = $event" ref="sidebarComponent" />
    <main :class="[
      'flex-1 overflow-x-hidden transition-all duration-300',
      { 'lg:ml-70': isSidebarOpen }
    ]">
      <Header :title="title" :breadcrumb="breadcrumb" @toggle-sidebar="toggleSidebar" ref="headerComponent" />
      <section class="p-6">
        <slot />
      </section>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import type { Ref } from 'vue';
import Sidebar from '@/cms/components/Sidebar.vue';
import Header from '@/cms/components/Header.vue';

defineProps<{
  title?: string;
  breadcrumb?: string;
}>();

const isSidebarOpen: Ref<boolean> = ref(window.innerWidth >= 1024); // Default based on screen size
const sidebarComponent = ref<InstanceType<typeof Sidebar> | null>(null);
const headerComponent = ref<InstanceType<typeof Header> | null>(null);

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

const handleOutsideClick = (event: MouseEvent) => {
  if (window.innerWidth < 1024 && isSidebarOpen.value) {
    const sidebar = sidebarComponent.value?.$el;
    const hamburger = headerComponent.value?.$el.querySelector('button');
    
    if (sidebar && hamburger && !sidebar.contains(event.target) && !hamburger.contains(event.target)) {
      isSidebarOpen.value = false;
    }
  }
};

// Handle screen resize
const updateSidebarState = () => {
  isSidebarOpen.value = window.innerWidth >= 1024;
};

onMounted(() => {
  window.addEventListener('resize', updateSidebarState);
});

onUnmounted(() => {
  window.removeEventListener('resize', updateSidebarState);
});
</script>