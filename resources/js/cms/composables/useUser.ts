import { ref } from 'vue'
import { route } from 'ziggy-js';

import type { ParamsSearchUserType, ParamsUserType } from '@/cms/types/user.d';
import type { MessageTypes } from '@/cms/types/message';
import type { AxiosError, AxiosResponse } from 'axios';

import { useAuthStore } from '@/cms/stores/useAuthStore';

export const useUser = () => {
    const { authStoreData } = useAuthStore()
    const loading = ref<{
        getUser: boolean,
        getUserDetail: boolean,
        addUser: boolean,
        deleteUser: boolean,
        updateUser: boolean
    }>({
        getUser: false,
        getUserDetail: false,
        addUser: false,
        deleteUser: false,
        updateUser: false
    })

    const getUser = async (paramSearchUser: ParamsSearchUserType): Promise<MessageTypes> => {
        try {
            loading.value.getUser = true
            const response: AxiosResponse<MessageTypes> = await axios.get(
                route('usermanagement.user', { ...paramSearchUser }),
                {
                    headers: {
                        Authorization: `Bearer ${authStoreData?.token}`
                    }
                }
            )
            return response.data
        } catch (error: any) {
            loading.value.getUser = false
            const axiosError = error as AxiosError<MessageTypes>;
            return axiosError.response?.data ?? {
                code: 'error_unknown',
                message: { 
                    head: 'Error', 
                    detail: [error.message] 
                }
            } as MessageTypes
        } finally {
            loading.value.getUser = false
        }
    }

    const getUserDetail = async (userId: number): Promise<MessageTypes> => {
        try {
            loading.value.getUserDetail = true
            const response: AxiosResponse<MessageTypes> = await axios.get(
                route('usermanagement.user.detail', { id: userId }),
                {
                    headers: {
                        Authorization: `Bearer ${authStoreData?.token}`
                    }
                }
            )
            return response.data
        } catch (error: any) {
            loading.value.getUserDetail = false
            const axiosError = error as AxiosError<MessageTypes>;
            return axiosError.response?.data ?? {
                code: 'error_unknown',
                message: { 
                    head: 'Error', 
                    detail: [error.message] 
                }
            } as MessageTypes
        } finally {
            loading.value.getUserDetail = false
        }
    }

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
            const axiosError = error as AxiosError<MessageTypes>;
            return axiosError.response?.data ?? {
                code: 'error_unknown',
                message: { 
                    head: 'Error', 
                    detail: [error.message] 
                }
            } as MessageTypes
        } finally {
            loading.value.addUser = false
        }
    }

    const updateUser = async (userId: number, paramsUser: ParamsUserType): Promise<MessageTypes> => {
        try {
            loading.value.updateUser = true
            const response: AxiosResponse<MessageTypes> = await axios.put(
                route('usermanagement.user.update', { id: userId }),
                paramsUser,
                {
                    headers: {
                        Authorization: `Bearer ${authStoreData?.token}`
                    }
                }
            )
            return response.data
        } catch (error: any) {
            loading.value.updateUser = false
            const axiosError = error as AxiosError<MessageTypes>;
            return axiosError.response?.data ?? {
                code: 'error_unknown',
                message: { 
                    head: 'Error', 
                    detail: [error.message] 
                }
            } as MessageTypes
        } finally {
            loading.value.updateUser = false
        }
    }

    const deleteUser = async (userIdIsBeingDeleted: number): Promise<MessageTypes> => {
        try {
            loading.value.deleteUser = true
            const response: AxiosResponse<MessageTypes> = await axios.delete(
                route('usermanagement.user.delete', { id: userIdIsBeingDeleted }),
                {
                    headers: {
                        Authorization: `Bearer ${authStoreData?.token}`
                    }
                }
            )
            return response.data
        } catch (error: any) {
            loading.value.deleteUser = false
            const axiosError = error as AxiosError<MessageTypes>;
            return axiosError.response?.data ?? {
                code: 'error_unknown',
                message: { 
                    head: 'Error', 
                    detail: [error.message] 
                }
            } as MessageTypes
        } finally {
            loading.value.deleteUser = false
        }
    }

    return {
        getUser,
        getUserDetail,
        addUser,
        deleteUser,
        updateUser,
        loading
    }
}