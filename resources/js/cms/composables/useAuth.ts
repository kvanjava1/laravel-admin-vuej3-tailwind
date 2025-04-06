import { ref } from 'vue'
import { route } from 'ziggy-js';

import { useAuthStore } from "@/cms/stores/useAuthStore"
import type { AuthStoreTypes } from '@/cms/types/authstore';
import type { LoginTypes } from '@/cms/types/auth.d';
import type { MessageTypes } from "@/cms/types/message.d"
import type { AxiosError, AxiosResponse } from 'axios';

export const useAuth = () => {

    const { authStoreData, setAuthData, deleteAuthData } = useAuthStore()
    const loading = ref<{ login: boolean }>({
        login: false
    })

    const login = async (login: LoginTypes): Promise<MessageTypes> => {
        try {
            loading.value.login = true
            const response: AxiosResponse<MessageTypes> = await axios.post(
                route('auth.login'),
                login
            )
            const authStore: AuthStoreTypes = response.data.data
            setAuthData(authStore)
            return response.data
        } catch (error: any) {
            loading.value.login = false
            const axiosError = error as AxiosError<MessageTypes>;
            return axiosError.response?.data ?? {
                code: 'error_unknown',
                message: {
                    head: 'Error',
                    detail: [error.message]
                }
            } as MessageTypes
        } finally {
            loading.value.login = false
        }
    };

    const isAuthenticated = async (): Promise<boolean> => {
        try {
            if (authStoreData.token) {
                return true
            }

            const authData: string | null = localStorage.getItem("cms_auth");
            if (!authData) {
                return false
            }

            const cmsAuth: AuthStoreTypes = JSON.parse(authData);
            const response: AxiosResponse<MessageTypes> = await axios.get(
                route('auth.check'),
                {
                    headers: {
                        Authorization: `Bearer ${cmsAuth.token}`
                    }
                }
            )

            if(response.data.code == "success"){
                setAuthData(response.data.data)
                return true
            }else{
                return false
            }
            
        } catch (error: any) {
            return false
        }
    }

    const logout = (): boolean => {

        try {
            deleteAuthData()
            return true
        } catch (error: any) {
            return false
        }
    };

    const resetPassword = (email: string) => {
        try {

        } catch (error) {

        } finally {

        }
    };

    return {
        login,
        isAuthenticated,
        logout,
        resetPassword,
        loading
    };
}