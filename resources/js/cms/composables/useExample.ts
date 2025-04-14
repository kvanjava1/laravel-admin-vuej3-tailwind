import { ref } from 'vue'
import { route } from 'ziggy-js';

import type { MessageTypes } from "@/cms/types/message.d"
import type { AxiosResponse } from 'axios';

import { useAuthStore } from '@/cms/stores/useAuthStore';
import { errorCatchHelper } from '@/cms/helpers/errorCatchHelper';

export const useExample = () => {
    const { authStoreData } = useAuthStore()
    const loading = ref<{
        addExample: boolean
    }>({
        addExample: false
    })

    const addExample = async (): Promise<MessageTypes> => {
        try {
            loading.value.addExample = true
            const response: AxiosResponse<MessageTypes> = await axios.post(
                route('categorymanagement.add'),
                {},
                {
                    headers: {
                        Authorization: `Bearer ${authStoreData?.token}`
                    }
                }
            )
            return response.data
        } catch (error: any) {
            loading.value.addExample = false
            return errorCatchHelper(error)
        } finally {
            loading.value.addExample = false
        }
    }

    return {
        addExample,
        loading
    }
}