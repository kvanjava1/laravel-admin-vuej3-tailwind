<template>
  <Dashboard title="Category" breadcrumb="index">
    <AlertBox :message="message" />
    <ContentBox title="Add Category">
      <VerticalMenu>
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
      </VerticalMenu>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left  text-gray-500 uppercase tracking-wider">
                Name</th>
              <th scope="col" class="px-6 py-3 text-left  text-gray-500 uppercase tracking-wider">
                Slug</th>
              <th scope="col" class="px-6 py-3 text-left  text-gray-500 uppercase tracking-wider">
                Status</th>
              <th scope="col" class="px-6 py-3 text-left  text-gray-500 uppercase tracking-wider">
                Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <!-- Parent Category -->
            <tr>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class=" font-medium text-gray-900">Technology</div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap  text-gray-500">technology</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="px-2 inline-flex  leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap  font-medium">
                <VerticalMenu>
                  <router-link to="">
                    <Button>
                      <PencilIcon class="w-5 h-5" />
                      <label>Edit</label>
                    </Button>
                  </router-link>
                  <Button color="red">
                    <TrashIcon class="w-5 h-5" />
                    <label>Delete</label>
                  </Button>
                </VerticalMenu>
              </td>
            </tr>
            <!-- Child Category - Indented -->
            <tr class="bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="ml-6  font-medium text-gray-900">
                    <i class="fas fa-level-down-alt rotate-90 mr-2 text-gray-400"></i>Web Development
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap  text-gray-500">web-development</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="px-2 inline-flex  leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap  font-medium">
                <VerticalMenu>
                  <router-link to="">
                    <Button>
                      <PencilIcon class="w-5 h-5" />
                      <label>Edit</label>
                    </Button>
                  </router-link>
                  <Button color="red">
                    <TrashIcon class="w-5 h-5" />
                    <label>Delete</label>
                  </Button>
                </VerticalMenu>
              </td>
            </tr>
            <!-- Another Child Category -->
            <tr class="bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="ml-6  font-medium text-gray-900">
                    <i class="fas fa-level-down-alt rotate-90 mr-2 text-gray-400"></i>Mobile Development
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap  text-gray-500">mobile-development</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="px-2 inline-flex leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap  font-medium">
                <VerticalMenu>
                  <router-link to="">
                    <Button>
                      <PencilIcon class="w-5 h-5" />
                      <label>Edit</label>
                    </Button>
                  </router-link>
                  <Button color="red">
                    <TrashIcon class="w-5 h-5" />
                    <label>Delete</label>
                  </Button>
                </VerticalMenu>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </ContentBox>
    <Modal v-show="showSearchModal">
      <ContentBox title="Search Category">
        <VForm>
          <VFormItem>
            <VFormLabel label="Category Name" />
            <VFormInput type="text" name="category" placeholder="Enter category name" />
          </VFormItem>
          <VFormItem>
            <VerticalMenu>
              <Button color="gray" @click.prevent="showSearchModal = false">
                <XMarkIcon class="w-5 h-5" />
                <label>Cancel</label>
              </Button>
              <Button>
                <MagnifyingGlassIcon class="w-5 h-5" />
                <label>Search</label>
              </Button>
            </VerticalMenu>
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
import VerticalMenu from '@/cms/components/VerticalMenu.vue';
import Modal from '@/cms/components/Modal.vue'
import VForm from '@/cms/components/form/vertical/VForm.vue'
import VFormItem from '@/cms/components/form/vertical/VFormItem.vue'
import VFormLabel from '@/cms/components/form/vertical/VFormLabel.vue'
import VFormInput from '@/cms/components/form/vertical/VFormInput.vue'

import { ref } from 'vue';
import { PlusIcon, MagnifyingGlassIcon, PencilIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const message = ref<MessageTypes>({} as MessageTypes)
const showSearchModal = ref<boolean>(false)
const isSearching = ref<boolean>(false)
const clearSearch = (): void => { }
</script>