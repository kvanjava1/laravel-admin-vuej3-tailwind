<template>
    <button :class="buttonClasses">
        <slot />
    </button>
</template>

<script setup lang="ts">
const props = withDefaults(
    defineProps<{ color?: 'red' | 'blue' | 'gray' | 'green' | 'cyan' }>(), // Updated to 'gray'
    { color: 'green' }
);

const buttonClasses = [
    props.color === 'red' ? 'bg-red-600 hover:bg-red-700' : 
    props.color === 'blue' ? 'bg-blue-500 hover:bg-blue-600' :
    props.color === 'gray' ? 'bg-gray-600 hover:bg-gray-700' :
    props.color === 'cyan' ? 'bg-cyan-600 hover:bg-cyan-700' : // Changed 'grey' → 'gray'
    'bg-green-600 hover:bg-green-700',
    'text-white px-4 py-2 rounded flex items-center justify-center gap-2',
    'disabled:bg-gray-400 disabled:cursor-not-allowed',
    'px-4 py-3'
].join(' ');
</script>