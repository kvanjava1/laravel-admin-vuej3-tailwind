import { route } from 'ziggy-js';
import { ref } from 'vue';

import { useAuthStore } from '@/cms/stores/useAuthStore';

import type { MessageTypes } from '@/cms/types/message.d';
import type { ParamRoleSearchType, ParamCreateRoleType } from '@/cms/types/role.d';
import type { AxiosError, AxiosResponse } from 'axios';

export const useRole = () => {
    const { authStoreData } = useAuthStore();
    const loading = ref<{
        saveNewRole: boolean,
        updateNewRole: boolean,
        deleteRole: boolean
    }>({
        saveNewRole: false,
        updateNewRole: false,
        deleteRole: false
    })

    const getAllPermission = async (): Promise<MessageTypes> => {
        try {
            const response: AxiosResponse<MessageTypes> = await axios.get(
                route('usermanagement.permission'),
                {
                    headers: {
                        Authorization: `Bearer ${authStoreData?.token}`
                    }
                }
            );
            return response.data;
        } catch (error: any) {
            const axiosError = error as AxiosError<MessageTypes>;
            return axiosError.response?.data ?? {
                code: 'error_unknown',
                message: {
                    head: 'Error',
                    detail: [error.message]
                }
            } as MessageTypes
        }
    }

    const getAllRole = async (query: ParamRoleSearchType): Promise<MessageTypes> => {
        try {
            const response: AxiosResponse<MessageTypes> = await axios.get(
                route('usermanagement.role', { ...query }),
                {
                    headers: {
                        Authorization: `Bearer ${authStoreData?.token}`
                    }
                }
            )
            return response.data
        } catch (error: any) {
            const axiosError = error as AxiosError<MessageTypes>;
            return axiosError.response?.data ?? {
                code: 'error_unknown',
                message: { 
                    head: 'Error', 
                    detail: [error.message] 
                }
            } as MessageTypes
        }
    }

    const saveNewRole = async (newRole: ParamCreateRoleType): Promise<MessageTypes> => {
        try {
            loading.value.saveNewRole = true
            const response: AxiosResponse<MessageTypes> = await axios.post(
                route('usermanagement.role.add'),
                newRole,
                {
                    headers: {
                        Authorization: `Bearer ${authStoreData?.token}`
                    }
                }
            )
            return response.data
        } catch (error: any) {
            const axiosError = error as AxiosError<MessageTypes>;
            return axiosError.response?.data ?? {
                code: 'error_unknown',
                message: { 
                    head: 'Error', 
                    detail: [error.message] 
                }
            } as MessageTypes
        } finally {
            loading.value.saveNewRole = false
        }
    }

    const getRoleDetail = async (id: number): Promise<MessageTypes> => {
        try {
            const response: AxiosResponse<MessageTypes> = await axios.get(
                route('usermanagement.role.detail', { id: id }),
                {
                    headers: {
                        Authorization: `Bearer ${authStoreData?.token}`
                    }
                }
            )
            return response.data
        } catch (error: any) {
            const axiosError = error as AxiosError<MessageTypes>;
            return axiosError.response?.data ?? {
                code: 'error_unknown',
                message: { 
                    head: 'Error', 
                    detail: [error.message] 
                }
            } as MessageTypes
        }
    }

    const updateRole = async (roleIdIsBeingEdited: number, editRole: ParamCreateRoleType): Promise<MessageTypes> => {
        try {
            loading.value.updateNewRole = true
            const response: AxiosResponse<MessageTypes> = await axios.put(
                route('usermanagement.role.update', { id: roleIdIsBeingEdited }),
                editRole,
                {
                    headers: {
                        Authorization: `Bearer ${authStoreData?.token}`
                    }
                }
            )
            return response.data
        } catch (error: any) {
            const axiosError = error as AxiosError<MessageTypes>;
            return axiosError.response?.data ?? {
                code: 'error_unknown',
                message: { 
                    head: 'Error', 
                    detail: [error.message] 
                }
            } as MessageTypes
        } finally {
            loading.value.updateNewRole = false
        }
    }

    const deleteRole = async (roleIdIsBeingDeleted: number): Promise<MessageTypes> => {
        try {
            loading.value.deleteRole = true
            const response: AxiosResponse<MessageTypes> = await axios.delete(
                route('usermanagement.role.delete', { id: roleIdIsBeingDeleted }),
                {
                    headers: {
                        Authorization: `Bearer ${authStoreData?.token}`
                    }
                }
            )
            return response.data
        } catch (error: any) {
            const axiosError = error as AxiosError<MessageTypes>;
            return axiosError.response?.data ?? {
                code: 'error_unknown',
                message: { 
                    head: 'Error', 
                    detail: [error.message] 
                }
            } as MessageTypes
        } finally {
            loading.value.deleteRole = false
        }
    }

    return {
        deleteRole,
        getRoleDetail,
        getAllPermission,
        saveNewRole,
        getAllRole,
        updateRole,
        loading
    }
}