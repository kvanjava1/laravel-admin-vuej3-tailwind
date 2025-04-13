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
      <Table>
        <TableHead>
          <TableRow>
            <TableHeadItem>Name</TableHeadItem>
            <TableHeadItem>Slug</TableHeadItem>
            <TableHeadItem>Status</TableHeadItem>
            <TableHeadItem>Actions</TableHeadItem>
          </TableRow>
        </TableHead>
        <TableBody>
          <TableRow>
            <TableData>
              <div class="flex items-center">
                <div class="font-medium text-gray-900">Technology</div>
              </div>
            </TableData>
            <TableData>technology</TableData>
            <TableData>
              <span class="px-2 inline-flex  leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                Active
              </span>
            </TableData>
            <TableData>
              <VerticalMenu>
                <Button>
                  <PlusIcon class="w-5 h-5" />
                  <label>Add</label>
                </Button>
                <router-link to="">
                  <Button color="blue">
                    <PencilIcon class="w-5 h-5" />
                    <label>Edit</label>
                  </Button>
                </router-link>
                <Button color="red">
                  <TrashIcon class="w-5 h-5" />
                  <label>Delete</label>
                </Button>
              </VerticalMenu>
            </TableData>
          </TableRow>
          <!-- Child Category - Indented -->
          <TableRow>
            <TableData class="px-6 py-4 whitespace-nowrap text-gray-900">
              <div class="flex items-center">
                <div class="ml-6 text-gray-900">
                  Web Development
                </div>
              </div>
            </TableData>
            <TableData>web-development</TableData>
            <TableData>
              <span class="px-2 inline-flex leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
            </TableData>
            <TableData>
              <VerticalMenu>
                <Button>
                  <PlusIcon class="w-5 h-5" />
                  <label>Add</label>
                </Button>
                <router-link to="">
                  <Button color="blue">
                    <PencilIcon class="w-5 h-5" />
                    <label>Edit</label>
                  </Button>
                </router-link>
                <Button color="red">
                  <TrashIcon class="w-5 h-5" />
                  <label>Delete</label>
                </Button>
              </VerticalMenu>
            </TableData>
          </TableRow>
          <TableRow>
            <TableData class="px-6 py-4 whitespace-nowrap text-gray-900">
              <div class="flex items-center">
                <div class="ml-6 text-gray-900">
                  Web Development
                </div>
              </div>
            </TableData>
            <TableData>web-development</TableData>
            <TableData>
              <span class="px-2 inline-flex leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
            </TableData>
            <TableData>
              <VerticalMenu>
                <Button>
                  <PlusIcon class="w-5 h-5" />
                  <label>Add</label>
                </Button>
                <router-link to="">
                  <Button color="blue">
                    <PencilIcon class="w-5 h-5" />
                    <label>Edit</label>
                  </Button>
                </router-link>
                <Button color="red">
                  <TrashIcon class="w-5 h-5" />
                  <label>Delete</label>
                </Button>
              </VerticalMenu>
            </TableData>
          </TableRow>
        </TableBody>
      </Table>
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
              <Button color="cyan">
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
import Table from '@/cms/components/table/normal/Table.vue';
import TableHead from '@/cms/components/table/normal/TableHead.vue';
import TableRow from '@/cms/components/table/normal/TableRow.vue';
import TableHeadItem from '@/cms/components/table/normal/TableHeadItem.vue';
import TableBody from '@/cms/components/table/normal/TableBody.vue';
import TableData from '@/cms/components/table/normal/TableData.vue';

const message = ref<MessageTypes>({} as MessageTypes)
const showSearchModal = ref<boolean>(false)
const isSearching = ref<boolean>(false)
const clearSearch = (): void => { }
</script>