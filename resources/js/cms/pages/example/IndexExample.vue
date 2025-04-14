<template>
    <Dashboard title="Category" breadcrumb="index">
        <AlertBox :message="message" />
        <ContentBox title="Add Category">
            <VMenu>
                <router-link :to="{ 'name': 'category.add' }">
                    <Button>
                        <PlusIcon class="w-5 h-5" />
                        <label>Add</label>
                    </Button>
                </router-link>
                <Button color="cyan" @click="showSearchModal = true">
                    <MagnifyingGlassIcon class="w-5 h-5" />
                    <label>Search</label>
                </Button>
                <Button color="gray" v-if="isSearching" @click="clearSearch">
                    <XMarkIcon class="w-5 h-5" />
                    <label>Clear Search</label>
                </Button>
            </VMenu>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-gray-500 tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Created At</th>
                            <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Update At</th>
                            <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    </tbody>
                </table>
            </div>
        </ContentBox>
        <Modal v-show="showSearchModal">
            <ContentBox title="Search Category">
                <VForm>
                    <VFormItem>
                        <VFormLabel label="Category Name" />
                        <VFormInput type="text" name="category"
                            placeholder="Enter category name" />
                    </VFormItem>
                    <VFormItem>
                        <VMenu>
                            <Button color="gray" @click.prevent="showSearchModal = false">
                                <XMarkIcon class="w-5 h-5" />
                                <label>Cancel</label>
                            </Button>
                            <Button>
                                <MagnifyingGlassIcon class="w-5 h-5" />
                                <label>Search</label>
                            </Button>
                        </VMenu>
                    </VFormItem>
                </VForm>
            </ContentBox>
        </Modal>
    </Dashboard>
</template>

<script setup lang="ts">
import type { MessageTypes } from '@/cms/types/message';

import Dashboard from '@/cms/layouts/Dashboard.vue';
import ContentBox from '@/cms/components/ContentBox.vue';
import Button from '@/cms/components/Button.vue';
import AlertBox from '@/cms/components/AlertBox.vue';
import VMenu from '@/cms/components/VMenu.vue';
import Modal from '@/cms/components/Modal.vue'
import VForm from '@/cms/components/form/vertical/VForm.vue'
import VFormItem from '@/cms/components/form/vertical/VFormItem.vue'
import VFormLabel from '@/cms/components/form/vertical/VFormLabel.vue'
import VFormInput from '@/cms/components/form/vertical/VFormInput.vue'

import { ref } from 'vue';
import { PlusIcon, MagnifyingGlassIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const message = ref<MessageTypes>({} as MessageTypes)
const showSearchModal = ref<boolean>(false)
const isSearching = ref<boolean>(false)
const clearSearch = (): void => { }
</script>