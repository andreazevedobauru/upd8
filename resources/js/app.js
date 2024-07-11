import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory, createMemoryHistory } from 'vue-router';
import VueTheMask from 'vue-the-mask'
import Vue3Toasity from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import { TailwindPagination } from 'laravel-vue-pagination';

import App from './App.vue';
import CreateClients from './pages/clients/Create.vue';
import ListClient from './pages/clients/ListClient.vue';
import EditClient from './pages/clients/Edit.vue';

import CreateSeller from './pages/sellers/Create.vue';
import ListSeller from './pages/sellers/List.vue';
import EditSeller from './pages/sellers/Edit.vue';

import CreateCity from './pages/cities/Create.vue';
import ListCity from './pages/cities/List.vue';
import EditCity from './pages/cities/Edit.vue';

const routes = [
    {
        name: 'ListClient',
        path: '/',
        component: ListClient
    },
    {
        name: 'CreateClients',
        path: '/clients/create',
        component: CreateClients
    },
    {
        name: 'EditClient',
        path: '/clients/:id',
        component: EditClient
    },
    {
        name: 'ListSellers',
        path: '/sellers',
        component: ListSeller
    },
    {
        name: 'CreateSeller',
        path: '/sellers/create',
        component: CreateSeller
    },
    {
        name: 'EditSeller',
        path: '/sellers/:id',
        component: EditSeller
    },
    {
        name: 'ListCity',
        path: '/cities',
        component: ListCity
    },
    {
        name: 'CreateCity',
        path: '/cities/create',
        component: CreateCity
    },
    {
        name: 'EditCity',
        path: '/cities/:id',
        component: EditCity
    },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

createApp(App)
    .use(router)
    .use(VueTheMask)
    .use(Vue3Toasity)
    .use(Bootstrap5Pagination)
    .use(TailwindPagination)
    .mount('#app');
