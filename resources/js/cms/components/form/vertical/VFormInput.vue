<template>
    <input 
        :type="type"
        :name="name"
        :placeholder="placeholder"
        :value="modelValue"
        @input="handleInput"
        :required="required"
        :class="[
            'block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm',
            'focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500',
            'transition duration-150 ease-in-out placeholder-gray-400',
            customClass
        ]"
    />
</template>

<script setup lang="ts">
interface Props {
    type?: 'text' | 'email' | 'password' | 'number' | 'tel' | 'url'; // Common input types
    name: string;
    placeholder?: string;
    modelValue?: string | number;
    customClass?: string;
    required?: boolean;
}

withDefaults(defineProps<Props>(), {
    type: 'text',
    placeholder: '',
    customClass: '',
    required: false
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>();

const handleInput = (event: Event) => {
    emit('update:modelValue', (event.target as HTMLInputElement).value);
};
</script>