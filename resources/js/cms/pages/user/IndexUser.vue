<template>
  <Dashboard title="Manage Users" breadcrumb="Index">
    <AlertBox :message="message" />
    <ContentBox title="User Lists">
      <VerticalMenu>
        <router-link :to="{ 'name': 'usermanagement.user.add' }">
          <Button>
            <PlusIcon class="w-5 h-5" />
            Add
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
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">No</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Email</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Active</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Role</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Created At</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Update At</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(val, key) in availableUser.data">
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{
                (availableUser?.per_page ?? 0) * (availableUser?.current_page ?? 0) - (availableUser?.per_page ?? 0) +
                key + 1
              }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ val.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ val.email }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                <span v-if="val.is_active" class="px-2 inline-flex font-semibold rounded-full bg-green-100 text-green-800 capitalize">active</span>
                <span v-else class="px-2 inline-flex font-semibold rounded-full bg-red-100 text-red-800 capitalize">inactive</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ val.roles[0].name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ val.created_at }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ val.updated_at }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                <VerticalMenu>
                  <router-link :to="{ name: 'usermanagement.user.edit', params: { id: val.id } }"
                    v-if="val.roles[0].name != 'superadmin'">
                    <Button>
                      <PencilIcon class="w-5 h-5" />
                      <label>Edit</label>
                    </Button>
                  </router-link>
                  <Button color="red" @click="clickToDelete(val.id)" v-if="val.roles[0].name != 'superadmin'">
                    <TrashIcon class="w-5 h-5" />
                    <label>Delete</label>
                  </Button>
                </VerticalMenu>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <Pagination :meta="availableUser ?? { per_page: 0, current_page: 1 }" :method="getAvailableUser" />
    </ContentBox>
    <!-- search modal -->
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
              <option class="capitalize" v-for="role in searchRole?.data" :value="role.id">{{ role.name }}
              </option>
            </VFormSelect>
          </VFormItem>
          <VFormItem>
            <VFormLabel label="Active Status" />
            <VFormRadio v-model="paramsSearchUser.activeStatus" name="is_active" radio-value="1" label="Yes" />
            <VFormRadio v-model="paramsSearchUser.activeStatus" name="is_active" radio-value="0" label="No" />
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
import { PlusIcon, MagnifyingGlassIcon, PencilIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { onBeforeMount, ref } from 'vue';

import Dashboard from '@/cms/layouts/Dashboard.vue';
import ContentBox from '@/cms/components/ContentBox.vue';

import Pagination from '@/cms/components/Pagination.vue'
import Modal from '@/cms/components/Modal.vue';
import VerticalMenu from '@/cms/components/VerticalMenu.vue'
import Button from '@/cms/components/Button.vue'
import VForm from '@/cms/components/form/vertical/VForm.vue'
import VFormItem from '@/cms/components/form/vertical/VFormItem.vue'
import VFormLabel from '@/cms/components/form/vertical/VFormLabel.vue'
import VFormInput from '@/cms/components/form/vertical/VFormInput.vue'
import VFormSelect from '@/cms/components/form/vertical/VFormSelect.vue';
import VFormRadio from '@/cms/components/form/vertical/VFormRadio.vue';

import { useUser } from '@/cms/composables/useUser';
import { useRole } from '@/cms/composables/useRole';

import type { AvailableUserType, ParamsSearchUserType } from '@/cms/types/user';
import type { MessageTypes } from '@/cms/types/message';
import type { ParamRoleSearchType } from '@/cms/types/role';
import AlertBox from '@/cms/components/AlertBox.vue';

const { getUser, deleteUser } = useUser()
const { getAllRole } = useRole()

const paramsSearchUser = ref<ParamsSearchUserType>({} as ParamsSearchUserType)
const availableUser = ref<AvailableUserType>({} as AvailableUserType)
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