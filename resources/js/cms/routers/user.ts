import type { RouteRecordRaw } from 'vue-router';
import { authCheck } from '@/cms/middlewares/authmiddleware'

const routes: RouteRecordRaw[] = [
  {
    path: '/user-management',
    beforeEnter: [authCheck],
    children: [
      {
        path: 'role',
        children: [
          {
            path: 'index',
            name: 'usermanagement.role.index',
            component: () => import('@/cms/pages/user/IndexRole.vue'),
          },
          {
            path: 'add',
            name: 'usermanagement.role.add',
            component: () => import('@/cms/pages/user/role/AddRole.vue'),
          },
          {
            path: ':id/edit',
            name: 'usermanagement.role.edit',
            component: () => import('@/cms/pages/user/role/EditRole.vue'),
          },
        ],
      },
      {
        path: 'user',
        children: [
          {
            path: 'index',
            name: 'usermanagement.user.index',
            component: () => import('@/cms/pages/user/IndexUser.vue'),
          },
          {
            path: 'add',
            name: 'usermanagement.user.add',
            component: () => import('@/cms/pages/user/user/AddUser.vue'),
          }
        ]
      }
    ],
  },
];

export default routes;