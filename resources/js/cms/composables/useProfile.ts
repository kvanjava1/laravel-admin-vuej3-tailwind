import { ref } from 'vue'
import { route } from 'ziggy-js';

import type { MessageTypes } from '@/cms/types/message';
import type { AxiosResponse } from 'axios';
import type { ParamsProfileType } from '@/cms/types/profile';

import { useAuthStore } from '@/cms/stores/useAuthStore';
import { errorCatchHelper } from '@/cms/helpers/errorCatchHelper';

export const useProfile = () => {
    const { authStoreData } = useAuthStore()
    const loading = ref<{
        getProfileDetail: boolean,
        updateProfile: boolean
    }>({
        getProfileDetail: false,
        updateProfile: false
    })

    const getProfileDetail = async (): Promise<MessageTypes> => {
        try {
            loading.value.getProfileDetail = true
            const response: AxiosResponse<MessageTypes> = await axios.get(
                route('account.profile.detail'),
                {
                    headers: {
                        Authorization: `Bearer ${authStoreData?.token}`
                    }
                }
            )
            return response.data
        } catch (error: any) {
            loading.value.getProfileDetail = false
            return errorCatchHelper(error)
        } finally {
            loading.value.getProfileDetail = false
        }
    }

    const updateProfile = async (paramsProfile: ParamsProfileType): Promise<MessageTypes> => {
        try {
            loading.value.updateProfile = true
            const response: AxiosResponse<MessageTypes> = await axios.put(
                route('account.profile.update'),
                paramsProfile,
                {
                    headers: {
                        Authorization: `Bearer ${authStoreData?.token}`
                    }
                }
            )
            return response.data
        } catch (error: any) {
            loading.value.updateProfile = false
            return errorCatchHelper(error)
        } finally {
            loading.value.updateProfile = false
        }
    }

    return {
        getProfileDetail,
        updateProfile,
        loading
    }
}