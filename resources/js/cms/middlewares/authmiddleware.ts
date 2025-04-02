import type { RouteLocationNormalized, NavigationGuardNext } from 'vue-router';
import { useAuth } from '@/cms/composables/useAuth';
import { route } from 'ziggy-js';

export const authCheck = async (to: RouteLocationNormalized, from: RouteLocationNormalized, next: NavigationGuardNext): Promise<void> => {
    const { isAuthenticated } = useAuth();
    
    if(await isAuthenticated() == false){
        next({ name: 'auth.login' })    
    }

    next()
}