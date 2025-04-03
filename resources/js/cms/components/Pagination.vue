<!-- @/cms/components/Pagination.vue -->
<template>
    <div class="flex flex-col items-center mt-6 space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-2">
        <div class="flex flex-wrap justify-center gap-2">
            <button @click="prevPage" :disabled="meta.current_page === 1"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 disabled:opacity-50">
                Prev
            </button>
            <!-- First page -->
            <button v-if="totalPages > 1" @click="fetchPage(1)" :class="[
                'w-10 h-10 flex items-center justify-center text-sm rounded',
                meta.current_page === 1 ? 'bg-green-600 text-white' : 'bg-green-200 text-green-700 hover:bg-green-300'
            ]">
                1
            </button>
            <!-- Ellipsis before range -->
            <span v-if="startPage > 2" class="w-10 h-10 flex items-center justify-center text-sm text-green-500">
                ...
            </span>
            <!-- Page range -->
            <button v-for="page in displayedPages" :key="page" @click="fetchPage(page)" :class="[
                'w-10 h-10 flex items-center justify-center text-sm rounded',
                meta.current_page === page ? 'bg-green-600 text-white' : 'bg-green-200 text-green-700 hover:bg-green-300'
            ]">
                {{ page }}
            </button>
            <!-- Ellipsis after range -->
            <span v-if="endPage < totalPages - 1"
                class="w-10 h-10 flex items-center justify-center text-sm text-green-500">
                ...
            </span>
            <!-- Last page -->
            <button v-if="totalPages > 1 && endPage < totalPages" @click="fetchPage(totalPages)" :class="[
                'w-10 h-10 flex items-center justify-center text-sm rounded',
                meta.current_page === totalPages ? 'bg-green-600 text-white' : 'bg-green-200 text-green-700 hover:bg-green-300'
            ]">
                {{ totalPages }}
            </button>
            <button @click="nextPage" :disabled="meta.current_page === totalPages"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 disabled:opacity-50">
                Next
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    meta: {
        per_page: number;
        current_page: number;
        last_page?: number;
    };
    method: (page: number) => Promise<void>;
}>();

const totalPages = computed(() => {
    return (props.meta.last_page ?? Math.ceil((props.meta.per_page * props.meta.current_page) / props.meta.per_page)) || 1;
});

const pageRange = computed(() => {
    return window.innerWidth < 640 ? 3 : 5; // 3 on mobile, 5 on larger
});

const halfRange = computed(() => Math.floor(pageRange.value / 2));

const startPage = computed(() => Math.max(2, props.meta.current_page - halfRange.value));
const endPage = computed(() => Math.min(totalPages.value - 1, props.meta.current_page + halfRange.value));

const displayedPages = computed(() => {
    const pages = [];
    for (let i = startPage.value; i <= endPage.value; i++) {
        pages.push(i);
    }
    return pages;
});

const fetchPage = (page: number) => {
    if (props.meta.current_page !== page) {
        props.method(page);
    }
};

const prevPage = () => {
    if (props.meta.current_page > 1) {
        props.method(props.meta.current_page - 1);
    }
};

const nextPage = () => {
    if (props.meta.current_page < totalPages.value) {
        props.method(props.meta.current_page + 1);
    }
};
</script>