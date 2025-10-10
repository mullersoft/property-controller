import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/AuthStore'; // Import Pinia store

// Public pages
import Login from '../pages/AuthPages/login.vue';
import Register from '../pages/AuthPages/register.vue';

// Admin dashboard and children pages
import AdminDashBoard from '../pages/DashBoards/AdminDashBoard.vue';
import AssignmentForm from '../pages/FormPages/AssignmentForm.vue';
import CategoryForm from '../pages/FormPages/CategoryForm.vue';
import EmployeeForm from '../pages/FormPages/EmployeeForm.vue';
import propertyForm from '../pages/FormPages/PropertyForm.vue';
import Assignment from '../pages/ListingPages/Assignments.vue';
import Category from '../pages/ListingPages/Categories.vue';
import Employee from '../pages/ListingPages/Employees.vue';
import Property from '../pages/ListingPages/Properties.vue';

// -------------------------
// Define routes
const routes = [
  // Public routes
  { path: '/', name: 'Login', component: Login },
  { path: '/login', name: 'LoginPage', component: Login },
  { path: '/register', name: 'RegisterPage', component: Register },

  // Protected admin routes
  {
    path: '/admin',
    component: AdminDashBoard,
    meta: { requiresAuth: true }, // Mark as protected

    children: [
      // Assignments
      { path: 'assignments', component: Assignment },
      { path: 'assignment/add', component: AssignmentForm },
      { path: 'assignment/edit/:id', component: AssignmentForm },

      // Categories
      { path: 'categories', component: Category },
      { path: 'category/add', component: CategoryForm },
      { path: 'category/edit/:id', component: CategoryForm },

      // Employees
      { path: 'employees', component: Employee },
      { path: 'employee/add', component: EmployeeForm },
      { path: 'employee/edit/:id', component: EmployeeForm },

      // Properties
      { path: 'properties', component: Property },
      { path: 'property/add', component: propertyForm },
      { path: 'property/edit/:id', component: propertyForm },

      // Property Items (nested under property)
      {
        path: 'property/:id/items',
        name: 'PropertyItemsList',
        component: () => import('../pages/ListingPages/PropertyItemsList.vue'),
        props: true,
      },
      {
        path: 'property/:id/add-item',
        name: 'AddPropertyItem',
        component: () => import('../pages/FormPages/PropertyItemForm.vue'),
        props: true,
      },
      {
        path: 'property/:id/edit-item/:itemId',
        name: 'EditPropertyItem',
        component: () => import('../pages/FormPages/PropertyItemForm.vue'),
        props: true,
      },

      // Default redirect for /admin
      { path: '', redirect: '/Login' },
    ],
  },
];

// -------------------------
// Create Vue Router instance
const router = createRouter({
  history: createWebHistory(),
  routes,
});

// -------------------------
// Global navigation guard
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  // If route requires auth and user is NOT authenticated
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return next('/login'); // Redirect to login
  }

  // If trying to access login page while authenticated
  if ((to.path === '/login' || to.path === '/') && authStore.isAuthenticated) {
    return next('/admin'); // Redirect to admin dashboard
  }

  // Otherwise, allow navigation
  next();
});

export default router;
