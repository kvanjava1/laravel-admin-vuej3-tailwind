<template>
    <div class="bg-white rounded-lg shadow-lg p-6 space-y-6">
        <slot name="top" />
        <h2 class="mb-4 font-bold text-2xl">{{ title }}</h2>
        <slot />
    </div>
</template>

<script setup lang="ts">
defineProps<{
    title?: string; // Optional with ? since it has a default
}>();
</script>