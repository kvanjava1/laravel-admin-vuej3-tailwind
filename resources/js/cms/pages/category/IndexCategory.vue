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
          <template v-for="val in availableCategory" :key="val.id">
            <NestedCategoryRow :category-item="val" :shift-level="0"
              @clickToShowAddCategory="clickToShowAddCategory"
              @clickToShowEditCategory="clickToShowEditCategory"
              @clickToDeleteCategory="clickToDeleteCategory" />
          </template>
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
          <AlertBox :message="messageAddCategory" />
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
              <Button @click="clickToAddCategory()" :disabled="loading.addCategory">
                <PlusIcon class="w-5 h-5" />
                <label>Add</label>
              </Button>
            </VMenu>
          </VFormItem>
        </VForm>
      </ContentBox>
    </Modal>
    <Modal v-show="showEditCategory">
      <ContentBox title="Edit Category">
        <template #top>
          <AlertBox :message="messageEditCategory" />
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
              <Button color="gray" @click="clickToShowEditCategory({ show: false })">
                <XMarkIcon class="w-5 h-5" />
                <label>Cancel</label>
              </Button>
              <Button color="blue" @click="clickToEditCategory" :disabled="loading.addCategory">
                <PencilIcon class="w-5 h-5" />
                <label>Edit</label>
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
import type { CategoryType, ParamsCategoryType, ParamsSearchCategoryType } from '@/cms/types/category.d';

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
import VFormRadio from '@/cms/components/form/vertical/VFormRadio.vue';
import NestedCategoryRow from '@/cms/components/table/normal/NestedCategoryRow.vue';

import { onBeforeMount, ref } from 'vue';
import { PlusIcon, PencilIcon, MagnifyingGlassIcon, XMarkIcon } from '@heroicons/vue/24/outline'

import { useCategory } from '@/cms/composables/useCategory';

const message = ref<MessageTypes>({} as MessageTypes)
const messageAddCategory = ref<MessageTypes>({} as MessageTypes)
const messageEditCategory = ref<MessageTypes>({} as MessageTypes)
const showSearchModal = ref<boolean>(false)
const isSearching = ref<boolean>(false)
const showAddCategory = ref<boolean>(false)
const showEditCategory = ref<boolean>(false)
const paramsCategory = ref<ParamsCategoryType>({} as ParamsCategoryType)
const paramsSearchCategory = ref<ParamsSearchCategoryType>({} as ParamsSearchCategoryType)
const availableCategory = ref<CategoryType[]>([] as CategoryType[])
const categoryIdIsBeingEdited = ref<number>(0);

const { addCategory, getAllCategory, loading, updateCategory, deleteCategory } = useCategory()

const clickToShowAddCategory = (params: { show: boolean, parent?: CategoryType }): void => {
  if (params.show) {
    paramsCategory.value = {} as ParamsCategoryType
  }
  if (params.parent) {
    paramsCategory.value.parentId = params.parent.id
  }
  messageAddCategory.value = {} as MessageTypes
  showAddCategory.value = params.show
}

const clickToShowEditCategory = (params: { show: boolean, data?: CategoryType }): void => {
  if (params.show) {
    paramsCategory.value = {} as ParamsCategoryType
  }
  if (params.data) {
    paramsCategory.value.name = params.data.name
    paramsCategory.value.parentId = params.data.parent_id as typeof paramsCategory.value.parentId
    paramsCategory.value.isActive = params.data.is_active ? 1 : 0
    categoryIdIsBeingEdited.value = params.data.id
  }
  messageEditCategory.value = {} as MessageTypes
  showEditCategory.value = params.show
}

const clickToAddCategory = async (): Promise<void> => {
  messageAddCategory.value = await addCategory(paramsCategory.value)
  if (messageAddCategory.value.code == 'success') {
    await searchAllCategory()
  }
}

const clickToEditCategory = async (): Promise<void> => {
  messageEditCategory.value = await updateCategory(categoryIdIsBeingEdited.value, paramsCategory.value)
  if (messageEditCategory.value.code == 'success') {
    await searchAllCategory()
  }
}

const clickToDeleteCategory = async (params: { data?: CategoryType }): Promise<void> => {
  const confirm = window.confirm(`Are you sure you want to delete ${params.data?.name} in the category?`)
  if (confirm) {
    message.value = await deleteCategory(params.data?.id as number)
    if (message.value.code == 'success') {
      await searchAllCategory()
    }
  }
}

const clearSearch = (): void => {
}

const searchAllCategory = async (page: number = 1): Promise<void> => {
  paramsSearchCategory.value.page = page
  const response = await getAllCategory(paramsSearchCategory.value)
  if (response.code == 'success') {
    availableCategory.value = response.data
  } else {
    message.value = response
  }
}

onBeforeMount(async () => {
  await searchAllCategory()
})

</script>