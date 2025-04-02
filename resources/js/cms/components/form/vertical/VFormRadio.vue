<!-- VFormRadio.vue -->
<template>
    <div class="mt-1 flex items-center" :class="customClass">
        <input
            type="radio"
            :name="name"
            :value="radioValue"
            :checked="modelValue === radioValue"
            @input="handleInput"
            class="h-5 w-5 rounded border border-gray-300 text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-1 transition duration-150 ease-in-out"
        />
        <span class="ml-2 text-gray-600">
            {{ label }}
        </span>
    </div>
</template>

<script setup lang="ts">
// Define props interface
interface Props {
    name: string
    radioValue: string
    modelValue?: string
    customClass?: string,
    label: string
}

defineProps<Props>()

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>()

const handleInput = (event: Event) => {
    const target = event.target as HTMLInputElement
    if (target) {
        emit('update:modelValue', target.value)
    }
}
</script>