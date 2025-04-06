import { defineStore } from 'pinia';
import { ref } from 'vue';
import type { AuthStoreTypes } from '@/cms/types/authstore.d'

export const useAuthStore = defineStore('useAuthStore', () => {
    const authStoreData = ref<AuthStoreTypes>({} as AuthStoreTypes)
    const lsKey: string = 'cms_auth'

    const setAuthData = (authStoreParams: AuthStoreTypes) => {
        authStoreData.value = authStoreParams
        localStorage.setItem(lsKey, JSON.stringify(authStoreParams))
    }

    const deleteAuthData = (): void => {
        authStoreData.value = {} as AuthStoreTypes
        localStorage.setItem(lsKey, '{}');
    }
    
    return {
        authStoreData,
        setAuthData,
        deleteAuthData
    }
})