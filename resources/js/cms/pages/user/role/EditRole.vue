<template>
  <Dashboard title="Manage Roles" breadcrumb="User Management / Role / Edit">
    <AlertBox :message="message" />
    <ContentBox title="Edit Role">
      <VForm @submit="clickToUpdateRole">
        <VFormItem>
          <VFormLabel label="Role Name" />
          <VFormInput name="role_name" type="text" v-model="editRoleParam.roleName" placeholder="Enter Name Ex: Editor"/>
        </VFormItem>
        <VFormItem>
          <VFormLabel label="Permission" />
          <div class="space-y-4 overflow-y-auto p-5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 transition duration-150 ease-in-out placeholder-gray-400">
            <div class="space-y-4" v-for="(perms, group) in groupedPermissions" :key="group">
              <VFormLabel :label="group as string" />
              <div class="space-y-4 p-4 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 transition duration-150 ease-in-out placeholder-gray-400">
                <label v-for="perm in perms" :key="perm.id" class="flex items-center">
                  <input v-model="editRoleParam.selectedPermission" type="checkbox" :value="perm.name"
                    class="mr-2 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                  <VFormLabel :label="perm.name as string" />
                </label>
              </div>
            </div>
          </div>
        </VFormItem>
        <VFormItem>
          <VMenu>
            <RouterLink :to="{ name: 'usermanagement.role.index' }">
              <Button color="gray">
                <XMarkIcon class="w-5 h-5" />
                <label>
                  Cancel
                </label>
              </Button>
            </RouterLink>
            <Button :disabled="loading.saveNewRole">
              <PencilIcon class="w-5 h-5" />
              <label>
                Save
              </label>
            </Button>
          </VMenu>
        </VFormItem>
      </VForm>
    </ContentBox>
  </Dashboard>
</template>

<script setup lang="ts">
import { ref, onBeforeMount } from 'vue'
import { useRouter } from 'vue-router'; // Import router
import { XMarkIcon, PencilIcon } from '@heroicons/vue/24/outline';

import ContentBox from '@/cms/components/ContentBox.vue'
import Dashboard from '@/cms/layouts/Dashboard.vue'
import AlertBox from '@/cms/components/AlertBox.vue'
import VMenu from '@/cms/components/VMenu.vue';
import Button from '@/cms/components/Button.vue';
import VForm from '@/cms/components/form/vertical/VForm.vue';
import VFormItem from '@/cms/components/form/vertical/VFormItem.vue';
import VFormInput from '@/cms/components/form/vertical/VFormInput.vue';
import VFormLabel from '@/cms/components/form/vertical/VFormLabel.vue';

import { useRole } from '@/cms/composables/useRole'

import type { GroupedPermissionsType, ParamCreateRoleType } from '@/cms/types/role';
import type { MessageTypes } from '@/cms/types/message';

const message = ref<MessageTypes>({} as MessageTypes)
const { getRoleDetail, getAllPermission, updateRole, loading } = useRole()
const groupedPermissions = ref<GroupedPermissionsType>()
const editRoleParam = ref<ParamCreateRoleType>({
  roleName: '',
  selectedPermission: []
})

const route = useRouter().currentRoute;
const roleIdIsBeingEdited = ref<number>(Number(route.value.params.id));

const getAvailablePermission = async (): Promise<void> => {
  const response = await getAllPermission();

  if (response.code === 'success') {
    groupedPermissions.value = response.data;
  } else {
    console.error(response);
  }
}

const getRoleDetailIsBeingEdited = async (): Promise<void> => {
  const responseDetailRole = await getRoleDetail(roleIdIsBeingEdited.value)
  if (responseDetailRole.code === 'success') {
    editRoleParam.value.roleName = responseDetailRole.data.name

    Object.entries(responseDetailRole.data.permissions as { name: string }[]).forEach(([_, val]) => {
      editRoleParam.value.selectedPermission.push(val.name)
    });

  } else {
    console.error(responseDetailRole)
  }
}

onBeforeMount(async () => {
  getAvailablePermission()
  getRoleDetailIsBeingEdited()
});

const clickToUpdateRole = async (): Promise<void> => {
  message.value = await updateRole(roleIdIsBeingEdited.value, editRoleParam.value)
}
</script>