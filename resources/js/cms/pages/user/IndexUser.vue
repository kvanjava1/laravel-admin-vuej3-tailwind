<template>
  <Dashboard title="Manage Users" breadcrumb="User Management / User">
    <ContentBox title="User Lists">
      <template #top>
        <AlertBox :message="message" />
      </template>
      <VMenu>
        <router-link :to="{ 'name': 'usermanagement.user.add' }">
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
            <NTableHeadItem>Email</NTableHeadItem>
            <NTableHeadItem>Active</NTableHeadItem>
            <NTableHeadItem>Role</NTableHeadItem>
            <NTableHeadItem>Created At</NTableHeadItem>
            <NTableHeadItem>Update At</NTableHeadItem>
            <NTableHeadItem>Actions</NTableHeadItem>
          </NTableRow>
        </NTableHead>
        <NTableBody>
          <NTableRow v-for="(val, key) in availableUser.data">
            <NTableData>{{
              (availableUser?.per_page ?? 0) * (availableUser?.current_page ?? 0) - (availableUser?.per_page ?? 0) +
              key + 1
            }}</NTableData>
            <NTableData>{{ val.name }}</NTableData>
            <NTableData>{{ val.email }}</NTableData>
            <NTableData>
              <span v-if="val.is_active"
                class="px-2 inline-flex font-semibold rounded-full bg-green-100 text-green-800 capitalize">active</span>
              <span v-else
                class="px-2 inline-flex font-semibold rounded-full bg-red-100 text-red-800 capitalize">inactive</span>
            </NTableData>
            <NTableData>{{ val.roles[0].name }}</NTableData>
            <NTableData>{{ val.created_at }}</NTableData>
            <NTableData>{{ val.updated_at }}</NTableData>
            <NTableData>
              <VMenu>
                <router-link :to="{ name: 'usermanagement.user.edit', params: { id: val.id } }"
                  v-if="val.roles[0].name != 'superadmin'">
                  <Button color="blue">
                    <PencilIcon class="w-5 h-5" />
                    <label>Edit</label>
                  </Button>
                </router-link>
                <Button color="red" @click="clickToDelete(val.id)" v-if="val.roles[0].name != 'superadmin'">
                  <TrashIcon class="w-5 h-5" />
                  <label>Delete</label>
                </Button>
              </VMenu>
            </NTableData>
          </NTableRow>
        </NTableBody>
      </NTable>
      <Pagination :meta="availableUser ?? { per_page: 0, current_page: 1 }" :method="getAvailableUser" />
    </ContentBox>
    <Modal v-show="showSearchModal">
      <ContentBox title="Search Roles">
        <VForm @submit="searchAvailableUser">
          <VFormItem>
            <VFormLabel label="Name" />
            <VFormInput v-model="paramsSearchUser.name" type="text" name="name" placeholder="e.g., Swansa" />
          </VFormItem>
          <VFormItem>
            <VFormLabel label="Email" />
            <VFormInput v-model="paramsSearchUser.email" type="text" name="email" placeholder="e.g., hallo@gmail.com" />
          </VFormItem>
          <VFormItem>
            <VFormLabel label="Password" />
            <VFormInput v-model="paramsSearchUser.password" type="password" name="password"
              placeholder="Your Password" />
          </VFormItem>
          <VFormItem>
            <VFormLabel label="Select Role" />
            <VFormSelect v-model="paramsSearchUser.roleId" name="select_role" default-options-label="Select Role Name">
              <option class="capitalize" v-for="role in searchRole?.data" :value="role.id">
                {{ role.name }}
              </option>
            </VFormSelect>
          </VFormItem>
          <VFormItem>
            <VFormLabel label="Active Status" />
            <VFormRadio v-model="paramsSearchUser.activeStatus" name="is_active" radio-value="1" label="Yes" />
            <VFormRadio v-model="paramsSearchUser.activeStatus" name="is_active" radio-value="0" label="No" />
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
import { PlusIcon, MagnifyingGlassIcon, PencilIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { onBeforeMount, ref } from 'vue'

import Dashboard from '@/cms/layouts/Dashboard.vue'
import ContentBox from '@/cms/components/ContentBox.vue'

import Pagination from '@/cms/components/Pagination.vue'
import Modal from '@/cms/components/Modal.vue'
import VMenu from '@/cms/components/VMenu.vue'
import Button from '@/cms/components/Button.vue'
import VForm from '@/cms/components/form/vertical/VForm.vue'
import VFormItem from '@/cms/components/form/vertical/VFormItem.vue'
import VFormLabel from '@/cms/components/form/vertical/VFormLabel.vue'
import VFormInput from '@/cms/components/form/vertical/VFormInput.vue'
import VFormSelect from '@/cms/components/form/vertical/VFormSelect.vue'
import VFormRadio from '@/cms/components/form/vertical/VFormRadio.vue'
import AlertBox from '@/cms/components/AlertBox.vue'
import NTable from '@/cms/components/table/normal/NTable.vue'
import NTableHead from '@/cms/components/table/normal/NTableHead.vue'
import NTableRow from '@/cms/components/table/normal/NTableRow.vue'
import NTableHeadItem from '@/cms/components/table/normal/NTableHeadItem.vue'
import NTableBody from '@/cms/components/table/normal/NTableBody.vue'
import NTableData from '@/cms/components/table/normal/NTableData.vue'

import { useUser } from '@/cms/composables/useUser'
import { useRole } from '@/cms/composables/useRole'

import type { UserType, ParamsSearchUserType } from '@/cms/types/user'
import type { MessageTypes } from '@/cms/types/message'
import type { ParamRoleSearchType } from '@/cms/types/role'
import type { PaginationType } from '@/cms/types/pagination'

const { getUser, deleteUser } = useUser()
const { getAllRole } = useRole()

const paramsSearchUser = ref<ParamsSearchUserType>({} as ParamsSearchUserType)
const availableUser = ref<PaginationType<UserType>>({} as PaginationType<UserType>)
const message = ref<MessageTypes>({} as MessageTypes)
const showSearchModal = ref<boolean>(false)
const isSearching = ref<boolean>(false)
const searchRole = ref<MessageTypes>()
const perPage: number = 5

const getAvailableUser = async (page: number = 1) => {
  paramsSearchUser.value.page = page
  paramsSearchUser.value.perPage = perPage
  paramsSearchUser.value.paginate = true
  const responseGetUser = await getUser(paramsSearchUser.value)
  if (responseGetUser.code == 'success') {
    availableUser.value = responseGetUser.data
  } else {
    message.value = responseGetUser
  }
}

const searchAvailableRoles = async (): Promise<void> => {
  const paramsSearchRole: ParamRoleSearchType = { paginate: false } as ParamRoleSearchType
  searchRole.value = await getAllRole(paramsSearchRole)
  if (searchRole.value.code !== 'success') {
    message.value = searchRole.value
  }
}

const searchAvailableUser = async (): Promise<void> => {
  showSearchModal.value = false
  isSearching.value = true
  paramsSearchUser.value.page = 1
  paramsSearchUser.value.perPage = perPage
  paramsSearchUser.value.paginate = true
  getAvailableUser()
}

const clearSearchAvailableRoles = () => {
  paramsSearchUser.value = {} as ParamsSearchUserType
  isSearching.value = false
  getAvailableUser()
}

const clickToDelete = async (userIdIsBeingDeleted: number): Promise<void> => {
  const confirm = window.confirm('Are you sure to delete this role?')
  if (confirm) {
    const response = await deleteUser(userIdIsBeingDeleted)
    message.value = response
    if (response.code == 'success') {
      getAvailableUser(availableUser.value.current_page)
    }
  }
}

onBeforeMount(() => {
  getAvailableUser()
  searchAvailableRoles()
})
</script>