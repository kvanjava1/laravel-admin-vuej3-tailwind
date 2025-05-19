<template>
  <Dashboard title="Manage Roles" breadcrumb="User Management / Role">
    <ContentBox title="Roles List">
      <template #top>
        <AlertBox :message="message" />
      </template>
      <VMenu>
        <router-link :to="{ 'name': 'usermanagement.role.add' }">
          <Button>
            <PlusIcon class="w-5 h-5" />
            <label>Add</label>
          </Button>
        </router-link>
        <Button color="cyan" @click="showSearchModal = true">
          <MagnifyingGlassIcon class="w-5 h-5" />
          <label>Search</label>
        </Button>
        <Button color="gray" v-if="isSearching" @click="clearSearchAvailableRoles">
          <XMarkIcon class="w-5 h-5" />
          <label>Clear Search</label>
        </Button>
      </VMenu>
      <NTable>
        <NTableHead>
          <NTableRow>
            <NTableHeadItem>No</NTableHeadItem>
            <NTableHeadItem>Name</NTableHeadItem>
            <NTableHeadItem>Created At</NTableHeadItem>
            <NTableHeadItem>Update At</NTableHeadItem>
            <NTableHeadItem>Actions</NTableHeadItem>
          </NTableRow>
        </NTableHead>
        <NTableBody>
          <NTableRow v-for="(val, key) in availableRoles?.data">
            <NTableData>
              {{ (availableRoles?.per_page ?? 0) * (availableRoles?.current_page ?? 0) - (availableRoles?.per_page
                ??
                0) + key + 1 }}
            </NTableData>
            <NTableData>{{ val.name }}</NTableData>
            <NTableData>{{ val.created_at }}</NTableData>
            <NTableData>{{ val.updated_at }}</NTableData>
            <NTableData>
              <VMenu>
                <router-link :to="{ name: 'usermanagement.role.edit', params: { id: val.id } }"
                  v-if="val.name != 'superadmin'">
                  <Button color="blue">
                    <PencilIcon class="w-5 h-5" />
                    <label>Edit</label>
                  </Button>
                </router-link>
                <Button color="red" @click="clickToDeleteRole(val.id)" v-if="val.name != 'superadmin'">
                  <TrashIcon class="w-5 h-5" />
                  <label>Delete</label>
                </Button>
              </VMenu>
            </NTableData>
          </NTableRow>
        </NTableBody>
      </NTable>
      <Pagination :meta="availableRoles ?? { per_page: 0, current_page: 1 }" :method="getAvailableRoles" />
    </ContentBox>
    <Modal v-show="showSearchModal">
      <ContentBox title="Search Roles">
        <VForm @submit="searchAvailableRoles">
          <VFormItem @submit="searchAvailableRoles">
            <VFormLabel label="Roles Name" />
            <VFormInput v-model="paramSearchRole.name" type="text" name="role_name" placeholder="Enter role name" />
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
  </Dashboard>
</template>

<script setup lang="ts">
// system
import { onBeforeMount, ref } from 'vue'
import { PlusIcon, MagnifyingGlassIcon, PencilIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/outline'

// components
import ContentBox from '@/cms/components/ContentBox.vue'
import Dashboard from '@/cms/layouts/Dashboard.vue'
import Modal from '@/cms/components/Modal.vue'
import AlertBox from '@/cms/components/AlertBox.vue'
import Pagination from '@/cms/components/Pagination.vue'
import VMenu from '@/cms/components/VMenu.vue'
import Button from '@/cms/components/Button.vue'
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

// composable
import { useRole } from '@/cms/composables/useRole'

// types
import type { RoleType, ParamRoleSearchType } from '@/cms/types/role'
import type { PaginationType } from '@/cms/types/pagination'
import type { MessageTypes } from '@/cms/types/message'

const { getAllRole, deleteRole } = useRole()
const availableRoles = ref<PaginationType<RoleType>>({} as PaginationType<RoleType>)
const showSearchModal = ref<boolean>(false)
const isSearching = ref<boolean>(false)
const paramSearchRole = ref<ParamRoleSearchType>({} as ParamRoleSearchType)
const message = ref<MessageTypes>({} as MessageTypes)
const perpage: number = 5

onBeforeMount(async (): Promise<void> => {
  getAvailableRoles()
})

const getAvailableRoles = async (page: number = 1): Promise<void> => {
  paramSearchRole.value.page = page
  paramSearchRole.value.per_page = perpage
  paramSearchRole.value.paginate = true
  const response = await getAllRole(paramSearchRole.value)
  if (response.code == 'success') {
    availableRoles.value = response.data
  } else {
    message.value = response
  }
}

const searchAvailableRoles = async (): Promise<void> => {
  showSearchModal.value = false
  isSearching.value = true
  paramSearchRole.value.page = 1
  paramSearchRole.value.per_page = perpage
  paramSearchRole.value.paginate = true
  getAvailableRoles()
}

const clearSearchAvailableRoles = () => {
  paramSearchRole.value = {} as ParamRoleSearchType
  isSearching.value = false
  getAvailableRoles()
}

const clickToDeleteRole = async (id: number): Promise<void> => {
  const confirm = window.confirm('Are you sure to delete this role?')
  if (confirm) {
    message.value = await deleteRole(id)
    if (message.value.code == 'success') {
      getAvailableRoles(availableRoles.value?.current_page ?? 1)
    }
  }
}
</script>