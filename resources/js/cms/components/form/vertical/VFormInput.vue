<template>
    <input 
        :type="type" 
        :name="name" 
        :placeholder="placeholder" 
        :value="modelValue"
        @input="handleInput" 
        :class="[
            'block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 transition duration-150 ease-in-out placeholder-gray-400',
            customClass
        ]" 
    />
</template>

<script setup lang="ts">
defineProps({
    type: { type: String, default: "text" },
    name: { type: String, required: true },
    placeholder: { type: String, default: "" },
    modelValue: String,
    customClass: { type: String, default: "" }
});

const emit = defineEmits(["update:modelValue"]);

const handleInput = (event: Event) => {
    const target = event.target as HTMLInputElement;
    emit('update:modelValue', target.value);
};
</script>