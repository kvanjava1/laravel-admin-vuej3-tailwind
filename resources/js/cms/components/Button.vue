<template>
    <button v-if="color == 'blue'" :class="[
        `bg-blue-600`,
        'text-white px-4 py-2 rounded flex justify-center w-full sm:w-auto',
        `hover:bg-blue-700`,
        'disabled:bg-gray-400 disabled:cursor-not-allowed',
        'space-x-2'
    ]">
        <slot />
    </button>
    <button v-else-if="color == 'red'" :class="[
        `bg-red-600`,
        'text-white px-4 py-2 rounded flex justify-center w-full sm:w-auto',
        `hover:bg-red-700`,
        'disabled:bg-gray-400 disabled:cursor-not-allowed',
        'space-x-2'
    ]">
        <slot />
    </button>
    <button v-else :class="[
        `bg-green-600`,
        'text-white px-4 py-2 rounded flex justify-center w-full sm:w-auto',
        `hover:bg-green-700`,
        'disabled:bg-gray-400 disabled:cursor-not-allowed',
        'space-x-2'
    ]">
        <slot />
    </button>
</template>

<script setup lang="ts">
withDefaults(
    defineProps<{ color?: string }>(),
    { color: '' }
);
</script>