<template>
    <label class="block text-gray-700 font-bold">{{ label }}</label>
</template>

<script setup lang="ts">
defineProps<{
    label: string
}>()
</script>