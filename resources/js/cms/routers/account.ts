import type { RouteRecordRaw } from 'vue-router';
import { authCheck } from '@/cms/middlewares/authmiddleware'

const routes: RouteRecordRaw[] = [
  {
    path: '/account',
    beforeEnter: [authCheck],
    children: [
      {
        path: 'profile',
        children: [
          {
            path: 'index',
            name: 'account.profile.index',
            component: () => import('@/cms/pages/profile/EditProfile.vue'),
          },
        ],
      }
    ],
  },
];

export default routes;