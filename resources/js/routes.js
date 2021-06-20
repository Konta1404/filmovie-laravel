import VueRouter from 'vue-router'

import homepage from "./components/homepage";
import login from "./components/login";
import register from "./components/register";
import admin from "./components/admin/admin";
import movieList from "./components/movie-list";
import movieSingle from "./components/movie";

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
        name: 'movies',
        path: '/movies',
        component: movieList,
        props: true,
    },
    {
        name: 'singleMovie',
        path: '/movies/:id',
        component: movieSingle,
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
