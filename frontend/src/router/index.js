import { createRouter, createWebHistory } from 'vue-router';
// import MainLayout from '../components/MainLayout.vue';
import Assignment from '../pages/ListingPages/Assignments.vue';
import Category from '../pages/ListingPages/Categories.vue';
import AdminDashBoard from '../pages/DashBoards/AdminDashBoard.vue';
import Employee from '../pages/ListingPages/Employees.vue';
import Login from '../pages/AuthPages/login.vue';
import Property from '../pages/ListingPages/Properties.vue';
import Register from '../pages/AuthPages/register.vue';

const routes = [
  // Public routes
  { path: '/', component: Login },
  { path: '/login', component: Login },
  { path: '/register', component: Register },

  // Admin dashboard with sidebar and children
  {
    path: '/admin',
    component: AdminDashBoard,
    // meta: {
    //   layout: MainLayout,
    //   // requiresAuth: true,
    //   // role: ["preparer", "admin", "approver"]
    // },
    children: [
      { path: 'assignments', component: Assignment },
      { path: 'categories', component: Category },
      { path: 'employees', component: Employee },
      { path: 'properties', component: Property },
      // Default redirect when visiting /admin
      { path: '', redirect: '/admin/assignments' },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
