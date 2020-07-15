import VueRouter from 'vue-router'

import homepage from "./components/homepage";
import login from "./components/login";
import register from "./components/register";
import admin from "./components/admin/admin";

export const routes = [
    {
        name: 'home',
        path: '/',
        component: homepage,
    },
    {
        name: 'login',
        path: '/login',
        component: login,
        props: true,
    },
    {
        name: 'register',
        path: '/register',
        component: register,
        props: true,
    },
    {
        name: 'admin',
        path: '/admin',
        component: admin,
        props: true,
    }
];
const router = new VueRouter({
    history: true,
    mode: 'history',
    routes,
})
export default router