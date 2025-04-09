<template>
    <div v-if="message.code" :class="[getAlertClasses(message.code), 'px-4 py-3 rounded relative mb-4']" role="alert">
        <!-- Success Message -->
        <h1 v-if="message.code === 'success'" class="font-bold capitalize flex items-center gap-2">
            <CheckCircleIcon class="h-5 w-5 flex-shrink-0" />
            {{ message.message.head }}
        </h1>

        <!-- Validation Error Message -->
        <h1 v-else-if="message.code === 'error_validation'" class="font-bold capitalize flex items-center gap-2">
            <ExclamationTriangleIcon class="h-5 w-5 flex-shrink-0" />
            {{ message.message.head }}
        </h1>

        <!-- Other Error Messages -->
        <h1 v-else class="font-bold capitalize flex items-center gap-2">
            <ExclamationTriangleIcon class="h-5 w-5 flex-shrink-0" />
            {{ message.message.head }}
        </h1>

        <!-- Validation Errors List -->
        <ul v-if="message.code === 'error_validation' && Object.keys(message.message.detail).length" class="pt-5">
            <li v-for="(errors, field) in message.message.detail" :key="field">
                <ul>
                    <li v-for="error in errors" :key="error" class="capitalize mt-2 flex items-start gap-2">
                        <span>{{ error }}</span>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Non-Validation Errors List -->
        <ul v-if="message.code !== 'error_validation' && Object.keys(message.message.detail).length" class="pt-5">
            <li v-for="(detail, index) in message.message.detail" :key="index"
                class="capitalize mt-2 flex items-start gap-2">
                <span>{{ detail }}</span>
            </li>
        </ul>
    </div>
</template>

<script setup lang="ts">
import { ExclamationTriangleIcon, CheckCircleIcon } from '@heroicons/vue/24/outline';
import type { MessageTypes } from '@/cms/types/message';

defineProps<{
    message: MessageTypes;
}>();

const getAlertClasses = (code: string) => {
    return code !== 'success'
        ? 'bg-red-100 border border-red-400 text-red-700'
        : 'bg-green-100 border border-green-400 text-green-700';
};
</script>