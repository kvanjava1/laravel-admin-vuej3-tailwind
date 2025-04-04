import { ref, watch } from 'vue'
import { route } from 'ziggy-js';

import type { ParamsUserType } from '@/cms/types/user.d';
import type { MessageTypes } from '@/cms/types/message';
import type { AxiosResponse } from 'axios';

import { useAuthStore } from '@/cms/stores/useAuthStore';

export const useUser = () => {
    const { authStoreData } = useAuthStore()
    const loading = ref<{
        getUser: boolean,
        getUserDetail: boolean,
        addUser: boolean,
        deleteUser: boolean
    }>({
        getUser: false,
        getUserDetail: false,
        addUser: false,
        deleteUser: false
    })

    const getUser = () => {}

    const getUserDetail = () => {}

    const addUser = async (paramsUser: ParamsUserType): Promise<MessageTypes> => {
        try {
            loading.value.addUser = true
            const response: AxiosResponse<MessageTypes> = await axios.post(
                route('usermanagement.user.add'),
                paramsUser,
                { 
                    headers: { 
                        Authorization: `Bearer ${authStoreData?.token}` 
                    } 
                }
            )
            return response.data
        } catch (error: any) {
            loading.value.addUser = false
            return error.response?.data as MessageTypes
        } finally {
            loading.value.addUser = false
        }
    }

    const updateUser = () => {}

    const deleteUser = () => {}

    return {
        getUser,
        getUserDetail,
        addUser,
        deleteUser,
        updateUser,
        loading
    }
}