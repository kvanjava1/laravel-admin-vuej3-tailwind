<template>
    <Dashboard title="My Profile" breadcrumb="My Account / Profile">
        <AlertBox :message="message" />
        <ContentBox title="Profile Information">
            <VForm @submit="clickToUpdateProfile">
                <VFormItem>
                    <VFormLabel label="Name" />
                    <VFormInput v-model="paramsProfile.name" type="text" name="name" placeholder="Your full name" required />
                </VFormItem>
                <VFormItem>
                    <VFormLabel label="Email" />
                    <VFormInput v-model="paramsProfile.email" type="email" name="email" placeholder="your.email@example.com" disabled />
                </VFormItem>
                <VFormItem>
                    <VFormLabel label="Old Password" />
                    <VFormInput v-model="paramsProfile.oldPassword" type="password" name="current_password"
                        placeholder="Enter current password to make changes" />
                </VFormItem>
                <VFormItem>
                    <VFormLabel label="New Password" />
                    <VFormInput v-model="paramsProfile.password" type="password" name="new_password" placeholder="Leave blank to keep current" />
                </VFormItem>
                <VFormItem>
                    <VFormLabel label="Confirm New Password" />
                    <VFormInput v-model="paramsProfile.passwordConfirmation"  type="password" name="new_password_confirmation" placeholder="Re-type new password" />
                </VFormItem>
                <VFormItem>
                    <VerticalMenu>
                        <Button type="submit" :disabled="loading.updateProfile">
                            <PencilIcon class="w-5 h-5" />
                            Update
                        </Button>
                    </VerticalMenu>
                </VFormItem>
            </VForm>
        </ContentBox>
    </Dashboard>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { PencilIcon } from '@heroicons/vue/24/outline'

import { useProfile } from '@/cms/composables/useProfile'

import ContentBox from '@/cms/components/ContentBox.vue'
import Dashboard from '@/cms/layouts/Dashboard.vue'
import AlertBox from '@/cms/components/AlertBox.vue'
import VerticalMenu from '@/cms/components/VerticalMenu.vue';
import Button from '@/cms/components/Button.vue';
import VForm from '@/cms/components/form/vertical/VForm.vue';
import VFormItem from '@/cms/components/form/vertical/VFormItem.vue';
import VFormLabel from '@/cms/components/form/vertical/VFormLabel.vue';
import VFormInput from '@/cms/components/form/vertical/VFormInput.vue';

import type { MessageTypes } from '@/cms/types/message'
import type { ProfileDetailType, ParamsProfileType } from '@/cms/types/profile'

const { loading, getProfileDetail, updateProfile } = useProfile()
const message = ref<MessageTypes>({} as MessageTypes)
const paramsProfile = ref<ParamsProfileType>({} as ParamsProfileType) 
const getCurrentUserDetail = async (): Promise<void> => {
    const responseGetProfileDetail = await getProfileDetail()
    if(responseGetProfileDetail.code == 'success'){
        const profileDetail = responseGetProfileDetail.data as ProfileDetailType
        paramsProfile.value.name = profileDetail.name
        paramsProfile.value.email = profileDetail.email
    }else{
        message.value = responseGetProfileDetail
    }
}
const clickToUpdateProfile = async (): Promise<void> => {
    message.value = await updateProfile(paramsProfile.value)
}
onMounted(async () => {
    await getCurrentUserDetail()
})
</script>