<template>
    <NTableRow>
        <NTableData>
            <div class="flex items-center" :style="`margin-left: ${shiftLevel}px;`">
                <div class="font-medium text-gray-900">{{ categoryItem.name }}</div>
            </div>
        </NTableData>
        <NTableData>{{ categoryItem.slug }}</NTableData>
        <NTableData>
            <span v-if="categoryItem.is_active"
                class="px-2 inline-flex leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                Active
            </span>
            <span v-else class="px-2 inline-flex leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                Not Active
            </span>
        </NTableData>
        <NTableData>
            <VMenu>
                <Button @click="emit('clickToShowAddCategory', { show: true, parent: categoryItem })" color="green">
                    <PlusIcon class="w-5 h-5" />
                    <label>Add</label>
                </Button>
                <Button @click="emit('clickToShowEditCategory', { show: true, data: categoryItem })" color="blue">
                    <PencilIcon class="w-5 h-5" />
                    <label>Edit</label>
                </Button>
                <Button @click="emit('clickToDeleteCategory', { data: categoryItem })" color="red">
                    <TrashIcon class="w-5 h-5" />
                    <label>Delete</label>
                </Button>
            </VMenu>
        </NTableData>
    </NTableRow>
    <template v-for="val in categoryItem.recursive_children" :key="val.id">
        <NTableNestedRow :category-item="val" :shift-level="shiftLevel + 50"
            @clickToShowAddCategory="(params) => emit('clickToShowAddCategory', params)"
            @clickToShowEditCategory="(params) => emit('clickToShowEditCategory', params)" 
            @clickToDeleteCategory="(params) => emit('clickToDeleteCategory', params)"
        />
    </template>
</template>

<script setup lang="ts">
import NTableRow from '@/cms/components/table/normal/NTableRow.vue'
import NTableData from '@/cms/components/table/normal/NTableData.vue'
import Button from '@/cms/components/Button.vue';
import VMenu from '@/cms/components/VMenu.vue';
import { PlusIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline'
import type { CategoryType } from '@/cms/types/category';

defineProps<{ categoryItem: CategoryType, shiftLevel: number }>()
const emit = defineEmits<{
    (e: 'clickToShowAddCategory', params: { show: boolean, parent?: CategoryType }): void,
    (e: 'clickToShowEditCategory', params: { show: boolean, data?: CategoryType }): void,
    (e: 'clickToDeleteCategory', params: { data?: CategoryType }): void
}>()
</script>