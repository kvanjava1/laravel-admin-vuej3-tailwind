import { createRouter, createWebHistory } from 'vue-router';
import type { RouteRecordRaw } from 'vue-router';
import userRoutes from './user';
import dashboardRoutes from './dashboard';
import authRoutes from './auth';
import accountRoutes from './account';

const routes: RouteRecordRaw[] = [
  ...authRoutes,
  ...dashboardRoutes,
  ...userRoutes,
  ...accountRoutes,
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