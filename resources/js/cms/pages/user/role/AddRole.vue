<template>
  <Dashboard title="Manage Roles" breadcrumb="Add Role">
    <AlertBox :message="message" />
    <ContentBox title="Add New Role">
      <VForm @submit="clickToSaveNewRole">
        <VFormItem>
          <VFormLabel label="Role Name" />
          <VFormInput name="role_name" type="text" v-model="paramCreateRole.roleName"
            placeholder="Enter Name Ex: Editor" required />
        </VFormItem>
        <VFormItem>
          <VFormLabel label="Permission" />
          <div
            class="space-y-4 overflow-y-auto p-5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 transition duration-150 ease-in-out placeholder-gray-400">
            <div class="space-y-4" v-for="(perms, group) in groupedPermissions" :key="group">
              <VFormLabel :label="group as string" />
              <div
                class="space-y-4 p-4 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 transition duration-150 ease-in-out placeholder-gray-400">
                <label v-for="perm in perms" :key="perm.id" class="flex items-center">
                  <input v-model="paramCreateRole.selectedPermission" type="checkbox" :value="perm.name"
                    class="mr-2 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                  <VFormLabel :label="perm.name as string" />
                </label>
              </div>
            </div>
          </div>
        </VFormItem>
        <VFormItem>
          <VerticalMenu>
            <RouterLink :to="{ name: 'usermanagement.role.index' }">
              <Button color="gray">
                <XMarkIcon class="w-5 h-5" />
                <label>
                  Cancel
                </label>
              </Button>
            </RouterLink>
            <Button :disabled="loading.saveNewRole">
              <PlusIcon class="w-5 h-5" />
              <label>
                Save
              </label>
            </Button>
          </VerticalMenu>
        </VFormItem>
      </VForm>
    </ContentBox>
  </Dashboard>
</template>

<script setup lang="ts">
// system
import { ref, onBeforeMount } from 'vue'
import { XMarkIcon, PlusIcon } from '@heroicons/vue/24/outline';

// components
import ContentBox from '@/cms/components/ContentBox.vue'
import Dashboard from '@/cms/layouts/Dashboard.vue'
import AlertBox from '@/cms/components/AlertBox.vue'
import VForm from '@/cms/components/form/vertical/VForm.vue';
import VFormItem from '@/cms/components/form/vertical/VFormItem.vue';
import VFormInput from '@/cms/components/form/vertical/VFormInput.vue';
import VFormLabel from '@/cms/components/form/vertical/VFormLabel.vue';

// composable
import { useRole } from '@/cms/composables/useRole'

// types
import type { GroupedPermissionsType, ParamCreateRoleType } from '@/cms/types/role';
import type { MessageTypes } from '@/cms/types/message';
import VerticalMenu from '@/cms/components/VerticalMenu.vue';
import Button from '@/cms/components/Button.vue';

const message = ref<MessageTypes>({} as MessageTypes)
const { getAllPermission, saveNewRole, loading } = useRole()
const groupedPermissions = ref<GroupedPermissionsType>()
const paramCreateRole = ref<ParamCreateRoleType>({
  roleName: '',
  selectedPermission: []
})

const clickToSaveNewRole = async () => {
  message.value = await saveNewRole(paramCreateRole.value)

  if (message.value.code == 'success') {
    paramCreateRole.value = {} as ParamCreateRoleType
  }
}

const getAvailablePermission = async (): Promise<void> => {
  const response = await getAllPermission();

  if (response.code === 'success') {
    groupedPermissions.value = response.data;
  } else {
    console.error(response);
  }
}

onBeforeMount(async () => {
  getAvailablePermission()
});
</script>