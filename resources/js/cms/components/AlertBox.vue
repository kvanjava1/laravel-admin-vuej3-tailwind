<template>
    <div v-if="message.code" :class="[getAlertClasses(message.code), 'px-4 py-3 rounded relative mb-4']" role="alert">
        <h1 class="font-bold capitalize">{{ message.message.head }}</h1>
        <ul v-if="message.code == 'error_validation' && Object.keys(message.message.detail).length" class="pt-5">
            <li v-for="detail in message.message.detail" :key="detail">
                <ul>
                    <li v-for="moreDetail in detail" class="capitalize">
                        {{ moreDetail }}
                    </li>
                </ul>
            </li>
        </ul>
        <ul v-if="message.code !== 'error_validation' && Object.keys(message.message.detail).length" class="pt-5">
            <li v-for="detail in message.message.detail" :key="detail" class="capitalize">
                {{ detail }}
            </li>
        </ul>
    </div>
</template>

<script setup lang="ts">
import type { MessageTypes } from "@/cms/types/message";

const props = defineProps<{ message: MessageTypes }>();
const getAlertClasses = (code: string) => {
    return code !== 'success'
        ? 'bg-red-100 border border-red-400 text-red-700'
        : 'bg-green-100 border border-green-400 text-green-700';
};
</script>