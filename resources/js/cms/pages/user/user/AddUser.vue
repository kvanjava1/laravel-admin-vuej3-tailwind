<template>
    <Dashboard title="Manage Users" breadcrumb="Add User">
        <AlertBox :message="message" />
        <ContentBox title="Add User">
            <VForm @submit="clickToSaveAddUser">
                <VFormItem>
                    <VFormLabel label="Name" />
                    <VFormInput v-model="paramsUser.name" type="text" name="name" placeholder="e.g., Swansa" />
                </VFormItem>
                <VFormItem>
                    <VFormLabel label="Email" />
                    <VFormInput v-model="paramsUser.email" type="email" name="email" placeholder="e.g., hallo@gmail.com" />
                </VFormItem>
                <VFormItem>
                    <VFormLabel label="Password" />
                    <VFormInput v-model="paramsUser.password" type="password" name="password"
                        placeholder="Your Password" />
                </VFormItem>
                <VFormItem>
                    <VFormLabel label="Password Confirmation" />
                    <VFormInput v-model="paramsUser.passwordConfirmation" type="password" name="password_confirmation"
                        placeholder="Re-Type Your Password"/>
                </VFormItem>
                <VFormItem>
                    <VFormLabel label="Select Role" />
                    <VFormSelect v-model="paramsUser.roleId" name="select_role"
                        default-options-label="Select Role Name">
                        <option class="capitalize" v-for="role in searchRole?.data" :value="role.id">{{ role.name }}
                        </option>
                    </VFormSelect>
                </VFormItem>
                <VFormItem>
                    <VFormLabel label="Active Status" />
                    <VFormRadio v-model="paramsUser.activeStatus" name="is_active" radio-value="1" label="Yes" />
                    <VFormRadio v-model="paramsUser.activeStatus" name="is_active" radio-value="0" label="No" />
                </VFormItem>
                <VFormItem>
                    <VerticalMenu>
                        <router-link :to="{ name: 'usermanagement.user.index' }">
                            <Button color="gray">
                                <XMarkIcon class="w-5 h-5" />
                                <label>Cancel</label>
                            </Button>
                        </router-link>
                        <Button :disabled="loading.addUser">
                            <PlusIcon class="w-5 h-5" />
                            <label>Save</label>
                        </Button>
                    </VerticalMenu>
                </VFormItem>
            </VForm>
        </ContentBox>
    </Dashboard>
</template>

<script setup lang="ts">
// system
import { XMarkIcon, PlusIcon } from '@heroicons/vue/24/outline';
import { onBeforeMount, ref } from 'vue';

// components
import ContentBox from '@/cms/components/ContentBox.vue'
import Dashboard from '@/cms/layouts/Dashboard.vue'
import AlertBox from '@/cms/components/AlertBox.vue'
import VerticalMenu from '@/cms/components/VerticalMenu.vue';
import Button from '@/cms/components/Button.vue';
import VForm from '@/cms/components/form/vertical/VForm.vue';
import VFormItem from '@/cms/components/form/vertical/VFormItem.vue';
import VFormLabel from '@/cms/components/form/vertical/VFormLabel.vue';
import VFormInput from '@/cms/components/form/vertical/VFormInput.vue';
import VFormSelect from '@/cms/components/form/vertical/VFormSelect.vue';
import VFormRadio from '@/cms/components/form/vertical/VFormRadio.vue';

import type { ParamsUserType } from '@/cms/types/user';
import type { ParamRoleSearchType } from '@/cms/types/role';
import type { MessageTypes } from '@/cms/types/message';

import { useRole } from '@/cms/composables/useRole';
import { useUser } from '@/cms/composables/useUser';

const paramsUser = ref<ParamsUserType>({} as ParamsUserType)
const { getAllRole } = useRole()
const { addUser, loading } = useUser()
const message = ref<MessageTypes>({} as MessageTypes)
const searchRole = ref<MessageTypes>()

const clickToSaveAddUser = async () => {
    const responseAddUser = await addUser(paramsUser.value)
    message.value = responseAddUser
}

const searchAvailableRoles = async (): Promise<void> => {
    searchRole.value = await getAllRole({ paginate: false } as ParamRoleSearchType)

    if (searchRole.value.code !== 'success') {
        message.value = searchRole.value
    }
}

onBeforeMount(() => {
    searchAvailableRoles()
})
</script>