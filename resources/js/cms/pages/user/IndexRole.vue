<template>
  <Dashboard title="Manage Roles" breadcrumb="Index">
    <AlertBox :message="message" />
    <ContentBox title="Roles List">
      <VerticalMenu>
        <router-link :to="{ 'name': 'usermanagement.role.add' }">
          <Button>
            <PlusIcon class="w-5 h-5" />
            <label>Add</label>
          </Button>
        </router-link>
        <Button @click="showSearchModal = true">
          <MagnifyingGlassIcon class="w-5 h-5" />
          <label>Search</label>
        </Button>
        <Button color="red" v-if="isSearching" @click="clearSearchAvailableRoles">
          <XMarkIcon class="w-5 h-5" />
          <label>Clear Search</label>
        </Button>
      </VerticalMenu>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-grey-200">
          <thead class="bg-black-200">
            <tr>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">No</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Created At</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Update At</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(val, key) in availableRoles?.data">
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                {{ (availableRoles?.per_page ?? 0) * (availableRoles?.current_page ?? 0) - (availableRoles?.per_page ??
                  0) + key + 1 }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ val.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ val.created_at }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ val.updated_at }}</td>
              <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                <router-link :to="{ name: 'usermanagement.role.edit', params: { id: val.id } }"
                  v-if="val.name != 'superadmin'">
                  <Button>
                    <PencilIcon class="w-5 h-5" />
                    <label>Edit</label>
                  </Button>
                </router-link>
                <Button color="red" @click="clickToDeleteRole(val.id)" v-if="val.name != 'superadmin'">
                  <TrashIcon class="w-5 h-5" />
                  <label>Delete</label>
                </Button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <Pagination :meta="availableRoles ?? { per_page: 0, current_page: 1 }" :method="getAvailableRoles" />
    </ContentBox>
    
    <!-- search modal -->
    <Modal v-show="showSearchModal">
      <ContentBox title="Search Form">
        <div>
          <label for="search" class="block text-gray-700">Search Roles</label>
          <input type="text" id="search" v-model="paramSearchRole.name"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            placeholder="Enter role name..." />
        </div>
        <VerticalMenu>
          <Button color="red" @click.prevent="showSearchModal = false">
            <XMarkIcon class="w-5 h-5" />
            <label>Cancel</label>
          </Button>
          <Button @click.prevent="searchAvailableRoles">
            <MagnifyingGlassIcon class="w-5 h-5" />
            <label>Search</label>
          </Button>
        </VerticalMenu>
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

// composable
import { useRole } from '@/cms/composables/useRole'

// types
import type { AvailableRolesType, ParamRoleSearchType } from '@/cms/types/role'
import type { MessageTypes } from '@/cms/types/message'
import Button from '@/cms/components/Button.vue'

const { getAllRole, deleteRole } = useRole()
const availableRoles = ref<AvailableRolesType>()
const showSearchModal = ref<boolean>(false)
const isSearching = ref<boolean>(false)
const paramSearchRole = ref<ParamRoleSearchType>({} as ParamRoleSearchType)
const message = ref<MessageTypes>({} as MessageTypes)
const perpage: number = 5

onBeforeMount(async (): Promise<void> => {
  getAvailableRoles(1)
})

const getAvailableRoles = async (page: number): Promise<void> => {
  paramSearchRole.value.page = page
  paramSearchRole.value.per_page = perpage
  paramSearchRole.value.paginate = true
  const response = await getAllRole(paramSearchRole.value)
  availableRoles.value = response.data
}

const searchAvailableRoles = async (): Promise<void> => {
  showSearchModal.value = false
  isSearching.value = true
  paramSearchRole.value.page = 1
  paramSearchRole.value.per_page = perpage
  paramSearchRole.value.paginate = true
  const response = await getAllRole(paramSearchRole.value)
  availableRoles.value = response.data
}

const clearSearchAvailableRoles = () => {
  paramSearchRole.value = {} as ParamRoleSearchType
  isSearching.value = false
  getAvailableRoles(1)
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