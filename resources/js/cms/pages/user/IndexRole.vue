<template>
  <Dashboard title="Manage Roles" breadcrumb="User Management / Role">
    <AlertBox :message="message" />
    <ContentBox title="Roles List">
      <VerticalMenu>
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
      </VerticalMenu>
      <Table>
        <TableHead>
          <TableRow>
            <TableHeadItem>No</TableHeadItem>
            <TableHeadItem>Name</TableHeadItem>
            <TableHeadItem>Created At</TableHeadItem>
            <TableHeadItem>Update At</TableHeadItem>
            <TableHeadItem>Actions</TableHeadItem>
          </TableRow>
        </TableHead>
        <TableBody>
          <TableRow v-for="(val, key) in availableRoles?.data">
            <TableData>
              {{ (availableRoles?.per_page ?? 0) * (availableRoles?.current_page ?? 0) - (availableRoles?.per_page
                ??
                0) + key + 1 }}
            </TableData>
            <TableData>{{ val.name }}</TableData>
            <TableData>{{ val.created_at }}</TableData>
            <TableData>{{ val.updated_at }}</TableData>
            <TableData>
              <VerticalMenu>
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
              </VerticalMenu>
            </TableData>
          </TableRow>
        </TableBody>
      </Table>
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
// system
import { onBeforeMount, ref } from 'vue'
import { PlusIcon, MagnifyingGlassIcon, PencilIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/outline'

// components
import ContentBox from '@/cms/components/ContentBox.vue'
import Dashboard from '@/cms/layouts/Dashboard.vue'
import Modal from '@/cms/components/Modal.vue'
import AlertBox from '@/cms/components/AlertBox.vue'
import Pagination from '@/cms/components/Pagination.vue'
import VerticalMenu from '@/cms/components/VerticalMenu.vue'
import Button from '@/cms/components/Button.vue'
import VForm from '@/cms/components/form/vertical/VForm.vue'
import VFormItem from '@/cms/components/form/vertical/VFormItem.vue'
import VFormLabel from '@/cms/components/form/vertical/VFormLabel.vue'
import VFormInput from '@/cms/components/form/vertical/VFormInput.vue'
import Table from '@/cms/components/table/normal/Table.vue'
import TableHead from '@/cms/components/table/normal/TableHead.vue'
import TableRow from '@/cms/components/table/normal/TableRow.vue'
import TableHeadItem from '@/cms/components/table/normal/TableHeadItem.vue'
import TableBody from '@/cms/components/table/normal/TableBody.vue'
import TableData from '@/cms/components/table/normal/TableData.vue'

// composable
import { useRole } from '@/cms/composables/useRole'

// types
import type { AvailableRolesType, ParamRoleSearchType } from '@/cms/types/role'
import type { MessageTypes } from '@/cms/types/message'

const { getAllRole, deleteRole } = useRole()
const availableRoles = ref<AvailableRolesType>({} as AvailableRolesType)
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