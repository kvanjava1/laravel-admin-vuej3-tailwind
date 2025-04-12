import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'
import userRoutes from './userRouter'
import dashboardRoutes from './dashboardRouter'
import authRoutes from './authRouter'
import accountRoutes from './accountRouter'
import categoryRoutes from './categoryRouter'

const routes: RouteRecordRaw[] = [
  ...authRoutes,
  ...dashboardRoutes,
  ...userRoutes,
  ...accountRoutes,
  ...categoryRoutes,
  {
    path: '/:pathMatch(.*)*', // Catch-all route
    name: 'not-found',
    component: () => import('@/cms/pages/404/Index404.vue'),
  }
];

const router = createRouter({
  history: createWebHistory('/cms'),
  routes,
});

export default router;