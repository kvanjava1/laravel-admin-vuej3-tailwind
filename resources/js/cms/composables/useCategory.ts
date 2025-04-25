import { ref } from 'vue'
import { route } from 'ziggy-js';

import type { MessageTypes } from "@/cms/types/message.d"
import type { AxiosResponse } from 'axios';
import type { ParamsCategoryType, ParamsSearchCategoryType } from "@/cms/types/category";

import { useAuthStore } from '@/cms/stores/useAuthStore';
import { errorCatchHelper } from '@/cms/helpers/errorCatchHelper';

export const useCategory = () => {
    const { authStoreData } = useAuthStore()
    const loading = ref<{
        addCategory: boolean,
        getAllCategory: boolean
    }>({
        addCategory: false,
        getAllCategory: false
    })

    const addCategory = async (paramsCategory: ParamsCategoryType): Promise<MessageTypes> => {
        try {
            loading.value.addCategory = true
            const response: AxiosResponse<MessageTypes> = await axios.post(
                route('categorymanagement.add'),
                paramsCategory,
                {
                    headers: {
                        Authorization: `Bearer ${authStoreData?.token}`
                    }
                }
            )
            return response.data
        } catch (error: any) {
            loading.value.addCategory = false
            return errorCatchHelper(error)
        } finally {
            loading.value.addCategory = false
        }
    }

    const getAllCategory = async (paramsSearchCategory: ParamsSearchCategoryType): Promise<MessageTypes> => {
        try {
            loading.value.getAllCategory = true
            const response: AxiosResponse<MessageTypes> = await axios.get(
                route('categorymanagement', { ...paramsSearchCategory }),
                {
                    headers: {
                        Authorization: `Bearer ${authStoreData?.token}`
                    }
                }
            )
            return response.data
        } catch (error: any) {
            loading.value.getAllCategory = false
            return errorCatchHelper(error)
        } finally {
            loading.value.getAllCategory = false
        }
    }

    return {
        addCategory,
        getAllCategory,
        loading
    }
}