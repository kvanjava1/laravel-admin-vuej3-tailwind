import type { RouteRecordRaw } from 'vue-router';
import { authCheck } from '@/cms/middlewares/authmiddleware'

const routes: RouteRecordRaw[] = [
  {
    path: '/category-management',
    beforeEnter: [authCheck],
    children: [
        {
            path: 'index',
            name: 'categorymanagement.index',
            component: () => import('@/cms/pages/category/IndexCategory.vue'),
        }
    ],
  },
];

export default routes;