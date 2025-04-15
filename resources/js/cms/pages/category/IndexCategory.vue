<template>
  <Dashboard title="Category" breadcrumb="index">
    <AlertBox :message="message" />
    <ContentBox title="Add Category">
      <VMenu>
        <Button @click="clickToShowAddCategory({ show: true })">
          <PlusIcon class="w-5 h-5" />
          <label>Add</label>
        </Button>
        <Button color="cyan" @click="showSearchModal = true">
          <MagnifyingGlassIcon class="w-5 h-5" />
          <label>Search</label>
        </Button>
        <Button color="gray" v-if="isSearching" @click="clearSearch">
          <XMarkIcon class="w-5 h-5" />
          <label>Clear Search</label>
        </Button>
      </VMenu>
      <NTable>
        <NTableHead>
          <NTableRow>
            <NTableHeadItem>Name</NTableHeadItem>
            <NTableHeadItem>Slug</NTableHeadItem>
            <NTableHeadItem>Status</NTableHeadItem>
            <NTableHeadItem>Actions</NTableHeadItem>
          </NTableRow>
        </NTableHead>
        <NTableBody>
          <NTableRow>
            <NTableData>
              <div class="flex items-center">
                <div class="font-medium text-gray-900">Technology</div>
              </div>
            </NTableData>
            <NTableData>technology</NTableData>
            <NTableData>
              <span class="px-2 inline-flex  leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                Active
              </span>
            </NTableData>
            <NTableData>
              <VMenu>
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
              </VMenu>
            </NTableData>
          </NTableRow>
          <!-- Child Category - Indented -->
          <NTableRow>
            <NTableData class="px-6 py-4 whitespace-nowrap text-gray-900">
              <div class="flex items-center">
                <div class="ml-6 text-gray-900">
                  Web Development
                </div>
              </div>
            </NTableData>
            <NTableData>web-development</NTableData>
            <NTableData>
              <span
                class="px-2 inline-flex leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
            </NTableData>
            <NTableData>
              <VMenu>
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
              </VMenu>
            </NTableData>
          </NTableRow>
          <NTableRow>
            <NTableData class="px-6 py-4 whitespace-nowrap text-gray-900">
              <div class="flex items-center">
                <div class="ml-6 text-gray-900">
                  Web Development
                </div>
              </div>
            </NTableData>
            <NTableData>web-development</NTableData>
            <NTableData>
              <span
                class="px-2 inline-flex leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
            </NTableData>
            <NTableData>
              <VMenu>
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
              </VMenu>
            </NTableData>
          </NTableRow>
        </NTableBody>
      </NTable>
    </ContentBox>
    <Modal v-show="showSearchModal">
      <ContentBox title="Search Category">
        <VForm>
          <VFormItem>
            <VFormLabel label="Category Name" />
            <VFormInput type="text" name="category" placeholder="Enter category name" />
          </VFormItem>
          <VFormItem>
            <VMenu>
              <Button color="gray" @click.prevent="showSearchModal = false">
                <XMarkIcon class="w-5 h-5" />
                <label>Cancel</label>
              </Button>
              <Button color="cyan">
                <MagnifyingGlassIcon class="w-5 h-5" />
                <label>Search</label>
              </Button>
            </VMenu>
          </VFormItem>
        </VForm>
      </ContentBox>
    </Modal>
    <Modal v-show="showAddCategory">
      <ContentBox title="Add Category">
        <template #top>
          <AlertBox :message="messageAddCategory"/>
        </template>
        <VForm>
          <VFormItem>
            <VFormLabel label="Category Name" />
            <VFormInput v-model="paramsCategory.name" type="text" name="category" placeholder="Enter category name" />
          </VFormItem>
          <VFormItem>
            <VFormLabel label="Active Status" />
            <VFormRadio v-model="paramsCategory.isActive" name="is_active" radio-value="1" label="Yes" />
            <VFormRadio v-model="paramsCategory.isActive" name="is_active" radio-value="0" label="No" />
          </VFormItem>
          <VFormItem>
            <VMenu>
              <Button color="gray" @click="clickToShowAddCategory({ show: false })">
                <XMarkIcon class="w-5 h-5" />
                <label>Cancel</label>
              </Button>
              <Button @click="clickToAddCategory({type: 'parent_category'})" :disabled="loading.addCategory">
                <PlusIcon class="w-5 h-5" />
                <label>Add</label>
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
import type { ParamsCategoryType } from '@/cms/types/category';

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
import NTable from '@/cms/components/table/normal/NTable.vue'
import NTableHead from '@/cms/components/table/normal/NTableHead.vue'
import NTableRow from '@/cms/components/table/normal/NTableRow.vue'
import NTableHeadItem from '@/cms/components/table/normal/NTableHeadItem.vue'
import NTableBody from '@/cms/components/table/normal/NTableBody.vue'
import NTableData from '@/cms/components/table/normal/NTableData.vue'
import VFormRadio from '@/cms/components/form/vertical/VFormRadio.vue';

import { ref } from 'vue';
import { PlusIcon, MagnifyingGlassIcon, PencilIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/outline'

import { useCategory } from '@/cms/composables/useCategory';

const message = ref<MessageTypes>({} as MessageTypes)
const messageAddCategory = ref<MessageTypes>({} as MessageTypes)
const showSearchModal = ref<boolean>(false)
const isSearching = ref<boolean>(false)
const showAddCategory = ref<boolean>(false)
const paramsCategory = ref<ParamsCategoryType>({} as ParamsCategoryType)
const { addCategory, loading } = useCategory()

const clickToShowAddCategory = (params: { show: boolean, parent?: object }): void => {
  if (params.show) {
    paramsCategory.value = {} as ParamsCategoryType
  }
  showAddCategory.value = params.show
}

const clickToAddCategory = async (params: { type: typeof paramsCategory.value.categoryType }): Promise<void> => {
  paramsCategory.value.categoryType = params.type
  messageAddCategory.value = await addCategory(paramsCategory.value)
}

const clearSearch = (): void => {}
</script>