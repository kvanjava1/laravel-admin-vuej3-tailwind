import { ref, watch } from 'vue'
import { route } from 'ziggy-js';

import type { ParamsSearchUserType, ParamsUserType } from '@/cms/types/user.d';
import type { MessageTypes } from '@/cms/types/message';
import type { AxiosResponse } from 'axios';

import { useAuthStore } from '@/cms/stores/useAuthStore';

export const useProfile = () => {
    const { authStoreData } = useAuthStore()
    const loading = ref<{
        getProfileDetail: boolean,
        updateProfile: boolean
    }>({
        getProfileDetail: false,
        updateProfile: false
    })

    const getProfileDetail = async (userId: number): Promise<MessageTypes> => {
        try {
            loading.value.getProfileDetail = true
            const response: AxiosResponse<MessageTypes> = await axios.get(
                route('usermanagement.user.detail', { id: userId}),
                { 
                    headers: { 
                        Authorization: `Bearer ${authStoreData?.token}` 
                    } 
                }
            )
            return response.data
        } catch (error: any) {
            loading.value.getProfileDetail = false
            return error.response?.data as MessageTypes
        } finally {
            loading.value.getProfileDetail = false
        }
    }

    const updateProfile = () => {

    }

    return {
        getProfileDetail,
        updateProfile,
        loading
    }
}