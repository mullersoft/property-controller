import { createRouter, createWebHistory } from 'vue-router';
import Assignment from '../pages/Assignments.vue';
import Category from '../pages/Categories.vue';
import AdminDashBoard from '../pages/DashBoards/AdminDashBoard.vue';
import Employee from '../pages/Employees.vue';
import Login from '../pages/login.vue';
import Property from '../pages/Properties.vue';

const routes = [
  { path: '/', component: Login },

  // Admin dashboard with sidebar
  {
    path: '/admin',
    component: AdminDashBoard,
    children: [
      { path: 'assignments', component: Assignment },
      { path: 'categories', component: Category },
      { path: 'employees', component: Employee },
      { path: 'properties', component: Property },
      { path: '', redirect: '/admin/assignments' }, // default page
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
