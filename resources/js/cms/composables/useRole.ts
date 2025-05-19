import { route } from 'ziggy-js';
import { ref } from 'vue';

import { useAuthStore } from '@/cms/stores/useAuthStore';

import type { MessageTypes } from '@/cms/types/message.d';
import type { ParamRoleSearchType, ParamCreateRoleType, RoleType } from '@/cms/types/role.d';
import type { AxiosResponse } from 'axios';
import { errorCatchHelper } from '@/cms/helpers/errorCatchHelper';
import type { PaginationType } from '../types/pagination';

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
            return errorCatchHelper(error)
        }
    }

    const getAllRole = async (query: ParamRoleSearchType): Promise<MessageTypes<PaginationType<RoleType>>> => {
        try {
            const response: AxiosResponse<MessageTypes<PaginationType<RoleType>>> = await axios.get(
                route('usermanagement.role', { ...query }),
                {
                    headers: {
                        Authorization: `Bearer ${authStoreData?.token}`
                    }
                }
            )
            return response.data
        } catch (error: any) {
            return errorCatchHelper(error)
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
            return errorCatchHelper(error)
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
            return errorCatchHelper(error)
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
            return errorCatchHelper(error)
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
            return errorCatchHelper(error)
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