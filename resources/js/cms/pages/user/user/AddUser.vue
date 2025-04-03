<template>
    <Dashboard title="Manage Roles" breadcrumb="Add User">
        <ContentBox title="Add User">
            <VForm>
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
                    <VFormInput v-model="paramsUser.password" type="password" name="password" placeholder="Your Password" />
                </VFormItem>
                <VFormItem>
                    <VFormLabel label="Password Confirmation" />
                    <VFormInput v-model="paramsUser.passwordConfirmation" type="password" name="password_confirmation" placeholder="Re-Type Your Password" />
                </VFormItem>
                <VFormItem>
                    <VFormLabel label="Select Role" />
                    <VFormSelect v-model="paramsUser.roleId" name="select_role" default-options-label="Select Role Name">
                        <option v-for="role in roleAvailable" :value="role.id">{{ role.name }}</option>
                    </VFormSelect>
                </VFormItem>
                <div class="space-y-2">
                    <VFormLabel label="Active Status" />
                    <VFormRadio v-model="paramsUser.activeStatus" name="is_active" radio-value="1" label="Yes"/>
                    <VFormRadio v-model="paramsUser.activeStatus" name="is_active" radio-value="0" label="No"/>
                </div>
                <VerticalMenu>
                    <router-link to="">
                        <Button color="gray">
                            <XMarkIcon class="w-5 h-5" />
                            <label>Cancel</label>
                        </Button>
                    </router-link>
                    <Button>
                        <PlusIcon class="w-5 h-5" />
                        <label>Save</label>
                    </Button>
                </VerticalMenu>
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

import { useRole } from '@/cms/composables/useRole';
import type { ParamRoleSearchType, RoleType } from '@/cms/types/role';

const paramsUser = ref<ParamsUserType>({} as ParamsUserType)
const { getAllRole } = useRole()
const roleAvailable = ref<RoleType[]>()

onBeforeMount(async () => {
    const paramsSearchRole: ParamRoleSearchType = { paginate: false } as ParamRoleSearchType
    const responseSearchRole = await getAllRole(paramsSearchRole)

    if (responseSearchRole.code == 'success'){
        roleAvailable.value = responseSearchRole.data
    }
    
})
</script>