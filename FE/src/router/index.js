import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: '/',
        name: 'trang-chu',
        component: () => import('../components/TrangChu/index.vue'),
        meta: { layout: 'client' }
    },
    {
        path: '/login',
        name: 'login',
        component: () => import('../components/Auth/Login.vue'),
        meta: { layout: 'blank' }
    },
    {
        path: '/register',
        name: 'register',
        component: () => import('../components/Auth/Register.vue'),
        meta: { layout: 'blank' }
    },
    {
        path: '/forgot-password',
        name: 'forgot-password',
        component: () => import('../components/Auth/Forgot.vue'),
        meta: { layout: 'blank' }
    },
    {
        path: '/reset-password',
        name: 'reset-password',
        component: () => import('../components/Auth/Reset.vue'),
        meta: { layout: 'blank' }
    },
    // Admin Routes
    {
        path: '/admin/dashboard',
        name: 'admin-dashboard',
        component: () => import('../components/Admin/Dashboard.vue'),
        meta: { layout: 'default' }
    },
    {
        path: '/admin/gia-pha',
        name: 'admin-gia-pha',
        component: () => import('../components/Admin/GiaPha/index.vue'),
        meta: { layout: 'default' }
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes: routes
})

export default router