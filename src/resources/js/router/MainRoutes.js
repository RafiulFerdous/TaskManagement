const MainRoutes = {
  path: '/',
  meta: {
    requiresAuth: true
  },
  redirect: '/app/login',
  component: () => import('@/layouts/full/FullLayout.vue'),
  children: [
    {
      name: 'Dashboard',
      path: '/app/dashboard',
      component: () => import('@/components/dashboard/DashboardHome.vue')
    },

    {
      name: 'Task',
      path: '/app/task',
      component: () => import('@/components/task/TaskHome.vue')
    }

  ]
};

export default MainRoutes;
