import type { RouteRecordRaw } from 'vue-router';
import { authCheck } from '@/cms/middlewares/authmiddleware'

const routes: RouteRecordRaw[] = [
  { 
    path: '/dashboard', 
    beforeEnter: [authCheck],
    children: [
      {
        path: '/index',
        name: 'dashboard.index',
        component: () => import('@/cms/pages/dashboard/IndexDashboard.vue')
      }
    ],
  }
]

export default routes;