<template>
  <Dashboard title="User" breadcrumb="Index">
    <ContentBox title="User Lists">
      <VerticalMenu>
        <router-link :to="{ 'name': 'usermanagement.user.add' }">
          <Button>
            <PlusIcon class="w-5 h-5" />
            Add
          </Button>
        </router-link>
      </VerticalMenu>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">No</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Email</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Created At</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Update At</th>
              <th class="px-6 py-3 text-left text-gray-500 tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(val, key) in availableUser.data">
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ key }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ val.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ val.email }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ val.created_at }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ val.updated_at }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">-</td>
            </tr>
          </tbody>
        </table>
      </div>
      <Pagination :meta="availableUser ?? { per_page: 0, current_page: 1 }" :method="getAvailableUser" />
    </ContentBox>
  </Dashboard>
</template>

<script setup lang="ts">
import { PlusIcon } from '@heroicons/vue/24/outline';
import { onBeforeMount, ref } from 'vue';

import Dashboard from '@/cms/layouts/Dashboard.vue';
import ContentBox from '@/cms/components/ContentBox.vue';
import VerticalMenu from '@/cms/components/VerticalMenu.vue';
import Button from '@/cms/components/Button.vue';
import Pagination from '@/cms/components/Pagination.vue'

import { useUser } from '@/cms/composables/useUser';

import type { AvailableUserType, ParamsSearchUserType } from '@/cms/types/user';
import type { MessageTypes } from '@/cms/types/message';

const { getUser } = useUser()
const paramsSearchUser = ref<ParamsSearchUserType>({} as ParamsSearchUserType)
const availableUser = ref<AvailableUserType>({} as AvailableUserType)
const message = ref<MessageTypes>({} as MessageTypes)

const getAvailableUser = async (page: number = 1) => {
  paramsSearchUser.value.page = page
  paramsSearchUser.value.perPage = 2
  paramsSearchUser.value.paginate = true
  const responseGetUser = await getUser(paramsSearchUser.value)
  if (responseGetUser.code == 'success') {
    availableUser.value = responseGetUser.data
  } else {
    message.value = responseGetUser
  }
}

onBeforeMount(() => {
  getAvailableUser()
})
</script>