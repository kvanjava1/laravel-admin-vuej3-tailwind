// resources/js/cms/router/auth.ts
import type { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
  {
    path: '/auth',
    children: [
      {
        path: 'login',
        name: 'auth.login',
        component: () => import('@/cms/pages/auth/IndexLogin.vue'),
      },
      {
        path: 'reset-password',
        name: 'auth.reset-password',
        component: () => import('@/cms/pages/auth/IndexResetPassword.vue'),
      },
    ],
  },
];

export default routes;