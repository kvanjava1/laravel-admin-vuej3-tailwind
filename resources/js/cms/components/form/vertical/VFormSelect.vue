<!-- VFormSelect.vue -->
<template>
    <select
        :name="name"
        :value="modelValue"
        @input="handleInput"
        :class="[
            'block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 transition duration-150 ease-in-out placeholder-gray-400',
            customClass
        ]"
    >
        <option value="">{{ defaultOptionsLabel }}</option>
        <slot />
    </select>
</template>

<script setup lang="ts">
// Define props interface
interface Props {
    name: string
    modelValue?: string | number,
    customClass?: string,
    defaultOptionsLabel: string
}

defineProps<Props>()

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>()

const handleInput = (event: Event) => {
    const target = event.target as HTMLSelectElement
    if (target) {
        emit('update:modelValue', target.value)
    }
}
</script>