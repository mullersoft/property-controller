import { createRouter, createWebHistory } from 'vue-router';
// import MainLayout from '../components/MainLayout.vue';
import Login from '../pages/AuthPages/login.vue';
import Register from '../pages/AuthPages/register.vue';
import AdminDashBoard from '../pages/DashBoards/AdminDashBoard.vue';
import CategoryForm from '../pages/FormPages/CategoryForm.vue';
import propertyForm from '../pages/FormPages/PropertyForm.vue';
import Assignment from '../pages/ListingPages/Assignments.vue';
import Category from '../pages/ListingPages/Categories.vue';
import Employee from '../pages/ListingPages/Employees.vue';
import Property from '../pages/ListingPages/Properties.vue';

const routes = [
  // Public routes
  { path: '/', component: Login },
  { path: '/login', component: Login },
  { path: '/register', component: Register },

  // Admin dashboard with sidebar and children
  {
    path: '/admin',
    component: AdminDashBoard,

    children: [
      { path: 'assignments', component: Assignment },
      { path: 'categories', component: Category },
      { path: 'category/add', component: CategoryForm },
      { path: 'category/edit/:id', component: CategoryForm },
      { path: 'employees', component: Employee },
      { path: 'properties', component: Property },
      { path: 'property/add', component: propertyForm },
      { path: 'property/edit/:id', component: propertyForm },

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
