import type { RouteRecordRaw } from 'vue-router';
import { authCheck } from '@/cms/middlewares/authmiddleware'

const routes: RouteRecordRaw[] = [
  {
    path: '/category',
    beforeEnter: [authCheck],
    children: [
        {
            path: 'index',
            name: 'category.index',
            component: () => import('@/cms/pages/category/IndexCategory.vue'),
        },
        {
            path: 'add',
            name: 'category.add',
            component: () => import('@/cms/pages/category/IndexCategory.vue'),
        },
    ],
  },
];

export default routes;